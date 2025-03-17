<?php
namespace LIB\App;

// 공지사항 가져오기 함수
function getNotices($page = 1, $category = 'all', $sort = 'desc') {
    $itemsPerPage = 10;
    $offset = ($page - 1) * $itemsPerPage;
    
    // 카테고리 필터링
    $whereClause = $category !== 'all' ? "WHERE category = ?" : "";
    $params = $category !== 'all' ? [$category] : [];
    
    // 정렬 방향
    $orderDir = $sort === 'asc' ? 'ASC' : 'DESC';
    
    // 전체 공지사항 수 조회
    $countSql = "SELECT COUNT(*) as total FROM notices $whereClause";
    $totalResult = DB::fetch($countSql, $params);
    $totalNotices = $totalResult->total;
    
    // 총 페이지 수 계산
    $totalPages = ceil($totalNotices / $itemsPerPage);
    
    // 현재 페이지가 총 페이지 수보다 큰 경우 조정
    if ($page > $totalPages && $totalPages > 0) {
        $page = $totalPages;
        $offset = ($page - 1) * $itemsPerPage;
    }
    
    // 공지사항 목록 조회
    $sql = "SELECT * FROM notices $whereClause ORDER BY date $orderDir LIMIT $offset, $itemsPerPage";
    $notices = DB::fetchAll($sql, $params);
    
    return [
        'notices' => $notices,
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'totalNotices' => $totalNotices
    ];
}