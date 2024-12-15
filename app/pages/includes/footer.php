<div class="listeningSpace">
    <div class="info">
        <img src="/web_nghe_nhac/public/assets/img/data-songs-image/dgtrr.jpg" alt="songImage" class="picOfSong songImage">

        <div class="infoText">
            <p class="name ui_semibold songName">Đi giữa trời rực rỡ</p>
            <p class="author ui_regular op_75 songAuthor">Ngô Lan Hương</p>
        </div>

        <div class="addToPlaylist" id="addToPlaylist">
            <iconify-icon icon="ic:round-plus"></iconify-icon>
        </div>
    </div>

    <!-- Music player -->
    <div class="musicPlayer">
        <audio id="audioPlayer" src="/web_nghe_nhac/public/song/dgtrr.mp3"></audio>
        <div class="controlbar">
            <button id="lyricsBtn">
                <iconify-icon id="lyricsIcon" class="" icon="maki:karaoke"></iconify-icon>
            </button>

            <button id="backBtn">
                <iconify-icon id="backIcon" class="" icon="solar:skip-previous-bold"></iconify-icon>
            </button>

            <button id="mainControlBtn">
                <iconify-icon id="mainControlIcon" class="" icon="solar:play-bold"></iconify-icon>
            </button>

            <button id="nextBtn">
                <iconify-icon id="nextIcon" class="" icon="solar:skip-next-bold"></iconify-icon>
            </button>

            <button id="returnBtn">
                <iconify-icon id="returnIcon" class="" icon="icon-park-outline:return"></iconify-icon>
            </button>
        </div>

        <div class="timeDisplay">
            <span id="currentTime" class="progressTime current">0:00</span>
            <div class="progressContainer" id="progressContainer">
                <div class="progress" id="progress"></div>
            </div>
            <span id="duration" class="progressTime remaining">3:12</span>
        </div>
    </div>

    <!-- General space -->
    <div class="general">
        <button id="info">
            <iconify-icon id="infoIcon" icon="ic:round-info"></iconify-icon>
        </button>

        <div class="volumnSpace">
            <button id="output">
                <iconify-icon id="outputIcon" class="icon-active" icon="solar:volume-loud-bold"></iconify-icon>
            </button>
            <input type="range" id="volumnContainer" class="volumnContainer" min="0" max="100" value="100">
        </div>
    </div>
</div>
        <script src="/web_nghe_nhac/public/assets/script/song.js"></script>
        <script src="/web_nghe_nhac/public/assets/script/listeningSpace.js"></script>

