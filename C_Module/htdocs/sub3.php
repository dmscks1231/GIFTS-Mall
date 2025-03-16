<?php
namespace LIB\App;

require_once './lib/DB.php';
require_once './lib/lib.php';
require_once "./util/header.php";

// 할인 중인 인기 상품 가져오기 (각 카테고리별로 할인율이 높은 상품 순으로)
$popularProducts = DB::fetchAll(
    "SELECT p.*, 
    CASE 
        WHEN p.discountOption = 'minus' THEN (p.discountValue / p.price) * 100
        WHEN p.discountOption = 'percent' THEN p.discountValue * 100
        ELSE 0
    END AS discount_percentage,
    c.name as category_name
    FROM products p
    JOIN categories c ON p.category = c.code
    WHERE p.discountOption != 'none'
    ORDER BY discount_percentage DESC
    LIMIT 10"
);

$categoryImages = [
    'health' => '건강식품',
    'digital' => '디지털',
    'fancy' => '팬시',
    'perfume' => '향수',
    'haircare' => '헤어케어'
];
?>
<style>
    /* 인기상품 페이지 추가 스타일 */
    .product-card {
        position: relative;
    }

    .on-sale-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: #e54980;
        color: white;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        z-index: 5;
        box-shadow: 0 4px 10px rgba(229, 73, 128, 0.3);
    }

    .discount-rate {
        background-color: #ff4757;
        color: white;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 700;
        margin-left: 5px;
        position: relative;
        top: -1px;
    }

    .popular-section {
        padding: 50px 0;
        background-color: #fff;
        margin-top: 85px;
    }
    
    .category-tag {
        position: absolute;
        right: 15px;
        top: 15px;
        padding: 5px 10px;
        background-color: rgba(0, 0, 0, 0.6);
        color: white;
        font-size: 11px;
        border-radius: 4px;
        z-index: 5;
    }
</style>
<!-- 인기상품 섹션 -->
<section class="popular-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">인기 할인 상품</h2>
            <p class="section-subtitle">고객님들이 가장 많이 찾는 특별 할인 상품들을 모았습니다</p>
        </div>

        <!-- 인기 상품 그리드 -->
        <div class="product-grid">
            <?php foreach ($popularProducts as $product): 
                // 할인율 계산
                $discountRate = 0;
                if ($product->discountOption === 'minus') {
                    $discountRate = round(($product->discountValue / $product->price) * 100);
                } elseif ($product->discountOption === 'percent') {
                    $discountRate = round($product->discountValue * 100);
                }
                
                // 할인 가격 계산
                $discountedPrice = $product->price;
                if ($product->discountOption === 'minus') {
                    $discountedPrice = $product->price - $product->discountValue;
                } elseif ($product->discountOption === 'percent') {
                    $discountedPrice = $product->price - ($product->price * $product->discountValue);
                }
            ?>
            <div class="product-card">
                <div class="on-sale-badge">SALE <span class="discount-rate">-<?= $discountRate ?>%</span></div>
                <div class="category-tag"><?= htmlspecialchars($product->category_name) ?></div>
                <div class="product-thumb">
                    <img src="./resources/images/<?= $categoryImages[$product->category] ?>/<?= basename($product->image) ?>" alt="<?= htmlspecialchars($product->title) ?>" />
                    <div class="product-wishlist">
                        <i class="fa fa-heart-o"></i>
                    </div>
                    <div class="product-action">
                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                        <a href="./process/add_to_cart.php?product_id=<?= $product->id ?>" class="add-to-cart"><i class="fa fa-shopping-cart"></i> 장바구니담기</a>
                    </div>
                </div>
                <div class="product-info">
                    <div class="product-category"><?= $product->category_name ?></div>
                    <h4 class="product-title"><?= htmlspecialchars($product->title) ?></h4>
                    <div class="product-price">
                        <span class="old-price"><?= number_format($product->price) ?>원</span>
                        <span class="current-price"><?= number_format($discountedPrice) ?>원</span>
                    </div>
                    <div class="product-rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star-half-o"></i>
                        <span>(<?= rand(20, 100) ?>)</span>
                    </div>
                    <div class="product-description">
                        <?= htmlspecialchars($product->description) ?>
                    </div>
                    <div class="product-benefits">
                        <span><i class="fa fa-truck"></i> 무료배송</span>
                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
    require_once "./util/footer.php";
?>