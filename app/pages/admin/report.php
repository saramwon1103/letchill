<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once '../../core/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/assets/css/report.css">
    <link rel="stylesheet" href="../../../public/assets/css/admin_left_side.css">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <title>Quản lý báo cáo</title>
</head>

<body>
    <!-- header -->
    <?php
    include 'header.php';
    ?>
    <img src="../../../public/assets/img/admin-bg.png" alt="" class="bgimage">

    <main>
        <!-- Định dạng nền của trang -->
        <div class="mainLight"></div>

        <!-- Màn hình chính -->
        <div class="main-Space">
            <!-- Chèn menu trái -->
            <?php
            include_once 'left_side.php';
            ?>

            <!-- Phần nội dung chính -->
            <div class="report" style="color: #fff;">
        <div class="searchBar">
            <!-- Chọn mốc thời gian -->
             <div class="date-range">
                <label class="label" for="start-month">Từ mốc</label>
                <input type="month" id="start-month" name="month-year">

                <label class="label" for="end-month">Đến mốc</label>
                <input type="month" id="end-month" name="month-year">
             </div>

            <!-- Chọn gói dịch vụ -->
            <div class="select-package">
                <label class="label" for="package">Gói:</label>
                <select id="package" class="package">
                    <option value="all">All</option>
                    <option value="mini">Mini</option>
                    <option value="individual">Individual</option>
                    <option value="student">Student</option>
            </select>
            <button id="search-btn" class="search-btn">Tra cứu</button>

            </div>

        </div>
        <button id="export-btn" class="export-btn">Xuất</button>

        <div class="result-table">
            <table id="result">
                <thead>
                    <tr>
                        <th>Tên gói</th>
                        <th>Mốc thời gian</th>
                        <th>Số người đăng ký</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dữ liệu sẽ được AJAX thêm vào đây -->
                </tbody>
            </table>
        </div>
        <div class="total">
            <h3>Tổng doanh thu: <span id="total-revenue">0</span></h3>
        </div>
    </div>
        </div>
    </main>
</body>

</html>

<script src="/web_nghe_nhac/public/assets/script/report.js"></script>
