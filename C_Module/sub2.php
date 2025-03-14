<?php
namespace LIB\App;

    require_once './lib/DB.php';
    require_once './lib/lib.php';
    require_once "./util/header.php";
?>
<link rel="stylesheet" href="./resources/css/sub2.css">
    <!-- 비디오 섹션은 원래 코드 그대로 유지 -->
    <section class="video-ad-section">
        <div class="container">
            <div class="video-wrapper">
                <video id="adVideo" src="./resources/js/AD.mp4" preload="auto" muted>
                    브라우저가 비디오 태그를 지원하지 않습니다.
                </video>
                <button id="showControlsBtn" class="show-controls-btn">
                    <i class="fa fa-sliders"></i>
                    <span>컨트롤러 보이기</span>
                </button>
                <div class="video-controls" id="videoControls">
                    <div class="controls-row">
                        <div class="control-panel">
                            <div class="control-group main-controls">
                                <button id="rewindBtn" class="control-btn" title="10초 되감기">
                                    <i class="fa fa-backward"></i>
                                </button>
                                <button id="playBtn" class="control-btn play-btn" title="재생">
                                    <i class="fa fa-play"></i>
                                </button>
                                <button id="pauseBtn" class="control-btn play-btn" title="일시정지">
                                    <i class="fa fa-pause"></i>
                                </button>
                                <button id="stopBtn" class="control-btn" title="정지">
                                    <i class="fa fa-stop"></i>
                                </button>
                                <button id="forwardBtn" class="control-btn" title="10초 빨리감기">
                                    <i class="fa fa-forward"></i>
                                </button>
                            </div>

                            <div class="control-group speed-controls">
                                <button id="slowDownBtn" class="control-btn" title="감속 (0.1배씩)">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <button id="normalSpeedBtn" class="control-btn" title="기본 속도">
                                    <span class="speed-value">1.0x</span>
                                </button>
                                <button id="speedUpBtn" class="control-btn" title="배속 (0.1배씩)">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>

                            <div class="control-group setting-controls">
                                <button id="loopBtn" class="control-btn toggle-btn" title="반복 재생" data-active="false">
                                    <i class="fa fa-repeat"></i>
                                </button>
                                <button id="autoplayBtn" class="control-btn toggle-btn" title="자동 재생"
                                    data-active="false">
                                    <i class="fa fa-play-circle"></i>
                                </button>
                                <button id="hideControlsBtn" class="control-btn" title="컨트롤러 숨기기">
                                    <i class="fa fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 판매상품 섹션 시작 -->
    <section class="products-section">
        <!-- 배경 도형 요소 -->
        <div class="dynamic-background">
            <div class="bg-shape large-circle"></div>
            <div class="bg-shape large-square"></div>
            <div class="bg-shape medium-circle"></div>
            <div class="bg-shape medium-square"></div>
        </div>

        <div class="container">
            <div class="section-header">
                <h2 class="section-title">GIFTS:Mall의 전체상품</h2>
                <p class="section-subtitle">특별한 순간을 위한 GIFTS:Mall의 모든 제품들을 만나보세요</p>
            </div>

            <!-- 카테고리 탭 메뉴 -->
            <div class="category-tabs">
                <input type="radio" name="category-tab" id="tab-healthcare" checked class="tab-input">
                <input type="radio" name="category-tab" id="tab-digital" class="tab-input">
                <input type="radio" name="category-tab" id="tab-accessories" class="tab-input">
                <input type="radio" name="category-tab" id="tab-fragrance" class="tab-input">
                <input type="radio" name="category-tab" id="tab-haircare" class="tab-input">

                <ul class="tab-list">
                    <li class="tab-item"><label for="tab-healthcare"><i class="fa fa-heartbeat"></i> 건강식품</label></li>
                    <li class="tab-item"><label for="tab-digital"><i class="fa fa-laptop"></i> 디지털/가전</label></li>
                    <li class="tab-item"><label for="tab-accessories"><i class="fa fa-gift"></i> 주얼리/액세서리</label></li>
                    <li class="tab-item"><label for="tab-fragrance"><i class="fa fa-leaf"></i> 향수/화장품</label></li>
                    <li class="tab-item"><label for="tab-haircare"><i class="fa fa-cut"></i> 헤어케어</label></li>
                </ul>

                <!-- 상품 컨테이너 -->
                <div class="products-container">
                    <?php
                    // 각 카테고리별 상품 조회
                    $categories = ['health', 'digital', 'fancy', 'perfume', 'haircare'];
                    $categoryIds = [
                        'health' => 'healthcare', 
                        'digital' => 'digital', 
                        'fancy' => 'accessories', 
                        'perfume' => 'fragrance', 
                        'haircare' => 'haircare'
                    ];
                    $categoryNames = [
                        'health' => '건강식품',
                        'digital' => '디지털',
                        'fancy' => '팬시',
                        'perfume' => '향수',
                        'haircare' => '헤어케어'
                    ];
                    
                    // 각 카테고리별 처리
                    foreach ($categories as $category) {
                        // 카테고리별 상품 가져오기 - 세일 상품 먼저 정렬
                        $products = DB::fetchAll("SELECT * FROM products WHERE category = ? ORDER BY 
                                                (CASE WHEN discountOption != 'none' THEN 0 ELSE 1 END), 
                                                id ASC", [$category]);
                        $count = count($products);
                        
                        // 카테고리 ID 정의
                        $categoryId = $categoryIds[$category];
                    ?>
                    <!-- <?= $categoryNames[$category] ?> 카테고리 -->
                    <div class="product-category" id="<?= $categoryId ?>">
                        <div class="category-header">
                            <a href="#" class="view-all result-count">총 <strong><?= $count ?></strong>개 상품</a>
                        </div>
                        <div class="product-grid">
                            <?php foreach ($products as $product) { ?>
                            <div class="product-card">
                                <?php if ($product->discountOption !== 'none') { ?>
                                <div class="product-badge sale">SALE</div>
                                <?php } ?>
                                
                                <div class="product-thumb">
                                    <img src="./resources/images/<?= $categoryNames[$category] ?>/<?= basename($product->image) ?>" alt="<?= htmlspecialchars($product->title) ?>" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="buy-now"><i class="fa fa-bolt"></i> 구매하기</a>
                                        <a href="./process/add_to_cart.php?product_id=<?= $product->id ?>" class="cart-btn"><i class="fa fa-shopping-cart"></i> 장바구니담기</a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category"><?= $categoryNames[$category] ?></div>
                                    <h4 class="product-title"><?= htmlspecialchars($product->title) ?></h4>
                                    <div class="product-price">
                                        <?php 
                                        $discountedPrice = $product->price;
                                        if ($product->discountOption === 'minus') {
                                            $discountedPrice = $product->price - $product->discountValue;
                                            $discountRate = round(($product->discountValue / $product->price) * 100);
                                        } elseif ($product->discountOption === 'percent') {
                                            $discountedPrice = $product->price - ($product->price * $product->discountValue);
                                            $discountRate = round($product->discountValue * 100);
                                        }
                                        
                                        if ($product->discountOption !== 'none') { 
                                        ?>
                                        <span class="old-price"><?= number_format($product->price) ?>원</span>
                                        <span class="current-price"><?= number_format($discountedPrice) ?>원</span>
                                        <div class="discount-rate">-<?= $discountRate ?>%</div>
                                        <?php } else { ?>
                                        <span class="current-price"><?= number_format($product->price) ?>원</span>
                                        <?php } ?>
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
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <!-- 비회원 주문 버튼 -->
    <div class="non-member-order-button-container">
        <button id="nonMemberOrderBtn" class="non-member-order-btn">
            <i class="fa fa-shopping-cart"></i> 비회원 주문하기
        </button>
    </div>

    <!-- 비회원 주문 모달 (원래 코드 그대로 유지) -->
    <div id="nonMemberOrderModal" class="modal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h2>비회원 주문</h2>
                <button class="close-modal"><i class="fa fa-times"></i></button>
            </div>

            <div class="modal-body">
                <!-- 회원정보 영역 -->
                <div class="member-info-area">
                    <h3>회원정보</h3>
                    <div class="guest-id-container">
                        <span>비회원 ID: </span>
                        <span id="guestId" class="guest-id"></span>
                    </div>
                </div>

                <!-- 전시 영역 -->
                <div class="display-area">
                    <h3>상품 선택</h3>
                    <p class="drag-instruction"><i class="fa fa-hand-paper-o"></i> 상품을 드래그하여 주문 영역으로 옮기세요</p>

                    <div class="product-categories">
                        <ul class="tab-list">
                            <li class="tab-item" data-category="health">
                                <i class="fa fa-heartbeat"></i> 건강식품
                            </li>
                            <li class="tab-item" data-category="digital">
                                <i class="fa fa-laptop"></i> 디지털/가전
                            </li>
                            <li class="tab-item" data-category="fancy">
                                <i class="fa fa-gift"></i> 주얼리/액세서리
                            </li>
                            <li class="tab-item" data-category="perfume">
                                <i class="fa fa-leaf"></i> 향수/화장품
                            </li>
                            <li class="tab-item" data-category="haircare">
                                <i class="fa fa-cut"></i> 헤어케어
                            </li>
                        </ul>

                        <div class="order-products-container">
                            <div class="product-category" id="display-health">
                                <!-- 상품 카드는 JavaScript로 동적 생성됨 -->
                            </div>
                            <div class="product-category" id="display-digital"></div>
                            <div class="product-category" id="display-fancy"></div>
                            <div class="product-category" id="display-perfume"></div>
                            <div class="product-category" id="display-haircare"></div>
                        </div>
                    </div>
                </div>

                <!-- 주문 영역 -->
                <div class="order-area" id="orderArea">
                    <h3>주문 상품</h3>
                    <p class="drag-instruction empty-cart"><i class="fa fa-shopping-cart"></i> 상품을 이곳으로 드래그하세요</p>
                    <p class="drag-instruction remove-instruction"><i class="fa fa-trash"></i> 상품을 밖으로 드래그하여 제거할 수 있습니다
                    </p>

                    <div class="order-product-list" id="orderProductList">
                        <!-- 주문된 상품들이 여기에 추가됩니다 -->
                    </div>
                </div>

                <!-- 결제 영역 -->
                <div class="payment-area">
                    <h3>결제 정보</h3>
                    <div class="payment-info">
                        <div class="payment-row">
                            <span>총 상품금액:</span>
                            <span id="totalProductAmount">0원</span>
                        </div>
                        <div class="payment-row">
                            <span>배송비:</span>
                            <span id="shippingFee">0원</span>
                        </div>
                        <div class="payment-row total">
                            <span>최종 결제금액:</span>
                            <span id="totalPaymentAmount">0원</span>
                        </div>
                    </div>

                    <button id="orderButton" class="order-button" disabled>
                        <i class="fa fa-check-circle"></i> 주문하기
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 주문 완료 알림 -->
    <div id="orderCompleteNotification" class="order-notification">
        <div class="notification-content">
            <i class="fa fa-check-circle"></i>
            <p id="notificationMessage"></p>
        </div>
    </div>

    <script src="./resources/js/jquery-3.4.1.min.js"></script>
    <script src="./resources/js/video.js"></script>
    <script src="./resources/js/app.js"></script>

<?php
    require_once "./util/footer.php";
?>