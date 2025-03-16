<?php
// 네임스페이스 및 필요한 클래스 로드
namespace LIB\App;
require_once './lib/DB.php';
require_once './lib/lib.php';
require_once './process/products_process.php';

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 관리자 권한 확인
if (!Lib::isAdmin()) {
    Lib::redirect("index.php", "관리자만 접근 가능합니다.");
    exit;
}

// DB 클래스와 Lib 클래스 로드

// 페이지 파라미터 가져오기
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc';

// 페이지 유효성 검사
if ($page < 1) $page = 1;

// 초기 메시지 설정
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// 상품 카테고리 가져오기
$categories = DB::fetchAll("SELECT * FROM categories ORDER BY name");

// 상품 가져오기
$productsData = getProducts($page, $category, $sort);
$products = $productsData['products'];
$totalPages = $productsData['totalPages'];
$currentPage = $productsData['currentPage'];
$totalProducts = $productsData['totalProducts'];

// 이전, 다음 페이지 계산
$prevPage = ($currentPage > 1) ? $currentPage - 1 : 1;
$nextPage = ($currentPage < $totalPages) ? $currentPage + 1 : $totalPages;

// ALTER TABLE products ADD COLUMN isPopular TINYINT(1) DEFAULT 0 AFTER category;
// ALTER TABLE products ADD COLUMN registerTime DATETIME DEFAULT CURRENT_TIMESTAMP AFTER category;

// 헤더 포함
require_once "./util/header.php";
?>

<div class="admin-section product-admin-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">판매상품 관리</h2>
            <p class="section-subtitle">GIFTS:Mall의 판매상품을 관리합니다</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- 상품 추가 버튼 -->
        <div class="d-flex justify-content-end mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fa fa-plus"></i> 새 상품 추가
            </button>
        </div>

        <!-- 상품 목록 및 관리 -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">상품 목록</h5>
                    <div class="product-filters">
                        <div class="category-filter">
                            <a href="?category=all&sort=<?= $sort ?>" class="<?= $category === 'all' ? 'active' : '' ?>">전체</a>
                            <?php foreach ($categories as $cat): ?>
                                <a href="?category=<?= $cat->code ?>&sort=<?= $sort ?>" class="<?= $category === $cat->code ? 'active' : '' ?>"><?= $cat->name ?></a>
                            <?php endforeach; ?>
                        </div>
                        <div class="sort-control">
                            <a href="?category=<?= $category ?>&sort=desc" class="<?= $sort === 'desc' ? 'active' : '' ?>">최신순</a>
                            <span>|</span>
                            <a href="?category=<?= $category ?>&sort=asc" class="<?= $sort === 'asc' ? 'active' : '' ?>">오래된순</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- 페이지 정보 및 네비게이션 -->
                <div class="product-navigation mb-3">
                    <div class="page-info">
                        <div class="total-count">총 <strong><?= $totalProducts ?></strong>개</div>
                        <span class="page-indicator"><?= $currentPage ?> / <?= $totalPages ?> 페이지</span>
                    </div>
                    <div class="page-controls">
                        <a href="?page=<?= $prevPage ?>&category=<?= $category ?>&sort=<?= $sort ?>" class="nav-btn prev-btn <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a href="?page=<?= $nextPage ?>&category=<?= $category ?>&sort=<?= $sort ?>" class="nav-btn next-btn <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- 상품 테이블 -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="10%">이미지</th>
                                <th width="10%">카테고리</th>
                                <th width="15%">상품명</th>
                                <th width="10%">가격</th>
                                <th width="10%">할인</th>
                                <th width="10%">등록시간</th>
                                <th width="10%">인기상품</th>
                                <th width="20%">작업</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($products) > 0): ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= $product->id ?></td>
                                        <td>
                                            <img src="./resources<?= $product->image ?>" alt="<?= $product->title ?>" class="img-thumbnail product-thumb">
                                        </td>
                                        <td><?= $product->categoryName ?></td>
                                        <td><?= $product->title ?></td>
                                        <td><?= number_format($product->price) ?>원</td>
                                        <td>
                                            <?php if ($product->discountOption === 'none'): ?>
                                                없음
                                            <?php elseif ($product->discountOption === 'minus'): ?>
                                                <?= number_format($product->discountValue) ?>원 할인
                                            <?php elseif ($product->discountOption === 'percent'): ?>
                                                <?= ($product->discountValue * 100) ?>% 할인
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('y.m.d H:i', strtotime($product->registerTime)) ?></td>
                                        <td>
                                            <?php if ($product->isPopular): ?>
                                                <span class="badge bg-success">인기상품</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">일반상품</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary edit-product-btn" 
                                                   data-id="<?= $product->id ?>"
                                                   data-bs-toggle="modal" 
                                                   data-bs-target="#editProductModal">
                                                수정
                                            </button>
                                            <a href="?delete=<?= $product->id ?>&page=<?= $currentPage ?>&category=<?= $category ?>&sort=<?= $sort ?>" 
                                               class="btn btn-sm btn-danger delete-product-btn"
                                               onclick="return confirm('정말 삭제하시겠습니까?');">
                                                삭제
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">등록된 상품이 없습니다.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- 모바일 페이지 컨트롤 -->
                <div class="mobile-page-controls">
                    <a href="?page=<?= $prevPage ?>&category=<?= $category ?>&sort=<?= $sort ?>" class="nav-btn prev-btn <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <i class="fa fa-angle-left"></i> 이전
                    </a>
                    <span class="page-indicator"><?= $currentPage ?> / <?= $totalPages ?> 페이지</span>
                    <a href="?page=<?= $nextPage ?>&category=<?= $category ?>&sort=<?= $sort ?>" class="nav-btn next-btn <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        다음 <i class="fa fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 상품 추가 모달 -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">새 상품 추가</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="" id="addProductForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="product_title" class="form-label">상품명 <span class="text-danger">*</span></label>
                            <input type="text" id="product_title" name="product_title" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="product_category" class="form-label">카테고리 <span class="text-danger">*</span></label>
                            <select id="product_category" name="product_category" class="form-select" required>
                                <option value="">선택하세요</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category->code ?>"><?= $category->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="product_description" class="form-label">상품 설명 <span class="text-danger">*</span></label>
                        <textarea id="product_description" name="product_description" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="product_price" class="form-label">가격 <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" id="product_price" name="product_price" class="form-control" required min="0">
                                <span class="input-group-text">원</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="product_shipPrice" class="form-label">배송비 <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" id="product_shipPrice" name="product_shipPrice" class="form-control" required min="0">
                                <span class="input-group-text">원</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="product_benefits" class="form-label">혜택 <span class="text-danger">*</span></label>
                        <input type="text" id="product_benefits" name="product_benefits" class="form-control" required>
                        <div class="form-text">쉼표(,)로 구분하여 여러 혜택을 입력하세요</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="product_image" class="form-label">이미지 경로 <span class="text-danger">*</span></label>
                        <input type="text" id="product_image" name="product_image" class="form-control" required>
                        <div class="form-text">예: /images/카테고리명/1.PNG</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_popular" name="is_popular" value="yes">
                            <label class="form-check-label" for="is_popular">인기상품으로 지정</label>
                        </div>
                        <div class="form-text">인기상품으로 지정하면 같은 카테고리의 기존 인기상품은 자동으로 해제됩니다.</div>
                    </div>
                    
                    <div class="mb-3 discount-options" style="display: none;">
                        <label class="form-label">할인 옵션 <span class="text-danger">*</span></label>
                        <div class="discount-radios">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="discount_option" id="discount_none" value="none" checked>
                                <label class="form-check-label" for="discount_none">할인 없음</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="discount_option" id="discount_minus" value="minus">
                                <label class="form-check-label" for="discount_minus">만원 할인</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="discount_option" id="discount_percent" value="percent">
                                <label class="form-check-label" for="discount_percent">퍼센트 할인</label>
                            </div>
                        </div>
                        
                        <div class="percent-options mt-2" style="display: none;">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="discount_percent" id="discount_10" value="10" checked>
                                <label class="form-check-label" for="discount_10">10% 할인</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="discount_percent" id="discount_30" value="30">
                                <label class="form-check-label" for="discount_30">30% 할인</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" name="add_product" class="btn btn-primary">상품 추가</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- 상품 수정 모달 -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">상품 수정</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="" id="editProductForm">
                    <input type="hidden" id="edit_product_id" name="product_id">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_product_title" class="form-label">상품명 <span class="text-danger">*</span></label>
                            <input type="text" id="edit_product_title" name="product_title" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_product_category" class="form-label">카테고리 <span class="text-danger">*</span></label>
                            <select id="edit_product_category" name="product_category" class="form-select" required>
                                <option value="">선택하세요</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category->code ?>"><?= $category->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_product_description" class="form-label">상품 설명 <span class="text-danger">*</span></label>
                        <textarea id="edit_product_description" name="product_description" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_product_price" class="form-label">가격 <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" id="edit_product_price" name="product_price" class="form-control" required min="0">
                                <span class="input-group-text">원</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_product_shipPrice" class="form-label">배송비 <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" id="edit_product_shipPrice" name="product_shipPrice" class="form-control" required min="0">
                                <span class="input-group-text">원</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_product_benefits" class="form-label">혜택 <span class="text-danger">*</span></label>
                        <input type="text" id="edit_product_benefits" name="product_benefits" class="form-control" required>
                        <div class="form-text">쉼표(,)로 구분하여 여러 혜택을 입력하세요</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_product_image" class="form-label">이미지 경로 <span class="text-danger">*</span></label>
                        <input type="text" id="edit_product_image" name="product_image" class="form-control" required>
                        <div class="form-text">예: /images/카테고리명/1.PNG</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="edit_is_popular" name="is_popular" value="yes">
                            <label class="form-check-label" for="edit_is_popular">인기상품으로 지정</label>
                        </div>
                        <div class="form-text">인기상품으로 지정하면 같은 카테고리의 기존 인기상품은 자동으로 해제됩니다.</div>
                    </div>
                    
                    <div class="mb-3 edit-discount-options">
                        <label class="form-label">할인 옵션 <span class="text-danger">*</span></label>
                        <div class="discount-radios">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="discount_option" id="edit_discount_none" value="none">
                                <label class="form-check-label" for="edit_discount_none">할인 없음</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="discount_option" id="edit_discount_minus" value="minus">
                                <label class="form-check-label" for="edit_discount_minus">만원 할인</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="discount_option" id="edit_discount_percent" value="percent">
                                <label class="form-check-label" for="edit_discount_percent">퍼센트 할인</label>
                            </div>
                        </div>
                        
                        <div class="edit-percent-options mt-2" style="display: none;">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="discount_percent" id="edit_discount_10" value="10">
                                <label class="form-check-label" for="edit_discount_10">10% 할인</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="discount_percent" id="edit_discount_30" value="30">
                                <label class="form-check-label" for="edit_discount_30">30% 할인</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" name="edit_product" class="btn btn-primary">상품 수정</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* 페이지네이션 스타일 */
.product-navigation {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.page-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-controls {
    display: flex;
    gap: 0.5rem;
}

.nav-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: #f8f9fa;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.nav-btn:hover:not(.disabled) {
    background-color: #007bff;
    color: white;
}

.nav-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* 필터 스타일 */
.product-filters {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.category-filter a, .sort-control a {
    color: #666;
    text-decoration: none;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.category-filter a.active, .sort-control a.active {
    background-color: #007bff;
    color: white;
}

.sort-control {
    display: flex;
    align-items: center;
}

.sort-control span {
    color: #ddd;
    margin: 0 0.25rem;
}

/* 모바일 페이지 컨트롤 */
.mobile-page-controls {
    display: none;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

/* 상품 썸네일 */
.product-thumb {
    width: 80px;
    height: 80px;
    object-fit: cover;
}

/* 모바일 최적화 */
@media (max-width: 767.98px) {
    .product-navigation {
        display: none;
    }
    
    .mobile-page-controls {
        display: flex;
    }
    
    .product-filters {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .table-responsive th, .table-responsive td {
        white-space: nowrap;
    }
}
</style>
<script src="./resources/js/jquery-3.4.1.min.js"></script>
<script>
$(document).ready(function() {
    // 인기상품 체크박스에 따라 할인 옵션 표시 여부 설정
    $('#is_popular').on('change', function() {
        if ($(this).is(':checked')) {
            $('.discount-options').slideDown();
        } else {
            $('.discount-options').slideUp();
        }
    });
    
    // 할인 옵션 라디오 버튼에 따라 퍼센트 옵션 표시 여부 설정
    $('input[name="discount_option"]').on('change', function() {
        if ($(this).val() === 'percent') {
            $('.percent-options').slideDown();
        } else {
            $('.percent-options').slideUp();
        }
    });
    
    // 수정 모달에서도 동일하게 적용
    $('#edit_is_popular').on('change', function() {
        toggleEditDiscountOptions();
    });
    
    // 수정 모달의 할인 옵션 라디오 버튼에 따라 퍼센트 옵션 표시 여부 설정
    $('input[name="discount_option"]').on('change', function() {
        if ($(this).val() === 'percent') {
            $('.edit-percent-options').slideDown();
        } else {
            $('.edit-percent-options').slideUp();
        }
    });
    
    // 상품 편집 버튼 클릭 시 상품 정보 로드
    $('.edit-product-btn').on('click', function() {
        const productId = $(this).data('id');
        
        // AJAX로 상품 정보 가져오기
        $.ajax({
            url: 'get_product.php',
            type: 'GET',
            data: { id: productId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const product = response.product;
                    
                    // 상품 정보 채우기
                    $('#edit_product_id').val(product.id);
                    $('#edit_product_title').val(product.title);
                    $('#edit_product_category').val(product.category);
                    $('#edit_product_description').val(product.description);
                    $('#edit_product_price').val(product.price);
                    $('#edit_product_shipPrice').val(product.shipPrice);
                    $('#edit_product_benefits').val(product.benefits);
                    $('#edit_product_image').val(product.image);
                    
                    // 인기상품 여부
                    $('#edit_is_popular').prop('checked', product.isPopular == 1);
                    
                    // 할인 옵션 설정
                    $(`#edit_discount_${product.discountOption}`).prop('checked', true);
                    
                    if (product.discountOption === 'percent') {
                        const percentValue = product.discountValue * 100;
                        if (percentValue === 10) {
                            $('#edit_discount_10').prop('checked', true);
                        } else {
                            $('#edit_discount_30').prop('checked', true);
                        }
                        $('.edit-percent-options').show();
                    } else {
                        $('.edit-percent-options').hide();
                    }
                    
                    // 인기상품이 아닌 경우 할인 옵션 숨기기
                    toggleEditDiscountOptions();
                }
            },
            error: function() {
                alert('상품 정보를 불러오는데 실패했습니다.');
            }
        });
    });
    
    // 폼 제출 전 유효성 검사
    $('#addProductForm, #editProductForm').on('submit', function(e) {
        const isPopular = $(this).find('input[name="is_popular"]').is(':checked');
        
        if (isPopular) {
            const discountOption = $(this).find('input[name="discount_option"]:checked').val();
            
            if (discountOption === 'none') {
                e.preventDefault();
                alert('인기상품은 반드시 할인 방법(만원할인, 10%할인, 30%할인)을 선택해야 합니다.');
                return false;
            }
        }
    });
    
    function toggleEditDiscountOptions() {
        if ($('#edit_is_popular').is(':checked')) {
            $('.edit-discount-options').slideDown();
        } else {
            $('.edit-discount-options').slideUp();
        }
    }
});
</script>

<?php
// 푸터 포함
require_once "./util/footer.php";
?>