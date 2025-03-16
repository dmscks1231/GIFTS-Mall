<?php
namespace LIB\App;

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// DB 클래스와 Lib 클래스 로드
require_once './lib/DB.php';
require_once './lib/lib.php';

// 관리자 권한 확인
if (!Lib::isAdmin()) {
    Lib::redirect("index.php", "관리자만 접근 가능합니다.");
    exit;
}

// 상품 추가
if (isset($_POST['add_product'])) {
    $product_title = $_POST['product_title'] ?? '';
    $product_description = $_POST['product_description'] ?? '';
    $product_price = $_POST['product_price'] ?? '';
    $product_shipPrice = $_POST['product_shipPrice'] ?? '';
    $product_benefits = $_POST['product_benefits'] ?? '';
    $product_category = $_POST['product_category'] ?? '';
    $product_image = $_POST['product_image'] ?? '';
    
    // 필드 검증
    if (empty($product_title) || empty($product_description) || empty($product_price) || 
        empty($product_shipPrice) || empty($product_benefits) || empty($product_category) || 
        empty($product_image)) {
        Lib::back("모든 필드를 입력해주세요.");
        exit;
    }
    
    // 인기상품 및 할인 정보
    $discountOption = $_POST['discount_option'] ?? 'none';
    $discountValue = 0;
    
    if ($discountOption === 'minus') {
        $discountValue = 10000; // 만원 할인
    } else if ($discountOption === 'percent') {
        $discountValue = ($_POST['discount_percent'] === '10') ? 0.1 : 0.3; // 10% 또는 30% 할인
    }
    
    // 인기상품 확인
    $isPopular = isset($_POST['is_popular']) && $_POST['is_popular'] === 'yes' ? true : false;
    
    // 인기상품인데 할인이 없는 경우
    if ($isPopular && $discountOption === 'none') {
        Lib::back("인기상품은 반드시 할인 방법(만원할인, 10%할인, 30%할인)을 선택해야 합니다.");
        exit;
    }
    
    // 상품 추가
    $result = DB::execute(
        "INSERT INTO products (title, description, discountOption, discountValue, price, shipPrice, benefits, image, category, isPopular, registerTime) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        [
            $product_title, 
            $product_description, 
            $discountOption, 
            $discountValue, 
            $product_price, 
            $product_shipPrice, 
            $product_benefits, 
            $product_image, 
            $product_category,
            $isPopular ? 1 : 0,
            date('Y-m-d H:i:s')
        ]
    );
    
    if ($result) {
        // 인기상품으로 지정된 경우
        if ($isPopular) {
            // 기존 인기상품 해제
            DB::execute(
                "UPDATE products SET isPopular = 0 WHERE category = ? AND id != ?",
                [$product_category, DB::lastId()]
            );
        }
        
        Lib::redirect("admin_products.php", "상품이 성공적으로 추가되었습니다.");
        exit;
    } else {
        Lib::back("상품 추가에 실패했습니다.");
        exit;
    }
}

// 상품 수정
if (isset($_POST['edit_product'])) {
    $product_id = $_POST['product_id'] ?? 0;
    $product_title = $_POST['product_title'] ?? '';
    $product_description = $_POST['product_description'] ?? '';
    $product_price = $_POST['product_price'] ?? '';
    $product_shipPrice = $_POST['product_shipPrice'] ?? '';
    $product_benefits = $_POST['product_benefits'] ?? '';
    $product_category = $_POST['product_category'] ?? '';
    $product_image = $_POST['product_image'] ?? '';
    
    // 유효성 검사
    if (empty($product_id) || $product_id <= 0) {
        Lib::back("유효하지 않은 상품 ID입니다.");
        exit;
    }
    
    // 필드 검증
    if (empty($product_title) || empty($product_description) || empty($product_price) || 
        empty($product_shipPrice) || empty($product_benefits) || empty($product_category) || 
        empty($product_image)) {
        Lib::back("모든 필드를 입력해주세요.");
        exit;
    }
    
    // 기존 상품의 카테고리 가져오기
    $existingProduct = DB::fetch("SELECT category FROM products WHERE id = ?", [$product_id]);
    
    if (!$existingProduct) {
        Lib::back("상품을 찾을 수 없습니다.");
        exit;
    }
    
    $oldCategory = $existingProduct->category;
    
    // 인기상품 및 할인 정보
    $discountOption = $_POST['discount_option'] ?? 'none';
    $discountValue = 0;
    
    if ($discountOption === 'minus') {
        $discountValue = 10000; // 만원 할인
    } else if ($discountOption === 'percent') {
        $discountValue = ($_POST['discount_percent'] === '10') ? 0.1 : 0.3; // 10% 또는 30% 할인
    }
    
    // 인기상품 여부
    $isPopular = isset($_POST['is_popular']) && $_POST['is_popular'] === 'yes' ? true : false;
    
    // 인기상품인데 할인이 없는 경우
    if ($isPopular && $discountOption === 'none') {
        Lib::back("인기상품은 반드시 할인 방법(만원할인, 10%할인, 30%할인)을 선택해야 합니다.");
        exit;
    }
    
    // 상품 수정
    $result = DB::execute(
        "UPDATE products SET 
            title = ?, 
            description = ?, 
            discountOption = ?, 
            discountValue = ?, 
            price = ?, 
            shipPrice = ?, 
            benefits = ?, 
            image = ?, 
            category = ?,
            isPopular = ?,
            registerTime = ?
         WHERE id = ?",
        [
            $product_title, 
            $product_description, 
            $discountOption, 
            $discountValue, 
            $product_price, 
            $product_shipPrice, 
            $product_benefits, 
            $product_image, 
            $product_category,
            $isPopular ? 1 : 0,
            date('Y-m-d H:i:s'),
            $product_id
        ]
    );
    
    if ($result) {
        // 인기상품으로 지정된 경우, 같은 카테고리의 다른 상품들은 인기상품에서 해제
        if ($isPopular) {
            DB::execute(
                "UPDATE products SET isPopular = 0 WHERE category = ? AND id != ?",
                [$product_category, $product_id]
            );
            
            // 카테고리가 변경된 경우, 이전 카테고리의 인기상품도 처리
            if ($oldCategory !== $product_category) {
                DB::execute(
                    "UPDATE products SET isPopular = 0 WHERE category = ? AND id != ?",
                    [$oldCategory, $product_id]
                );
            }
        }
        
        Lib::redirect("admin_products.php", "상품이 성공적으로 수정되었습니다.");
        exit;
    } else {
        Lib::back("상품 수정에 실패했습니다.");
        exit;
    }
}

// 상품 삭제
if (isset($_GET['delete']) && $_GET['delete'] > 0) {
    $delete_id = (int)$_GET['delete'];
    
    // 상품 정보 가져오기
    $productToDelete = DB::fetch("SELECT isPopular, category FROM products WHERE id = ?", [$delete_id]);
    
    if (!$productToDelete) {
        Lib::back("상품을 찾을 수 없습니다.");
        exit;
    }
    
    $result = DB::execute("DELETE FROM products WHERE id = ?", [$delete_id]);
    
    if ($result) {
        Lib::redirect("admin_products.php", "상품이 성공적으로 삭제되었습니다.");
        exit;
    } else {
        Lib::back("상품 삭제에 실패했습니다.");
        exit;
    }
}

// 상품 목록 가져오기
function getProducts($page = 1, $category = 'all', $sort = 'desc') {
    $itemsPerPage = 10;
    $offset = ($page - 1) * $itemsPerPage;
    
    // 카테고리 필터링
    $whereClause = $category !== 'all' ? "WHERE p.category = ?" : "";
    $params = $category !== 'all' ? [$category] : [];
    
    // 정렬 방향
    $orderDir = $sort === 'asc' ? 'ASC' : 'DESC';
    
    // 전체 상품 수 조회
    $countSql = "SELECT COUNT(*) as total FROM products p $whereClause";
    $totalResult = DB::fetch($countSql, $params);
    $totalProducts = $totalResult->total;
    
    // 총 페이지 수 계산
    $totalPages = ceil($totalProducts / $itemsPerPage);
    
    // 현재 페이지가 총 페이지 수보다 큰 경우 조정
    if ($page > $totalPages && $totalPages > 0) {
        $page = $totalPages;
        $offset = ($page - 1) * $itemsPerPage;
    }
    
    // 상품 목록 조회 (카테고리 이름 포함)
    $sql = "SELECT p.*, c.name as categoryName 
            FROM products p 
            LEFT JOIN categories c ON p.category = c.code
            $whereClause 
            ORDER BY p.registerTime $orderDir 
            LIMIT $offset, $itemsPerPage";
    
    $products = DB::fetchAll($sql, $params);
    
    return [
        'products' => $products,
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'totalProducts' => $totalProducts
    ];
}