$(document).ready(function() {
    // 비디오 플레이어 요소 선택
    const $video = $("#adVideo");
    const $btnRepeat = $("#loopBtn");
    const $btnAuto = $("#autoplayBtn");
    const $controls = $("#videoControls");
    const $hideBtn = $("#hideControlsBtn");
    const $showBtn = $("#showControlsBtn");
    
    // 초기 상태 설정
    $showBtn.hide();
    
    // 로컬 스토리지에서 자동재생 설정 불러오기
    let isAutoPlay = localStorage.getItem("autoPlay") || "false";
    if(isAutoPlay === "true") {
        $video.prop("autoplay", true);
        $video.prop("muted", true);
        $btnAuto.html('<i class="fa fa-play-circle"></i>');
        $btnAuto.addClass('active');

    } else {
        $btnAuto.html('<i class="fa fa-play-circle"></i>');
    }
    
    // 초기 반복 상태 설정
    $btnRepeat.html('<i class="fa fa-refresh"></i>');
    
    // 기본 함수들
    function playVideo() {
        $video[0].play();
    }
    
    function pauseVideo() {
        $video[0].pause();
    }
    
    function stopVideo() {
        $video[0].pause();
        $video[0].currentTime = 0;
    }
    
    function rewindVideo() {
        $video[0].currentTime -= 10;
    }
    
    function fastForward() {
        $video[0].currentTime += 10;
    }
    
    function reduceSpeed() {
        $video[0].playbackRate = Math.max(0.1, $video[0].playbackRate - 0.1);
    }
    
    function increaseSpeed() {
        $video[0].playbackRate = Math.min(3.0, $video[0].playbackRate + 0.1);
    }
    
    function originalSpeed() {
        $video[0].playbackRate = 1.0;
    }
    
    function repeatToggle() {
        $video.prop("loop", !$video.prop("loop"));
        if($video.prop("loop")) {
            $btnRepeat.html('<i class="fa fa-refresh fa-spin"></i>');
            $btnRepeat.addClass('active');

        } else {
            $btnRepeat.html('<i class="fa fa-refresh"></i>');
            $btnRepeat.removeClass('active');

        }
    }
    
    function toggleAutoplay() {
        let autoPlay = (localStorage.getItem("autoPlay") || "false") === "true";
        let nextValue = !autoPlay;
        
        if(nextValue) {
            $btnAuto.html('<i class="fa fa-play-circle"></i>');
            $btnAuto.addClass('active');

        } else {
            $btnAuto.html('<i class="fa fa-play-circle"></i>');
            $btnAuto.removeClass('active');
        }
        
        $video.prop("autoplay", nextValue);
        localStorage.setItem("autoPlay", nextValue.toString());
        
        if(nextValue) {
            $video.prop("muted", true);
            $video[0].play();
        } else {
            $video[0].pause();
        }
    }
    
    // 컨트롤러 숨김/표시 기능
    function hideControls() {
        $controls.hide();
        $hideBtn.hide();
        $showBtn.show();
    }
    
    function showControls() {
        $controls.show();
        $hideBtn.show();
        $showBtn.hide();
    }
    
    // 비디오 상태에 따른 재생/일시정지 버튼 토글
    $video.on('play', function() {
        $('#playBtn').hide();
        $('#pauseBtn').show();
    });
    
    $video.on('pause', function() {
        $('#playBtn').show();
        $('#pauseBtn').hide();
    });
    
    // 초기 상태 설정 - 처음에는 재생 버튼만 표시
    $('#playBtn').show();
    $('#pauseBtn').hide();
    
    // 이벤트 핸들러 등록
    $('#playBtn').on('click', playVideo);
    $('#pauseBtn').on('click', pauseVideo);
    $('#stopBtn').on('click', stopVideo);
    $('#rewindBtn').on('click', rewindVideo);
    $('#forwardBtn').on('click', fastForward);
    $('#slowDownBtn').on('click', reduceSpeed);
    $('#speedUpBtn').on('click', increaseSpeed);
    $('#normalSpeedBtn').on('click', originalSpeed);
    $('#loopBtn').on('click', repeatToggle);
    $('#autoplayBtn').on('click', toggleAutoplay);
    $('#hideControlsBtn').on('click', hideControls);
    $('#showControlsBtn').on('click', showControls);
});