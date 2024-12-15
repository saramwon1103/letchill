<?php
session_start();

// Bật hiển thị lỗi khi phát triển
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra dữ liệu đầu vào
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Kết nối cơ sở dữ liệu
        include '\xampp\htdocs\web_nghe_nhac\public\assets\php\config\config.php'; 
        $database = new Database();
        $conn = $database->getConnection();

        if ($conn) {
            // Truy vấn kiểm tra email và mật khẩu
            $sql = "SELECT * FROM taikhoan WHERE Email = :email AND MatKhau = :password";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
            
            // Lấy dữ liệu người dùng
            $user = $stmt->fetch();
            if ($user) {
                // Lưu thông tin người dùng vào session
                $_SESSION['user'] = $user;
                $_SESSION['user_id'] = $user['MaTaiKhoan'];
                $_SESSION['email'] = $email;

                // Kiểm tra quyền và chuyển hướng
                if ($user['Quyen'] === 'Admin') {
                    header("Location: /web_nghe_nhac/app/pages/admin/home.php");
                } elseif ($user['Quyen'] === 'User') {
                    header("Location: /web_nghe_nhac/app/pages/home.php");
                } else {
                    $errorMessage = "Quyền không hợp lệ.";
                }
                exit();
            } else {
                $errorMessage = "Email hoặc mật khẩu không đúng.";
            }
        } else {
            $errorMessage = "Không thể kết nối cơ sở dữ liệu.";
        }
    } else {
        $errorMessage = "Vui lòng nhập đầy đủ thông tin.";
    }
}
?>


<!DOCTYPE html>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/d1b353cfc4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/signin.css">
    <title>Đăng nhập</title>
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
            <span class="white-text" style="margin-right: 10px">Đăng nhập vào</span>
            <span class="gradient-text">letchill</span>
        </h2>
    </div>
    <!--Form đăng nhập-->
    <div class="container-2">
        <form action="" method="POST">
            <div class="container-2-top">
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" placeholder="name@domain.com"
                    style="margin-bottom:30px"><br>
                <label for="password">Mật khẩu</label><br>
                <div class="input-container">
                    <input type="password" id="password" name="password" placeholder="**********">
                    <img src="/web_nghe_nhac/public/assets/img/fluent--eye-32-filled.svg" alt="iconPass"
                        class="iconPass" onclick="togglePassword()" style="cursor: pointer;">
                </div>
                <div class="forget-password-container">
                    <!-- Thêm phần tử cha -->
                    <a href="resetPassword.php" class="forget-password">Quên mật khẩu?</a>
                </div>
                <button type="submit" class="sign-in">Đăng nhập</button>
            </div>
        </form>
        <!--Hiển thị thông báo lỗi-->
        <?php if (isset($errorMessage)): ?>
        <p class="error-message"><?php echo $errorMessage;?></p>
        <?php endif; ?>
        <div class="container-2-center">
            <div class="divider">
                <hr class="custom-line-1" style="margin-right: 30px">
                <span class="hoac-text">Hoặc</span>
                <hr class="custom-line-1" style="margin-left: 30px">
            </div>
            <!--Nút social đăng nhập-->
            <button class="social-button google">
                <img src="/web_nghe_nhac/public/assets/img/logos--google-icon.svg" alt="icon" class="icon">
                Đăng nhập với Google
            </button>
            <button class="social-button facebook">
                <img src="/web_nghe_nhac/public/assets/img/logos--facebook.svg" alt="icon" class="icon">
                Đăng nhập với Facebook
            </button>
        </div>
        <!--Nút link đăng ký-->
        <div class="container-2-bottom">
            <hr class="custom-line">
            <span class="normal-text" style="margin-right: 5px">Chưa có tài khoản?</span>
            <a href="signup_emailView.php" class="sign-up"><u>Đăng ký ngay</u></a>
        </div>
    </div>

    <script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const icon = document.querySelector(".iconPass");

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