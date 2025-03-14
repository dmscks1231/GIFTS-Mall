<?php
// 네임스페이스 사용 및 오토로드 설정
namespace LIB\App;

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// DB 클래스와 Lib 클래스 로드
require_once 'DB.php';
require_once 'Lib.php';

// 관리자 권한 체크
if (!Lib::isAdmin()) {
    // 관리자가 아니면 홈으로 리다이렉트
    $_SESSION['msg'] = '관리자만 접근 가능한 페이지입니다.';
    header('Location: index.php');
    exit;
}

// 모든 사용자 정보 가져오기
$users = DB::fetchAll("SELECT * FROM users ORDER BY register_date DESC");
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 페이지 - GIFTS:Mall</title>
    <link rel="stylesheet" href="./resources/css/font-awesome.min.css">
    <link rel="stylesheet" href="./resources/css/style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #343a40;
            padding: 15px 0;
        }
        .navbar-brand {
            font-weight: bold;
            color: white;
            font-size: 24px;
        }
        .hash-display {
            font-family: monospace;
            word-break: break-all;
        }
        .admin-header {
            background-color: #343a40;
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .admin-container {
            padding-bottom: 2rem;
        }
    </style>
</head>

<body>
    <!-- 네비게이션 바 -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">GIFTS:Mall</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">홈</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub1.html">소개</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub2.html">판매상품</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub3.html">공지사항</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sub4.html">장바구니</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link"><?= htmlspecialchars($_SESSION['username']) ?>님 환영합니다</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin.php">관리자</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?logout=1">로그아웃</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- 관리자 헤더 -->
    <header class="admin-header">
        <div class="container">
            <h1 class="display-4">관리자 페이지</h1>
            <p class="lead">회원 관리 기능을 통해 모든 사용자 정보를 확인할 수 있습니다.</p>
        </div>
    </header>
    
    <!-- 관리자 컨텐츠 -->
    <div class="container admin-container">
        <!-- 회원 관리 -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">회원 관리</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>아이디</th>
                                <th>이름</th>
                                <th>이메일</th>
                                <th>비밀번호 (암호화됨)</th>
                                <th>Salt</th>
                                <th>가입일</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user->id) ?></td>
                                    <td><?= htmlspecialchars($user->username) ?></td>
                                    <td><?= htmlspecialchars($user->email) ?></td>
                                    <td style="max-width: 300px;">
                                        <div class="hash-display"><?= htmlspecialchars($user->password) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($user->salt) ?></td>
                                    <td><?= htmlspecialchars($user->register_date) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <h5>비밀번호 정보</h5>
                    <p class="text-muted">
                        모든 비밀번호는 SHA256 해시 알고리즘과 각 사용자별 고유 salt 값을 사용하여 암호화되어 저장됩니다.<br>
                        암호화 방식: <code>hash('sha256', salt + id + password)</code>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="./resources/js/jquery-3.4.1.min.js"></script>
</body>
</html>