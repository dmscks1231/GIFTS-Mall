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
require_once './util/header.php';

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
    
    <!-- 관리자 컨텐츠 -->
    <div class="container admin-container" style="margin-top: 200px;">
    <div class="section-header">
                <h2 class="section-title">회원관리 메뉴</h2>
            </div>
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
                                    <td><?= $user->id ?></td>
                                    <td><?= $user->username ?></td>
                                    <td><?= $user->email ?></td>
                                    <td style="max-width: 300px;">
                                        <div class="hash-display"><?= $user->password ?></div>
                                    </td>
                                    <td><?= $user->salt ?></td>
                                    <td><?= $user->register_date ?></td>
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
<?php 
require_once './util/header.php';

?>