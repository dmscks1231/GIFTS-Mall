<?php
// 네임스페이스 및 필요한 클래스 로드
namespace LIB\App;
// DB 클래스와 Lib 클래스 로드
require_once './lib/DB.php';
require_once './lib/lib.php';
// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 관리자 권한 확인
if (!Lib::isAdmin()) {
    Lib::redirect("index.php", "관리자만 접근 가능합니다.");
    exit;
}



// 페이지 파라미터 가져오기
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc';

// 페이지 유효성 검사
if ($page < 1) $page = 1;

// 초기 메시지 설정
$message = '';

// 공지사항 추가
if (isset($_POST['add_notice'])) {
    $notice_category = $_POST['notice_category'] ?? '';
    $notice_content = $_POST['notice_content'] ?? '';
    
    if (empty($notice_category) || empty($notice_content)) {
        $message = "카테고리와 내용을 모두 입력해주세요.";
    } else {
        $result = DB::execute(
            "INSERT INTO notices (category, content, date) VALUES (?, ?, ?)",
            [$notice_category, $notice_content, date('Y-m-d')]
        );
        
        if ($result) {
            $message = "공지사항이 성공적으로 추가되었습니다.";
        } else {
            $message = "공지사항 추가에 실패했습니다.";
        }
    }
}

// 공지사항 수정
if (isset($_POST['edit_notice'])) {
    $notice_id = $_POST['notice_id'] ?? 0;
    $notice_category = $_POST['notice_category'] ?? '';
    $notice_content = $_POST['notice_content'] ?? '';
    
    if (empty($notice_category) || empty($notice_content) || $notice_id <= 0) {
        $message = "유효하지 않은 입력입니다.";
    } else {
        $result = DB::execute(
            "UPDATE notices SET category = ?, content = ? WHERE id = ?",
            [$notice_category, $notice_content, $notice_id]
        );
        
        if ($result) {
            $message = "공지사항이 성공적으로 수정되었습니다.";
        } else {
            $message = "공지사항 수정에 실패했습니다.";
        }
    }
}

// 공지사항 삭제
if (isset($_GET['delete']) && $_GET['delete'] > 0) {
    $delete_id = (int)$_GET['delete'];
    
    $result = DB::execute("DELETE FROM notices WHERE id = ?", [$delete_id]);
    
    if ($result) {
        $message = "공지사항이 성공적으로 삭제되었습니다.";
    } else {
        $message = "공지사항 삭제에 실패했습니다.";
    }
}

// 공지사항 가져오기
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

// 공지사항 가져오기
$noticesData = getNotices($page, $category, $sort);
$notices = $noticesData['notices'];
$totalPages = $noticesData['totalPages'];
$currentPage = $noticesData['currentPage'];
$totalNotices = $noticesData['totalNotices'];

// 이전, 다음 페이지 계산
$prevPage = ($currentPage > 1) ? $currentPage - 1 : 1;
$nextPage = ($currentPage < $totalPages) ? $currentPage + 1 : $totalPages;

// 헤더 포함
require_once "./util/header.php";
?>

<div class="admin-section notice-admin-section" style="margin: 150px 0px;">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">공지사항 관리</h2>
            <p class="section-subtitle">GIFTS:Mall의 공지사항을 관리합니다</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info" role="alert">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <!-- 공지사항 추가 폼 -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">공지사항 추가</h5>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="notice_category" class="form-label">카테고리</label>
                            <select id="notice_category" name="notice_category" class="form-select" required>
                                <option value="">선택하세요</option>
                                <option value="일반">일반</option>
                                <option value="이벤트">이벤트</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label for="notice_content" class="form-label">내용</label>
                            <input type="text" id="notice_content" name="notice_content" class="form-control" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" name="add_notice" class="btn btn-primary w-100">추가</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- 공지사항 목록 및 관리 -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">공지사항 목록</h5>
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
                </div>
            </div>
            <div class="card-body">
                <!-- 페이지 정보 및 네비게이션 -->
                <div class="notice-navigation mb-3">
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

                <!-- 공지사항 테이블 -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%">번호</th>
                                <th width="15%">카테고리</th>
                                <th width="45%">내용</th>
                                <th width="15%">날짜</th>
                                <th width="20%">작업</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($notices) > 0): ?>
                                <?php foreach ($notices as $notice): ?>
                                    <tr>
                                        <td><?= $notice->id ?></td>
                                        <td>
                                            <span class="badge <?= $notice->category === '이벤트' ? 'bg-warning text-dark' : 'bg-secondary' ?>">
                                                <?= $notice->category ?>
                                            </span>
                                        </td>
                                        <td><?= $notice->content ?></td>
                                        <td><?= date('Y.m.d', strtotime($notice->date)) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary edit-notice-btn" 
                                                   data-id="<?= $notice->id ?>" 
                                                   data-category="<?= $notice->category ?>" 
                                                   data-content="<?= htmlspecialchars($notice->content) ?>">
                                                수정
                                            </button>
                                            <a href="?delete=<?= $notice->id ?>&page=<?= $currentPage ?>&category=<?= $category ?>&sort=<?= $sort ?>" 
                                               class="btn btn-sm btn-danger delete-notice-btn"
                                               onclick="return confirm('정말 삭제하시겠습니까?');">
                                                삭제
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">공지사항이 없습니다.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- 모바일 페이지 컨트롤 -->
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
</div>

<!-- 공지사항 수정 모달 -->
<div class="modal fade" id="editNoticeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">공지사항 수정</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <input type="hidden" id="edit_notice_id" name="notice_id">
                    <div class="mb-3">
                        <label for="edit_notice_category" class="form-label">카테고리</label>
                        <select id="edit_notice_category" name="notice_category" class="form-select" required>
                            <option value="일반">일반</option>
                            <option value="이벤트">이벤트</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_notice_content" class="form-label">내용</label>
                        <input type="text" id="edit_notice_content" name="notice_content" class="form-control" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="edit_notice" class="btn btn-primary">수정하기</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* 페이지네이션 스타일 */
.notice-navigation {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.page-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-controls {
    display: flex;
    gap: 0.5rem;
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

/* 필터 스타일 */
.notice-filters {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.category-filter a, .sort-control a {
    color: #666;
    text-decoration: none;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.category-filter a.active, .sort-control a.active {
    background-color: #007bff;
    color: white;
}

.sort-control {
    display: flex;
    align-items: center;
}

.sort-control span {
    color: #ddd;
    margin: 0 0.25rem;
}

/* 모바일 페이지 컨트롤 */
.mobile-page-controls {
    display: none;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

@media (max-width: 767.98px) {
    .notice-navigation {
        display: none;
    }
    
    .mobile-page-controls {
        display: flex;
    }
}
</style>

<script src="./resources/js/jquery-3.4.1.min.js"></script>

<script>
$(document).ready(function() {
    // 공지사항 수정 버튼 클릭 시 모달 열기
    $('.edit-notice-btn').on('click', function() {
        const id = $(this).data('id');
        const category = $(this).data('category');
        const content = $(this).data('content');
        
        $('#edit_notice_id').val(id);
        $('#edit_notice_category').val(category);
        $('#edit_notice_content').val(content);
        
        const editModal = new bootstrap.Modal(document.getElementById('editNoticeModal'));
        editModal.show();
    });
});
</script>

<?php
// 푸터 포함
require_once "./util/footer.php";
?>