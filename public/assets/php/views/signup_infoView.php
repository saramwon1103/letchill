<?php
session_start();
$email = $_SESSION['email'];
$password = $_SESSION['password'];

// Kết nối đến cơ sở dữ liệu thông qua PDO
include 'C:\xampp\htdocs\web_nghe_nhac\public\assets\php\config\config.php';  // Bao gồm file cấu hình

try {
    // Khởi tạo kết nối
    $database = new Database();
    $conn = $database->getConnection();

    // Kiểm tra nếu người dùng đã gửi form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Lấy dữ liệu từ form và kiểm tra
        $ho_ten = trim($_POST['ho_ten']);
        $date_of_birth = $_POST['date'];
        $gender = $_POST['gender'];

        // Kiểm tra xem tất cả các trường có được điền đầy đủ hay không
        if (empty($ho_ten) || empty($date_of_birth) || empty($gender)) {
            $errorMessage = "Vui lòng điền đầy đủ thông tin!";
        } else {
            // Bắt đầu một giao dịch
            $conn->beginTransaction();

            // Thêm người dùng vào bảng `nguoidung`
            $sqlNguoiDung = "INSERT INTO `nguoidung` (`TenNguoiDung`, `NgaySinh`, `GioiTinh`, `Email`, `MatKhau`) 
                             VALUES (?, ?, ?, ?, ?)";
            $stmtNguoiDung = $conn->prepare($sqlNguoiDung);
            $stmtNguoiDung->bindParam(1, $ho_ten);
            $stmtNguoiDung->bindParam(2, $date_of_birth);
            $stmtNguoiDung->bindParam(3, $gender);
            $stmtNguoiDung->bindParam(4, $email);
            $stmtNguoiDung->bindParam(5, $password);

            if ($stmtNguoiDung->execute()) {
                // Lấy MaNguoiDung vừa tạo
                $maNguoiDung = $conn->lastInsertId();

                // Thêm tài khoản vào bảng `taikhoan`
                $sqlTaiKhoan = "INSERT INTO `taikhoan` (`MaTaiKhoan`, `Email`, `MatKhau`, `MaNguoiDung`) 
                                VALUES (NULL, ?, ?, ?)";
                $stmtTaiKhoan = $conn->prepare($sqlTaiKhoan);
                $stmtTaiKhoan->bindParam(1, $email);
                $stmtTaiKhoan->bindParam(2, $password);  // Giả sử password đã được mã hóa (hash)
                $stmtTaiKhoan->bindParam(3, $maNguoiDung);

                if ($stmtTaiKhoan->execute()) {
                    // Xác nhận giao dịch
                    $conn->commit();
                    echo "Đăng ký thành công!";
                    header("Location: signinView.php");
                    exit();
                } else {
                    // Hủy giao dịch nếu có lỗi khi thêm vào bảng `taikhoan`
                    $conn->rollBack();
                    $errorMessage = "Lỗi khi thêm tài khoản: " . $stmtTaiKhoan->errorInfo()[2];
                }
            } else {
                // Hủy giao dịch nếu có lỗi khi thêm vào bảng `nguoidung`
                $conn->rollBack();
                $errorMessage = "Lỗi khi thêm người dùng: " . $stmtNguoiDung->errorInfo()[2];
            }
        }
    }
} catch (PDOException $e) {
    // Hiển thị lỗi nếu có vấn đề trong quá trình kết nối hoặc truy vấn
    $errorMessage = "Lỗi kết nối hoặc truy vấn: " . $e->getMessage();
} finally {
    // Đóng kết nối
    $conn = null;
}
?>


<!DOCTYPE html>
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/d1b353cfc4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/signup-info.css">
    <title>Màn hình đăng ký - thông tin</title>
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
    <form method="POST" action="signup_infoView.php">
        <div class="container-2-top">
            <!--Họ và tên-->
            <label for="ho_ten">Họ và tên</label><br>
            <input type="text" id="ho_ten" name="ho_ten" style="margin-bottom:30px">
            <!--Ngày tháng năm sinh-->
            <label for="date">Ngày tháng năm sinh</label><br>
            <input type="date" id="date" name="date" placeholder="dd/MM/yyyy">
            <!--Giới tính-->
            <label for="gender" style="margin-top: 30px; margin-bottom: 20px;">Giới tính</label>
            <div class="container-2-center">
                <div>
                    <input type="radio" id="male" name="gender" value="Nam">
                    <label for="male">Nam</label>
                </div>
                <div>
                    <input type="radio" id="female" name="gender" value="Nu">
                    <label for="female">Nữ</label>
                </div>
                <div>
                    <input type="radio" id="other" name="gender" value="other">
                    <label for="other">Không tiết lộ</label>
                </div>
            </div>
        </div>
        <div class="container-2-bottom">
            <p>Với việc ấn Đăng ký, bạn đã đồng ý với mọi Điều khoản và Điều kiện sử dụng của chúng tôi</p>
            <button class="sign-up">Đăng ký</button>
        </div>
        <!--Hiển thị thông báo lỗi-->
        <?php if (isset($errorMessage)): ?>
            <p class = "error-message"><?php echo $errorMessage;?></p>
        <?php endif; ?>
    </form>
    </div>
    <script>
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



