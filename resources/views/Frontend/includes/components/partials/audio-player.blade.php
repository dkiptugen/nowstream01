<div id="global-audio-player" class="spotify-player d-none">
    <!-- LEFT -->
    <div class="sp-left">
        <img id="player-thumbnail" class="sp-artwork" src="" alt="">
        <div class="sp-meta">
            <div id="player-title" class="sp-title">No audio</div>
            <div id="player-podcast" class="sp-artist"></div>
        </div>
    </div>

    <!-- CENTER -->
    <div class="sp-center">
        <div class="sp-controls">
            <button id="player-prev" class="sp-btn">
                <i class="fas fa-step-backward"></i>
            </button>

            <button id="player-play" class="sp-btn sp-play">
                <i class="fas fa-play"></i>
            </button>

            <button id="player-next" class="sp-btn">
                <i class="fas fa-step-forward"></i>
            </button>
        </div>

        <div class="sp-progress-wrap">
            <span id="sp-current">0:00</span>
            <input type="range" id="player-progress" value="0">
            <span id="sp-duration">0:00</span>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="sp-right">
        <button id="player-mute" class="sp-btn">
            <i class="fas fa-volume-up"></i>
        </button>
        <input type="range" id="player-volume" min="0" max="1" step="0.01">
        <button id="toggle-queue" class="player-btn btn-dark"><i class="fas fa-list"></i></button>
    </div>

    <audio id="global-audio"></audio>
    <div id="youtube-container" class="d-none">
        <div id="youtube-player"></div>
    </div>
</div>

<!-- Queue -->
<div id="player-queue" class="queue d-none">
    <h4>Up Next</h4>
    <ul id="queue-list"></ul>
</div>

<script src="https://www.youtube.com/iframe_api"></script>
<script>
(function() {
    /* ===============================
       Elements
    =============================== */
    const audio = document.getElementById('global-audio');
    const player = document.getElementById('global-audio-player');

    const playBtn = document.getElementById('player-play');
    const prevBtn = document.getElementById('player-prev');
    const nextBtn = document.getElementById('player-next');
    const muteBtn = document.getElementById('player-mute');
    const toggleQueueBtn = document.getElementById('toggle-queue');

    const progress = document.getElementById('player-progress');
    const volume = document.getElementById('player-volume');

    const titleEl = document.getElementById('player-title');
    const podcastEl = document.getElementById('player-podcast');
    const thumbEl = document.getElementById('player-thumbnail');
    const currentTimeEl = document.getElementById('sp-current');
    const durationEl = document.getElementById('sp-duration');

    const queueEl = document.getElementById('player-queue');
    const queueListEl = document.getElementById('queue-list');

    const youtubeContainer = document.getElementById('youtube-container');

    const STORAGE_KEY = 'audio_state';
    let playlist = [];
    let currentIndex = 0;
    let ytPlayer = null;
    let isYouTube = false;
    let updateInterval = null;

    /* ===============================
       Helpers
    =============================== */
    function formatTime(sec) {
        if (!sec) return '0:00';
        const m = Math.floor(sec / 60);
        const s = Math.floor(sec % 60).toString().padStart(2,'0');
        return `${m}:${s}`;
    }

    function updateQueue() {
        queueListEl.innerHTML = '';
        playlist.forEach((track, i) => {
            const li = document.createElement('li');
            li.textContent = track.title;
            if (i === currentIndex) li.classList.add('active');
            li.addEventListener('click', () => loadTrack(i));
            queueListEl.appendChild(li);
        });
    }

    function updateUI(track) {
        titleEl.innerText = track.title || 'Unknown';
        podcastEl.innerText = track.podcast || '';
        thumbEl.src = track.thumbnail || '';
        player.classList.remove('d-none');
        updateQueue();
    }

    function saveState() {
        localStorage.setItem(STORAGE_KEY, JSON.stringify({
            playlist,
            currentIndex,
            time: audio.currentTime || 0,
            volume: audio.volume,
            muted: audio.muted,
            playing: !audio.paused,
            isYouTube
        }));
    }

    function restoreState() {
        const state = JSON.parse(localStorage.getItem(STORAGE_KEY));
        if (!state?.playlist?.length) return;
        playlist = state.playlist;
        currentIndex = state.currentIndex || 0;
        loadTrack(currentIndex, state.time || 0);
        volume.value = state.volume ?? 1;
        audio.volume = volume.value;
        audio.muted = state.muted ?? false;
    }

    /* ===============================
       YouTube Handling
    =============================== */
    function createYouTubePlayer(videoId, startTime = 0) {
        if (ytPlayer) ytPlayer.destroy();
        youtubeContainer.classList.remove('d-none');
        audio.style.display = 'none';

        ytPlayer = new YT.Player('youtube-player', {
            height: '0',
            width: '0',
            videoId: videoId,
            playerVars: { 
                autoplay: 1,
                controls: 0,
                start: startTime,
                modestbranding: 1
            },
            events: {
                onReady: (e) => {
                    e.target.playVideo();
                    updatePlayIcon();
                    startYTInterval();
                },
                onStateChange: (e) => {
                    if(e.data === YT.PlayerState.ENDED){
                        nextTrack();
                    }
                }
            }
        });
    }

    function startYTInterval() {
        clearInterval(updateInterval);
        updateInterval = setInterval(() => {
            if(!ytPlayer) return;
            const current = ytPlayer.getCurrentTime();
            const duration = ytPlayer.getDuration();
            currentTimeEl.innerText = formatTime(current);
            durationEl.innerText = formatTime(duration);
            progress.value = (current / duration) * 100;
        }, 500);
    }

    function stopYTInterval() {
        clearInterval(updateInterval);
    }

    function extractYouTubeId(url) {
        const reg = /(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/;
        const match = url.match(reg);
        return match ? match[1] : null;
    }

    /* ===============================
       Core Playback
    =============================== */
    function loadTrack(index, startTime = 0) {
        if(!playlist[index]) return;
        currentIndex = index;
        const track = playlist[index];
        updateUI(track);

        isYouTube = track.type === 'youtube';
        if(isYouTube){
            const ytId = extractYouTubeId(track.src);
            if(ytId) createYouTubePlayer(ytId, startTime);
        } else {
            youtubeContainer.classList.add('d-none');
            audio.style.display = 'block';
            stopYTInterval();
            audio.src = track.src;
            audio.currentTime = startTime;
            audio.play().catch(()=>{});
        }

        saveState();
    }

    function nextTrack() {
        if(currentIndex < playlist.length -1) loadTrack(currentIndex+1);
    }

    function prevTrack() {
        if(currentIndex>0) loadTrack(currentIndex-1);
    }

    function updatePlayIcon() {
        if(isYouTube){
            playBtn.innerHTML = ytPlayer && ytPlayer.getPlayerState()===YT.PlayerState.PLAYING ? 
                '<i class="fas fa-pause"></i>' : '<i class="fas fa-play"></i>';
        } else {
            playBtn.innerHTML = audio.paused ? '<i class="fas fa-play"></i>' : '<i class="fas fa-pause"></i>';
        }
    }

    /* ===============================
       Controls
    =============================== */
    playBtn?.addEventListener('click', ()=>{
        if(isYouTube){
            if(ytPlayer.getPlayerState()===YT.PlayerState.PLAYING) ytPlayer.pauseVideo();
            else ytPlayer.playVideo();
        } else {
            audio.paused ? audio.play() : audio.pause();
        }
        updatePlayIcon();
    });

    nextBtn?.addEventListener('click', nextTrack);
    prevBtn?.addEventListener('click', prevTrack);

    toggleQueueBtn?.addEventListener('click', ()=>queueEl.classList.toggle('d-none'));

    muteBtn?.addEventListener('click', ()=>{
        if(isYouTube) ytPlayer.setVolume(audio.muted?100:0); 
        audio.muted = !audio.muted;
        updatePlayIcon();
    });

    volume?.addEventListener('input', ()=>{
        audio.volume = volume.value;
        audio.muted = false;
        updatePlayIcon();
    });

    progress?.addEventListener('input', ()=>{
        if(isYouTube && ytPlayer){
            const duration = ytPlayer.getDuration();
            ytPlayer.seekTo((progress.value/100)*duration,true);
        } else if(audio.duration){
            audio.currentTime = (progress.value/100)*audio.duration;
        }
    });

    audio.addEventListener('timeupdate', ()=>{
        if(!isYouTube && audio.duration){
            currentTimeEl.innerText = formatTime(audio.currentTime);
            durationEl.innerText = formatTime(audio.duration);
            progress.value = (audio.currentTime/audio.duration)*100;
        }
    });

    audio.addEventListener('ended', nextTrack);

    /* ===============================
       Global API
    =============================== */
    window.playGlobalAudio = function(list,index=0){
        if(!Array.isArray(list) || !list.length) return;
        playlist = list;
        loadTrack(index);
    };

    window.playSingleAudio = function(src,title='',podcast='',thumbnail='',type='file'){
        playlist = [{src,title,podcast,thumbnail,type}];
        loadTrack(0);
    };

    document.addEventListener('DOMContentLoaded', restoreState);

})();
</script>

<style>
.queue {
    position: fixed;
    bottom: 70px;
    right: 20px;
    width: 250px;
    max-height: 300px;
    overflow-y: auto;
    background: #111;
    color: #fff;
    border-radius: 6px;
    padding: 10px;
    box-shadow: 0 0 10px #000;
    z-index: 10000;
}
.queue ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.queue li {
    padding: 8px;
    cursor: pointer;
    border-bottom: 1px solid #333;
}
.queue li.active {
    background: #e50914;
}
.queue li:hover {
    background: #222;
}
</style>
 
<style>
   .spotify-player {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 80px;
    background: #121212;
    border-top: 1px solid #282828;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 16px;
    z-index: 9999;
    color: #fff;
    font-family: Arial, sans-serif;
}

/* LEFT */
.sp-left {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 25%;
    min-width: 220px;
}

.sp-artwork {
    width: 56px;
    height: 56px;
    object-fit: cover;
    border-radius: 4px;
}

.sp-title {
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sp-artist {
    font-size: 12px;
    color: #b3b3b3;
}

/* CENTER */
.sp-center {
    width: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.sp-controls {
    display: flex;
    align-items: center;
    gap: 18px;
    margin-bottom: 4px;
}

.sp-btn {
    background: none;
    border: none;
    color: #b3b3b3;
    font-size: 16px;
    cursor: pointer;
    transition: 0.2s;
}

.sp-btn:hover {
    color: #fff;
}

.sp-play {
    background: #fff;
    color: #000;
    border-radius: 50%;
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sp-play:hover {
    transform: scale(1.08);
}

/* Progress */
.sp-progress-wrap {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 8px;
}

#player-progress {
    flex: 1;
    height: 4px;
    appearance: none;
    background: #535353;
    border-radius: 2px;
    cursor: pointer;
}

#player-progress::-webkit-slider-thumb {
    appearance: none;
    width: 10px;
    height: 10px;
    background: #e4d804;
    border-radius: 50%;
}

/* RIGHT */
.sp-right {
    width: 25%;
    min-width: 180px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
}

#player-volume {
    width: 100px;
    height: 4px;
    appearance: none;
    background: #535353;
}

#player-volume::-webkit-slider-thumb {
    appearance: none;
    width: 10px;
    height: 10px;
    background: #e4d804;
    border-radius: 50%;
}

/* Time */
#sp-current,
#sp-duration {
    font-size: 11px;
    color: #b3b3b3;
    width: 40px;
    text-align: center;
}

/* Mobile */
@media (max-width: 768px) {
    .sp-left { width: 40%; }
    .sp-center { width: 60%; }
    .sp-right { display: none; }
}
</style>