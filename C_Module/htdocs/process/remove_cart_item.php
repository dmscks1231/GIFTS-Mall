<?php
namespace LIB\App;

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// DB 클래스와 Lib 클래스 로드
require_once '../lib/DB.php';
require_once '../lib/lib.php';

// 요청 검증
if (!isset($_GET['id'])) {
    Lib::back("상품 정보가 올바르지 않습니다.");
    exit;
}

$item_id = $_GET['id'];

// 로그인 확인
if (!Lib::isLoggedIn()) {
    Lib::redirect("../index.php", "로그인이 필요합니다.");
    exit;
}

$user_id = $_SESSION['user_id'];

// 해당 아이템이 사용자의 장바구니에 있는지 확인
$cartItem = DB::fetch("SELECT * FROM buckets WHERE id = ? AND user_id = ?", [$item_id, $user_id]);

if (!$cartItem) {
    Lib::back("장바구니에 해당 상품이 없습니다.");
    exit;
}

// 장바구니에서 아이템 삭제
$result = DB::execute("DELETE FROM buckets WHERE id = ? AND user_id = ?", [$item_id, $user_id]);

if ($result) {
    Lib::redirect("../sub4.php", "상품이 장바구니에서 삭제되었습니다.");
} else {
    Lib::back("상품 삭제에 실패했습니다.");
}
?>