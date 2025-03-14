<?php
// 헤더 포함
require_once "./util/header.php";
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
                        <!-- 디지털/가전 제품 내용 -->
                    </div>

                    <!-- 주얼리/액세서리 카테고리 -->
                    <div class="product-category" id="accessories">
                        <!-- 주얼리/액세서리 제품 내용 -->
                    </div>

                    <!-- 향수/화장품 카테고리 -->
                    <div class="product-category" id="fragrance">
                        <!-- 향수/화장품 제품 내용 -->
                    </div>

                    <!-- 헤어케어 카테고리 -->
                    <div class="product-category" id="haircare">
                        <!-- 헤어케어 제품 내용 -->
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
                    <div class="highlight-content">
                        <div class="highlight-icon">
                            <i class="fa fa-bullhorn"></i>
                        </div>
                        <div class="highlight-text">
                            <h4>8/14(수)~8/15(목) 택배사 휴무 안내</h4>
                            <p>광복절 연휴로 인한 택배사 휴무로 배송이 지연될 수 있습니다. 양해 부탁드립니다.</p>
                            <div class="highlight-date">2024.08.06</div>
                        </div>
                    </div>
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
                    <!-- 페이지 정보 및 네비게이션 -->
                    <div class="notice-navigation">
                        <div class="page-info">
                            <div class="total-count">총 <strong>31</strong>건</div>
                            <span class="page-indicator">1 / 6 페이지</span>
                        </div>
                        <div class="page-controls">
                            <button class="nav-btn prev-btn" disabled><i class="fa fa-angle-left"></i></button>
                            <button class="nav-btn next-btn"><i class="fa fa-angle-right"></i></button>
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
                            <div class="notice-item">
                                <div class="notice-type">
                                    <span class="type-badge event">이벤트</span>
                                </div>
                                <div class="notice-title">
                                    <a href="#">24년 7월 &lt;헬스+출석체크인&gt; 이벤트 당첨자 공지</a>
                                    <span class="new-tag">N</span>
                                </div>
                                <div class="notice-date">2024.08.08</div>
                            </div>

                            <div class="notice-item">
                                <div class="notice-type">
                                    <span class="type-badge event">이벤트</span>
                                </div>
                                <div class="notice-title">
                                    <a href="#">7월 [기프트몰TV 보러갈래?] 이벤트 당첨자 발표</a>
                                    <span class="new-tag">N</span>
                                </div>
                                <div class="notice-date">2024.08.07</div>
                            </div>

                            <div class="notice-item">
                                <div class="notice-type">
                                    <span class="type-badge normal">일반</span>
                                </div>
                                <div class="notice-title">
                                    <a href="#">[배송안내] 8/14(수)~8/15(목) 택배사 휴무 관련</a>
                                    <span class="new-tag">N</span>
                                </div>
                                <div class="notice-date">2024.08.06</div>
                            </div>

                            <div class="notice-item">
                                <div class="notice-type">
                                    <span class="type-badge normal">일반</span>
                                </div>
                                <div class="notice-title">
                                    <a href="#">딘토 이벤트 조기 종료 안내</a>
                                </div>
                                <div class="notice-date">2024.08.05</div>
                            </div>

                            <div class="notice-item">
                                <div class="notice-type">
                                    <span class="type-badge normal">일반</span>
                                </div>
                                <div class="notice-title">
                                    <a href="#">하월곡점 폐점으로 인한 영업종료 안내</a>
                                </div>
                                <div class="notice-date">2024.07.31</div>
                            </div>

                            <div class="notice-item">
                                <div class="notice-type">
                                    <span class="type-badge normal">일반</span>
                                </div>
                                <div class="notice-title">
                                    <a href="#">양평점 리로케이션으로 인한 영업 중단 안내</a>
                                </div>
                                <div class="notice-date">2024.07.31</div>
                            </div>
                        </div>
                    </div>

                    <!-- 모바일 페이지 컨트롤 (작은 화면에서만 표시) -->
                    <div class="mobile-page-controls">
                        <button class="nav-btn prev-btn" disabled><i class="fa fa-angle-left"></i> 이전</button>
                        <span class="page-indicator">1 / 5 페이지</span>
                        <button class="nav-btn next-btn">다음 <i class="fa fa-angle-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
<?php
// 푸터 포함
require_once "./util/footer.php";
?>
