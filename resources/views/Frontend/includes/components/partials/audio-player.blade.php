<!-- Persistent Audio Player -->
<div id="global-audio-player" class="audio-player d-none shadow-lg">

    <div class="player-left">
        <img id="player-thumbnail" src="" alt="" class="player-thumb">
        <div class="player-meta">
            <div id="player-title" class="player-title">No audio</div>
            <div id="player-podcast" class="player-podcast"></div>
        </div>
    </div>

    <div class="player-center">
        <button id="player-prev" class="player-btn">
            <i class="fas fa-backward"></i>
        </button>

        <button id="player-play" class="player-btn">
            <i class="fas fa-play"></i>
        </button>

        <button id="player-next" class="player-btn">
            <i class="fas fa-forward"></i>
        </button>

        <input type="range" id="player-progress" value="0">
    </div>

    <div class="player-right">
        <button id="player-mute" class="player-btn">
            <i class="fas fa-volume-up"></i>
        </button>

        <input type="range" id="player-volume" min="0" max="1" step="0.01">
    </div>

    <audio id="global-audio"></audio>
</div>
<script>
(function () {

    /* ===============================
       Element Bindings (Safe)
    =============================== */
    const audio   = document.getElementById('global-audio');
    const player  = document.getElementById('global-audio-player');

    if (!audio || !player) return; // Exit if player not present

    const playBtn = document.getElementById('player-play');
    const prevBtn = document.getElementById('player-prev');
    const nextBtn = document.getElementById('player-next');
    const muteBtn = document.getElementById('player-mute');

    const progress = document.getElementById('player-progress');
    const volume   = document.getElementById('player-volume');

    const titleEl    = document.getElementById('player-title');
    const podcastEl  = document.getElementById('player-podcast');
    const thumbEl    = document.getElementById('player-thumbnail');

    const STORAGE_KEY = 'audio_state';

    let playlist = [];
    let currentIndex = 0;

    /* ===============================
       Helpers
    =============================== */

    function updatePlayIcon() {
        playBtn.innerHTML = audio.paused
            ? '<i class="fas fa-play"></i>'
            : '<i class="fas fa-pause"></i>';
    }

    function updateMuteIcon() {
        muteBtn.innerHTML = audio.muted
            ? '<i class="fas fa-volume-mute"></i>'
            : '<i class="fas fa-volume-up"></i>';
    }

    function updateUI(track) {
        titleEl.innerText = track.title || 'Unknown';
        podcastEl.innerText = track.podcast || '';
        thumbEl.src = track.thumbnail || '';
        player.classList.remove('d-none');
    }

    function saveState() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify({
                playlist,
                currentIndex,
                time: audio.currentTime || 0,
                volume: audio.volume,
                muted: audio.muted,
                playing: !audio.paused
            }));
        } catch (e) {
            // Storage may fail silently (private mode)
        }
    }

    function restoreState() {
        const state = JSON.parse(localStorage.getItem(STORAGE_KEY));
        if (!state || !state.playlist || !state.playlist.length) return;

        playlist = state.playlist;
        currentIndex = state.currentIndex || 0;

        const track = playlist[currentIndex];
        audio.src = track.src;
        updateUI(track);

        audio.currentTime = state.time || 0;
        audio.volume = state.volume ?? 1;
        audio.muted = state.muted ?? false;

        volume.value = audio.volume;
        updateMuteIcon();

        if (state.playing) {
            audio.play().catch(() => {});
        }

        updatePlayIcon();
    }

    /* ===============================
       Core Playback
    =============================== */

    function loadTrack(index) {
        if (!playlist[index]) return;

        const track = playlist[index];

        // Prevent reload if same track
        if (audio.src === track.src) {
            audio.play();
            updatePlayIcon();
            return;
        }

        currentIndex = index;
        audio.src = track.src;
        audio.currentTime = 0;

        updateUI(track);

        audio.play().catch(() => {});
        updatePlayIcon();
        saveState();
    }

    /* ===============================
       Controls
    =============================== */

    playBtn?.addEventListener('click', () => {
        if (audio.paused) {
            audio.play();
        } else {
            audio.pause();
        }
        updatePlayIcon();
        saveState();
    });

    prevBtn?.addEventListener('click', () => {
        if (currentIndex > 0) {
            loadTrack(currentIndex - 1);
        }
    });

    nextBtn?.addEventListener('click', () => {
        if (currentIndex < playlist.length - 1) {
            loadTrack(currentIndex + 1);
        }
    });

    muteBtn?.addEventListener('click', () => {
        audio.muted = !audio.muted;
        updateMuteIcon();
        saveState();
    });

    volume?.addEventListener('input', () => {
        audio.volume = volume.value;
        audio.muted = false;
        updateMuteIcon();
        saveState();
    });

    progress?.addEventListener('input', () => {
        if (!isNaN(audio.duration)) {
            audio.currentTime = (progress.value / 100) * audio.duration;
        }
    });

    audio.addEventListener('timeupdate', () => {
        if (!isNaN(audio.duration) && audio.duration > 0) {
            progress.value = (audio.currentTime / audio.duration) * 100;
        }
        saveState();
    });

    audio.addEventListener('ended', () => {
        if (currentIndex < playlist.length - 1) {
            loadTrack(currentIndex + 1);
        }
    });

    /* ===============================
       Global API (for Blade)
    =============================== */

    window.playGlobalAudio = function (list, index = 0) {
        if (!Array.isArray(list) || !list.length) return;
        playlist = list;
        loadTrack(index);
    };

    window.playSingleAudio = function (src, title = '', podcast = '', thumbnail = '') {
        playlist = [{
            src,
            title,
            podcast,
            thumbnail
        }];
        loadTrack(0);
    };

    /* ===============================
       Init
    =============================== */

    document.addEventListener('DOMContentLoaded', restoreState);

})();
</script>

<style>
.audio-player {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #000;
    color: #fff;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px;
    box-shadow: 0 -2px 10px rgba(0,0,0,.5);
}

.player-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.player-thumb {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
}

.player-title {
    font-weight: 600;
    font-size: 14px;
}

.player-podcast {
    font-size: 12px;
    color: #aaa;
}

.player-center {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 40%;
}

.player-btn {
    background: #e50914;
    border: none;
    color: #fff;
    padding: 8px 12px;
    border-radius: 50%;
    cursor: pointer;
}

#player-progress {
    width: 100%;
    height: 3px;
}

#player-volume {
    width: 80px;
    height: 3px;
}

.player-right {
    display: flex;
    align-items: center;
    gap: 8px;
}
input[type="range" i]
{
    -webkit-appearance: none;
    background: #555;
    border-radius: 5px;
    cursor: pointer;
}
input[type="range" i]::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 12px;
    height: 12px;
    background: #e50914;
    border-radius: 50%;
    cursor: pointer;
}

</style>
