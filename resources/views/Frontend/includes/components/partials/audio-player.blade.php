<div id="global-audio-player" class="spotify-player d-none">

    <div class="sp-left">
        <video id="player-mini-video" class="sp-artwork d-none" muted playsinline></video>
        <img id="player-thumbnail" class="sp-artwork" src="" alt="">
        <div class="sp-meta">
            <div id="player-title" class="sp-title">No audio</div>
            <div id="player-podcast" class="sp-artist"></div>
        </div>
    </div>

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

    <div class="sp-right">
        <button id="player-mute" class="sp-btn">
            <i class="fas fa-volume-up"></i>
        </button>
        <input type="range" id="player-volume" min="0" max="1" step="0.01">

    </div>
       <button id="player-float-toggle" class="sp-btn">
    <i class="fas fa-expand"></i>
</button>


    <video id="global-audio" playsinline></video>

</div>
<script>
(function () {

    /* ===============================
       Elements
    =============================== */
    const media = document.getElementById('global-audio'); // video element used for all media
    const miniVideo = document.getElementById('player-mini-video');

// Sync video element with audio source
audio.addEventListener('play', () => {
    if (miniVideo && !miniVideo.classList.contains('d-none')) {
        miniVideo.currentTime = audio.currentTime;
        miniVideo.play().catch(()=>{});
    }
});

audio.addEventListener('pause', () => {
    if (miniVideo) miniVideo.pause();
});

audio.addEventListener('timeupdate', () => {
    if (miniVideo && Math.abs(miniVideo.currentTime - audio.currentTime) > 0.3) {
        miniVideo.currentTime = audio.currentTime;
    }
});

    const player = document.getElementById('global-audio-player');
    if (!media || !player) return;

    const playBtn = document.getElementById('player-play');
    const prevBtn = document.getElementById('player-prev');
    const nextBtn = document.getElementById('player-next');
    const muteBtn = document.getElementById('player-mute');

    const progress = document.getElementById('player-progress');
    const volume = document.getElementById('player-volume');

    const titleEl = document.getElementById('player-title');
    const podcastEl = document.getElementById('player-podcast');
    const thumbEl = document.getElementById('player-thumbnail');
    const miniVideo = document.getElementById('player-mini-video');
    const currentTimeEl = document.getElementById('sp-current');
    const durationEl = document.getElementById('sp-duration');

    const STORAGE_KEY = 'nowstream_player_state';

    let playlist = [];
    let currentIndex = 0;
    let hlsInstance = null;

    /* ===============================
       Helpers
    =============================== */
    function formatTime(sec) {
        if (!sec || isNaN(sec)) return '0:00';
        const m = Math.floor(sec / 60);
        const s = Math.floor(sec % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    }

    function updatePlayIcon() {
        playBtn.innerHTML = media.paused
            ? '<i class="fas fa-play"></i>'
            : '<i class="fas fa-pause"></i>';
    }

    function updateMuteIcon() {
        muteBtn.innerHTML = media.muted
            ? '<i class="fas fa-volume-mute"></i>'
            : '<i class="fas fa-volume-up"></i>';
    }

   function updateUI(track) {
    titleEl.innerText = track.title || 'Unknown';
    podcastEl.innerText = track.podcast || '';

    const miniVideo = document.getElementById('player-mini-video');

    if (track.type === 'video') {
        // Show video
        miniVideo.classList.remove('d-none');
        thumbEl.classList.add('d-none');

        if (miniVideo.src !== track.src) {
            miniVideo.src = track.src;
        }

        miniVideo.play().catch(()=>{});
    } else {
        // Audio mode
        miniVideo.classList.add('d-none');
        thumbEl.classList.remove('d-none');
        thumbEl.src = track.thumbnail || '/assets/img/default.png';
    }

    currentTimeEl.innerText = '0:00';
    durationEl.innerText = '0:00';

    player.classList.remove('d-none');
}

    function destroyHls() {
        if (hlsInstance) {
            hlsInstance.destroy();
            hlsInstance = null;
        }
    }

    function loadSource(src) {
        destroyHls();

        if (src.includes('.m3u8') && window.Hls && Hls.isSupported()) {
            hlsInstance = new Hls();
            hlsInstance.loadSource(src);
            hlsInstance.attachMedia(media);
        } else {
            media.src = src;
        }
    }

    function saveState() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify({
                playlist,
                currentIndex,
                time: media.currentTime || 0,
                volume: media.volume,
                muted: media.muted,
                playing: !media.paused
            }));
        } catch (e) {}
    }

    function restoreState() {
        const state = JSON.parse(localStorage.getItem(STORAGE_KEY));
        if (!state?.playlist?.length) return;

        playlist = state.playlist;
        currentIndex = state.currentIndex || 0;

        const track = playlist[currentIndex];
        updateUI(track);
        loadSource(track.src);

        media.volume = state.volume ?? 1;
        media.muted = state.muted ?? false;
        volume.value = media.volume;
        updateMuteIcon();

        const seek = () => {
            media.currentTime = state.time || 0;
            if (state.playing) media.play().catch(()=>{});
            updatePlayIcon();
            media.removeEventListener('loadedmetadata', seek);
        };

        if (media.readyState >= 1) seek();
        else media.addEventListener('loadedmetadata', seek);
    }

    /* ===============================
       Core Playback
    =============================== */
    function loadTrack(index) {
        if (!playlist[index]) return;

        const track = playlist[index];
        currentIndex = index;

        loadSource(track.src);
        media.currentTime = 0;

        updateUI(track);
        media.play().catch(()=>{});
        updatePlayIcon();
        saveState();
    }

    /* ===============================
       Controls
    =============================== */
    playBtn?.addEventListener('click', () => {
        media.paused ? media.play() : media.pause();
        updatePlayIcon();
        saveState();
    });

    prevBtn?.addEventListener('click', () => {
        if (currentIndex > 0) loadTrack(currentIndex - 1);
    });

    nextBtn?.addEventListener('click', () => {
        if (currentIndex < playlist.length - 1) loadTrack(currentIndex + 1);
    });

    muteBtn?.addEventListener('click', () => {
        media.muted = !media.muted;
        updateMuteIcon();
        saveState();
    });

    volume?.addEventListener('input', () => {
        media.volume = volume.value;
        media.muted = false;
        updateMuteIcon();
        saveState();
    });

    progress?.addEventListener('input', () => {
        if (!isNaN(media.duration)) {
            media.currentTime = (progress.value / 100) * media.duration;
        }
    });

    media.addEventListener('timeupdate', () => {
        currentTimeEl.innerText = formatTime(media.currentTime);

        if (!isNaN(media.duration) && media.duration > 0) {
            progress.value = (media.currentTime / media.duration) * 100;
            durationEl.innerText = formatTime(media.duration);
        }

        saveState();
    });

    media.addEventListener('ended', () => {
        if (currentIndex < playlist.length - 1) {
            loadTrack(currentIndex + 1);
        }
    });

    /* ===============================
       Global API
    =============================== */
    window.playSingleAudio = function (src, title = '', podcast = '', thumbnail = '') {
        playlist = [{
            src,
            title,
            podcast,
            thumbnail,
            type: 'audio'
        }];
        loadTrack(0);
    };

    window.playGlobalVideo = function (src, title = '', channel = '', 'Video', thumbnail = '') {
        playlist = [{
            src,
            title,
            podcast: channel,
            thumbnail,
            type: 'video'
        }];
        loadTrack(0);
    };

    window.playGlobalPlaylist = function (list, index = 0) {
        if (!Array.isArray(list) || !list.length) return;
        playlist = list;
        loadTrack(index);
    };

    /* ===============================
       Init
    =============================== */
    document.addEventListener('DOMContentLoaded', restoreState);

})();
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const mainVideo = document.getElementById('player');
    if (!mainVideo) return;

    const src = mainVideo.dataset.src;
    const title = mainVideo.dataset.title;
    const thumb = mainVideo.dataset.thumb;

    let moved = false;

    window.addEventListener('scroll', function () {

        const rect = mainVideo.getBoundingClientRect();

        if (rect.bottom < 0 && !moved) {
            moved = true;
            playGlobalVideo(src, title, 'Video', thumb);
            mainVideo.pause();
        }

        if (rect.top >= 0 && moved) {
            moved = false;

            const globalMedia = document.getElementById('global-audio');

            mainVideo.src = globalMedia.src;
            mainVideo.currentTime = globalMedia.currentTime;
            mainVideo.play();

            globalMedia.pause();
            document.getElementById('global-audio-player').classList.add('d-none');
        }

    });

});
</script>
<script>
(function () {

    const player = document.getElementById('global-audio-player');
    const toggleBtn = document.getElementById('player-float-toggle');

    if (!player || !toggleBtn) return;

    /* ===============================
       Toggle Floating Mode
    =============================== */
    toggleBtn.addEventListener('click', function () {
        player.classList.toggle('floating');

        if (player.classList.contains('floating')) {
            toggleBtn.innerHTML = '<i class="fas fa-compress"></i>';
        } else {
            toggleBtn.innerHTML = '<i class="fas fa-expand"></i>';
            // Reset position when exiting float
            player.style.top = '';
            player.style.left = '';
            player.style.right = '';
            player.style.bottom = '0';
        }
    });

    /* ===============================
       Draggable (only in floating mode)
    =============================== */
    let isDragging = false;
    let offsetX = 0;
    let offsetY = 0;

    player.addEventListener('mousedown', function (e) {
        if (!player.classList.contains('floating')) return;

        isDragging = true;
        const rect = player.getBoundingClientRect();
        offsetX = e.clientX - rect.left;
        offsetY = e.clientY - rect.top;

        player.style.right = 'auto';
        player.style.bottom = 'auto';
    });

    document.addEventListener('mousemove', function (e) {
        if (!isDragging) return;

        player.style.left = (e.clientX - offsetX) + 'px';
        player.style.top = (e.clientY - offsetY) + 'px';
    });

    document.addEventListener('mouseup', function () {
        isDragging = false;
    });

})();
</script>

<style>
/* Floating container */
.spotify-player.floating {
    width: 360px;
    height: 220px;
    bottom: 20px;
    right: 20px;
    left: auto;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,.5);
    overflow: hidden;
    padding: 0;
}

/* Hide controls in floating mode */
.spotify-player.floating .sp-center,
.spotify-player.floating .sp-right,
.spotify-player.floating .sp-meta,
.spotify-player.floating #player-thumbnail {
    display: none;
}

/* Video fills container */
.spotify-player.floating #player-mini-video {
    display: block !important;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Default (non-floating) video hidden */
#player-mini-video {
    width: 56px;
    height: 56px;
    object-fit: cover;
    border-radius: 4px;
}


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

    #global-audio {
        height: 0;
        width: 0;
        position: absolute;
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
        .sp-left {
            width: 40%;
        }

        .sp-center {
            width: 60%;
        }

        .sp-right {
            display: none;
        }
    }
</style>