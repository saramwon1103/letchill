<?php
session_start();

// Kiểm tra nếu dữ liệu đã có trong session
if (isset($_SESSION['package_type'], $_SESSION['price'], $_SESSION['duration'], $_SESSION['description'], $_SESSION['user_id'])) {
    // Lấy thông tin từ session
    $time = $_SESSION['duration'];
    $price = $_SESSION['price'];
    $total = $_SESSION['price'];  // Giữ giá trị này nếu không có logic tính toán bổ sung
    $description = $_SESSION['description'];
    $pack = $_SESSION['package_type'];
    $user_id = $_SESSION['user_id'];

    // Lấy ngày bắt đầu là ngày hiện tại
    $start_date = date('Y-m-d');  // Đảm bảo ngày bắt đầu có định dạng 'Y-m-d'
    
    // Lấy mã gói và ngày kết thúc dựa trên loại gói
    $ma_goi = getPackageCode($pack); // Lấy mã gói tương ứng với package_type
    if ($ma_goi === 0) {
        echo "Gói không hợp lệ.";
        exit();
    }
    $end_date = calculateEndDate($pack, $start_date); // Tính ngày kết thúc

} else {
    // Nếu không có dữ liệu trong session hoặc người dùng chưa đăng nhập
    echo "Không có thông tin đơn hàng hoặc bạn chưa đăng nhập. Vui lòng quay lại và chọn gói.";
    exit();
}

// Kiểm tra nếu form đã được gửi qua POST và người dùng đã chọn phương thức thanh toán
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['payment_method'])) { // Đảm bảo kiểm tra đúng tên của radio button
        $payment_method = $_POST['payment_method']; // Lấy phương thức thanh toán

        // Kiểm tra phương thức thanh toán hợp lệ
        if (!in_array($payment_method, ['Momo', 'VNPay'])) {
            echo "Phương thức thanh toán không hợp lệ.";
            exit();
        }

        // Kết nối đến cơ sở dữ liệu để lưu phương thức thanh toán
        // Kết nối đến cơ sở dữ liệu
        include_once 'C:\xampp\htdocs\web_nghe_nhac\public\assets\php\config\config.php'; // Bao gồm file kết nối DB
        $database = new Database();
        $conn = $database->getConnection();
        try {
            $sql = "INSERT IGNORE INTO lichsumua (MaTaiKhoan, MaGoi, NgayBatDau, NgayKetThuc, PhuongThuc)
                    VALUES (:user_id, :pack_id, :start_date, :end_date, :payment_method)";
            $stmt = $conn->prepare($sql);

            // Liên kết các giá trị với câu lệnh SQL
            $stmt->bindParam(':user_id', $user_id); // Liên kết mã tài khoản
            $stmt->bindParam(':pack_id', $ma_goi); // Liên kết mã gói
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->bindParam(':payment_method', $payment_method); // Liên kết phương thức thanh toán

            // Thực thi câu lệnh
            $stmt->execute();

        } catch (PDOException $e) {
            echo "Lỗi khi cập nhật dữ liệu: " . $e->getMessage();
            exit();
        }

        // Điều hướng tới file tương ứng sau khi lưu phương thức thanh toán
        if ($payment_method === 'Momo') {
            header('Location: momo.php');
            exit();
        } elseif ($payment_method === 'VNPay') {
            header('Location: vnpay.php');
            exit();
        }
    } else {
        echo "Vui lòng chọn phương thức thanh toán.";
        exit();
    }
}


// Hàm tính toán ngày kết thúc dựa trên loại gói
function calculateEndDate($package_type, $start_date) {
    $start_timestamp = strtotime($start_date);

    switch ($package_type) {
        case 'Individual':
        case 'Student':
            $end_timestamp = strtotime("+1 month", $start_timestamp); // Gói "Individual" và "Student" kéo dài 1 tháng
            break;
        case 'Mini':
            $end_timestamp = strtotime("+1 week", $start_timestamp); // Gói "Mini" kéo dài 1 tuần
            break;
        default:
            $end_timestamp = $start_timestamp;
    }

    return date('Y-m-d', $end_timestamp);
}

// Hàm chuyển đổi package_type sang mã gói (MaGoi)
function getPackageCode($package_type) {
    switch ($package_type) {
        case 'Individual':
            return 2; // Mã gói cho "individual"
        case 'Student':
            return 3; // Mã gói cho "student"
        case 'Mini':
            return 1; // Mã gói cho "mini"
        default:
            return 0; // Trường hợp không xác định, có thể xử lý thêm tùy ý
    }
}
?>


<!DOCTYPE html>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/d1b353cfc4.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/payment.css">
    <title>Thanh toán</title>
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
    <div class="navigation-buttons" onclick="goBack()">
        <button class="left-button">
            <img src="/web_nghe_nhac/public/assets/img/bx--caret-left-circle.svg" alt="icon_left" id="icon1">
        </button>
    </div>
    
    <div class="container">
        <p>Gói của bạn</p>
        <div class="info">
            <div class="info-1">
                <div class="row">
                    <h3 class="pack">Premium <?php echo $pack; ?></h3>
                    <label class="time"><?php echo $time; ?></label>
                </div>
                <div class="row">
                    <label class="description"><?php echo $description; ?></label>
                    <label class="price"></label>
                </div>
            </div>
            <div class="info-2">
                <div class="row">
                    <label class="title">Tổng:</label>
                    <label for="total" id="total" class="total"><?php echo $total; ?></label>
                </div>
                <div class="row">
                    <label class="title">Bắt đầu từ:</label>
                    <label class="start-date"><?php echo $start_date; ?></label>
                </div>
            </div>
        </div>
        <form method="POST" action="payment.php">
        <div class="method">
            <div class="radio-item">
                <input type="radio" name="payment_method" id="method" value="Momo">
                <img src="/web_nghe_nhac/public/assets/img/arcticons--momo.svg" alt="icon" class="icon">
                <label>Momo</label>
            </div>
            <div class="radio-item">
                <input type="radio" name="payment_method" id="method" value="VNPay">
                <img src="/web_nghe_nhac/public/assets/img/arcticons--v-vnpay.svg" alt="icon" class="icon">
                <label>VNPay</label>
            </div>
        </div>
        <div class="Thanh-toan">
            <button type="submit" class="Thanhtoan">Thanh toán</button>
        </div>
    </form>
    </div>
    <script>
        // Hàm quay lại trang trước đó
        function goBack() {
            window.history.back(); // Quay lại trang trước đó trong lịch sử trình duyệt
        }
    </script>
</body>

</html>