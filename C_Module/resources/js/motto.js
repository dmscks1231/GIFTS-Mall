$(document).ready(function() {
    // 각 갤러리 아이템에 모든 오버레이 이미지 미리 추가
    $('.gallery-item').each(function() {
        // 모든 타입 미리 추가
        const types = ['motto1', 'motto2', 'motto3', 'motto4', 'motto5'];
        for (let type of types) {
            $(this).append(`<img class="overlay-image ${type}-overlay" src="./resources/images/${type}.jpg" alt="${type}" style="opacity: 0;">`);
        }
    });
    
    // 각 갤러리 아이템에 호버 이벤트 추가
    $('.gallery-item').hover(
        function() {
            const hoverType = $(this).data('type');
            
            // 모든 캡션 숨기기
            $('.gallery-caption').css('opacity', '0');
            
            // 현재 타입의 오버레이만 표시하고 나머지는 숨김
            $('.overlay-image').css('opacity', '0');
            $(`.${hoverType}-overlay`).css('opacity', '1');
            
            // 모든 모토 콘텐츠 숨기기
            $('.motto-content').css('opacity', '0').css('transform', 'translate(-50%, -55%)');
            
            // 호버된 아이템의 모토 콘텐츠만 표시
            $(`.${hoverType}-content`).css('opacity', '1').css('transform', 'translate(-50%, -50%)');
            
            // 정보보안 아이템일 경우 추가 커서 아이콘 표시
            if (hoverType === 'motto5') {
                $('.cursor-icon').css('opacity', '1');
            }
        },
        function() {
            // 호버 해제 시 모든 오버레이 숨김
            $('.overlay-image').css('opacity', '0');
            
            // 모든 캡션 다시 표시
            $('.gallery-caption').css('opacity', '1');
            
            // 모든 모토 콘텐츠 숨기기
            $('.motto-content').css('opacity', '0').css('transform', 'translate(-50%, -55%)');
            
            // 정보보안 아이템일 경우 추가 커서 아이콘 숨김
            $('.cursor-icon').css('opacity', '0');
        }
    );
});