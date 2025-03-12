// 비디오 플레이어 클래스 정의
class VideoPlayer {
    constructor() {
        // DOM 요소
        this.video = document.getElementById('adVideo');
        this.playBtn = document.getElementById('playBtn');
        this.pauseBtn = document.getElementById('pauseBtn');
        this.stopBtn = document.getElementById('stopBtn');
        this.rewindBtn = document.getElementById('rewindBtn');
        this.forwardBtn = document.getElementById('forwardBtn');
        this.slowDownBtn = document.getElementById('slowDownBtn');
        this.speedUpBtn = document.getElementById('speedUpBtn');
        this.normalSpeedBtn = document.getElementById('normalSpeedBtn');
        this.loopBtn = document.getElementById('loopBtn');
        this.autoplayBtn = document.getElementById('autoplayBtn');
        this.hideControlsBtn = document.getElementById('hideControlsBtn');
        this.showControlsBtn = document.getElementById('showControlsBtn');
        this.videoControls = document.getElementById('videoControls');
        
        // 상태 변수
        this.isControlsVisible = true;
        this.playbackRate = 1.0;
        
        // 로컬 스토리지에서 자동재생 설정 불러오기
        this.loadAutoplayFromLocalStorage();
        
        // 비디오 로드 및 초기화
        this.loadVideo();
        
        // 이벤트 리스너 초기화
        this.initEventListeners();
    }
    
    // 로컬 스토리지에서 자동재생 설정 불러오기
    loadAutoplayFromLocalStorage() {
        // 자동 재생 설정 불러오기
        const autoplay = localStorage.getItem('videoAutoplay');
        if (autoplay !== null) {
            this.video.autoplay = autoplay === 'true';
        }
    }
    
    // 로컬 스토리지에 자동재생 설정 저장하기
    saveAutoplayToLocalStorage() {
        localStorage.setItem('videoAutoplay', this.video.autoplay.toString());
    }
    
    // 비디오 로드 메서드 - 첫 프레임이 보이도록 함
    loadVideo() {
        // 비디오가 로드되면 첫 프레임으로 이동 (0초)
        $(this.video).on('loadedmetadata', () => {
            // 비디오 메타데이터 로드 완료 후 실행
            this.video.currentTime = 0.001; // 첫 프레임을 표시하기 위해 아주 작은 값 설정
            
            // 상태 업데이트
            this.updateLoopState();
            this.updateAutoplayState();
            
            // 자동 재생 설정이 켜져 있다면 재생 시도
            if (this.video.autoplay) {
                this.play();
            }
        });
    }
    
    // 이벤트 리스너 초기화 메서드
    initEventListeners() {
        // 비디오 상태 이벤트
        $(this.video).on('play', () => this.updatePlayPauseState(true));
        $(this.video).on('pause', () => this.updatePlayPauseState(false));
        $(this.video).on('ended', () => this.handleVideoEnd());
        
        // 컨트롤 버튼 이벤트
        $(this.playBtn).on('click', () => this.play());
        $(this.pauseBtn).on('click', () => this.pause());
        $(this.stopBtn).on('click', () => this.stop());
        $(this.rewindBtn).on('click', () => this.rewind());
        $(this.forwardBtn).on('click', () => this.forward());
        $(this.slowDownBtn).on('click', () => this.changeSpeed(-0.1));
        $(this.speedUpBtn).on('click', () => this.changeSpeed(0.1));
        $(this.normalSpeedBtn).on('click', () => this.resetSpeed());
        $(this.loopBtn).on('click', () => this.toggleLoop());
        $(this.autoplayBtn).on('click', () => this.toggleAutoplay());
        $(this.hideControlsBtn).on('click', () => this.hideControls());
        $(this.showControlsBtn).on('click', () => this.showControls());
        
        // 비디오 클릭으로 재생/일시정지 토글
        $(this.video).on('click', () => this.togglePlayPause());
        
        // 마우스 움직임 감지로 컨트롤러 표시
        $(this.video).on('mousemove', () => {
            if (!this.isControlsVisible) {
                $(this.showControlsBtn).fadeIn(300);
            }
        });
        
        // 마우스가 비디오에서 벗어났을 때 컨트롤러 보이기 버튼 숨김
        $(this.video).on('mouseout', () => {
            if (!this.isControlsVisible) {
                setTimeout(() => {
                    $(this.showControlsBtn).fadeOut(300);
                }, 1500);
            }
        });
        
        // 툴팁 추가 - 버튼 제목을 툴팁으로 표시
        $('.control-btn').each(function() {
            const title = $(this).attr('title');
            if (title) {
                $(this).hover(
                    function() {
                        $('<div class="tooltip"></div>')
                            .text(title)
                            .appendTo('body')
                            .css({
                                top: $(this).offset().top - 30,
                                left: $(this).offset().left + $(this).width() / 2 - 50
                            });
                    },
                    function() {
                        $('.tooltip').remove();
                    }
                );
            }
        });
        
        // 초기 설정
        this.updatePlayPauseState(false);
        this.updateLoopState();
        this.updateAutoplayState();
        // 처음에는 '컨트롤러 보이기' 버튼 숨기기
        $(this.showControlsBtn).hide();
    }
    
    // 재생 메서드
    play() {
        const playPromise = this.video.play();
        
        // 재생 Promise 처리 (브라우저 호환성)
        if (playPromise !== undefined) {
            playPromise.catch(error => {
                console.log('비디오 재생 실패:', error);
                // 자동 재생 실패 시 사용자 입력 필요를 알림
                alert('브라우저 정책으로 인해 자동 재생이 차단되었습니다. 영상을 재생하려면 재생 버튼을 클릭하세요.');
            });
        }
    }
    
    // 일시정지 메서드
    pause() {
        this.video.pause();
    }
    
    // 정지 메서드
    stop() {
        this.video.pause();
        this.video.currentTime = 0;
    }
    
    // 재생/일시정지 토글 메서드
    togglePlayPause() {
        if(this.video.paused) {
            this.play();
        } else {
            this.pause();
        }
    }
    
    // 되감기 메서드 (10초)
    rewind() {
        this.video.currentTime = Math.max(0, this.video.currentTime - 10);
    }
    
    // 빨리감기 메서드 (10초)
    forward() {
        this.video.currentTime = Math.min(this.video.duration, this.video.currentTime + 10);
    }
    
    // 재생 속도 변경 메서드
    changeSpeed(delta) {
        this.playbackRate = Math.max(0.1, Math.min(3.0, this.playbackRate + delta));
        this.playbackRate = parseFloat(this.playbackRate.toFixed(1)); // 소수점 한 자리로 정리
        this.video.playbackRate = this.playbackRate;
        $(this.normalSpeedBtn).find('.speed-value').text(this.playbackRate.toFixed(1) + 'x');
    }
    
    // 재생 속도 초기화 메서드
    resetSpeed() {
        this.playbackRate = 1.0;
        this.video.playbackRate = this.playbackRate;
        $(this.normalSpeedBtn).find('.speed-value').text('1.0x');
    }
    
    // 반복 재생 토글 메서드
    toggleLoop() {
        this.video.loop = !this.video.loop;
        this.updateLoopState();
    }
    
    // 반복 상태 업데이트 메서드
    updateLoopState() {
        if (this.video.loop) {
            $(this.loopBtn).attr('data-active', 'true');
        } else {
            $(this.loopBtn).attr('data-active', 'false');
        }
    }
    
    // 자동 재생 토글 메서드
    toggleAutoplay() {
        this.video.autoplay = !this.video.autoplay;
        this.updateAutoplayState();
        
        // 로컬 스토리지에 자동 재생 설정 저장
        this.saveAutoplayToLocalStorage();
        
        // 상태 알림으로 사용자에게 피드백 제공
        const message = this.video.autoplay ? 
            '자동 재생이 활성화되었습니다. 다음 방문 시 자동으로 재생됩니다.' : 
            '자동 재생이 비활성화되었습니다.';
        
        this.showNotification(message);
    }
    
    // 알림 메시지 표시
    showNotification(message) {
        // 이미 표시된 알림이 있다면 제거
        $('.video-notification').remove();
        
        // 새 알림 생성
        const notification = $('<div class="video-notification"></div>')
            .text(message)
            .appendTo('.video-wrapper');
        
        // 3초 후 알림 자동 제거
        setTimeout(() => {
            notification.fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    // 자동 재생 상태 업데이트 메서드
    updateAutoplayState() {
        if (this.video.autoplay) {
            $(this.autoplayBtn).attr('data-active', 'true');
        } else {
            $(this.autoplayBtn).attr('data-active', 'false');
        }
    }
    
    // 컨트롤러 숨김 메서드
    hideControls() {
        this.isControlsVisible = false;
        $(this.videoControls).addClass('hidden');
        $(this.showControlsBtn).fadeIn(300);
    }
    
    // 컨트롤러 표시 메서드
    showControls() {
        this.isControlsVisible = true;
        $(this.videoControls).removeClass('hidden');
        $(this.showControlsBtn).fadeOut(300);
    }
    
    // 재생/일시정지 상태 업데이트 메서드
    updatePlayPauseState(isPlaying) {
        if (isPlaying) {
            $(this.playBtn).hide();
            $(this.pauseBtn).show();
        } else {
            $(this.playBtn).show();
            $(this.pauseBtn).hide();
        }
    }
    
    // 비디오 끝났을 때 처리 메서드
    handleVideoEnd() {
        if (!this.video.loop) {
            this.updatePlayPauseState(false);
        }
    }
}

// 문서 로드 완료 시 비디오 플레이어 인스턴스 생성
$(document).ready(() => {
    const videoPlayer = new VideoPlayer();
    
    // 초기 설정으로 일부 요소 숨김
    $('#pauseBtn').hide();
    
    // 알림 스타일 추가
    $('<style>\
        .video-notification {\
            position: absolute;\
            top: 70px;\
            left: 50%;\
            transform: translateX(-50%);\
            background-color: rgba(0, 0, 0, 0.8);\
            color: white;\
            padding: 10px 20px;\
            border-radius: 5px;\
            font-size: 14px;\
            z-index: 100;\
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);\
            text-align: center;\
            animation: fadeInOut 3s ease-in-out;\
        }\
        @keyframes fadeInOut {\
            0% { opacity: 0; transform: translate(-50%, -20px); }\
            10% { opacity: 1; transform: translate(-50%, 0); }\
            90% { opacity: 1; transform: translate(-50%, 0); }\
            100% { opacity: 0; transform: translate(-50%, -20px); }\
        }\
        .tooltip {\
            position: absolute;\
            background-color: rgba(0, 0, 0, 0.8);\
            color: white;\
            padding: 5px 10px;\
            border-radius: 4px;\
            font-size: 12px;\
            z-index: 200;\
            pointer-events: none;\
            width: 100px;\
            text-align: center;\
        }\
    </style>').appendTo('head');
});