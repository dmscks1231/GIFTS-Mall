<?php
namespace LIB\App;

require_once './lib/DB.php';
require_once './lib/lib.php';
require_once "./util/header.php";

// 로그인 여부 확인
if (Lib::isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    
    // 장바구니 데이터 가져오기
    $cartItems = DB::fetchAll(
        "SELECT b.id, b.product_id, b.count, 
                p.title, p.price, p.description, p.category, p.shipPrice, 
                p.discountOption, p.discountValue, p.image,
                c.name as category_name
        FROM buckets b
        JOIN products p ON b.product_id = p.id
        JOIN categories c ON p.category = c.code
        WHERE b.user_id = ?
        ORDER BY b.id DESC", 
        [$user_id]
    );
    
    // 카테고리별 이미지 폴더 매핑
    $categoryImages = [
        'health' => '건강식품',
        'digital' => '디지털',
        'fancy' => '팬시',
        'perfume' => '향수',
        'haircare' => '헤어케어'
    ];
    
    // 전체 상품 금액과 할인 금액 계산
    $totalOriginalPrice = 0;
    $totalDiscountAmount = 0;
    $shippingFee = 0; // 기본 배송비
    $categories = []; // 카테고리별 배송비 한 번만 계산하기 위한 배열
    
    foreach ($cartItems as $item) {
        // 할인된 가격 계산
        $discountedPrice = $item->price;
        if ($item->discountOption === 'minus') {
            $discountedPrice = $item->price - $item->discountValue;
            $totalDiscountAmount += ($item->discountValue * $item->count);
        } elseif ($item->discountOption === 'percent') {
            $discountedPrice = $item->price - ($item->price * $item->discountValue);
            $totalDiscountAmount += (($item->price * $item->discountValue) * $item->count);
        }
        
        // 원래 가격 * 수량 누적
        $totalOriginalPrice += ($item->price * $item->count);
        
        // 카테고리별 배송비 계산 (같은 카테고리는 한 번만 계산)
        if (!in_array($item->category, $categories)) {
            $shippingFee += $item->shipPrice;
            $categories[] = $item->category;
        }
    }
    
    // 최종 결제 금액
    $totalPaymentAmount = $totalOriginalPrice - $totalDiscountAmount + $shippingFee;
} else {
    // 비로그인 상태일 경우 빈 배열로 초기화
    $cartItems = [];
    $totalOriginalPrice = 0;
    $totalDiscountAmount = 0;
    $shippingFee = 0;
    $totalPaymentAmount = 0;
}
?>

<style>
    /* 장바구니 페이지 추가 스타일 */
    .cart-section {
        padding: 40px 0 80px;
        background-color: #f9f9f9;
        min-height: calc(100vh - 85px - 300px);
        margin-top: 85px;
    }

    .cart-header {
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-title {
        font-size: 28px;
        font-weight: 700;
        color: #333;
        position: relative;
        display: inline-block;
        letter-spacing: -0.5px;
    }

    .cart-title::after {
        content: "";
        position: absolute;
        width: 40px;
        height: 3px;
        background-color: #e54980;
        bottom: -10px;
        left: 0;
        border-radius: 2px;
    }

    .continue-shopping {
        display: flex;
        align-items: center;
        color: #666;
        font-size: 14px;
        transition: all 0.3s;
        font-weight: 500;
    }

    .continue-shopping i {
        margin-right: 6px;
        transition: transform 0.3s;
    }

    .continue-shopping:hover {
        color: #e54980;
    }

    .continue-shopping:hover i {
        transform: translateX(-4px);
    }

    .cart-items {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 125px 1fr 150px 150px 120px 50px;
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        align-items: center;
        position: relative;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-header-row {
        background-color: #f8f8f8;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .cart-header-row .cart-cell {
        padding: 15px 20px;
    }

    .item-image {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        overflow: hidden;
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-details {
        padding: 0 20px;
    }

    .item-name {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        overflow: hidden;
        line-height: 1.3;
    }

    .item-category {
        font-size: 13px;
        color: #888;
    }

    .item-price {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .item-original-price {
        font-size: 13px;
        color: #999;
        text-decoration: line-through;
        margin-right: 5px;
    }

    .item-current-price {
        color: #e54980;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee;
        border-radius: 5px;
        width: 120px;
        overflow: hidden;
    }

    .quantity-btn {
        width: 36px;
        height: 36px;
        background-color: #f5f5f5;
        border: none;
        color: #555;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .quantity-btn:hover {
        background-color: #e9e9e9;
    }

    .quantity-input {
        width: 48px;
        height: 36px;
        border: none;
        text-align: center;
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .item-total {
        font-size: 16px;
        font-weight: 700;
        color: #e54980;
    }

    .item-remove {
        color: #ccc;
        font-size: 18px;
        cursor: pointer;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        border-radius: 50%;
    }

    .item-remove:hover {
        color: #e54980;
        background-color: #f9f9f9;
    }

    .cart-summary {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        padding: 25px;
        margin-top: 20px;
    }

    .summary-heading {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .summary-items {
        margin-bottom: 20px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
        color: #666;
    }

    .summary-item.total {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        padding-top: 15px;
        margin-top: 15px;
        border-top: 1px solid #f0f0f0;
    }

    .summary-item.total .price {
        color: #e54980;
    }

    .checkout-btn {
        width: 100%;
        height: 50px;
        background-color: #e54980;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .checkout-btn:hover {
        background-color: #d43a6e;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(229, 73, 128, 0.3);
    }

    .empty-cart {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 0;
        text-align: center;
    }

    .empty-cart-icon {
        font-size: 60px;
        color: #ddd;
        margin-bottom: 20px;
    }

    .empty-cart-text {
        font-size: 18px;
        color: #888;
        margin-bottom: 25px;
    }

    .shop-now-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 25px;
        background-color: #e54980;
        color: white;
        border-radius: 5px;
        font-size: 15px;
        font-weight: 600;
        transition: all 0.3s;
        gap: 8px;
    }

    .shop-now-btn:hover {
        background-color: #d43a6e;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(229, 73, 128, 0.3);
    }

    .benefits-info {
        font-size: 12px;
        color: #888;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 6px;
    }

    .benefits-info span {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .benefits-info i {
        color: #e54980;
    }
    
    .option-text {
        margin-top: 4px;
        font-size: 12px;
        color: #666;
    }

    /* 480px 이하 모바일 반응형 스타일 */
    @media (max-width: 480px) {
        /* 장바구니 섹션 기본 스타일 조정 */
        .cart-section {
            padding: 30px 0 50px;
            margin-top: 60px;
        }

        /* 헤더 영역 조정 */
        .cart-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
        }

        .cart-title {
            font-size: 22px;
        }

        .cart-title::after {
            width: 30px;
            height: 2px;
            bottom: -8px;
        }

        /* 카트 아이템 조정 */
        .cart-item {
            grid-template-columns: 70px 1fr;
            padding: 12px;
            row-gap: 8px;
        }

        .item-image {
            width: 60px;
            height: 60px;
        }

        .item-details {
            padding: 0 0 0 10px;
        }

        .item-name {
            font-size: 14px;
            margin-bottom: 2px;
            padding-right: 20px; /* 삭제 버튼을 위한 공간 */
        }

        .item-category {
            font-size: 12px;
        }

        .option-text {
            font-size: 11px;
            margin-top: 2px;
        }

        .benefits-info {
            font-size: 10px;
            margin-top: 4px;
            flex-wrap: wrap;
            gap: 6px;
        }

        /* 가격 정보 */
        .item-price {
            font-size: 14px;
        }

        .item-original-price {
            font-size: 11px;
        }

        .item-current-price {
            font-size: 14px;
        }

        .item-total {
            font-size: 14px;
        }

        /* 수량 컨트롤 */
        .quantity-control {
            width: 100px;
            margin: 5px 0;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            font-size: 14px;
        }

        .quantity-input {
            width: 40px;
            height: 30px;
            font-size: 13px;
        }

        /* 삭제 버튼 */
        .item-remove {
            width: 24px;
            height: 24px;
            font-size: 14px;
            top: 10px;
            right: 10px;
        }

        /* 주문 요약 */
        .cart-summary {
            padding: 15px;
            margin-top: 30px;
        }

        .summary-heading {
            font-size: 16px;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .summary-item {
            font-size: 13px;
            margin-bottom: 8px;
        }

        .summary-item.total {
            font-size: 16px;
            padding-top: 10px;
            margin-top: 10px;
        }

        /* 체크아웃 버튼 */
        .checkout-btn {
            height: 45px;
            font-size: 15px;
        }

        /* 빈 장바구니 */
        .empty-cart {
            padding: 40px 0;
        }

        .empty-cart-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .empty-cart-text {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .shop-now-btn {
            padding: 10px 20px;
            font-size: 14px;
        }

        .cart-header-row {
            display: none; /* 모바일에서는 헤더 행을 숨김 */
        }
    }
</style>

<!-- 장바구니 섹션 시작 -->
<section class="cart-section">
    <div class="container">
        <div class="cart-header">
            <h1 class="cart-title">장바구니</h1>
            <a href="products.php" class="continue-shopping">
                <i class="fa fa-arrow-left"></i> 계속 쇼핑하기
            </a>
        </div>

        <?php if (Lib::isLoggedIn() && !empty($cartItems)): ?>
        <!-- 장바구니 상품 목록 -->
        <div class="cart-items">
            <!-- 헤더 로우 -->
            <div class="cart-item cart-header-row">
                <div class="cart-cell">상품 이미지</div>
                <div class="cart-cell">상품 정보</div>
                <div class="cart-cell">상품 가격</div>
                <div class="cart-cell">수량</div>
                <div class="cart-cell">총 금액</div>
                <div class="cart-cell"></div>
            </div>

            <?php foreach ($cartItems as $item): 
                // 할인된 가격 계산
                $discountedPrice = $item->price;
                if ($item->discountOption === 'minus') {
                    $discountedPrice = $item->price - $item->discountValue;
                } elseif ($item->discountOption === 'percent') {
                    $discountedPrice = $item->price - ($item->price * $item->discountValue);
                }
                
                // 해당 상품의 총 금액 계산
                $itemTotal = $discountedPrice * $item->count;
            ?>
            <div class="cart-item">
                <div class="item-image">
                    <img src="./resources/images/<?= $categoryImages[$item->category] ?>/<?= basename($item->image) ?>" alt="<?= htmlspecialchars($item->title) ?>">
                </div>
                <div class="item-details">
                    <h3 class="item-name"><?= htmlspecialchars($item->title) ?></h3>
                    <div class="item-category"><?= htmlspecialchars($item->category_name) ?></div>
                    <div class="benefits-info">
                        <span><i class="fa fa-truck"></i> 무료배송</span>
                        <?php if ($item->discountOption !== 'none'): ?>
                        <span><i class="fa fa-credit-card"></i> 할인 적용</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="item-price">
                    <?php if ($item->discountOption !== 'none'): ?>
                    <div class="item-original-price"><?= number_format($item->price) ?>원</div>
                    <div class="item-current-price"><?= number_format($discountedPrice) ?>원</div>
                    <?php else: ?>
                    <div class="item-current-price"><?= number_format($item->price) ?>원</div>
                    <?php endif; ?>
                </div>
                <div class="quantity-control">
                    <button class="quantity-btn minus" data-id="<?= $item->id ?>" data-action="decrease">-</button>
                    <input type="text" class="quantity-input" value="<?= $item->count ?>" readonly>
                    <button class="quantity-btn plus" data-id="<?= $item->id ?>" data-action="increase">+</button>
                </div>
                <div class="item-total"><?= number_format($itemTotal) ?>원</div>
                <a href="process/remove_cart_item.php?id=<?= $item->id ?>" class="item-remove"><i class="fa fa-times"></i></a>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-bottom">
            <div class="row">
                <div class="col-md-5">
                    <!-- 주문 요약 -->
                    <div class="cart-summary">
                        <h3 class="summary-heading">주문 요약</h3>
                        <div class="summary-items">
                            <div class="summary-item">
                                <span>상품 금액</span>
                                <span class="price"><?= number_format($totalOriginalPrice) ?>원</span>
                            </div>
                            <div class="summary-item">
                                <span>배송비</span>
                                <span class="price"><?= $shippingFee > 0 ? number_format($shippingFee).'원' : '무료' ?></span>
                            </div>
                            <div class="summary-item">
                                <span>할인 금액</span>
                                <span class="price">-<?= number_format($totalDiscountAmount) ?>원</span>
                            </div>
                            <div class="summary-item total">
                                <span>결제 예정 금액</span>
                                <span class="price"><?= number_format($totalPaymentAmount) ?>원</span>
                            </div>
                        </div>
                        <button class="checkout-btn">
                            <i class="fa fa-credit-card"></i> 구매하기
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- 빈 장바구니 또는 로그인 필요 메시지 -->
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="empty-cart-text">
                <?php if (!Lib::isLoggedIn()): ?>
                장바구니를 이용하시려면 로그인이 필요합니다.
                <?php else: ?>
                장바구니가 비어있습니다.
                <?php endif; ?>
            </div>
            <?php if (!Lib::isLoggedIn()): ?>
            <a href="#" class="shop-now-btn" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="fa fa-sign-in"></i> 로그인하기
            </a>
            <?php else: ?>
            <a href="products.php" class="shop-now-btn">
                <i class="fa fa-shopping-bag"></i> 쇼핑하러 가기
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
$(document).ready(function() {
    // 수량 조절 버튼 이벤트 처리
    $('.quantity-btn').on('click', function() {
        var itemId = $(this).data('id');
        var action = $(this).data('action');
        
        // AJAX 요청으로 수량 업데이트
        $.ajax({
            url: './process/update_cart_quantity.php',
            type: 'GET',
            dataType: 'json',
            data: {
                id: itemId,
                action: action
            },
            success: function(data) {
                if (data.success) {
                    // 성공 시 페이지 새로고침
                    window.location.reload();
                } else {
                    alert(data.message || '수량 변경에 실패했습니다.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('오류가 발생했습니다.');
            }
        });
    });
    
    // 장바구니 아이템 삭제 확인
    $('.item-remove').on('click', function(e) {
        if (!confirm('정말 이 상품을 장바구니에서 삭제하시겠습니까?')) {
            e.preventDefault();
        }
    });
});
</script>

<?php
require_once "./util/footer.php";
?>