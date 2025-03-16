<!-- 푸터 영역 시작 -->
<footer class="footer-section">
        <div class="container">
            <!-- 푸터 상단 영역 -->
            <div class="footer-top">
                <div class="footer-info-column">
                    <!-- 푸터 로고 -->
                    <a href="index.php" class="footer-logo-link">
                        <div class="footer-symbol">
                            <img src="./logo.png" alt="GIFTS:Mall 로고" class="footer-logo-img">
                        </div>
                    </a>
                    <p class="footer-slogan">특별한 순간을 찾아드립니다</p>
                </div>

                <div class="footer-customer-center">
                    <h3 class="footer-title">고객센터 이용안내</h3>
                    <div class="customer-info">
                        <div class="info-group">
                            <p><strong>온라인몰 고객센터</strong> 1580-8282</p>
                            <p><strong>매장고객센터</strong> 1577-8254</p>
                        </div>
                        <div class="info-group operation-time">
                            <p><strong>고객센터 운영시간</strong> [평일 09:00 - 18:00]</p>
                            <p class="note">주말 및 공휴일은 1:1문의하기를 이용해주세요.<br>업무가 시작되면 바로 처리해드립니다.</p>
                        </div>
                    </div>
                </div>

                <div class="footer-contact">
                    <h3 class="footer-title">문의하기</h3>
                    <div class="contact-buttons">
                        <a href="#" class="footer-btn primary-btn">
                            <i class="fa fa-headphones"></i>
                            <span>1:1 문의하기</span>
                        </a>
                        <a href="#" class="footer-btn outline-btn">
                            <i class="fa fa-question-circle"></i>
                            <span>자주 묻는 질문</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 푸터 중간 영역 - 메뉴 및 SNS 링크 -->
            <div class="footer-middle">
                <div class="footer-menu">
                    <a href="#">개인정보처리방침</a>
                    <a href="#">이용약관·법적고지</a>
                    <a href="#">청소년보호방침</a>
                    <a href="#">이메일무단수집거부</a>
                    <a href="#">사이트맵</a>
                    <a href="#">채용</a>
                </div>

                <div class="footer-social">
                    <a href="#" class="social-icon" title="페이스북">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="#" class="social-icon" title="인스타그램">
                        <i class="fa fa-instagram"></i>
                    </a>
                    <a href="#" class="social-icon" title="유튜브">
                        <i class="fa fa-youtube-play"></i>
                    </a>
                    <a href="#" class="social-icon" title="트위터">
                        <i class="fa fa-twitter"></i>
                    </a>
                    <a href="#" class="social-icon" title="카카오톡">
                        <i class="fa fa-google-plus"></i>
                    </a>
                </div>
            </div>

            <!-- 푸터 하단 영역 - 회사 정보 -->
            <div class="footer-bottom">
                <div class="company-info">
                    <p>(주)GIFTS:Mall | 사업자등록번호 : 809-81-01157 | 대표이사 황기영</p>
                    <p>주소 : 서울특별시 용산구 한강대로 123, 40층</p>
                    <p>본사 대표전화 : 02-123-4567 | GIFTS:Mall 가맹상담전화 : 02-123-4568</p>
                    <p class="copyright">COPYRIGHTⓒ 2024 GIFTS:MALL KOREA INC. ALL RIGHTS RESERVED</p>
                </div>

                <div class="secure-service">
                    <p class="secure-text">
                        <strong>지방은행구매안전서비스</strong>
                        <span>GIFTS:Mall은 현금 결제한 금액에 대해 지방은행과 채무지급보증 계약을<br>체결하여 안전한 거래를 보장하고 있습니다</span>
                    </p>
                    <a href="#" class="secure-link">서비스 가입사실 확인 <i class="fa fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="./resources/js/jquery-3.4.1.min.js"></script>
    
    <!-- 기존 자바스크립트 -->
    <script src="./resources/js/app.js"></script>
    
    <!-- 조건부 모달 스크립트 -->
    <?php if (!empty($login_error)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        });
    </script>
    <?php endif; ?>
    
    <?php if (!empty($register_error)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
            registerModal.show();
        });
    </script>
    <?php endif; ?>
</body>
</html>