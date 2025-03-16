<?php
// 헤더 포함
require_once "./util/header.php";

// 공지사항 처리 파일 포함
require_once './process/notices_process.php';

// 페이지 파라미터 가져오기
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc';

// 페이지 유효성 검사
if ($page < 1) $page = 1;

// 공지사항 가져오기
$noticesData = LIB\App\getNotices($page, $category, $sort);
$notices = $noticesData['notices'];
$totalPages = $noticesData['totalPages'];
$currentPage = $noticesData['currentPage'];
$totalNotices = $noticesData['totalNotices'];

// 이전, 다음 페이지 계산
$prevPage = ($currentPage > 1) ? $currentPage - 1 : 1;
$nextPage = ($currentPage < $totalPages) ? $currentPage + 1 : $totalPages;

// 최신 주요 공지사항 가져오기
$latestNotice = LIB\App\DB::fetch("SELECT * FROM notices WHERE category = '일반' ORDER BY date DESC LIMIT 1");
?>

    <!-- 비주얼 슬라이더 영역 시작 -->
    <div class="visual-slider">
        <!-- 슬라이더 컨테이너 -->
        <div class="slider-container">
            <!-- 첫 번째 슬라이드 -->
            <div class="slide">
                <div class="slide-bg slide-bg-1"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <span class="slide-tag"><i class="fa fa-gift"></i> 브랜드 철학</span>
                    <h2 class="slide-title">Better Give & Take</h2>
                    <p class="slide-subtitle">옴니채널 플랫폼 GIFTS로 전 세계 고객에게 선물의 가치를 높입니다.</p>
                    <div class="slide-action">
                        <a href="#" class="slide-btn primary-btn">자세히 보기 <i class="fa fa-arrow-right"></i></a>
                        <a href="#" class="slide-btn outline-btn">선물하기 <i class="fa fa-gift"></i></a>
                    </div>
                </div>
                <div class="slide-decor">
                    <i class="fa fa-gift decor-icon"></i>
                    <i class="fa fa-heart decor-icon"></i>
                    <i class="fa fa-star decor-icon"></i>
                </div>
            </div>

            <!-- 두 번째 슬라이드 -->
            <div class="slide">
                <div class="slide-bg slide-bg-2"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <span class="slide-tag"><i class="fa fa-star"></i> 비전</span>
                    <h2 class="slide-title">Life Style Platforms</h2>
                    <p class="slide-subtitle">고객과 가장 가까운 곳에서 고객에게 다양한 즐거움을 선물합니다.</p>
                    <div class="slide-action">
                        <a href="#" class="slide-btn primary-btn">스타일 보기 <i class="fa fa-arrow-right"></i></a>
                        <a href="#" class="slide-btn outline-btn">라이프스타일 <i class="fa fa-heart"></i></a>
                    </div>
                </div>
                <div class="slide-decor">
                    <i class="fa fa-star decor-icon"></i>
                    <i class="fa fa-magic decor-icon"></i>
                    <i class="fa fa-heart decor-icon"></i>
                </div>
            </div>

            <!-- 세 번째 슬라이드 -->
            <div class="slide">
                <div class="slide-bg slide-bg-3"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <span class="slide-tag"><i class="fa fa-truck"></i> 서비스</span>
                    <h2 class="slide-title">Online PLAYGROUND</h2>
                    <p class="slide-subtitle">업계 최초 당일 배송 서비스인 '오늘드림'으로 고객 니즈 충족과 고객 경험을 혁신합니다.</p>
                    <div class="slide-action">
                        <a href="#" class="slide-btn primary-btn">당일배송 확인 <i class="fa fa-arrow-right"></i></a>
                        <a href="#" class="slide-btn outline-btn">배송조회 <i class="fa fa-truck"></i></a>
                    </div>
                </div>
                <div class="slide-decor">
                    <i class="fa fa-truck decor-icon"></i>
                    <i class="fa fa-clock-o decor-icon"></i>
                    <i class="fa fa-thumbs-up decor-icon"></i>
                </div>
            </div>
            <!-- 네 번째 슬라이드 -->
            <div class="slide">
                <div class="slide-bg slide-bg-4"></div>
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <span class="slide-tag"><i class="fa fa-gift"></i> 브랜드 철학</span>
                    <h2 class="slide-title">Better Give & Take</h2>
                    <p class="slide-subtitle">옴니채널 플랫폼 GIFTS로 전 세계 고객에게 선물의 가치를 높입니다.</p>
                    <div class="slide-action">
                        <a href="#" class="slide-btn primary-btn">자세히 보기 <i class="fa fa-arrow-right"></i></a>
                        <a href="#" class="slide-btn outline-btn">선물하기 <i class="fa fa-gift"></i></a>
                    </div>
                </div>
                <div class="slide-decor">
                    <i class="fa fa-gift decor-icon"></i>
                    <i class="fa fa-heart decor-icon"></i>
                    <i class="fa fa-star decor-icon"></i>
                </div>
            </div>
        </div>

        <!-- 슬라이드 네비게이션 및 인디케이터 -->
        <div class="slider-controls">
            <button class="prev-btn"><i class="fa fa-angle-left"></i></button>
            <button class="next-btn"><i class="fa fa-angle-right"></i></button>
        </div>
    </div>

   <!-- 판매상품 섹션 시작 -->
   <section class="products-section">
        <!-- 더 눈에 띄는 배경 도형 요소 -->
        <div class="dynamic-background">
            <!-- 큰 도형 요소들 -->
            <div class="bg-shape large-circle"></div>
            <div class="bg-shape large-square"></div>

            <!-- 중간 도형 요소들 -->
            <div class="bg-shape medium-circle"></div>
            <div class="bg-shape medium-square"></div>
        </div>

        <div class="container">
            <div class="section-header">
                <h2 class="section-title">이달의 BEST 판매 상품</h2>
                <p class="section-subtitle">특별한 순간을 위한 GIFTS:Mall의 엄선된 제품들을 만나보세요</p>
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
                    <!-- 건강식품 카테고리 -->
                    <div class="product-category" id="healthcare">
                        <div class="category-header">
                            <a href="#" class="view-all">전체보기 <i class="fa fa-angle-right"></i></a>
                        </div>
                        <div class="product-grid">
                            <!-- 상품 1 -->
                            <div class="product-card">
                                <div class="product-badge sale">SALE</div>
                                <div class="product-thumb">
                                    <img src="./resources/images/건강식품/1.PNG" alt="이뮨 멀티비타민&미네랄" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">멀티비타민</div>
                                    <h4 class="product-title">이뮨 멀티비타민&미네랄</h4>
                                    <div class="product-price">
                                        <span class="old-price">75,000원</span>
                                        <span class="current-price">65,000원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                        <span>(42)</span>
                                    </div>
                                    <div class="product-description">
                                        국내 판매 1위 멀티비타민 이뮨 14일분에 이중제형 + 남/녀 맞춤설계 포뮬러를 적용한 신제품
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 상품 2 -->
                            <div class="product-card">
                                <div class="product-thumb">
                                    <img src="./resources/images/건강식품/2.PNG" alt="센트룸 우먼 더블업" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">여성 종합비타민</div>
                                    <h4 class="product-title">센트룸 우먼 더블업</h4>
                                    <div class="product-price">
                                        <span class="current-price">27,000원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                        <span>(31)</span>
                                    </div>
                                    <div class="product-description">
                                        생기 넘치는 일상을 위한 센트룸 우먼 더블업. 비타민 B군 8종 함량 2배, 23가지 비타민과 미네랄
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 상품 3 -->
                            <div class="product-card">
                                <div class="product-thumb">
                                    <img src="./resources/images/건강식품/3.PNG" alt="닥터브라이언 비타민 구미" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">비타민 젤리</div>
                                    <h4 class="product-title">닥터브라이언 비타민 구미</h4>
                                    <div class="product-price">
                                        <span class="current-price">2,000원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <span>(87)</span>
                                    </div>
                                    <div class="product-description">
                                        달콤한 청포도맛 구미로 맛있게 비타민 C와 D를 충전하세요. 활기찬 하루와 튼튼한 뼈 건강
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 디지털/가전 카테고리 -->
                    <div class="product-category" id="digital">
                        <div class="category-header">
                            <a href="#" class="view-all">전체보기 <i class="fa fa-angle-right"></i></a>
                        </div>
                        <div class="product-grid">
                            <!-- 상품 1 -->
                            <div class="product-card">
                                <div class="product-thumb">
                                    <img src="./resources/images/디지털/1.PNG" alt="PANTONE PD충전 보조배터리" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">보조배터리</div>
                                    <h4 class="product-title">PANTONE PD충전 보조배터리</h4>
                                    <div class="product-price">
                                        <span class="current-price">24,400원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                        <span>(29)</span>
                                    </div>
                                    <div class="product-description">
                                        230g의 가벼운 무게로 휴대성 극대화, 3way 빌트인 케이블 채용, 10,000mAh
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 상품 2 -->
                            <div class="product-card">
                                <div class="product-thumb">
                                    <img src="./resources/images/디지털/2.PNG" alt="Bowie D05 무선 블루투스 헤드셋" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">헤드셋</div>
                                    <h4 class="product-title">Bowie D05 무선 블루투스 5.3 헤드셋</h4>
                                    <div class="product-price">
                                        <span class="current-price">36,900원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                        <span>(54)</span>
                                    </div>
                                    <div class="product-description">
                                        현실같은 3D사운드 스테이지 제공, 70시간의 오디오 재생시간, 2시간 급속 충전
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 상품 3 -->
                            <div class="product-card">
                                <div class="product-badge hot">HOT</div>
                                <div class="product-thumb">
                                    <img src="./resources/images/디지털/3.PNG" alt="독거미 F99 기계식 키보드" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">키보드</div>
                                    <h4 class="product-title">독거미 F99 기계식 키보드</h4>
                                    <div class="product-price">
                                        <span class="current-price">70,750원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <span>(112)</span>
                                    </div>
                                    <div class="product-description">
                                        최고의 퍼포먼스를 경험하게 해주는 키보드, 안정적인 무선 전송, 저소음 디자인
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 주얼리/액세서리 카테고리 -->
                    <div class="product-category" id="accessories">
                        <div class="category-header">
                            <a href="#" class="view-all">전체보기 <i class="fa fa-angle-right"></i></a>
                        </div>
                        <div class="product-grid">
                            <!-- 상품 1 -->
                            <div class="product-card">
                                <div class="product-thumb">
                                    <img src="./resources/images/팬시/1.PNG" alt="명품 자동 장우산" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">우산</div>
                                    <h4 class="product-title">명품 자동 장우산</h4>
                                    <div class="product-price">
                                        <span class="current-price">31,600원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                        <span>(18)</span>
                                    </div>
                                    <div class="product-description">
                                        태풍에도 견디는 프리미엄 우드 장우산. 50개 이상 구매 시 손잡이 무료 각인
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 상품 2 -->
                            <div class="product-card">
                                <div class="product-badge custom">주문제작</div>
                                <div class="product-thumb">
                                    <img src="./resources/images/팬시/2.PNG" alt="14K 윙블링 원터치 링 귀걸이" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">귀걸이</div>
                                    <h4 class="product-title">14K 윙블링 원터치 링 귀걸이</h4>
                                    <div class="product-price">
                                        <span class="current-price">250,000원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <span>(36)</span>
                                    </div>
                                    <div class="product-description">
                                        언제나 당신의 일상에 '편안한 멋' 평범한 순간마저 매력을 돋보이게 만들어 줄 14K 컬렉션
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 상품 3 -->
                            <div class="product-card">
                                <div class="product-badge custom">주문제작</div>
                                <div class="product-thumb">
                                    <img src="./resources/images/팬시/3.PNG" alt="14K 윙블링 메르시 목걸이" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">목걸이</div>
                                    <h4 class="product-title">14K 윙블링 메르시 목걸이</h4>
                                    <div class="product-price">
                                        <span class="current-price">265,000원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                        <span>(28)</span>
                                    </div>
                                    <div class="product-description">
                                        언제나 변함없고 고급스러운 전체 14K 골드로 제작되어 소장 가치뿐만 아니라 우아한 아름다움
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 향수/화장품 카테고리 -->
                    <div class="product-category" id="fragrance">
                        <div class="category-header">
                            <a href="#" class="view-all">전체보기 <i class="fa fa-angle-right"></i></a>
                        </div>
                        <div class="product-grid">
                            <!-- 상품 1 -->
                            <div class="product-card">
                                <div class="product-thumb">
                                    <img src="./resources/images/향수/1.PNG" alt="에스쁘아 솔리드 퍼퓸" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">고체향수</div>
                                    <h4 class="product-title">에스쁘아 솔리드 퍼퓸 4.2g</h4>
                                    <div class="product-price">
                                        <span class="current-price">26,000원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                        <span>(42)</span>
                                    </div>
                                    <div class="product-description">
                                        새벽 달빛 아래 달큰한 체리가 어지럽게 흩어진 자리, 새하얀 자스민이 코끝에 느껴지는 향기
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 상품 2 -->
                            <div class="product-card">
                                <div class="product-thumb">
                                    <img src="./resources/images/향수/2.PNG" alt="호텔도슨 향수 오드퍼퓸" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">향수</div>
                                    <h4 class="product-title">호텔도슨 향수 오드퍼퓸 75ml</h4>
                                    <div class="product-price">
                                        <span class="current-price">153,000원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <span>(67)</span>
                                    </div>
                                    <div class="product-description">
                                        향긋하고 보드라운 마른 꽃과 나무 향 뒤로 낙엽이 타는 듯한 베티버 향이 퍼지는 우아한 향수
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 상품 3 -->
                            <div class="product-card">
                                <div class="product-badge sale">SALE</div>
                                <div class="product-thumb">
                                    <img src="./resources/images/향수/4.PNG" alt="몽블랑 익스플로러 EDP" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">향수</div>
                                    <h4 class="product-title">몽블랑 익스플로러 EDP 60ml</h4>
                                    <div class="product-price">
                                        <span class="old-price">103,000원</span>
                                        <span class="current-price">93,000원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-half-o"></i>
                                        <span>(52)</span>
                                    </div>
                                    <div class="product-description">
                                        전 세계를 여행하는 탐험가의 향기. 에너제틱 베르가못에서 자연스러운 패출리와 코코아 노트
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 헤어케어 카테고리 -->
                    <div class="product-category" id="haircare">
                        <div class="category-header">
                            <a href="#" class="view-all">전체보기 <i class="fa fa-angle-right"></i></a>
                        </div>
                        <div class="product-grid">
                            <!-- 상품 1 -->
                            <div class="product-card">
                                <div class="product-thumb">
                                    <img src="./resources/images/헤어케어/1.PNG" alt="어노브 딥 데미지 트리트먼트" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">트리트먼트</div>
                                    <h4 class="product-title">어노브 딥 데미지 트리트먼트 EX 더블</h4>
                                    <div class="product-price">
                                        <span class="current-price">29,800원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <span>(97)</span>
                                    </div>
                                    <div class="product-description">
                                        부드러움에 집착하다! 어노브 집착 헤어팩! 단백질 3,000% UP으로 완성하는 머릿결
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 상품 2 -->
                            <div class="product-card">
                                <div class="product-thumb">
                                    <img src="./resources/images/헤어케어/2.PNG" alt="려루트젠여성맞춤볼륨탈모증상케어샴퓨353ml" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">트리트먼트</div>
                                    <h4 class="product-title">려루트젠여성맞춤볼륨탈모증상케어샴퓨353ml</h4>
                                    <div class="product-price">
                                        <span class="current-price">21,900원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <span>(27)</span>
                                    </div>
                                    <div class="product-description">
                                        근거있는여성탈모전문가려루트젠이만든촘촘탄탄밀도탄력을채우는
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>

                            <!-- 상품 3 -->
                            <div class="product-card">
                                <div class="product-badge sale">SALE</div>
                                <div class="product-thumb">
                                    <img src="./resources/images/헤어케어/5.PNG" alt="닥터포헤어 피토프레시 헤어쿨링 스프레이" />
                                    <div class="product-wishlist">
                                        <i class="fa fa-heart-o"></i>
                                    </div>
                                    <div class="product-action">
                                        <a href="#" class="quick-view"><i class="fa fa-search"></i></a>
                                        <a href="#" class="add-to-cart"><i class="fa fa-shopping-cart"></i></a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <div class="product-category">헤어스프레이</div>
                                    <h4 class="product-title">닥터포헤어 피토프레시 헤어쿨링 스프레이</h4>
                                    <div class="product-price">
                                        <span class="old-price">16,000원</span>
                                        <span class="current-price">14,400원</span>
                                    </div>
                                    <div class="product-rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                        <span>(46)</span>
                                    </div>
                                    <div class="product-description">
                                        열받아 예민해진 두피를 위한 즉각적인 두피 쿨링 솔루션. 온종일 느껴지는 시원함
                                    </div>
                                    <div class="product-benefits">
                                        <span><i class="fa fa-truck"></i> 무료배송</span>
                                        <span><i class="fa fa-credit-card"></i> 10% 할인</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- 공지사항 섹션 시작 -->
    <section class="notice-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">공지사항</h2>
                <p class="section-subtitle">GIFTS:Mall의 최신 소식과 이벤트를 확인하세요</p>
            </div>

            <div class="notices-wrapper">
                <!-- 왼쪽 공지 하이라이트 -->
                <div class="notice-highlight">
                    <div class="highlight-header">
                        <h3>주요 공지사항</h3>
                        <span class="accent-line"></span>
                    </div>
                    <?php if ($latestNotice): ?>
                    <div class="highlight-content">
                        <div class="highlight-icon">
                            <i class="fa fa-bullhorn"></i>
                        </div>
                        <div class="highlight-text">
                            <h4><?= $latestNotice->content ?></h4>
                            <p><?= $latestNotice->content ?></p>
                            <div class="highlight-date"><?= date('Y.m.d', strtotime($latestNotice->date)) ?></div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="highlight-content">
                        <div class="highlight-icon">
                            <i class="fa fa-bullhorn"></i>
                        </div>
                        <div class="highlight-text">
                            <h4>공지사항이 없습니다</h4>
                            <p>새로운 공지사항이 등록되면 이곳에 표시됩니다.</p>
                            <div class="highlight-date"><?= date('Y.m.d') ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="highlight-footer">
                        <div class="highlight-items">
                            <div class="item active"></div>
                            <div class="item"></div>
                            <div class="item"></div>
                        </div>
                    </div>
                </div>

                <!-- 오른쪽 공지사항 목록 -->
                <div class="notice-container">
                    <!-- 필터 및 정렬 컨트롤 -->
                    <div class="notice-filters">
                        <div class="category-filter">
                            <a href="?category=all&sort=<?= $sort ?>" class="<?= $category === 'all' ? 'active' : '' ?>">전체</a>
                            <a href="?category=일반&sort=<?= $sort ?>" class="<?= $category === '일반' ? 'active' : '' ?>">일반</a>
                            <a href="?category=이벤트&sort=<?= $sort ?>" class="<?= $category === '이벤트' ? 'active' : '' ?>">이벤트</a>
                        </div>
                        <div class="sort-control">
                            <a href="?category=<?= $category ?>&sort=desc" class="<?= $sort === 'desc' ? 'active' : '' ?>">최신순</a>
                            <span>|</span>
                            <a href="?category=<?= $category ?>&sort=asc" class="<?= $sort === 'asc' ? 'active' : '' ?>">오래된순</a>
                        </div>
                    </div>
                    
                    <!-- 페이지 정보 및 네비게이션 -->
                    <div class="notice-navigation">
                        <div class="page-info">
                            <div class="total-count">총 <strong><?= $totalNotices ?></strong>건</div>
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

                    <!-- 공지사항 목록 -->
                    <div class="notice-list">
                        <!-- 공지사항 헤더 -->
                        <div class="notice-header">
                            <div class="notice-type">유형</div>
                            <div class="notice-title">제목</div>
                            <div class="notice-date">날짜</div>
                        </div>

                        <!-- 공지사항 항목들 -->
                        <div class="notice-items">
                            <?php if (count($notices) > 0): ?>
                                <?php foreach ($notices as $notice): ?>
                                    <?php 
                                        // 최신 공지사항 표시 (7일 이내)
                                        $isNew = (strtotime($notice->date) > strtotime('-7 days'));
                                    ?>
                                    <div class="notice-item">
                                        <div class="notice-type">
                                            <span class="type-badge <?= $notice->category === '이벤트' ? 'event' : 'normal' ?>">
                                                <?= $notice->category ?>
                                            </span>
                                        </div>
                                        <div class="notice-title">
                                            <a href="#"><?= $notice->content ?></a>
                                            <?php if ($isNew): ?>
                                                <span class="new-tag">N</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="notice-date"><?= date('Y.m.d', strtotime($notice->date)) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="notice-item no-data">
                                    <div class="notice-title text-center">
                                        <span>해당 공지사항이 없습니다.</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- 모바일 페이지 컨트롤 (작은 화면에서만 표시) -->
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
    </section>
    <!-- 상품입점/제휴문의 섹션 시작 -->
    <section class="partnership-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">상품입점/제휴문의</h2>
                <p class="section-subtitle">대한민국 No.1 GIFTS:Mall과 함께 할 WIN-WIN 파트너를 찾습니다.<br> 제휴사의 많은 지원을 기다립니다.</p>
            </div>

            <!-- 배너 컨테이너 -->
            <div class="banner-container">
                <!-- 상품입점/제휴문의 배너 -->
                <a href="#" class="partnership-banner banner-partnership">
                    <div class="banner-icon">
                        <i class="fa fa-check-square-o"></i>
                    </div>
                    <div class="banner-content">
                        <h3>상품입점/제휴문의</h3>
                        <p>새로운 파트너십을 위한 입점 및 제휴 문의를 시작하세요</p>
                        <span class="banner-button">바로가기 <i class="fa fa-long-arrow-right"></i></span>
                    </div>
                    <div class="pattern-dot"></div>
                </a>

                <!-- 문의결과조회 배너 -->
                <a href="#" class="partnership-banner banner-inquiry">
                    <div class="banner-icon">
                        <i class="fa fa-search"></i>
                    </div>
                    <div class="banner-content">
                        <h3>문의결과조회</h3>
                        <p>상담 신청 후 처리 상태 및 결과를 확인하실 수 있습니다</p>
                        <span class="banner-button">조회하기 <i class="fa fa-long-arrow-right"></i></span>
                    </div>
                    <div class="pattern-dot"></div>
                </a>

                <!-- 전자계약시스템 배너 -->
                <a href="#" class="partnership-banner banner-contract">
                    <div class="banner-icon">
                        <i class="fa fa-file-text-o"></i>
                    </div>
                    <div class="banner-content">
                        <h3>전자계약시스템</h3>
                        <p>편리한 온라인 계약 시스템으로 빠르게 절차를 진행하세요</p>
                        <span class="banner-button">계약하기 <i class="fa fa-long-arrow-right"></i></span>
                    </div>
                    <div class="pattern-dot"></div>
                </a>

                <!-- 파트너시스템 배너 -->
                <a href="#" class="partnership-banner banner-system">
                    <div class="banner-icon">
                        <i class="fa fa-cog"></i>
                    </div>
                    <div class="banner-content">
                        <h3>파트너시스템</h3>
                        <p>입점 파트너를 위한 전용 관리 시스템에 접속하세요</p>
                        <span class="banner-button">시스템접속 <i class="fa fa-long-arrow-right"></i></span>
                    </div>
                    <div class="pattern-dot"></div>
                </a>
            </div>

            <!-- 입점 절차 안내 -->
            <div class="process-container">
                <h3 class="process-title">입점 절차 안내</h3>

                <div class="process-timeline">
                    <!-- 1단계 -->
                    <div class="process-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <div class="step-icon">
                                <i class="fa fa-user-plus"></i>
                            </div>
                            <h4>임시회원가입</h4>
                            <p>미거래 업체는 임시회원<br> 가입 후 상담 신청</p>
                        </div>
                    </div>

                    <!-- 2단계 -->
                    <div class="process-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <div class="step-icon">
                                <i class="fa fa-comments"></i>
                            </div>
                            <h4>온라인상담</h4>
                            <p>입점/제휴를 위한 <br>온라인 상담 진행</p>
                        </div>
                    </div>

                    <!-- 3단계 -->
                    <div class="process-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <div class="step-icon">
                                <i class="fa fa-building"></i>
                            </div>
                            <h4>방문상담</h4>
                            <p>담당MD/제휴담당자와<br> 구체적 상담</p>
                        </div>
                    </div>

                    <!-- 4단계 -->
                    <div class="process-step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <div class="step-icon">
                                <i class="fa fa-thumbs-up"></i>
                            </div>
                            <h4>품평회</h4>
                            <p>상품력, 기획력 등<br> 내부 품평회 진행</p>
                        </div>
                    </div>

                    <!-- 5단계 -->
                    <div class="process-step">
                        <div class="step-number">5</div>
                        <div class="step-content">
                            <div class="step-icon">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <h4>신용평가</h4>
                            <p>신뢰있는 거래를 <br>위한 업체 신용평가</p>
                        </div>
                    </div>

                    <!-- 6단계 -->
                    <div class="process-step">
                        <div class="step-number">6</div>
                        <div class="step-content">
                            <div class="step-icon">
                                <i class="fa fa-handshake-o"></i>
                            </div>
                            <h4>계약체결</h4>
                            <p>전자계약서 작성으로<br> 입점절차 완료</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- 상품입점/제휴문의 섹션 끝 -->
    <style>
        /* 로딩 화면 숨김 */
        .loading-overlay {
            display: none;
        }
        
        /* 공지사항 필터 및 정렬 스타일 */
        .notice-filters {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .category-filter a {
            display: inline-block;
            padding: 5px 15px;
            margin-right: 5px;
            color: #666;
            text-decoration: none;
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        
        .category-filter a.active {
            background-color: #007bff;
            color: white;
            font-weight: 500;
        }
        
        .sort-control {
            display: flex;
            align-items: center;
        }
        
        .sort-control a {
            padding: 5px 10px;
            color: #666;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .sort-control a.active {
            color: #007bff;
            font-weight: bold;
        }
        
        .sort-control span {
            color: #ddd;
            margin: 0 5px;
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
        
        .notice-item.no-data {
            padding: 30px 0;
            color: #999;
        }
        
        .text-center {
            text-align: center;
            width: 100%;
        }
    </style>
    <script src="./resources/js/jquery-3.4.1.min.js"></script>
    <script src="./resources/js/data.js"></script>
<?php
// 푸터 포함
require_once "./util/footer.php";
?>
