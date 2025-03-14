<?php
namespace LIB\App;

// 공지사항 처리 함수
function getNotices($page = 1, $category = 'all', $sort = 'desc') {
    // 페이지당 표시할 공지사항 수
    $noticesPerPage = 6;
    
    // 시작 인덱스 계산
    $start = ($page - 1) * $noticesPerPage;
    
    // 카테고리 조건 설정
    $categoryCondition = '';
    $params = [];
    
    if ($category !== 'all') {
        $categoryCondition = 'WHERE category = ?';
        $params[] = $category;
    }
    
    // 정렬 설정
    $sortDirection = ($sort === 'asc') ? 'ASC' : 'DESC';
    
    // 전체 공지사항 수 조회
    $countQuery = "SELECT COUNT(*) as total FROM notices {$categoryCondition}";
    $totalResult = DB::fetch($countQuery, $params);
    $totalNotices = $totalResult->total;
    
    // 전체 페이지 수 계산
    $totalPages = ceil($totalNotices / $noticesPerPage);
    
    // 공지사항 조회
    $query = "SELECT * FROM notices {$categoryCondition} ORDER BY date {$sortDirection} LIMIT {$start}, {$noticesPerPage}";
    $notices = DB::fetchAll($query, $params);
    
    return [
        'notices' => $notices,
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'totalNotices' => $totalNotices
    ];
}