<?php
session_start();

// Khởi tạo thông báo lỗi trống
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['password'])) {
        // Lấy giá trị mật khẩu từ POST
        $password = trim($_POST['password']); // Loại bỏ khoảng trắng thừa

        // Kiểm tra mật khẩu có ít nhất 8 ký tự, có chứa ít nhất 1 số hoặc ký tự đặc biệt
        if (strlen($password) >= 8 && preg_match('/[0-9!@#$%^&*(),.?":{}|<>]/', $password)) {
            // Mã hóa mật khẩu (nếu cần lưu trữ hoặc sử dụng)
            $_SESSION['password'] = password_hash($password, PASSWORD_BCRYPT);

            // Chuyển hướng người dùng đến trang signup_infoView.php
            header("Location: signup_infoView.php");
            exit(); // Đảm bảo script dừng lại sau khi chuyển hướng
        } else {
            // Nếu mật khẩu không hợp lệ, hiển thị thông báo lỗi
            $errorMessage = "Mật khẩu phải có ít nhất 8 ký tự và chứa ít nhất 1 số hoặc ký tự đặc biệt (#, @, %, &,...)";
        }
    } else {
        // Nếu trường 'password' không tồn tại trong POST
        $errorMessage = "Không có dữ liệu password gửi lên.";
    }
}

// Tại đây, biến $errorMessage sẽ chỉ được thiết lập nếu có yêu cầu POST và xảy ra lỗi.
?>



<!DOCTYPE html>
<html lang = "en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/d1b353cfc4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/signup-password.css">
    <title>Màn hình đăng ký - Mật khẩu</title>
    <!-- <script type="javascript" src="script.js"></script> -->
    <style>
        /* cyrillic-ext */
        @font-face {
            font-family: 'Inter';
            font-style: italic;
            font-weight: 100 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/inter/v18/UcCm3FwrK3iLTcvnUwkT9nA2.woff2) format('woff2');
            unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
        }

        /* cyrillic */
        @font-face {
            font-family: 'Inter';
            font-style: italic;
            font-weight: 100 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/inter/v18/UcCm3FwrK3iLTcvnUwAT9nA2.woff2) format('woff2');
            unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
        }

        /* greek-ext */
        @font-face {
            font-family: 'Inter';
            font-style: italic;
            font-weight: 100 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/inter/v18/UcCm3FwrK3iLTcvnUwgT9nA2.woff2) format('woff2');
            unicode-range: U+1F00-1FFF;
        }

        /* greek */
        @font-face {
            font-family: 'Inter';
            font-style: italic;
            font-weight: 100 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/inter/v18/UcCm3FwrK3iLTcvnUwcT9nA2.woff2) format('woff2');
            unicode-range: U+0370-0377, U+037A-037F, U+0384-038A, U+038C, U+038E-03A1, U+03A3-03FF;
        }

        /* vietnamese */
        @font-face {
            font-family: 'Inter';
            font-style: italic;
            font-weight: 100 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/inter/v18/UcCm3FwrK3iLTcvnUwsT9nA2.woff2) format('woff2');
            unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
        }

        /* latin-ext */
        @font-face {
            font-family: 'Inter';
            font-style: italic;
            font-weight: 100 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/inter/v18/UcCm3FwrK3iLTcvnUwoT9nA2.woff2) format('woff2');
            unicode-range: U+0100-02AF, U+0304, U+0308, U+0329, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }

        /* latin */
        @font-face {
            font-family: 'Inter';
            font-style: italic;
            font-weight: 100 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/inter/v18/UcCm3FwrK3iLTcvnUwQT9g.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        /* cyrillic-ext */
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 100 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/inter/v18/UcCo3FwrK3iLTcvvYwYL8g.woff2) format('woff2');
            unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
        }

        /* cyrillic */
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 100 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/inter/v18/UcCo3FwrK3iLTcvmYwYL8g.woff2) format('woff2');
            unicode-range: U+0301, U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
        }

        /* greek-ext */
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 100 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/inter/v18/UcCo3FwrK3iLTcvuYwYL8g.woff2) format('woff2');
            unicode-range: U+1F00-1FFF;
        }

        /* greek */
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 100 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/inter/v18/UcCo3FwrK3iLTcvhYwYL8g.woff2) format('woff2');
            unicode-range: U+0370-0377, U+037A-037F, U+0384-038A, U+038C, U+038E-03A1, U+03A3-03FF;
        }

        /* vietnamese */
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 100 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/inter/v18/UcCo3FwrK3iLTcvtYwYL8g.woff2) format('woff2');
            unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
        }

        /* latin-ext */
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 100 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/inter/v18/UcCo3FwrK3iLTcvsYwYL8g.woff2) format('woff2');
            unicode-range: U+0100-02AF, U+0304, U+0308, U+0329, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }

        /* latin */
        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 100 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/inter/v18/UcCo3FwrK3iLTcviYwY.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
    </style>
</head>

<body>
    <!--Nút điều hướng-->
    <div class="navigation-buttons">
        <button class="left-button" onclick="goBack()">
            <img src="/web_nghe_nhac/public/assets/img/bx--caret-left-circle.svg" alt="icon_left" id="icon1">
        </button>
        <button class="right-button" onclick="goForward()">
            <img src="/web_nghe_nhac/public/assets/img/bx--caret-right-circle.svg" alt="icon_right" id="icon1">
        </button>
    </div>
    <!--Tiêu đề-->
    <div class="container-1">
        <h2 class="container-1-h2">
            <span class="white-text" style="margin-right: 10px">Đăng ký để bắt đầu</span>
            <span class="gradient-text">nghe nhạc</span>
        </h2>
    </div>
    <!--Form đăng ký-->
    <div class="container-2">
        <form action="signup_passwordView.php" method = "POST">
        <div class="container-2-top">
            <label for="password">Mật khẩu</label><br>
            <div class="input-container">
                <input type="password" id="password" name="password" placeholder="**********" style="margin-bottom:30px">
                <img src="/web_nghe_nhac/public/assets/img/fluent--eye-32-filled.svg" alt="icon" class="icon"
                onclick="togglePassword()" style="cursor: pointer;">;
            </div>
        </div>
        <div class="container-2-center">
            <label for="lưu-ý">Mật khẩu phải có ít nhất:</label>
            <p>1 số hoặc ký tự đặc biệt (#@%...)</p>
            <p>Nhiều hơn 8 ký tự </p>
        </div>
        <div class="container-2-bottom">
            <button type = "submit" class="next">Tiếp theo</button>
        </div>
        </form>
         <!--Hiển thị thông báo lỗi-->
         <?php if (isset($errorMessage)): ?>
            <p class = "error-message"><?php echo $errorMessage;?></p>
        <?php endif; ?>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const icon = document.querySelector(".icon");
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.src = "/web_nghe_nhac/public/assets/img/solar--eye-closed-bold.svg"; // Thay đổi icon khi hiện mật khẩu
            } else {
                passwordInput.type = "password";
                icon.src = "/web_nghe_nhac/public/assets/img/fluent--eye-32-filled.svg"; // Quay lại icon cũ khi ẩn mật khẩu
            }
        }

        // Hàm quay lại trang trước đó
        function goBack() {
            window.history.back(); // Quay lại trang trước đó trong lịch sử trình duyệt
        }

        // Hàm quay lại trang tiếp theo
        function goForward() {
            window.history.forward(); // Quay lại trang tiếp theo trong lịch sử trình duyệt
        }
    </script>
</body>
</html>