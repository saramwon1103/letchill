<?php 
session_start(); // Khởi tạo session để lưu trữ dữ liệu tạm thời

// Kiểm tra nếu form đã được gửi qua phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy và làm sạch dữ liệu đầu vào để tránh tấn công XSS
    $package_type = isset($_POST['package_type']) ? htmlspecialchars(trim($_POST['package_type'])) : '';
    $price = isset($_POST['price']) ? htmlspecialchars(trim($_POST['price'])) : '';
    $duration = isset($_POST['duration']) ? htmlspecialchars(trim($_POST['duration'])) : '';
    $description = isset($_POST['description']) ? htmlspecialchars(trim($_POST['description'])) : '';

    // Kiểm tra xem dữ liệu có đầy đủ không
    if (empty($package_type) || empty($price) || empty($duration)) {
        // In thông báo lỗi nếu thiếu dữ liệu
        echo "Dữ liệu chưa đầy đủ. Vui lòng kiểm tra lại.";
    } else {
        // Lưu thông tin vào session để sử dụng trong trang payment.php
        $_SESSION['package_type'] = $package_type;
        $_SESSION['price'] = $price;
        $_SESSION['duration'] = $duration;
        $_SESSION['description'] = $description;

        // Sau khi lưu dữ liệu vào session, chuyển hướng đến trang payment.php
        header("Location: payment.php");
        exit();  // Đảm bảo dừng lại ngay sau khi chuyển hướng
    }
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/pack-info.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <title>Playlist</title>
    <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
</head>


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

            <div class="pack-info">
                <div class="container-1">
                    <table>
                        <tr>
                            <th rowspan="4">Lợi ích của tất cả các gói Premium</th>
                            <td class="benefit-items">
                                <img src="/web_nghe_nhac/public/assets/img/subway--tick.svg" alt="tick" class="tick">
                                Nghe nhạc không quảng cáo
                            </td>
                        </tr>
                        <tr>
                            <td class="benefit-items">
                                <img src="/web_nghe_nhac/public/assets/img/subway--tick.svg" alt="tick" class="tick">
                                Chất lượng âm thanh cao
                            </td>
                        </tr>
                        <tr>
                            <td class="benefit-items">
                                <img src="/web_nghe_nhac/public/assets/img/subway--tick.svg" alt="tick" class="tick">
                                Sắp xếp danh sách chờ
                            </td>
                        </tr>
                        <tr>
                            <td class="benefit-items">
                                <img src="/web_nghe_nhac/public/assets/img/subway--tick.svg" alt="tick" class="tick">
                                Phát nhạc theo thứ tự bất kỳ
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="container-2">
                    <form action="pack-info.php" method="POST">
                        <div class="mini">
                            <label class="pre">Premium</label>
                            <label class="type">Mini</label>
                            <label class="price">15.000VND/tuần</label>
                            <p>1 tài khoản di động duy nhất</p>
                            <p>Nghe tối đa 30 bài hát trên 1 thiết bị khi không có kết nối mạng</p>
                            <p>Chất lượng âm thanh cơ bản</p>
                            <input type="hidden" name="package_type" value="Mini">
                            <input type="hidden" name="price" value="15.000VND">
                            <input type="hidden" name="duration" value="1 tuần">
                            <input type="hidden" name="description" value="1 tài khoản di động duy nhất">
                            <button type="submit" class="buy">Mua ngay</button>
                        </div>
                    </form>
                    <form action="pack-info.php" method="POST">
                        <div class="individual">
                            <label class="pre">Premium</label>
                            <label class="type">Individual</label>
                            <label class="price">59.000VND/tuần</label>
                            <p>1 tài khoản Premium</p>
                            <p>Hủy bất cứ lúc nào</p>
                            <p>Đăng ký và thanh toán một lần</p>
                            <input type="hidden" name="package_type" value="Individual">
                            <input type="hidden" name="price" value="59.000VND">
                            <input type="hidden" name="duration" value="1 tháng">
                            <input type="hidden" name="description" value="1 tài khoản Premium">
                            <button type="submit" class="buy">Mua ngay</button>
                        </div>
                    </form>
                    <form action="pack-info.php" method="POST">
                        <div class="student">
                            <label class="pre">Premium</label>
                            <label class="type">Student</label>
                            <label class="price">29.000VND/tuần</label>
                            <p>1 tài khoản Premium đã xác minh</p>
                            <p>Giảm giá cho sinh viên đủ điều kiện</p>
                            <p>Hủy bất cứ lúc nào</p>
                            <p>Đăng ký và thanh toán một lúc</p>
                            <input type="hidden" name="package_type" value="Student">
                            <input type="hidden" name="price" value="29.000VND">
                            <input type="hidden" name="duration" value="1 tháng">
                            <input type="hidden" name="description" value="1 tài khoản Premium đã xác minh">
                            <button type="submit" class="buy">Mua ngay</button>
                        </div>
                    </form>
                </div>
                <div class="pack" style="display:none;">
                    <!--div thông tin các gói-->
                    <div class="pack-container-1">
                        <h2>Lợi ích của tất cả các gói Premium</h2>
                        <div class="pack-description">
                            <div class="pack-benefit-items">
                                <img src="/web_nghe_nhac/public/assets/img/subway--tick.svg" alt="tick" class="tick">
                                <p>Nghe nhạc không quảng cáo</p>
                            </div>
                            <div class="pack-benefit-items">
                                <img src="/web_nghe_nhac/public/assets/img/subway--tick.svg" alt="tick" class="tick">
                                <p>Chất lượng âm thanh cao</p>
                            </div>
                            <div class="pack-benefit-items">
                                <img src="/web_nghe_nhac/public/assets/img/subway--tick.svg" alt="tick" class="tick">
                                <p>Sắp xếp danh sách chờ</p>
                            </div>
                            <div class="pack-benefit-items">
                                <img src="/web_nghe_nhac/public/assets/img/subway--tick.svg" class="tick">
                                <p>Phát nhạc theo thứ tự bất kỳ</p>
                            </div>
                        </div>
                    </div>

                    <div class="pack-container-2">
                        <form action="pack-info.php" method="POST">
                            <div class="pack-mini">
                                <label class="pre">Premium</label>
                                <label class="type">Mini</label>
                                <label class="price">15.000VND/tuần</label>
                                <p>1 tài khoản di động duy nhất</p>
                                <p>Nghe tối đa 30 bài hát trên 1 thiết bị khi không có kết nối mạng</p>
                                <p>Chất lượng âm thanh cơ bản</p>
                                <input type="hidden" name="package_type" value="Mini">
                                <input type="hidden" name="price" value="15.000VND">
                                <input type="hidden" name="duration" value="1 tuần">
                                <input type="hidden" name="description" value="1 tài khoản di động duy nhất">
                                <button type="submit" class="buy">Mua ngay</button>
                            </div>
                        </form>
                        <form action="pack-info.php" method="POST">
                            <div class="pack-individual">
                                <label class="pre">Premium</label>
                                <label class="type">Individual</label>
                                <label class="price">59.000VND/tuần</label>
                                <p>1 tài khoản Premium</p>
                                <p>Hủy bất cứ lúc nào</p>
                                <p>Đăng ký và thanh toán một lần</p>
                                <input type="hidden" name="package_type" value="Individual">
                                <input type="hidden" name="price" value="59.000VND">
                                <input type="hidden" name="duration" value="1 tháng">
                                <input type="hidden" name="description" value="1 tài khoản Premium">
                                <button type="submit" class="buy">Mua ngay</button>
                            </div>
                        </form>
                        <form action="pack-info.php" method="POST">
                            <div class="pack-student">
                                <label class="pre">Premium</label>
                                <label class="type">Student</label>
                                <label class="price">29.000VND/tuần</label>
                                <p>1 tài khoản Premium đã xác minh</p>
                                <p>Giảm giá cho sinh viên đủ điều kiện</p>
                                <p>Hủy bất cứ lúc nào</p>
                                <p>Đăng ký và thanh toán một lúc</p>
                                <input type="hidden" name="package_type" value="Student">
                                <input type="hidden" name="price" value="29.000VND">
                                <input type="hidden" name="duration" value="1 tháng">
                                <input type="hidden" name="description" value="1 tài khoản Premium đã xác minh">
                                <button type="submit" class="buy">Mua ngay</button>
                            </div>
                        </form>
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
        <div class="rightBar" id="rightBar" style="display:none;">
            <?php
            require $_SERVER['DOCUMENT_ROOT'] . "/web_nghe_nhac/app/pages/includes/right_side.php";
            ?>
        </div>

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
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Lưu nội dung gốc của pack-info
    var packInfo = document.querySelector(".pack-info");
    var originalPackInfoContent = packInfo.innerHTML;

    // Xử lý khi nhấn vào icon "info"
    document.getElementById("info").addEventListener("click", function() {
        var pack = document.querySelector(".pack");
        var rightBar = document.getElementById("rightBar");

        // Thay thế nội dung của pack-info bằng nội dung của pack
        packInfo.innerHTML = pack.innerHTML;

        // Ẩn pack-info
        packInfo.style.display = "block";

        // Hiển thị pack và rightBar
        pack.style.display = "block";
        rightBar.style.display = "block";
    });

    // Xử lý khi nhấn vào icon "exit" trong rightBar
    document.getElementById("rightBar").addEventListener("click", function(event) {
        if (event.target && event.target.id === "exit-lbl") {
            var pack = document.querySelector(".pack");
            var rightBar = document.getElementById("rightBar");

            // Khôi phục nội dung gốc của pack-info
            packInfo.innerHTML = originalPackInfoContent;

            // Ẩn pack và rightBar
            pack.style.display = "none";
            rightBar.style.display = "none";

            // Hiển thị lại pack-info
            packInfo.style.display = "block";
        }
    });
});
</script>

<!-- File javaScript -->
<script src="/web_nghe_nhac/public/assets/script/listeningSpace.js"></script>
<script src="/web_nghe_nhac/public/assets/script/main_cpn.js"></script>
<script src="/web_nghe_nhac/public/assets/script/leftBar.js"></script>
<script src="/web_nghe_nhac/public/assets/script/rightBar.js"></script>
<script src="/web_nghe_nhac/public/assets/script/header.js"></script>
<script src="/web_nghe_nhac/public/assets/script/song.js"></script>

</body>

</html>