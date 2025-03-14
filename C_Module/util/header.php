<?php
// 네임스페이스 사용 및 오토로드 설정
namespace LIB\App;

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// DB 클래스와 Lib 클래스 로드
require_once './lib/DB.php';
require_once './lib/lib.php';

// 초기 변수 설정
$data = [
    'login_error' => '',
    'register_error' => '',
    'message' => '',
];

// 메시지 확인
if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
    $data['message'] = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

// 로그인 처리
if (isset($_POST['login'])) {
    $id = $_POST['login_id'] ?? '';
    $password = $_POST['login_password'] ?? '';
    
    // 사용자 정보 조회
    $user = DB::fetch("SELECT * FROM users WHERE id = ?", [$id]);
    
    if ($user) {
        // 비밀번호 해시 생성 및 검증
        $hash = Lib::generateHash($id, $password, $user->salt);
        
        if ($hash === $user->password) {
            // 로그인 성공 시 세션에 사용자 정보 저장
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            
            // 메시지 설정 및 리다이렉트
            Lib::redirect("./index.php", "로그인 성공");
            exit;
        }
    }
    
    // 로그인 실패 - alert 창으로 처리
    Lib::back("아이디 또는 비밀번호가 일치하지 않습니다.");
    exit;
}

// 회원가입 처리
if (isset($_POST['register'])) {
    $id = $_POST['register_id'] ?? '';
    $password = $_POST['register_password'] ?? '';
    $email = $_POST['register_email'] ?? '';
    $username = $_POST['register_username'] ?? '';
    
    if (empty($id) || empty($password) || empty($email) || empty($username)) {
        // 필드 누락 - alert 창으로 처리
        Lib::back("모든 필드를 입력해주세요.");
        exit;
    } else {
        // 중복 확인
        $existingUser = DB::fetch("SELECT * FROM users WHERE id = ?", [$id]);
        
        if ($existingUser) {
            // 중복 아이디 - alert 창으로 처리
            Lib::back("이미 존재하는 아이디입니다.");
            exit;
        } else {
            // 솔트 생성 및 비밀번호 해싱
            $salt = Lib::createSalt();
            $hashedPassword = Lib::generateHash($id, $password, $salt);
            
            // 현재 날짜 및 시간
            $now = date('Y-m-d H:i:s');
            
            // 사용자 등록
            $result = DB::execute(
                "INSERT INTO users (id, password, email, username, salt, register_date) VALUES (?, ?, ?, ?, ?, ?)",
                [$id, $hashedPassword, $email, $username, $salt, $now]
            );
            
            if ($result) {
                // 등록 성공 시 자동 로그인
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                
                // 메시지 설정 및 리다이렉트
                Lib::redirect($_SERVER['PHP_SELF'], "회원가입 성공!");
                exit;
            } else {
                // 회원가입 실패 - alert 창으로 처리
                Lib::back("회원가입에 실패했습니다.");
                exit;
            }
        }
    }
}

// 로그아웃 처리
if (isset($_GET['logout'])) {
    session_destroy();
    Lib::redirect("index.php", "로그아웃 되었습니다.");
    exit;
}

// 변수 추출
extract($data);
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GIFTS:Mall</title>
    <link rel="stylesheet" href="./resources/css/font-awesome.min.css">
    <link rel="stylesheet" href="./resources/css/bootstrap.min.css">
    <link rel="stylesheet" href="./resources/css/style.css">
    <style>
        /* 로그인/회원가입 모달 스타일 */
        .modal-title {
            font-weight: bold;
        }
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
        .message-popup {
            background-color: rgba(40, 167, 69, 0.9);
            color: white;
            padding: 15px;
            border-radius: 5px;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            animation: fadeOut 5s forwards;
        }
        @keyframes fadeOut {
            0% { opacity: 1; }
            70% { opacity: 1; }
            100% { opacity: 0; visibility: hidden; }
        }
    </style>
</head>

<body>
    <!-- 메시지 표시 -->
    <?php if (!empty($message)): ?>
        <div class="message-popup">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- 로딩 화면 -->
    <div class="loading-overlay">
        <div class="loading-container">
            <a href="index.php"><img class="loading-logo" src="./logo.png" alt="logo" title="logo"></a>
            <div class="loading-icon-container">
                <div class="loading-icon">
                    <i class="fa fa-gift gift-icon"></i>
                    <i class="fa fa-heart heart-icon"></i>
                    <i class="fa fa-shopping-cart shopping-cart-icon"></i>
                    <i class="fa fa-star star-icon"></i>
                </div>
            </div>
            <div class="loading-text">
                <span>특</span>
                <span>별</span>
                <span>한</span>
                <span></span>
                <span>순</span>
                <span>간</span>
                <span>을</span>
                <span></span>
                <span>찾</span>
                <span>아</span>
                <span>드</span>
                <span>립</span>
                <span>니</span>
                <span>다</span>
            </div>
            <div class="loading-progress">
                <div class="loading-progress-bar"></div>
            </div>
        </div>
    </div>

    <!-- 헤더 영역 시작 -->
    <header>
        <div class="header-container">
            <!-- 로고 영역 -->
            <div class="logo">
                <a href="index.php">
                    <img src="./logo.png" alt="logo" title="logo">
                </a>
            </div>

            <!-- 모바일 메뉴 토글 추가 -->
            <input type="checkbox" id="mobile-menu-toggle" hidden>
            <label for="mobile-menu-toggle" class="mobile-menu-icon">
                <span></span>
                <span></span>
                <span></span>
            </label>

            <!-- 내비게이션 메뉴 (드롭다운 체크박스 추가 및 유틸 메뉴 통합) -->
            <nav class="mobile-main-nav">
                <ul>
                    <li><a href="sub1.php">소개</a></li>
                    <li class="drop">
                        <input type="checkbox" id="drop-toggle-1" class="drop-toggle" hidden>
                        <label for="drop-toggle-1" class="drop-label"></label>
                        <a href="sub2.php" class="nav-item">판매상품</a>
                        <div class="drop-content">
                            <a href="sub2.php" class="drop-item">
                                <i class="fa fa-th"></i>
                                전체상품
                            </a>
                            <a href="sub3.php" class="drop-item">
                                <i class="fa fa-fire"></i>
                                인기상품
                            </a>
                        </div>
                    </li>
                    <li><a href="#">가맹점</a></li>
                    <li><a href="sub4.php">장바구니</a></li>
                    
                </ul>

                <!-- 모바일용 유틸 메뉴 (모바일에서만 표시) -->
                <div class="mobile-util-menu">
                    <?php if (Lib::isLoggedIn()): ?>
                        <a href="#">
                            <i class="fa fa-user"></i>
                            <span><?= htmlspecialchars($_SESSION['username']) ?></span>
                        </a>
                        <?php if (Lib::isAdmin()): ?>
                            <a href="admin.php">
                                <i class="fa fa-cog"></i>
                                <span>관리자</span>
                            </a>
                        <?php endif; ?>
                        <a href="?logout=1">
                            <i class="fa fa-sign-out"></i>
                            <span>로그아웃</span>
                        </a>
                    <?php else: ?>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="fa fa-user"></i>
                            <span>로그인</span>
                        </a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal">
                            <i class="fa fa-gift"></i>
                            <span>회원가입</span>
                        </a>
                    <?php endif; ?>
                    <a href="sub4.php">
                        <i class="fa fa-shopping-cart"></i>
                        <span>장바구니</span>
                    </a>
                </div>
            </nav>
            <nav class="main-nav">
                <ul>
                    <li><a href="sub1.php">소개</a></li>
                    <li class="drop">
                        <a href="sub2.php" class="nav-item">판매상품</a>
                        <div class="drop-content">
                            <a href="sub2.php" class="drop-item">
                                <i class="fa fa-th"></i>
                                전체상품
                            </a>
                            <a href="sub3.php" class="drop-item">
                                <i class="fa fa-fire"></i>
                                인기상품
                            </a>
                        </div>
                    </li>
                    <li><a href="#">가맹점</a></li>
                    <li><a href="sub4.php">장바구니</a></li>
                    <?php if (Lib::isAdmin()): ?>
                        <li class="drop">
                        <a href="admin.php" class="nav-item">
                        관리자</a>
                        <div class="drop-content">
                            <a href="sub2.php" class="drop-item">
                                <i class="fa fa-th"></i>
                                전체상품
                            </a>
                            <a href="sub3.php" class="drop-item">
                                <i class="fa fa-fire"></i>
                                인기상품
                            </a>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <!-- 유틸 메뉴 (PC용) -->
            <div class="util-nav">
                <?php if (Lib::isLoggedIn()): ?>
                    <a href="#" class="util-item">
                        <i class="fa fa-user"></i>
                        <span><?= htmlspecialchars($_SESSION['username']) ?></span>
                    </a>
                    
                    <a href="?logout=1" class="util-item">
                        <i class="fa fa-sign-out"></i>
                        <span>로그아웃</span>
                    </a>
                <?php else: ?>
                    <a href="#" class="util-item" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fa fa-user"></i>
                        <span>로그인</span>
                    </a>
                    <a href="#" class="util-item" data-bs-toggle="modal" data-bs-target="#registerModal">
                        <i class="fa fa-gift"></i>
                        <span>회원가입</span>
                    </a>
                <?php endif; ?>
                <a href="sub4.php" class="util-item cart-icon">
                    <i class="fa fa-shopping-cart"></i>
                    <span>장바구니</span>
                </a>
            </div>
        </div>
    </header>

    <!-- 로그인 모달 -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">로그인</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                        <div class="mb-3">
                            <label for="login_id" class="form-label">아이디</label>
                            <input type="text" class="form-control" id="login_id" name="login_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="login_password" class="form-label">비밀번호</label>
                            <input type="password" class="form-control" id="login_password" name="login_password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="login" class="btn btn-primary">로그인</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <p class="text-muted">계정이 없으신가요? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">회원가입</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 회원가입 모달 -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">회원가입</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                        <div class="mb-3">
                            <label for="register_id" class="form-label">아이디</label>
                            <input type="text" class="form-control" id="register_id" name="register_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="register_password" class="form-label">비밀번호</label>
                            <input type="password" class="form-control" id="register_password" name="register_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="register_username" class="form-label">이름</label>
                            <input type="text" class="form-control" id="register_username" name="register_username" required>
                        </div>
                        <div class="mb-3">
                            <label for="register_email" class="form-label">이메일</label>
                            <input type="email" class="form-control" id="register_email" name="register_email" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="register" class="btn btn-primary">회원가입</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <p class="text-muted">이미 계정이 있으신가요? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">로그인</a></p>
                </div>
            </div>
        </div>
    </div>