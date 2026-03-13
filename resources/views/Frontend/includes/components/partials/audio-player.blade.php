 <div id="global-audio-player" class="spotify-player d-block py-2">

     <div class="w-100 d-flex align-items-center justify-content-between">
         <div class="sp-left">
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

             <div class="sp-progress-wrap d-none d-md-flex">
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
     </div>
     <div class="sp-progress-wrap d-md-none w-100 mt-1">
         <span id="sp-current">0:00</span>
         <input type="range" id="player-progress" value="0">
         <span id="sp-duration">0:00</span>
     </div>

     <audio id="global-audio"></audio>
 </div>
 <script>
     (function() {
         /* ===============================
            Element Bindings
         =============================== */
         const audio = document.getElementById('global-audio');
         const player = document.getElementById('global-audio-player');

         if (!audio || !player) return; // Exit if player not present

         const playBtn = document.getElementById('player-play');
         const prevBtn = document.getElementById('player-prev');
         const nextBtn = document.getElementById('player-next');
         const muteBtn = document.getElementById('player-mute');

         const progress = document.getElementById('player-progress');
         const volume = document.getElementById('player-volume');

         const titleEl = document.getElementById('player-title');
         const podcastEl = document.getElementById('player-podcast');
         const thumbEl = document.getElementById('player-thumbnail');
         const currentTimeEl = document.getElementById('sp-current');
         const durationEl = document.getElementById('sp-duration');

         const STORAGE_KEY = 'audio_state';
         let playlist = [];
         let currentIndex = 0;
         let saveProgressTimer = null;

         /* ===============================
            Helpers
         =============================== */
         function formatTime(sec) {
             if (!sec) return '0:00';
             const m = Math.floor(sec / 60);
             const s = Math.floor(sec % 60).toString().padStart(2, '0');
             return `${m}:${s}`;
         }

         function updatePlayIcon() {
             playBtn.innerHTML = audio.paused ?
                 '<i class="fas fa-play"></i>' :
                 '<i class="fas fa-pause"></i>';
         }

         function updateMuteIcon() {
             muteBtn.innerHTML = audio.muted ?
                 '<i class="fas fa-volume-mute"></i>' :
                 '<i class="fas fa-volume-up"></i>';
         }

         function updateUI(track) {
             titleEl.innerText = track.title || 'Unknown';
             podcastEl.innerText = track.podcast || '';
             thumbEl.src = track.thumbnail || '';
             currentTimeEl.innerText = '0:00';
             durationEl.innerText = '0:00';
             player.classList.remove('d-none');

             audio.addEventListener('loadedmetadata', () => {
                 durationEl.innerText = formatTime(audio.duration);
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
                     playing: !audio.paused
                 };
                 localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
             } catch (e) {}
         }

         function restoreState() {
             const state = JSON.parse(localStorage.getItem(STORAGE_KEY));
             if (!state?.playlist?.length) return;

             playlist = state.playlist;
             currentIndex = state.currentIndex || 0;
             const track = playlist[currentIndex];
             audio.src = track.src;
             updateUI(track);

             audio.volume = state.volume ?? 1;
             audio.muted = state.muted ?? false;
             volume.value = audio.volume;
             updateMuteIcon();

             const trackProgress = state.tracks.find(t => t.uuid === (track.uuid || track.src));
             const seekTime = trackProgress?.time ?? 0;

             const seekToTime = () => {
                 audio.currentTime = seekTime;
                 if (state.playing) audio.play().catch(() => {});
                 updatePlayIcon();
                 audio.removeEventListener('loadedmetadata', seekToTime);
             };

             if (audio.readyState >= 1) seekToTime();
             else audio.addEventListener('loadedmetadata', seekToTime);
         }

         /* ===============================
            Core Playback
         =============================== */
         function loadTrack(index) {
             if (!playlist[index]) return;
             const track = playlist[index];
             currentIndex = index;
             audio.src = track.src;
             audio.currentTime = 0;
             updateUI(track);
             audio.play().catch(() => {});
             updatePlayIcon();
             saveState();

             // Increment views immediately
             if (track.uuid) incrementViews(track.uuid, track.podcast_id);
         }

         /* ===============================
            Views + Watch History
         =============================== */
         function incrementViews(uuid, podcastId = null) {
             fetch(`/content/${uuid}/increment-views`, {
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                         'Accept': 'application/json',
                         'Content-Type': 'application/json'
                     }
                 })
                 .then(res => res.json())
                 .then(data => {
                     console.log('Views incremented', data);
                     const episodeEl = document.querySelector(`#views-${uuid}`);
                     if (episodeEl) episodeEl.innerText = data.episode_views;
                     if (podcastId) {
                         const podcastEl = document.querySelector(`#podcast-views-${podcastId}`);
                         if (podcastEl && data.podcast_views !== null) podcastEl.innerText = data.podcast_views;
                     }
                 })
                 .catch(err => console.error('Error incrementing views', err));
         }

         function saveWatchProgress(uuid, currentTime) {
             if (!uuid) return;
             fetch(`/watch-history/${uuid}`, {
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                         'Accept': 'application/json',
                         'Content-Type': 'application/json'
                     },
                     body: JSON.stringify({
                         watch_duration: Math.floor(currentTime)
                     })
                 })
                 .then(res => res.json())
                 .then(data => console.log('Watch progress saved', data))
                 .catch(err => console.error('Error saving watch history', err));
         }

         /* ===============================
            Controls
         =============================== */
         playBtn?.addEventListener('click', () => {
             if (audio.paused) audio.play();
             else audio.pause();
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
             currentTimeEl.innerText = formatTime(audio.currentTime);
             if (!isNaN(audio.duration) && audio.duration > 0) {
                 progress.value = (audio.currentTime / audio.duration) * 100;
             }

             if (playlist[currentIndex]) playlist[currentIndex].currentTime = audio.currentTime;

             saveState();

             // Save watch progress every 15 seconds
             if (saveProgressTimer) clearTimeout(saveProgressTimer);
             saveProgressTimer = setTimeout(() => {
                 saveWatchProgress(playlist[currentIndex]?.uuid, audio.currentTime);
             }, 15000);
         });

         audio.addEventListener('ended', () => {
             if (currentIndex < playlist.length - 1) loadTrack(currentIndex + 1);
             else saveWatchProgress(playlist[currentIndex]?.uuid, audio.currentTime);
         });

         /* ===============================
            Global API
         =============================== */
         window.playGlobalAudio = function(list, index = 0) {
             if (!Array.isArray(list) || !list.length) return;
             playlist = list;
             loadTrack(index);
         };

         window.playSingleAudio = function(src, title = '', podcast = '', thumbnail = '', uuid = '', podcastId = null) {
             playlist = [{
                 src,
                 title,
                 podcast,
                 thumbnail,
                 uuid,
                 podcast_id: podcastId
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

     @media (max-width: 420px) {
         .sp-left {
             width: 53%;
             min-width: 180px;
         }

         .sp-center {
             width: 47%;
         }
     }
 </style>