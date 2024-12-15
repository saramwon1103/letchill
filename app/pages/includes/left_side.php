<?php
include_once '/xampp/htdocs/web_nghe_nhac/public/assets/php/control/playlistControl.php';
if (!isset($playlists)) {
    include_once '/xampp/htdocs/web_nghe_nhac/public/assets/php/config/config.php';
    include_once '/xampp/htdocs/web_nghe_nhac/public/assets/models/playlistModel.php';

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

<head>
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/left_side.css">
</head>
<div id="overlay"></div>
<div id="library">
    <!--div thư viện-->
    <div style="width: auto; display: flex;">
        <i id="library-icon" class="fa-solid fa-books"></i>
        <span id="library-text">Thư viện</span>
        <button type="submit" id="plus-button" style="cursor: pointer;"><i class="fa-solid fa-plus"></i></button>
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

                // Tạo đường dẫn tới playlistView.php
                $playlistUrl = "/web_nghe_nhac/public/assets/php/views/playlistView.php?id=$maDSP";

                echo "<a href='$playlistUrl' style='text-decoration: none; color: inherit;'>
                        <div id='playlist$maDSP'>
                            <span id='chillingwithheart-icon'>
                                <img src='$imgPath' alt='$tenDSP'>
                            </span>
                            <span id='chillingwithheart-text'>
                                $tenDSP
                                <br><span id='playlist-text'>" . ($loaiDSP === 'Playlist' ? 'Playlist' : 'Nghệ sĩ') . "</span>
                            </span>
                        </div>
                    </a>";
            }
        } else {
            echo "Không có danh sách phát nào!";
        }
        ?>
    </div>
</div>
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