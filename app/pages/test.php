<?php
session_start();  // Khởi tạo session để lưu trữ dữ liệu tạm thời

// Kiểm tra nếu form đã được gửi qua phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra và lấy thông tin từ các trường đã gửi
    $package_type = isset($_POST['package_type']) ? htmlspecialchars($_POST['package_type']) : '';
    $price = isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '';
    $duration = isset($_POST['duration']) ? htmlspecialchars($_POST['duration']) : '';
    $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';

    // Kiểm tra xem dữ liệu có đầy đủ không
    if (empty($package_type) || empty($price) || empty($duration)) {
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
} else {
    // Nếu không phải là phương thức POST, bạn có thể xử lý lỗi hoặc chuyển hướng
    echo "Không có dữ liệu gửi đến.";
}
?>



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
<body>
<!-- leftBar -->
<?php
    require $_SERVER['DOCUMENT_ROOT'] . "/web_nghe_nhac/app/pages/includes/left_side.php";
?>

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
        </div>

        <div class="pack" style="display: none;"> <!--div thông tin các gói-->
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
        <div id="info-div" style="display:none;"> <!--Thông tin bài hát nghệ sĩ-->
            <div id="info-2icon">
                <button id="info-plus"><svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M14.4998 9.08122H9.08317V14.4979C9.08317 14.7852 8.96903 15.0608 8.76587 15.2639C8.5627 15.4671 8.28715 15.5812 7.99984 15.5812C7.71252 15.5812 7.43697 15.4671 7.2338 15.2639C7.03064 15.0608 6.9165 14.7852 6.9165 14.4979V9.08122H1.49984C1.21252 9.08122 0.936969 8.96708 0.733805 8.76392C0.530641 8.56075 0.416504 8.2852 0.416504 7.99788C0.416504 7.71057 0.530641 7.43502 0.733805 7.23185C0.936969 7.02869 1.21252 6.91455 1.49984 6.91455H6.9165V1.49788C6.9165 1.21057 7.03064 0.935016 7.2338 0.731851C7.43697 0.528687 7.71252 0.414551 7.99984 0.414551C8.28715 0.414551 8.5627 0.528687 8.76587 0.731851C8.96903 0.935016 9.08317 1.21057 9.08317 1.49788V6.91455H14.4998C14.7872 6.91455 15.0627 7.02869 15.2659 7.23185C15.469 7.43502 15.5832 7.71057 15.5832 7.99788C15.5832 8.2852 15.469 8.56075 15.2659 8.76392C15.0627 8.96708 14.7872 9.08122 14.4998 9.08122Z"
                            fill="white" />
                    </svg>
                </button>
                <button id="info-exit" onclick="close"><svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M14.8252 1.18583C14.725 1.0854 14.606 1.00573 14.4749 0.951363C14.3439 0.897 14.2034 0.869017 14.0615 0.869017C13.9196 0.869017 13.7791 0.897 13.6481 0.951363C13.517 1.00573 13.398 1.0854 13.2977 1.18583L8.00024 6.4725L2.70274 1.175C2.60245 1.0747 2.48338 0.995141 2.35233 0.94086C2.22129 0.88658 2.08083 0.858643 1.93899 0.858643C1.79715 0.858643 1.6567 0.88658 1.52565 0.94086C1.39461 0.995141 1.27554 1.0747 1.17524 1.175C1.07494 1.27529 0.995385 1.39436 0.941105 1.52541C0.886824 1.65645 0.858887 1.79691 0.858887 1.93875C0.858887 2.08059 0.886824 2.22104 0.941105 2.35209C0.995385 2.48313 1.07494 2.6022 1.17524 2.7025L6.47274 8L1.17524 13.2975C1.07494 13.3978 0.995385 13.5169 0.941105 13.6479C0.886824 13.779 0.858887 13.9194 0.858887 14.0612C0.858887 14.2031 0.886824 14.3435 0.941105 14.4746C0.995385 14.6056 1.07494 14.7247 1.17524 14.825C1.27554 14.9253 1.39461 15.0049 1.52565 15.0591C1.6567 15.1134 1.79715 15.1414 1.93899 15.1414C2.08083 15.1414 2.22129 15.1134 2.35233 15.0591C2.48338 15.0049 2.60245 14.9253 2.70274 14.825L8.00024 9.5275L13.2977 14.825C13.398 14.9253 13.5171 15.0049 13.6482 15.0591C13.7792 15.1134 13.9196 15.1414 14.0615 15.1414C14.2033 15.1414 14.3438 15.1134 14.4748 15.0591C14.6059 15.0049 14.7249 14.9253 14.8252 14.825C14.9255 14.7247 15.0051 14.6056 15.0594 14.4746C15.1137 14.3435 15.1416 14.2031 15.1416 14.0612C15.1416 13.9194 15.1137 13.779 15.0594 13.6479C15.0051 13.5169 14.9255 13.3978 14.8252 13.2975L9.52774 8L14.8252 2.7025C15.2369 2.29083 15.2369 1.5975 14.8252 1.18583Z"
                            fill="white" />
                    </svg>
                </button>
            </div>
            <div id="info-body">
                <div id="infodiv-song">
                    <img src="/web_nghe_nhac/public/assets/img/song3.png" alt="">
                    <span id="info-namesong">I can do it with a broken heart<br><span id="info-authorsong">Taylor
                            Swift</span></span>
                </div>
                <div id="custom-rate">
                    <div style="margin-bottom: 20px;">
                        <span id="rate-danhgia">Đánh giá</span>
                        <span id="rate-tatca">Tất cả</span>
                    </div>
                    <span id="custom-name">Hoang Trinh Anh Khoa<br></span>
                    <div style="margin-top: 5px; margin-bottom: 4px;">
                        <input type="radio" name="rating1" id="rating1">
                        <label for="rating1" class="fa-solid fa-circle"></label>
                        <input type="radio" name="rating2" id="rating2">
                        <label for="rating2" class="fa-solid fa-circle"></label>
                        <input type="radio" name="rating3" id="rating3">
                        <label for="rating3" class="fa-solid fa-circle"></label>
                        <input type="radio" name="rating4" id="rating4">
                        <label for="rating4" class="fa-solid fa-circle"></label>
                        <input type="radio" name="rating5" id="rating5">
                        <label for="rating5" class="fa-solid fa-circle"></label>
                    </div>
                    <span id="rate-comment">good</span>
                </div>
                <div id="info-artist-follow">
                    <span id="info-artist-follow-img">
                        <span>Nghệ sĩ</span>
                    </span>
                    <div id="info-artist-follow-title">
                        <span>Taylor Swift</span>
                        <button>Theo dõi</button>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- kết thúc div phần thân -->

    <div background-color="black" id="fot"> <!--div chân play pause-->
        <div id="mini-song">
            <img id="f-img-song" src="/web_nghe_nhac/public/assets/img/song3.png" alt="">
            <div id="mini-name">I can do it with a broken heart<br><span id="mini-author">Taylor Swift</span></div>
            <button id="mini-plus">+</button>
        </div>
        <div id="control">
            <div id="audio-player">
                <button><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M18.3537 1.65477C17.5922 0.884435 16.6227 0.352756 15.5639 0.124792C14.505 -0.103172 13.4026 -0.0175344 12.3916 0.371227C11.3806 0.759988 10.5049 1.43501 9.87158 2.3137C9.23824 3.19239 8.87477 4.23664 8.82565 5.31868L14.6842 11.1772C15.7655 11.1281 16.8091 10.765 17.6875 10.1324C18.5659 9.49978 19.2409 8.62497 19.6301 7.61491C20.0194 6.60484 20.1059 5.50326 19.8792 4.4448C19.6526 3.38633 19.1224 2.41683 18.3537 1.65477ZM4.50354 11.5371L0.546901 15.4993C0.197346 15.849 0.000976562 16.3231 0.000976562 16.8176C0.000976562 17.312 0.197346 17.7862 0.546901 18.1358L1.86516 19.4541C2.21482 19.8036 2.689 20 3.18342 20C3.67784 20 4.15202 19.8036 4.50168 19.4541L12.4131 11.5427L8.45832 7.58787L4.50354 11.5371ZM5.8852 15.4284L4.56694 14.1102L8.45832 10.2244L9.77658 11.5427L5.8852 15.4284Z"
                            fill="white" />
                    </svg></button>
                <button><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0.00292969 1.11102C0.00292969 0.816356 0.126894 0.533765 0.347551 0.325409C0.568208 0.117053 0.867483 0 1.17954 0C1.49159 0 1.79087 0.117053 2.01153 0.325409C2.23218 0.533765 2.35615 0.816356 2.35615 1.11102V8.32817L17.1038 0.248867C18.3745 -0.446628 20.0029 0.395522 20.0029 1.7954V18.2029C20.0029 19.6028 18.3745 20.4471 17.1038 19.7516L2.35615 11.6723V18.8873C2.35615 19.1819 2.23218 19.4645 2.01153 19.6729C1.79087 19.8812 1.49159 19.9983 1.17954 19.9983C0.867483 19.9983 0.568208 19.8812 0.347551 19.6729C0.126894 19.4645 0.00292969 19.1819 0.00292969 18.8873V1.11102Z"
                            fill="white" />
                    </svg></button>
                <button><svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="0.00292969" width="36" height="36" rx="18" fill="white" />
                        <path
                            d="M12.0029 28.5C13.6529 28.5 15.0029 27.15 15.0029 25.5V10.5C15.0029 8.85 13.6529 7.5 12.0029 7.5C10.3529 7.5 9.00293 8.85 9.00293 10.5V25.5C9.00293 27.15 10.3529 28.5 12.0029 28.5ZM21.0029 10.5V25.5C21.0029 27.15 22.3529 28.5 24.0029 28.5C25.6529 28.5 27.0029 27.15 27.0029 25.5V10.5C27.0029 8.85 25.6529 7.5 24.0029 7.5C22.3529 7.5 21.0029 8.85 21.0029 10.5Z"
                            fill="black" />
                    </svg></button>
                <button><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20.0029 18.889C20.0029 19.1836 19.879 19.4662 19.6583 19.6746C19.4377 19.8829 19.1384 20 18.8263 20C18.5143 20 18.215 19.8829 17.9943 19.6746C17.7737 19.4662 17.6497 19.1836 17.6497 18.889V11.6718L2.90209 19.7511C1.63136 20.4466 0.00292969 19.6045 0.00292969 18.2046L0.00292969 1.79713C0.00292969 0.397249 1.63136 -0.447121 2.90209 0.248375L17.6497 8.32767L17.6497 1.11274C17.6497 0.818085 17.7737 0.535492 17.9943 0.327137C18.215 0.11878 18.5143 0.00172806 18.8263 0.00172806C19.1384 0.00172806 19.4377 0.11878 19.6583 0.327137C19.879 0.535492 20.0029 0.818085 20.0029 1.11274L20.0029 18.889Z"
                            fill="white" />
                    </svg></button>
                <button><svg width="24" height="22" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.8917 2.11111L2.00293 5.44445L5.8917 9.33333" stroke="white" stroke-width="4"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M2.00293 5.44444H14.7772C18.6011 5.44444 21.8485 8.56688 21.9976 12.3889C22.1551 16.4275 18.818 19.8889 14.7772 19.8889H5.33537"
                            stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg></button>
            </div>
            <div id="progress">
                <span id="current-time">2:47</span>
                <input type="range" id="progress-slider" value="0" max="100" />
                <span id="total-time">3:38</span>
            </div>
        </div>
        <div id="info-volume">
            <button style="display:flex; align-items: center;" id="info"><svg width="20" height="20" viewBox="0 0 20 20"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9.99984 1.66667C14.6023 1.66667 18.3332 5.3975 18.3332 10C18.3332 14.6025 14.6023 18.3333 9.99984 18.3333C5.39734 18.3333 1.6665 14.6025 1.6665 10C1.6665 5.3975 5.39734 1.66667 9.99984 1.66667ZM9.9915 8.33334H9.1665C8.9541 8.33357 8.74981 8.4149 8.59536 8.56071C8.44092 8.70652 8.34797 8.9058 8.33553 9.11783C8.32308 9.32987 8.39206 9.53865 8.52839 9.70153C8.66471 9.86441 8.85809 9.96908 9.069 9.99417L9.1665 10V14.1583C9.1665 14.5917 9.49484 14.95 9.9165 14.995L10.0082 15H10.4165C10.5918 15 10.7626 14.9448 10.9046 14.8421C11.0467 14.7394 11.1527 14.5946 11.2077 14.4282C11.2628 14.2618 11.2639 14.0823 11.211 13.9152C11.1581 13.7481 11.0539 13.602 10.9132 13.4975L10.8332 13.445V9.175C10.8332 8.74167 10.5048 8.38334 10.0832 8.33834L9.9915 8.33334ZM9.99984 5.83334C9.77882 5.83334 9.56686 5.92114 9.41058 6.07742C9.2543 6.2337 9.1665 6.44566 9.1665 6.66667C9.1665 6.88768 9.2543 7.09965 9.41058 7.25593C9.56686 7.41221 9.77882 7.5 9.99984 7.5C10.2208 7.5 10.4328 7.41221 10.5891 7.25593C10.7454 7.09965 10.8332 6.88768 10.8332 6.66667C10.8332 6.44566 10.7454 6.2337 10.5891 6.07742C10.4328 5.92114 10.2208 5.83334 9.99984 5.83334Z"
                        fill="white" />
                </svg></button>
            <svg style="cursor: pointer; margin-right: 15px;" id="volume-icon" width="20" height="20"
                viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.0498 2.74998C11.1803 2.65668 11.3329 2.59923 11.4924 2.58336C11.652 2.56749 11.813 2.59376 11.9592 2.65954C12.1055 2.72533 12.2319 2.82833 12.3259 2.95825C12.4199 3.08818 12.4781 3.24049 12.4948 3.39998L12.4998 3.49498V16.505C12.4999 16.6653 12.4579 16.8229 12.378 16.962C12.2981 17.101 12.1831 17.2167 12.0445 17.2974C11.906 17.3781 11.7486 17.421 11.5883 17.4219C11.4279 17.4228 11.2701 17.3816 11.1307 17.3025L11.0507 17.2508L5.5665 13.3333H3.33317C2.91269 13.3334 2.5077 13.1746 2.19938 12.8887C1.89106 12.6028 1.70221 12.2109 1.67067 11.7916L1.6665 11.6666V8.33331C1.66637 7.91283 1.82518 7.50784 2.11108 7.19952C2.39699 6.8912 2.78887 6.70235 3.20817 6.67081L3.33317 6.66664H5.5665L11.0498 2.74998ZM16.389 5.65248C17.0006 6.19961 17.4899 6.86964 17.825 7.61879C18.16 8.36793 18.3331 9.17934 18.3332 9.99998C18.3331 10.8206 18.16 11.632 17.825 12.3812C17.4899 13.1303 17.0006 13.8003 16.389 14.3475C16.3077 14.4217 16.2124 14.479 16.1088 14.5162C16.0052 14.5534 15.8952 14.5696 15.7853 14.5641C15.6754 14.5585 15.5676 14.5312 15.4683 14.4838C15.3689 14.4363 15.28 14.3697 15.2066 14.2877C15.1331 14.2056 15.0767 14.1099 15.0405 14.0059C15.0042 13.902 14.989 13.7919 14.9956 13.682C15.0021 13.5721 15.0304 13.4646 15.0788 13.3657C15.1272 13.2668 15.1946 13.1785 15.2773 13.1058C15.7144 12.715 16.064 12.2363 16.3034 11.7011C16.5428 11.166 16.6665 10.5863 16.6665 9.99998C16.6665 8.76664 16.1315 7.65831 15.2773 6.89414C15.1946 6.82148 15.1272 6.73315 15.0788 6.63425C15.0304 6.53536 15.0021 6.42787 14.9956 6.31798C14.989 6.20809 15.0042 6.09799 15.0405 5.99403C15.0767 5.89007 15.1331 5.79432 15.2066 5.7123C15.28 5.63028 15.3689 5.56363 15.4683 5.51618C15.5676 5.46874 15.6754 5.44145 15.7853 5.43589C15.8952 5.43032 16.0052 5.4466 16.1088 5.48378C16.2124 5.52095 16.3077 5.57829 16.389 5.65248ZM14.7223 7.51581C15.0717 7.82836 15.3512 8.21109 15.5426 8.639C15.734 9.06691 15.833 9.53038 15.8332 9.99914C15.8333 10.4682 15.7344 10.932 15.543 11.3602C15.3515 11.7884 15.0719 12.1714 14.7223 12.4841C14.5643 12.6245 14.3588 12.6996 14.1476 12.6941C13.9363 12.6887 13.7349 12.6032 13.5843 12.4549C13.4337 12.3066 13.3451 12.1066 13.3363 11.8955C13.3276 11.6843 13.3995 11.4777 13.5373 11.3175L13.6107 11.2425C13.9523 10.9358 14.1665 10.4933 14.1665 9.99998C14.1666 9.5734 14.0032 9.16301 13.7098 8.85331L13.6107 8.75748C13.528 8.68481 13.4605 8.59648 13.4121 8.49759C13.3638 8.39869 13.3355 8.2912 13.3289 8.18131C13.3223 8.07142 13.3376 7.96132 13.3738 7.85736C13.41 7.7534 13.4665 7.65765 13.5399 7.57563C13.6133 7.49362 13.7023 7.42696 13.8016 7.37952C13.9009 7.33207 14.0087 7.30478 14.1186 7.29922C14.2286 7.29366 14.3385 7.30994 14.4422 7.34711C14.5458 7.38429 14.641 7.44162 14.7223 7.51581Z"
                    fill="white" />
            </svg>
            <input type="range" id="volume" value="100" max="100" />
        </div>
    </div><!--kết thúc div chân play pause-->
    <script>
        document.getElementById('progress-slider').addEventListener('input', function () {
            const value = this.value;
            const max = this.max;
            const percentage = (value / max) * 100;

            // Update the background of the slider based on the value
            this.style.background = `linear-gradient(90deg, #1DB954 ${percentage}%, #ddd ${percentage}%)`;
        });
        document.getElementById('volume').addEventListener('input', function () {
            const value = this.value;
            const max = this.max;
            const percentage = (value / max) * 100;

            // Update the background of the slider based on the value
            this.style.background = `linear-gradient(90deg, #1DB954 ${percentage}%, #ddd ${percentage}%)`;
        });

        // Lấy các phần tử cần thiết
        const fot = document.getElementById('fot');
        const packInfo = document.querySelector('.pack-info');
        const pack = document.querySelector('.pack');
        const infoDiv = document.getElementById('info-div');
        const infoExit=document.getElementById('info-exit');

        // Lắng nghe sự kiện click vào div#fot
        fot.addEventListener('click', function () {
            // Ẩn div.pack-info và hiển thị div.pack-song
            packInfo.style.display = 'none';
            pack.style.display = 'flex';
            infoDiv.style.display = 'flex';

        });

        infoExit.addEventListener('click', function () {
            // Ẩn div.pack-info và hiển thị div.pack-song
            packInfo.style.display = 'block';
            pack.style.display = 'none';
            infoDiv.style.display = 'none';

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