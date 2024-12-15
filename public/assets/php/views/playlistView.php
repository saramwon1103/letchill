<?php
include_once '/xampp/htdocs/web_nghe_nhac/public/assets/php/control/playlistControl.php';
include_once '/xampp/htdocs/web_nghe_nhac/public/assets/php/config/config.php';
include_once '/xampp/htdocs/web_nghe_nhac/public/assets/models/playlistModel.php';

if (!isset($playlists)) {
    $database = new Database();
    $db = $database->getConnection();
    $playlistModel = new PlaylistModel($db);
    $playlists = $playlistModel->getAllPlaylists();
}

// Khởi tạo kết nối Database và Controller
if (!isset($controller)) {
    include_once '/xampp/htdocs/web_nghe_nhac/public/assets/php/control/playlistControl.php';
    $database = new Database();
    $db = $database->getConnection();
    $controller = new PlaylistController($db);
}
?>
<div class="header">
    <?php
    require $_SERVER['DOCUMENT_ROOT'] . "/web_nghe_nhac/app/pages/includes/header.php";
    ?>
</div>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap">
    <title>Playlist</title>
    <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
</head>



<body>
    <div id="overlay"></div>
    <!--lớp phủ làm tối màn hình cho pop up-->
    <!-- kết thúc div thanh tìm kiếm -->
    <div class="main">
        <!-- div phần thân -->
        <div id="library">
            <!--div thư viện-->
            <div style="width: auto; display: flex;">
                <i id="library-icon" class="fa-solid fa-books"></i>
                <span id="library-text">Thư viện</span>
                <button type="submit" id="plus-button" style="cursor: pointer;"><i
                        class="fa-solid fa-plus"></i></button>
                <!--plus-->
            </div>
            <div id="type-playlist" style="width: auto;">
                <button id="playlist-button">Playlist</button>
                <button id="artist-button">Nghệ sĩ</button>
            </div>
            <div id="list-scroll">
                <?php
                if (!empty($playlists)) {
                    foreach ($playlists as $playlist) {
                        $maDSP = $playlist['MaDSP'];
                        $tenDSP = $playlist['TenDSP'];
                        $loaiDSP = $playlist['LoaiDSP'];
                        $imgName = $playlist['AnhDSP'];
                        $imgPath = "/web_nghe_nhac/public/assets/img/playlist/$imgName";

                        // Dữ liệu truyền vào JavaScript: mã, tên, loại, đường dẫn ảnh
                        echo "<div id='playlist$maDSP' onclick=\"updateArtistView('$maDSP', '$tenDSP', '$loaiDSP', '$imgPath')\">
                                <span id='chillingwithheart-icon'>
                                    <img src='$imgPath' alt='$tenDSP'>
                                </span>
                                <span id='chillingwithheart-text'>
                                    $tenDSP
                                    <br><span id='playlist-text'>" . ($loaiDSP === 'Playlist' ? 'Playlist' : 'Nghệ sĩ') . "</span>
                                </span>
                            </div>";
                    }
                } else {
                    echo "Không có danh sách phát nào!";
                }
                ?>
            </div>
        </div>

        <div id="artist">
            <!-- lyric -->
            <div class="wrapper-lyric" style="display: none;" id="lyric">
                <p>Lời bài hát</p>
            </div>
            <!--div nghệ sĩ-->
            <div class="wrapperSlider">
                <div id="main-artist">
                    <span id="avatar-artist">
                        <img src="/web_nghe_nhac/public/assets/img/playlist/<?php echo $AnhDSP; ?>" alt="Playlist">
                    </span>
                    <div class="info-artist">
                        <div id="artist-text">
                            <?php 
                            // Hiển thị "Nghệ sĩ" hoặc "Danh sách phát" dựa trên LoaiDSP
                            echo $loaiDSP === 'Nghệ sĩ' ? 'Nghệ sĩ' : 'Playlist'; 
                            ?>
                            <br>
                        </div>
                        <div id="artist-name">
                            <b><?php echo isset($tenPlaylist) ? $tenPlaylist : 'Loading tên playlist...'; ?></b><br>
                        </div>
                        <div id="artist-follower">
                            <?php 
                            // Hiển thị số người theo dõi hoặc số lượng bài hát
                            if ($loaiDSP === 'Nghệ sĩ') {
                                echo "123.456.789 người theo dõi";
                            } else {
                                echo isset($songCount) ? "$songCount bài hát" : "Loading số lượng bài hát...";;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="play">
                <button id="circle" onclick="togglePlayPause()"><i id="playbutton"
                        class="fa-solid fa-play"></i></button>
                <button id="threedots"><i class="fa-solid fa-ellipsis"></i></button>
                <button id="follow-button"
                    style="<?php echo ($loaiDSP === 'Playlist') ? 'display: none !important;' : 'display: block;'; ?>">Theo
                    dõi</button>
                <button id="add-song-button"><i class="fa-solid fa-plus-large"></i></button>
                <button id="delete-playlist-button"><i class="fa-solid fa-trash"></i></button>
                <button id="threebars"><i class="fa-solid fa-bars"></i></button>
            </div>
            <div id="listsong">
                <div id="listsong-title">
                    <span id="sharp-title">#</span>
                    <span id="tenbaihat-title">Tên bài hát</span>
                    <span id="ngaythem-title">Ngày thêm</span>
                </div>
                <div id="songs"></div>
                <!--listsong của playlist-->
                <audio id="audio-player" controls style="display: none;"></audio> <!-- Thẻ audio để phát nhạc -->
            </div>
        </div>
        <?php
        require $_SERVER['DOCUMENT_ROOT'] . "/web_nghe_nhac/app/pages/includes/right_side.php";
        ?>
    </div>

    </div> <!-- kết thúc div phần thân -->
    <!--Insert footer vào đây nhé-->
    <?php include '/xampp/htdocs/web_nghe_nhac/app/pages/includes/footer.php'; ?>
    <!--kết thúc div chân play pause-->
    <div id="create-newlist">
        <div id="return">
            <img src="/web_nghe_nhac/public/assets/icon/ic-return.svg" alt="">
        </div>

        <div id="lbl-create">
            <p>Tạo danh sách mới</p>
        </div>

        <form name="create-newlist" id="newlist-form">
            <button id="choose-image" onclick="document.getElementById('file-upload').click(); return false;">
                <img src="/web_nghe_nhac/public/assets/img/insert-img.svg" alt="">
            </button>
            <input type="file" id="file-upload" style="display: none;" accept="image/jpeg, image/png, image/jpg">
            <!--upload ảnh-->
            <div id="name-list">
                <p>Tên danh sách</p>
                <input type="text" name="name-list" required>
                <!--tên danh sách phát-->
            </div>

            <div id="scription">
                <p>Mô tả</p>
                <textarea name="scription" wrap="soft"></textarea>
                <!--Mô tả danh sách phát-->
            </div>
            <input type="submit" value="Tạo">
        </form>
    </div>
    <div id="them-bh-playlist">
        <div id="return">
            <img src="/web_nghe_nhac/public/assets/icon/ic-return.svg" alt="">
        </div>
        <p style="margin: 28px 0;">Thêm bài hát</p>
        <form id="form-them-bh">
            <div id="them-ten-bh">
                <label for="tenbh">Tên bài hát</label>
                <input type="text" name="tenbh" id="tenbh" required>
            </div>
            <input type="submit" value="Thêm">
        </form>
    </div>
    <div id="xoa-bh-playlist">
        <p>Bạn muốn xóa bài hát?</p>
        <div id="div-form-xoa-bh">
            <button id="cancel-button">Cancel</button>
            <form id="form-xoa-bh">
                <input type="hidden" id="song-id" name="songId" value="">
                <input type="submit" value="Xóa" id="xoa-bh-submit">
            </form>
        </div>
    </div>
    <div id="popup-xoa-playlist">
        <p>Bạn muốn xóa danh sách phát?</p>
        <div id="div-form-xoa-playlist">
            <button id="cancel-button-playlist">Cancel</button>
            <form id="form-xoa-playlist">
                <input type="hidden" id="song-id" name="songId" value="">
                <input type="submit" value="Xóa" id="xoa-playlist-submit">
            </form>
        </div>
    </div>
    <script src="/web_nghe_nhac/public/assets/script/playlist.js"></script>
</body>

</html>