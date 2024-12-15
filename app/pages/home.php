<?php
require $_SERVER['DOCUMENT_ROOT'] . "/web_nghe_nhac/app/pages/includes/header.php";
?>

<!-- Popup thông báo -->
<div id="notificationPopup" class="notification-popup" style="display: none;">
    <div class="popup-content">
        <h3>Thông báo</h3>
        <span id="closePopup" class="close-popup">&times;</span>
        <!-- Thông báo động sẽ được thêm vào đây bởi JavaScript -->
    </div>
</div>

<main>
    <!-- Main space -->
    <div class="mainSpace">
        <!-- leftBar -->
        <?php
            require $_SERVER['DOCUMENT_ROOT'] . "/web_nghe_nhac/app/pages/includes/left_side.php";
        ?>
        
        <div class="centerSpace scrollable">

            <div class="wrapperSlider">
                <!-- Slider -->
                <div class="sliderContainer">
                    <div class="trending">
                        <iconify-icon icon="solar:fire-bold"></iconify-icon>
                        <div class="trendingTopic">
                            <h3>Top bài hát&nbsp;</h3>
                            <h3 style="color: #296265;"> thịnh hành</h3>
                        </div>
                    </div>


                    <div id="slider">
                        <div class="info">
                            <h3 id="nameInfo" class="name">Ngáo ngơ</h3>
                            <p id="authodInfo" class="author p2">Erik, Jsol, Orange, HIEUTHUHAI, Anh Tú Atus</p>
                        </div>
                        <ul class="dots">
                            <li class="active"></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                        <div class="shadow"></div>
                        <div class="imgList">
                            <div class="itemImg">
                                <img id="imageSlider1" src="/web_nghe_nhac/public/assets/img/slider/ngao_ngo.jpg"
                                    alt="">
                            </div>
                            <div class="itemImg">
                                <img id="imageSlider1" src="/web_nghe_nhac/public/assets/img/slider/dlttad.jpg" alt="">
                            </div>
                            <div class="itemImg">
                                <img id="imageSlider1" src="/web_nghe_nhac/public/assets/img/slider/mong_yu.jpg" alt="">
                            </div>
                            <div class="itemImg">
                                <img id="imageSlider1" src="/web_nghe_nhac/public/assets/img/slider/seenderella.jpg"
                                    alt="">
                            </div>
                            <div class="itemImg">
                                <img id="imageSlider1" src="/web_nghe_nhac/public/assets/img/slider/mong_yu.jpg" alt="">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popular Artist -->
                <div class="popularArtistContainer">
                    <div class="popularArtistTopic">
                        <h4>Nghệ sĩ phổ biến</h4>
                    </div>

                    <div id="artistScroll" class="scrollable">
                        <a href="/web_nghe_nhac/public/assets/php/artist-info.php?manghesy=3"
                            style="text-decoration: none">
                            <div class="artistItem">
                                <img src="/web_nghe_nhac/public/assets/img/artists/hieuthuhai.jpg" alt=""
                                    class="avatarArtist">
                                <div class="info">
                                    <p class="ui_semibold">HIEUTHUHAI</p>
                                    <p class="ui_regular op_75">Nghệ sĩ</p>
                                </div>
                            </div>
                        </a>

                        <a href="/web_nghe_nhac/public/assets/php/artist-info.php?manghesy=7"
                            style="text-decoration: none">
                            <div class="artistItem">
                                <img src="/web_nghe_nhac/public/assets/img/artists/amee.webp" alt=""
                                    class="avatarArtist">
                                <div class="info">
                                    <p class="ui_semibold">AMEE</p>
                                    <p class="ui_regular op_75">Nghệ sĩ</p>
                                </div>
                            </div>
                        </a>

                        <a href="/web_nghe_nhac/public/assets/php/artist-info.php?manghesy=5"
                            style="text-decoration: none">
                            <div class="artistItem">
                                <img src="/web_nghe_nhac/public/assets/img/artists/son_tung_mtp.jpg" alt=""
                                    class="avatarArtist">
                                <div class="info">
                                    <p class="ui_semibold">Sơn Tùng MTP</p>
                                    <p class="ui_regular op_75">Nghệ sĩ</p>
                                </div>
                            </div>
                        </a>

                        <a href="/web_nghe_nhac/public/assets/php/artist-info.php?manghesy=10"
                            style="text-decoration: none">
                            <div class="artistItem">
                                <img src="/web_nghe_nhac/public/assets/img/data-artists-image/wrenevan.jpg" alt=""
                                    class="avatarArtist">
                                <div class="info">
                                    <p class="ui_semibold">Wren Evan</p>
                                    <p class="ui_regular op_75">Nghệ sĩ</p>
                                </div>
                            </div>
                        </a>

                        <a href="/web_nghe_nhac/public/assets/php/artist-info.php?manghesy=2"
                            style="text-decoration: none">
                            <div class="artistItem">
                                <img src="/web_nghe_nhac/public/assets/img/artists/vu.jpg" alt="" class="avatarArtist">
                                <div class="info">
                                    <p class="ui_semibold">Vũ</p>
                                    <p class="ui_regular op_75">Nghệ sĩ</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>

            <!-- search -->
            <div class="searches-container" id="search-container" style="display: none;">
                <div class="header">
                    <h2>Tìm kiếm gần đây</h2>
                    <span class="clear-history" onclick="clearSearchHistory()">Xóa lịch sử tìm kiếm</span>
                </div>
                <div class="search-items" id="search-items">
                    <!-- Các mục tìm kiếm sẽ được thêm vào đây bởi JavaScript -->
                </div>

            </div>

            <!-- lyric -->
            <div class="wrapper-lyric" style="display: none;" id="lyric">
                <p>Lời bài hát</p>
            </div>

        </div>
        <!-- rightBar -->
        <?php
            require $_SERVER['DOCUMENT_ROOT'] . "/web_nghe_nhac/app/pages/includes/right_side.php";
        ?>

        <!-- Listening Space -->
        <div class="listeningSpace">
            <div class="info">
                <img src="" alt="" class="picOfSong songImage">

                <div class="infoText">
                    <p class="name ui_semibold songName">Tên bài hát</p>
                    <p class="author ui_regular op_75 songAuthor">Tên nghệ sĩ</p>
                </div>

                <div class="addToPlaylist">
                    <iconify-icon icon="ic:round-plus"></iconify-icon>
                </div>
            </div>

            <!-- Music player -->
            <div class="musicPlayer">
                <audio id="audioPlayer" src=""></audio>
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
                    <span id="duration" class="progressTime remaining">0:00</span>
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
</main>

<!-- File javaScript -->
<script src="/web_nghe_nhac/public/assets/script/listeningSpace.js"></script>
<script src="/web_nghe_nhac/public/assets/script/main_cpn.js"></script>
<script src="/web_nghe_nhac/public/assets/script/leftBar.js"></script>
<script src="/web_nghe_nhac/public/assets/script/rightBar.js"></script>
<script src="/web_nghe_nhac/public/assets/script/header.js"></script>
<script src="/web_nghe_nhac/public/assets/script/song.js"></script>
<!-- <script src="/web_nghe_nhac/public/assets/script/playlist.js"></script> -->

</body>

</html>