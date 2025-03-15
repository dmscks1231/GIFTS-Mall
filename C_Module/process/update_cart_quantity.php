<?php
namespace LIB\App;

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// DB 클래스와 Lib 클래스 로드
require_once '../lib/DB.php';
require_once '../lib/lib.php';

// 응답 헤더 설정
header('Content-Type: application/json');

// 요청 검증
if (!isset($_GET['id']) || !isset($_GET['action'])) {
    echo json_encode(['success' => false, 'message' => '필수 파라미터가 누락되었습니다.']);
    exit;
}

// 로그인 확인
if (!Lib::isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => '로그인이 필요합니다.']);
    exit;
}

$item_id = $_GET['id'];
$action = $_GET['action'];
$user_id = $_SESSION['user_id'];

// 현재 장바구니 아이템 정보 조회
$cartItem = DB::fetch("SELECT * FROM buckets WHERE id = ? AND user_id = ?", [$item_id, $user_id]);

if (!$cartItem) {
    echo json_encode(['success' => false, 'message' => '장바구니에 해당 상품이 없습니다.']);
    exit;
}

// 수량 업데이트
$newCount = $cartItem->count;

if ($action === 'increase') {
    $newCount += 1;
} elseif ($action === 'decrease') {
    if ($newCount > 1) {
        $newCount -= 1;
    }
}

// 수량이 1 미만이면 1로 설정
if ($newCount < 1) {
    $newCount = 1;
}

// 수량 제한 (최대 10개)
if ($newCount > 10) {
    $newCount = 10;
    echo json_encode(['success' => false, 'message' => '최대 10개까지만 구매 가능합니다.']);
    exit;
}

// 데이터베이스 업데이트
$result = DB::execute("UPDATE buckets SET count = ? WHERE id = ? AND user_id = ?", [$newCount, $item_id, $user_id]);

if ($result) {
    echo json_encode([
        'success' => true, 
        'message' => '장바구니가 업데이트되었습니다.',
        'newCount' => $newCount
    ]);
} else {
    echo json_encode(['success' => false, 'message' => '장바구니 업데이트에 실패했습니다.']);
}
?>