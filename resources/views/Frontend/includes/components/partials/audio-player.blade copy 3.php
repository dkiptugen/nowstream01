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
const isReload = performance.getEntriesByType("navigation")[0]?.type === "reload";

        const media = document.getElementById('global-audio');
        const player = document.getElementById('global-audio-player');
        if (!media || !player) return;

        /* =====================
           Elements
        ===================== */
        const playBtn = document.getElementById('player-play');
        const nextBtn = document.getElementById('player-next');
        const prevBtn = document.getElementById('player-prev');
        const muteBtn = document.getElementById('player-mute');
        const volumeEl = document.getElementById('player-volume');
        const progress = document.getElementById('player-progress');
        const currentEl = document.getElementById('sp-current');
        const durationEl = document.getElementById('sp-duration');
        const floatBtn = document.getElementById('player-float-toggle');

        const titleEl = document.getElementById('player-title');
        const podcastEl = document.getElementById('player-podcast');
        const thumbEl = document.getElementById('player-thumbnail');
        const miniVideo = document.getElementById('player-mini-video');

        const STORAGE_KEY = 'nowstream_player';

        let playlist = [];
        let currentIndex = 0;
        let hls = null;

        /* =====================
           Helpers
        ===================== */
        function formatTime(sec) {
            sec = Math.floor(sec || 0);
            const m = Math.floor(sec / 60);
            const s = sec % 60;
            return m + ':' + (s < 10 ? '0' + s : s);
        }

        function updatePlayIcon() {
            playBtn.innerHTML = media.paused
                ? '<i class="fas fa-play"></i>'
                : '<i class="fas fa-pause"></i>';
        }

        function updateMuteIcon() {
            muteBtn.innerHTML = audio.muted ?
                '<i class="fas fa-volume-mute"></i>' :
                '<i class="fas fa-volume-up"></i>';
        }
        function destroyHLS() {
            if (hls) {
                hls.destroy();
                hls = null;
            }
        }

        function loadSource(src) {
            destroyHLS();

            if (src.includes('.m3u8') && window.Hls && Hls.isSupported()) {
                hls = new Hls();
                hls.loadSource(src);
                hls.attachMedia(media);
                hls.on(Hls.Events.MANIFEST_PARSED, () => media.play().catch(() => { }));
            } else {
                media.src = src;
                media.play().catch(() => { });
            }
        }

        function updateUI(track) {
            titleEl.innerText = track.title || '';
            podcastEl.innerText = track.podcast || '';

            if (track.type === 'video') {
                thumbEl.classList.add('d-none');
                miniVideo.classList.remove('d-none');
                miniVideo.src = track.src;
                miniVideo.currentTime = media.currentTime;
                miniVideo.play().catch(() => { });
            } else {
                miniVideo.classList.add('d-none');
                thumbEl.classList.remove('d-none');
                thumbEl.src = track.thumbnail || '/assets/img/default.png';
            }

            player.classList.remove('d-none');
        }

   function saveState() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify({
        playlist,
        currentIndex,
        time: media.currentTime,
        playing: !media.paused,
        volume: media.volume,
        muted: media.muted
    }));
}

media.addEventListener('play', saveState);
media.addEventListener('pause', saveState);
media.addEventListener('volumechange', saveState);

function restoreState() {
    const state = JSON.parse(localStorage.getItem(STORAGE_KEY));
    if (!state?.playlist?.length) return;

    playlist = state.playlist;
    currentIndex = state.currentIndex || 0;

    const track = playlist[currentIndex];

    // Restore user preferences first
    media.volume = typeof state.volume === 'number' ? state.volume : 1;
    media.muted = state.muted || false;

    loadSource(track.src);
    updateUI(track);

    media.addEventListener('loadedmetadata', () => {
        media.currentTime = state.time || 0;

        if (state.playing) {
            const originalMuted = media.muted;

            // Try normal play first
            let playPromise = media.play();

            if (playPromise !== undefined) {
                playPromise.catch(() => {
                    // Autoplay blocked → temporarily mute
                    media.muted = true;
                    media.play().then(() => {
                        // Restore user's mute preference
                        media.muted = originalMuted;
                    }).catch(() => {});
                });
            }
        }
    }, { once: true });
}

        function loadTrack(index) {
            if (!playlist[index]) return;
            currentIndex = index;

            const track = playlist[index];
            loadSource(track.src);
            updateUI(track);
            updatePlayIcon();
            saveState();
        }

        function nextTrack() {
            if (playlist.length < 2) return;
            currentIndex = (currentIndex + 1) % playlist.length;
            loadTrack(currentIndex);
        }

        function prevTrack() {
            if (playlist.length < 2) return;
            currentIndex = (currentIndex - 1 + playlist.length) % playlist.length;
            loadTrack(currentIndex);
        }

        /* =====================
           Controls
        ===================== */

        // Play / Pause
        playBtn.addEventListener('click', () => {
            media.paused ? media.play() : media.pause();
        });

        media.addEventListener('play', updatePlayIcon);
        media.addEventListener('pause', updatePlayIcon);

        // Next / Prev
        nextBtn.addEventListener('click', nextTrack);
        prevBtn.addEventListener('click', prevTrack);

        // Auto next when finished
        media.addEventListener('ended', nextTrack);

        /* =====================
           Volume / Mute
        ===================== */
        volumeEl.value = media.volume;

        volumeEl.addEventListener('input', () => {
            media.volume = volumeEl.value;
            media.muted = volumeEl.value == 0;
        });

        muteBtn.addEventListener('click', () => {
            media.muted = !media.muted;
            volumeEl.value = media.muted ? 0 : media.volume;
            muteBtn.innerHTML = media.muted
                ? '<i class="fas fa-volume-mute"></i>'
                : '<i class="fas fa-volume-up"></i>';
        });

        /* =====================
           Progress
        ===================== */
        media.addEventListener('loadedmetadata', () => {
            progress.max = media.duration || 0;
            durationEl.innerText = formatTime(media.duration);
        });

        media.addEventListener('timeupdate', () => {
            progress.value = media.currentTime;
            currentEl.innerText = formatTime(media.currentTime);

            // sync mini video
            if (!miniVideo.classList.contains('d-none')) {
                if (Math.abs(miniVideo.currentTime - media.currentTime) > 0.3) {
                    miniVideo.currentTime = media.currentTime;
                }
            }

            saveState();
        });

        progress.addEventListener('input', () => {
            media.currentTime = progress.value;
        });

        /* =====================
           Floating
        ===================== */
        floatBtn.addEventListener('click', () => {
            player.classList.toggle('floating');
        });

        /* =====================
           Global API
        ===================== */

        // Playlist player (THIS fixes your error)
        window.playGlobalAudio = (list, index = 0) => {
            playlist = list.map(item => ({
                src: item.src,
                title: item.title || '',
                podcast: item.podcast || '',
                thumbnail: item.thumbnail || '',
                type: item.type || 'audio'
            }));
            loadTrack(index);
        };

        window.playSingleAudio = (src, title = '', podcast = '', thumbnail = '') => {
            playlist = [{ src, title, podcast, thumbnail, type: 'audio' }];
            loadTrack(0);
        };

        window.playGlobalVideo = (src, title = '', channel = '', thumbnail = '') => {
            playlist = [{ src, title, podcast: channel, thumbnail, type: 'video' }];
            loadTrack(0);
        };

        /* =====================
           Restore on load
        ===================== */
       restoreState();
    })();
</script>
<script>
    /* =====================
   Scroll-to-Float Video Handoff
===================== */
document.addEventListener('DOMContentLoaded', () => {

    const pageVideo = document.getElementById('player'); // main page video
    if (!pageVideo) return;

    let movedToGlobal = false;

    window.addEventListener('scroll', () => {

        const rect = pageVideo.getBoundingClientRect();
        const outOfView = rect.bottom < 0 || rect.top > window.innerHeight;

        // === Move to global when video leaves screen ===
        if (outOfView && !movedToGlobal && !pageVideo.paused) {

            movedToGlobal = true;

            const src = pageVideo.currentSrc || pageVideo.src;

            window.playGlobalVideo(
                src,
                pageVideo.dataset.title || '',
                pageVideo.dataset.channel || 'Video',
                pageVideo.dataset.thumb || ''
            );

            // Sync time after global loads
            const globalMedia = document.getElementById('global-audio');

            globalMedia.addEventListener('loadedmetadata', function syncOnce() {
                globalMedia.currentTime = pageVideo.currentTime;
                pageVideo.pause();
                this.removeEventListener('loadedmetadata', syncOnce);
            });

            document.getElementById('global-audio-player').classList.remove('d-none');
        }

        // === Return to page video when back in view ===
        if (!outOfView && movedToGlobal) {

            movedToGlobal = false;

            const globalMedia = document.getElementById('global-audio');

            pageVideo.src = globalMedia.currentSrc || globalMedia.src;
            pageVideo.currentTime = globalMedia.currentTime;
            pageVideo.play().catch(()=>{});

            globalMedia.pause();
            document.getElementById('global-audio-player').classList.add('d-none');
        }

    });

});

</script>

<style>
    .spotify-player {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 80px;
        background: #121212;
        z-index: 9999;
        display: flex;
        align-items: center;
        padding: 10px;
        color: #fff;
    }

    .spotify-player.floating {
        width: 360px;
        height: 220px;
        bottom: 20px;
        right: 20px;
        left: auto;
        border-radius: 10px;
        overflow: hidden;
        cursor: move;
    }

    .spotify-player.floating .sp-meta,
    .spotify-player.floating .sp-center,
    .spotify-player.floating #player-thumbnail {
        display: none;
    }

    .spotify-player.floating #player-mini-video {
        display: block !important;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .sp-artwork {
        width: 56px;
        height: 56px;
        object-fit: cover;
    }


    /* .spotify-player {
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
    } */

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