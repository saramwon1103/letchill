<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song manage</title>
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/song_manage.css">
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/admin_left_side.css">
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/header_main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <title>Playlist Info</title>
    <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
</head>

<?php
if (!isset($songs)) {
    require_once '/xampp/htdocs/web_nghe_nhac/public/assets/php/config/config.php';
    include_once '/xampp/htdocs/web_nghe_nhac/public/assets/models/song_manage_model.php';

    $database = new Database();
    $db = $database->getConnection();
    $songManageModel = new SongManageModel($db);
    $songs = $songManageModel->getAllSongs();
}
// Khởi tạo kết nối Database và Controller
if (!isset($song_manage_controller)) {
    include_once '/xampp/htdocs/web_nghe_nhac/public/assets/php/control/song_manage_control.php';
    $database = new Database();
    $db = $database->getConnection();
    $song_manage_controller = new SongManageController($db);
}
?>

<body>
    <div id="overlay"></div>
    <?php include '/xampp/htdocs/web_nghe_nhac/app/pages/admin/header.php';?>
    <?php include '/xampp/htdocs/web_nghe_nhac/app/pages/admin/left_side.php';?>
    <div id="main">
        <div id="main-head">
            <div id="search-bar">
                <div id="search-typing">
                    <span>
                        <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.5 19L13.5 13M1.5 8C1.5 8.91925 1.68106 9.82951 2.03284 10.6788C2.38463 11.5281 2.90024 12.2997 3.55025 12.9497C4.20026 13.5998 4.97194 14.1154 5.82122 14.4672C6.6705 14.8189 7.58075 15 8.5 15C9.41925 15 10.3295 14.8189 11.1788 14.4672C12.0281 14.1154 12.7997 13.5998 13.4497 12.9497C14.0998 12.2997 14.6154 11.5281 14.9672 10.6788C15.3189 9.82951 15.5 8.91925 15.5 8C15.5 7.08075 15.3189 6.1705 14.9672 5.32122C14.6154 4.47194 14.0998 3.70026 13.4497 3.05025C12.7997 2.40024 12.0281 1.88463 11.1788 1.53284C10.3295 1.18106 9.41925 1 8.5 1C7.58075 1 6.6705 1.18106 5.82122 1.53284C4.97194 1.88463 4.20026 2.40024 3.55025 3.05025C2.90024 3.70026 2.38463 4.47194 2.03284 5.32122C1.68106 6.1705 1.5 7.08075 1.5 8Z"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <input id="search-input" type="text" placeholder="Tìm kiếm tên bài hát hoặc nghệ sỹ">
                    <!--Thanh tìm kiếm-->
                </div>
                <button id="search-button">
                    Tìm kiếm
                </button>
            </div>
        </div>
        <div id="main-body">
            <div id="songs-title">
                <span>#</span>
                <span>Tên bài hát</span>
                <span>Thể loại</span>
            </div>
            <div id="songs-list">
                <?php
                    if (!empty($songs)) {
                        foreach ($songs as $index => $song) {
                            $maBH = $song['MaBaiHat'];
                            $tenBH = $song['TenBaiHat'];
                            $anhBH = $song['AnhBaiHat'];
                            $anhBHPath = "/web_nghe_nhac/public/assets/img/data-songs-image/$anhBH";
                            $tenNgheSy = $song['TenNgheSy'];
                            $theLoai = $song['TenTheLoai'];

                            echo '<div class="song">
                                    <input class="checkbox" type="checkbox" value="' . htmlspecialchars($maBH) . '">
                                    <span class="stt">' . ($index + 1) . '</span>
                                    <img class="img" src="' . htmlspecialchars($anhBHPath) . '" alt="' . htmlspecialchars($tenBH) . '">
                                    <span class="title">
                                        <span class="tenBH">' . htmlspecialchars($tenBH) . '</span>
                                        <span class="tenNS">' . htmlspecialchars($tenNgheSy) . '</span>
                                    </span>
                                    <span class="type">' . htmlspecialchars($theLoai) . '</span>
                                </div>';
                        }
                    } else {
                        echo "<p>Không có bài hát nào để hiển thị.</p>";
                    }
                ?>
            </div>
        </div>
    </div>
    <!--Thêm bài hát-->
    <div id="them-bh-popup">
        <button id="them-bh-return">
            <svg width="51" height="51" viewBox="0 0 51 51" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="0.5" y="0.5" width="49.7412" height="49.7647" rx="24.8706" stroke="white" />
                <path
                    d="M30.3724 16.3672C30.2563 16.2508 30.1184 16.1585 29.9665 16.0955C29.8147 16.0324 29.6519 16 29.4874 16C29.323 16 29.1602 16.0324 29.0084 16.0955C28.8565 16.1585 28.7186 16.2508 28.6024 16.3672L20.2924 24.6772C20.1997 24.7697 20.1262 24.8796 20.076 25.0006C20.0258 25.1216 20 25.2513 20 25.3822C20 25.5132 20.0258 25.6429 20.076 25.7639C20.1262 25.8848 20.1997 25.9947 20.2924 26.0872L28.6024 34.3972C29.0924 34.8872 29.8824 34.8872 30.3724 34.3972C30.8624 33.9072 30.8624 33.1172 30.3724 32.6272L23.1324 25.3772L30.3824 18.1272C30.8624 17.6472 30.8624 16.8472 30.3724 16.3672Z"
                    fill="white" />
            </svg>
        </button>
        <span id="popup-title">Thêm bài hát mới</span>
        <form name="form-them-bh" id=form-them-bh>
            <button id="choose-image" onclick="document.getElementById('file-upload').click(); return false;">
                <img src="/web_nghe_nhac/public/assets/img/insert-img.svg" alt="">
            </button>
            <input type="file" id="file-upload" accept="image/jpeg, image/png, image/jpg" style="display: none;">
            <div id="name-bh">
                <p>Tên bài hát</p>
                <input type="text" name="name-bh" id="them-ten-bai-hat" required>
            </div>
            <div id="name-artist">
                <p>Nghệ sỹ</p>
                <input type="text" name="name-artist" id="them-ten-nghe-sy" required>
            </div>
            <div id="theloai">
                <p>Thể loại</p>
                <select name="theloai" id="select-theloai">
                    <option value="1">Pop</option>
                    <option value="2">K-Pop</option>
                    <option value="3">USUK</option>
                    <option value="4">R&B</option>
                    <option value="5">Hip-hop/Rap</option>
                    <option value="6">EDM</option>
                    <option value="7">Ballad</option>
                    <option value="8">Country</option>
                    <option value="9">Indie</option>
                    <option value="10">Latin</option>
                </select>
            </div>
            <div id="lyrics">
                <p>Lời bài hát</p>
                <textarea name="lyrics" wrap="soft"></textarea>
                <!--Mô tả danh sách phát-->
            </div>
            <div style="display: flex; align-items: center;">
                <button id="upload-bai-hat-button" type="button"
                    onclick="document.getElementById('upload-bai-hat').click()" ; return false;>Upload nhạc</button>
                <input type="file" id="upload-bai-hat" accept=".mp3, .wav" style="display: none;">
                <input type="submit" value="Tạo">
            </div>
        </form>
    </div>
    <!--Sửa bài hát-->
    <div id="cap-nhat-bh-popup">
        <input type="hidden" name="id-bh" id="cap-nhat-id-bh">
        <!--Input ẩn để lưu mã bài hát-->
        <button id="cap-nhat-return">
            <svg width="51" height="51" viewBox="0 0 51 51" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="0.5" y="0.5" width="49.7412" height="49.7647" rx="24.8706" stroke="white" />
                <path
                    d="M30.3724 16.3672C30.2563 16.2508 30.1184 16.1585 29.9665 16.0955C29.8147 16.0324 29.6519 16 29.4874 16C29.323 16 29.1602 16.0324 29.0084 16.0955C28.8565 16.1585 28.7186 16.2508 28.6024 16.3672L20.2924 24.6772C20.1997 24.7697 20.1262 24.8796 20.076 25.0006C20.0258 25.1216 20 25.2513 20 25.3822C20 25.5132 20.0258 25.6429 20.076 25.7639C20.1262 25.8848 20.1997 25.9947 20.2924 26.0872L28.6024 34.3972C29.0924 34.8872 29.8824 34.8872 30.3724 34.3972C30.8624 33.9072 30.8624 33.1172 30.3724 32.6272L23.1324 25.3772L30.3824 18.1272C30.8624 17.6472 30.8624 16.8472 30.3724 16.3672Z"
                    fill="white" />
            </svg>
        </button>
        <span id="cap-nhat-popup-title">Cập nhật bài hát</span>
        <form name="form-cap-nhat-bh" id=cap-nhat-bh-form>
            <button id="cap-nhat-choose-image" onclick="document.getElementById('file-upload').click(); return false;">
                <img src="/web_nghe_nhac/public/assets/img/insert-img.svg" alt="">
            </button>
            <input type="file" id="cap-nhat-file-upload" accept="image/jpeg, image/png, image/jpg"
                style="display: none;">
            <div id="cap-nhat-name-bh">
                <p>Tên bài hát</p>
                <input type="text" name="name-bh" id="cap-nhat-ten-bai-hat" required>
            </div>
            <div id="cap-nhat-name-artist">
                <p>Nghệ sỹ</p>
                <input type="text" name="name-artist" id="cap-nhat-ten-nghe-sy" required>
            </div>
            <div id="cap-nhat-theloai">
                <p>Thể loại</p>
                <select name="theloai" id="cap-nhat-select-theloai">
                    <option value="1">Pop</option>
                    <option value="2">K-Pop</option>
                    <option value="3">USUK</option>
                    <option value="4">R&B</option>
                    <option value="5">Hip-hop/Rap</option>
                    <option value="6">EDM</option>
                    <option value="7">Ballad</option>
                    <option value="8">Country</option>
                    <option value="9">Indie</option>
                    <option value="10">Latin</option>
                </select>
            </div>
            <div id="cap-nhat-lyrics">
                <p>Lời bài hát</p>
                <textarea name="cap-nhat-lyrics" id="cap-nhat-loi-bai-hat" wrap="soft"></textarea>
                <!--Mô tả danh sách phát-->
            </div>
            <div style="display: flex; align-items: center;">
                <!--<button id="cap-nhat-upload-bh" type="button"
                    onclick="document.getElementById('upload-bai-hat').click()" ; return false;>Upload nhạc</button>
                <input type="file" id="cap-nhat-upload-bh" accept=".mp3, .wav" style="display: none;">-->
                <input type="submit" value="Cập nhật" id="cap-nhat-submit">
            </div>
        </form>
    </div>
    <div id="xoa-bh-admin">
        <p>Bạn muốn xóa bài hát?</p>
        <div id="div-form-xoa-bh-admin">
            <button id="cancel-button-admin">Cancel</button>
            <form id="form-xoa-bh-admin">
                <input type="hidden" id="song-id-admin" name="songId" value="">
                <input type="submit" value="Xóa" id="xoa-bh-submit-admin">
            </form>
        </div>
    </div>
    <script src="/web_nghe_nhac/public/assets/script/song_manage.js"></script>
    <script src="/web_nghe_nhac/public/assets/script/admin_left_side.js"></script>
    <script src="/web_nghe_nhac/public/assets/script/admin_left.js"></script>
    <script src="/web_nghe_nhac/public/assets/script/admin.js"></script>
</body>

</html>