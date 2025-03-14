<?php
namespace LIB\App;

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// DB 클래스와 Lib 클래스 로드
require_once '../lib/DB.php';
require_once '../lib/lib.php';

// 상품 ID가 전달되었는지 확인
if (!isset($_GET['product_id']) || empty($_GET['product_id'])) {
    Lib::back("상품 정보가 올바르지 않습니다.");
    exit;
}

$product_id = $_GET['product_id'];

// 상품이 존재하는지 확인
$product = DB::fetch("SELECT * FROM products WHERE id = ?", [$product_id]);
if (!$product) {
    Lib::back("존재하지 않는 상품입니다.");
    exit;
}

// 로그인 여부 확인
if (!Lib::isLoggedIn()) {
    // 로그인 페이지로 리다이렉트 또는 로그인 모달 표시
    $_SESSION['msg'] = "장바구니 이용을 위해 로그인이 필요합니다.";
    Lib::redirect("../index.php", "장바구니 이용을 위해 로그인이 필요합니다.");
    exit;
}

$user_id = $_SESSION['user_id'];

// 이미 장바구니에 해당 상품이 있는지 확인
$existingItem = DB::fetch("SELECT * FROM buckets WHERE user_id = ? AND product_id = ?", [$user_id, $product_id]);

if ($existingItem) {
    // 이미 장바구니에 있는 경우 수량 증가
    $result = DB::execute(
        "UPDATE buckets SET count = count + 1 WHERE user_id = ? AND product_id = ?",
        [$user_id, $product_id]
    );
    
    if ($result) {
        Lib::redirect("../products.php", "장바구니에 상품 수량이 추가되었습니다.");
    } else {
        Lib::back("장바구니 업데이트에 실패했습니다.");
    }
} else {
    // 장바구니에 없는 경우 새로 추가
    $result = DB::execute(
        "INSERT INTO buckets (user_id, product_id, count) VALUES (?, ?, ?)",
        [$user_id, $product_id, 1]
    );
    
    if ($result) {
        Lib::redirect("../products.php", "장바구니에 상품이 추가되었습니다.");
    } else {
        Lib::back("장바구니에 상품 추가를 실패했습니다.");
    }
}
?>