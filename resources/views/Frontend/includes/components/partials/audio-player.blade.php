<div id="global-audio-player" class="spotify-player d-none">
    <div class="sp-shell">
        <div class="sp-left">
            <img id="player-thumbnail" class="sp-artwork" src="{{ asset('assets/img/default-thumbnail.jpg') }}" alt="Audio artwork">
            <div class="sp-meta">
                <div id="player-title" class="sp-title">No audio selected</div>
                <div id="player-podcast" class="sp-artist"></div>
            </div>
        </div>

        <div class="sp-center">
            <div class="sp-controls">
                <button id="player-prev" class="sp-btn" type="button" title="Previous">
                    <i class="fas fa-step-backward"></i>
                </button>
                <button id="player-rewind" class="sp-btn" type="button" title="Back 10 seconds">
                    <i class="fas fa-undo-alt"></i>
                </button>
                <button id="player-play" class="sp-btn sp-play" type="button" title="Play or pause">
                    <i class="fas fa-play"></i>
                </button>
                <button id="player-forward" class="sp-btn" type="button" title="Forward 30 seconds">
                    <i class="fas fa-redo-alt"></i>
                </button>
                <button id="player-next" class="sp-btn" type="button" title="Next">
                    <i class="fas fa-step-forward"></i>
                </button>
            </div>

            <div class="sp-progress-wrap">
                <span id="sp-current">0:00</span>
                <input type="range" id="player-progress" min="0" max="100" value="0">
                <span id="sp-duration">0:00</span>
            </div>
        </div>

        <div class="sp-right">
            <label class="sp-speed-wrap" for="player-speed">
                <span class="sp-speed-label">Speed</span>
                <select id="player-speed" class="sp-speed">
                    <option value="0.75">0.75x</option>
                    <option value="1" selected>1x</option>
                    <option value="1.25">1.25x</option>
                    <option value="1.5">1.5x</option>
                    <option value="2">2x</option>
                </select>
            </label>

            <button id="player-mute" class="sp-btn" type="button" title="Mute">
                <i class="fas fa-volume-up"></i>
            </button>
            <input type="range" id="player-volume" min="0" max="1" step="0.01" value="1">
        </div>
    </div>

    <audio id="global-audio"></audio>
</div>

<script>
    (function() {
        const audio = document.getElementById('global-audio');
        const player = document.getElementById('global-audio-player');

        if (!audio || !player) return;

        const playBtn = document.getElementById('player-play');
        const prevBtn = document.getElementById('player-prev');
        const rewindBtn = document.getElementById('player-rewind');
        const nextBtn = document.getElementById('player-next');
        const forwardBtn = document.getElementById('player-forward');
        const muteBtn = document.getElementById('player-mute');
        const progress = document.getElementById('player-progress');
        const volume = document.getElementById('player-volume');
        const speed = document.getElementById('player-speed');
        const titleEl = document.getElementById('player-title');
        const podcastEl = document.getElementById('player-podcast');
        const thumbEl = document.getElementById('player-thumbnail');
        const currentTimeEl = document.getElementById('sp-current');
        const durationEl = document.getElementById('sp-duration');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        const STORAGE_KEY = 'audio_state_v3';
        const FALLBACK_THUMBNAIL = '{{ asset('assets/img/default-thumbnail.jpg') }}';

        let playlist = [];
        let currentIndex = 0;
        let lastPersistedSecond = null;

        audio.preload = 'auto';

        function formatTime(seconds) {
            if (!seconds || Number.isNaN(seconds)) return '0:00';
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60).toString().padStart(2, '0');
            return `${mins}:${secs}`;
        }

        function getCurrentTrack() {
            return playlist[currentIndex] || null;
        }

        function updatePlayIcon() {
            playBtn.innerHTML = audio.paused
                ? '<i class="fas fa-play"></i>'
                : '<i class="fas fa-pause"></i>';
        }

        function updateMuteIcon() {
            muteBtn.innerHTML = audio.muted || Number(volume.value) === 0
                ? '<i class="fas fa-volume-mute"></i>'
                : '<i class="fas fa-volume-up"></i>';
        }

        function updateSpeedControl() {
            speed.value = String(audio.playbackRate || 1);
        }

        function updateProgressUI() {
            currentTimeEl.innerText = formatTime(audio.currentTime);
            durationEl.innerText = formatTime(audio.duration);

            if (!Number.isNaN(audio.duration) && audio.duration > 0) {
                progress.value = (audio.currentTime / audio.duration) * 100;
            } else {
                progress.value = 0;
            }
        }

        function updateUI(track) {
            titleEl.innerText = track?.title || 'Unknown audio';
            podcastEl.innerText = track?.podcast || '';
            thumbEl.src = track?.thumbnail || FALLBACK_THUMBNAIL;
            thumbEl.alt = track?.title || 'Audio artwork';
            player.classList.remove('d-none');
            player.classList.add('is-active');
            updatePlayIcon();
            updateMuteIcon();
            updateSpeedControl();
            updateProgressUI();
        }

        function syncMediaSession(track) {
            if (!('mediaSession' in navigator) || !track) return;

            navigator.mediaSession.metadata = new MediaMetadata({
                title: track.title || 'Unknown audio',
                artist: track.podcast || 'NowStream',
                album: track.podcast || 'NowStream',
                artwork: [{
                    src: track.thumbnail || FALLBACK_THUMBNAIL,
                    sizes: '512x512',
                    type: 'image/png'
                }]
            });
        }

        function saveState() {
            try {
                const state = {
                    playlist,
                    currentIndex,
                    tracks: playlist.map(track => ({
                        uuid: track.uuid || track.src,
                        time: track.currentTime || 0
                    })),
                    volume: audio.volume,
                    muted: audio.muted,
                    playbackRate: audio.playbackRate,
                    playing: !audio.paused,
                    updatedAt: Date.now()
                };

                localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
            } catch (error) {
                console.error('Unable to save audio state', error);
            }
        }

        function restoreState() {
            try {
                const rawState = localStorage.getItem(STORAGE_KEY);
                if (!rawState) return;

                const state = JSON.parse(rawState);
                if (!state?.playlist?.length) return;

                playlist = state.playlist;
                currentIndex = state.currentIndex || 0;

                const track = getCurrentTrack();
                if (!track?.src) return;

                audio.src = track.src;
                audio.volume = state.volume ?? 1;
                audio.muted = state.muted ?? false;
                audio.playbackRate = state.playbackRate ?? 1;
                volume.value = audio.volume;

                updateUI(track);
                syncMediaSession(track);

                const trackProgress = state.tracks?.find(item => item.uuid === (track.uuid || track.src));
                const elapsedSeconds = state.playing && state.updatedAt
                    ? Math.max(0, Math.floor((Date.now() - state.updatedAt) / 1000))
                    : 0;
                const resumeTime = Math.max(
                    trackProgress?.time ?? 0,
                    track.resume_at ?? 0
                ) + elapsedSeconds;

                const seekToResumePosition = () => {
                    audio.currentTime = Math.min(resumeTime, audio.duration || resumeTime);
                    updateProgressUI();

                    if (state.playing) {
                        audio.play().catch(() => {});
                    }

                    audio.removeEventListener('loadedmetadata', seekToResumePosition);
                };

                if (audio.readyState >= 1) {
                    seekToResumePosition();
                } else {
                    audio.addEventListener('loadedmetadata', seekToResumePosition);
                }
            } catch (error) {
                console.error('Unable to restore audio state', error);
            }
        }

        function incrementViews(uuid, podcastId = null) {
            if (!uuid || !csrfToken) return;

            fetch(`/content/${uuid}/increment-views`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    const episodeViewsEl = document.querySelector(`#views-${uuid}`);
                    if (episodeViewsEl && data.episode_views !== undefined) {
                        episodeViewsEl.innerText = data.episode_views;
                    }

                    if (podcastId) {
                        const podcastViewsEl = document.querySelector(`#podcast-views-${podcastId}`);
                        if (podcastViewsEl && data.podcast_views !== null) {
                            podcastViewsEl.innerText = data.podcast_views;
                        }
                    }
                })
                .catch(error => console.error('Error incrementing views', error));
        }

        function saveWatchProgress(uuid, currentTime, useKeepalive = false) {
            if (!uuid || !csrfToken) return;

            fetch(`/watch-history/${uuid}`, {
                method: 'POST',
                keepalive: useKeepalive,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    watch_duration: Math.floor(currentTime)
                })
            })
                .catch(error => console.error('Error saving watch history', error));
        }

        function loadTrack(index, options = {}) {
            if (!playlist[index]?.src) return;

            const {
                autoplay = true,
                resumeTime = null
            } = options;

            currentIndex = index;
            lastPersistedSecond = null;

            const track = getCurrentTrack();
            audio.src = track.src;
            updateUI(track);
            syncMediaSession(track);

            const applyResume = () => {
                const initialTime = resumeTime ?? track.currentTime ?? track.resume_at ?? 0;
                audio.currentTime = Math.min(initialTime, audio.duration || initialTime);
                updateProgressUI();

                if (autoplay) {
                    audio.play().catch(() => {});
                }

                audio.removeEventListener('loadedmetadata', applyResume);
            };

            if (audio.readyState >= 1) {
                applyResume();
            } else {
                audio.addEventListener('loadedmetadata', applyResume);
            }

            saveState();

            if (track.uuid) {
                incrementViews(track.uuid, track.podcast_id);
            }
        }

        function seekBy(seconds) {
            const target = audio.currentTime + seconds;
            if (Number.isFinite(audio.duration)) {
                audio.currentTime = Math.max(0, Math.min(audio.duration, target));
            } else {
                audio.currentTime = Math.max(0, target);
            }

            updateProgressUI();
            saveState();
        }

        playBtn?.addEventListener('click', () => {
            if (!getCurrentTrack()) return;

            if (audio.paused) audio.play().catch(() => {});
            else audio.pause();
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

        rewindBtn?.addEventListener('click', () => seekBy(-10));
        forwardBtn?.addEventListener('click', () => seekBy(30));

        muteBtn?.addEventListener('click', () => {
            audio.muted = !audio.muted;
            updateMuteIcon();
            saveState();
        });

        volume?.addEventListener('input', () => {
            audio.volume = Number(volume.value);
            audio.muted = audio.volume === 0;
            updateMuteIcon();
            saveState();
        });

        progress?.addEventListener('input', () => {
            if (!Number.isNaN(audio.duration) && audio.duration > 0) {
                audio.currentTime = (Number(progress.value) / 100) * audio.duration;
                updateProgressUI();
            }
        });

        progress?.addEventListener('change', saveState);

        speed?.addEventListener('change', () => {
            audio.playbackRate = Number(speed.value || 1);
            saveState();
        });

        audio.addEventListener('loadedmetadata', updateProgressUI);

        audio.addEventListener('timeupdate', () => {
            const track = getCurrentTrack();
            if (track) {
                track.currentTime = audio.currentTime;
            }

            updateProgressUI();
            saveState();

            const currentSecond = Math.floor(audio.currentTime);
            if (currentSecond > 0 && currentSecond % 10 === 0 && currentSecond !== lastPersistedSecond) {
                lastPersistedSecond = currentSecond;
                saveWatchProgress(track?.uuid, audio.currentTime);
            }
        });

        audio.addEventListener('play', () => {
            updatePlayIcon();
            saveState();
        });

        audio.addEventListener('pause', () => {
            updatePlayIcon();
            saveState();
            saveWatchProgress(getCurrentTrack()?.uuid, audio.currentTime);
        });

        audio.addEventListener('ratechange', () => {
            updateSpeedControl();
            saveState();
        });

        audio.addEventListener('ended', () => {
            saveWatchProgress(getCurrentTrack()?.uuid, audio.currentTime, true);

            if (currentIndex < playlist.length - 1) {
                loadTrack(currentIndex + 1);
            }
        });

        window.addEventListener('beforeunload', () => {
            saveState();
            saveWatchProgress(getCurrentTrack()?.uuid, audio.currentTime, true);
        });

        window.addEventListener('pagehide', () => {
            saveState();
            saveWatchProgress(getCurrentTrack()?.uuid, audio.currentTime, true);
        });

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                saveState();
                saveWatchProgress(getCurrentTrack()?.uuid, audio.currentTime, true);
            }
        });

        document.addEventListener('keydown', event => {
            const activeTag = document.activeElement?.tagName;
            if (['INPUT', 'TEXTAREA', 'SELECT'].includes(activeTag)) return;
            if (!getCurrentTrack()) return;

            if (event.code === 'Space') {
                event.preventDefault();
                if (audio.paused) audio.play().catch(() => {});
                else audio.pause();
            }

            if (event.code === 'ArrowLeft') {
                event.preventDefault();
                seekBy(-10);
            }

            if (event.code === 'ArrowRight') {
                event.preventDefault();
                seekBy(30);
            }
        });

        window.playGlobalAudio = function(list, index = 0) {
            if (!Array.isArray(list) || !list.length) return;

            playlist = list.map(item => ({
                ...item,
                type: item.type || 'audio'
            }));

            loadTrack(index);
        };

        window.playSingleAudio = function(src, title = '', podcast = '', thumbnail = '', uuid = '', podcastId = null) {
            if (!src) return;

            playlist = [{
                src,
                title,
                podcast,
                thumbnail,
                uuid,
                podcast_id: podcastId,
                type: 'audio'
            }];

            loadTrack(0);
        };

        if ('mediaSession' in navigator) {
            navigator.mediaSession.setActionHandler('play', () => audio.play());
            navigator.mediaSession.setActionHandler('pause', () => audio.pause());
            navigator.mediaSession.setActionHandler('previoustrack', () => {
                if (currentIndex > 0) loadTrack(currentIndex - 1);
            });
            navigator.mediaSession.setActionHandler('nexttrack', () => {
                if (currentIndex < playlist.length - 1) loadTrack(currentIndex + 1);
            });
            navigator.mediaSession.setActionHandler('seekbackward', () => seekBy(-10));
            navigator.mediaSession.setActionHandler('seekforward', () => seekBy(30));
        }

        restoreState();
    })();
</script>

<style>
    .spotify-player {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 9999;
        background: #121212;
        border-top: 1px solid #282828;
        color: #fff;
        font-family: Arial, sans-serif;
        box-shadow: 0 -8px 24px rgba(0, 0, 0, 0.35);
    }

    .sp-shell {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 12px 16px;
    }

    .sp-left,
    .sp-center,
    .sp-right {
        min-width: 0;
    }

    .sp-left {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 28%;
        min-width: 240px;
    }

    .sp-meta {
        min-width: 0;
    }

    .sp-artwork {
        width: 56px;
        height: 56px;
        border-radius: 6px;
        object-fit: cover;
        flex-shrink: 0;
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
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sp-center {
        width: 44%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .sp-controls {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 8px;
    }

    .sp-btn {
        background: transparent;
        border: none;
        color: #b3b3b3;
        font-size: 16px;
        cursor: pointer;
        transition: color 0.2s ease, transform 0.2s ease;
        padding: 0;
    }

    .sp-btn:hover {
        color: #fff;
    }

    .sp-play {
        width: 38px;
        height: 38px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        color: #000;
    }

    .sp-play:hover {
        transform: scale(1.05);
    }

    .sp-progress-wrap {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #player-progress,
    #player-volume {
        -webkit-appearance: none;
        appearance: none;
        background: #535353;
        border-radius: 999px;
        cursor: pointer;
    }

    #player-progress {
        flex: 1;
        height: 4px;
    }

    #player-volume {
        width: 90px;
        height: 4px;
    }

    #player-progress::-webkit-slider-thumb,
    #player-volume::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #e4d804;
    }

    #sp-current,
    #sp-duration {
        width: 42px;
        text-align: center;
        font-size: 11px;
        color: #b3b3b3;
    }

    .sp-right {
        width: 28%;
        min-width: 220px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    .sp-speed-wrap {
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 0;
    }

    .sp-speed-label {
        font-size: 11px;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #b3b3b3;
    }

    .sp-speed {
        background: #1f1f1f;
        color: #fff;
        border: 1px solid #383838;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 12px;
        outline: none;
    }

    @media (max-width: 991px) {
        .sp-shell {
            gap: 12px;
        }

        .sp-left {
            width: 34%;
            min-width: 200px;
        }

        .sp-center {
            width: 46%;
        }

        .sp-right {
            width: 20%;
            min-width: 160px;
        }

        .sp-speed-label {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .sp-shell {
            display: grid;
            grid-template-columns: 1fr auto;
            grid-template-areas:
                'meta side'
                'controls controls'
                'progress progress';
            align-items: center;
        }

        .sp-left {
            grid-area: meta;
            width: 100%;
            min-width: 0;
        }

        .sp-center {
            grid-area: controls;
            width: 100%;
        }

        .sp-right {
            grid-area: side;
            width: auto;
            min-width: 0;
        }

        .sp-progress-wrap {
            grid-area: progress;
        }

        .sp-controls {
            margin: 0;
        }

        #player-volume {
            width: 72px;
        }
    }

    @media (max-width: 480px) {
        .sp-shell {
            padding: 10px 12px;
        }

        .sp-right {
            display: none;
        }

        .sp-controls {
            gap: 12px;
        }

        .sp-artwork {
            width: 48px;
            height: 48px;
        }

        .sp-title {
            max-width: 180px;
        }
    }
</style>
