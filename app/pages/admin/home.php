<?php
require $_SERVER['DOCUMENT_ROOT'] . "/web_nghe_nhac/app/pages/admin/header.php";
?>
<style>
    .main{
    font-family: Arial, sans-serif;
    box-sizing: border-box;
    display: flex;
    justify-content: center;
    position: absolute;
    width: 68%;
    right: 26px;
    top: 118px;
    height:456px;
    color: #fff;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 20px 10px 10px 20px;
    }
</style>
<body>
    <img src="../../../public/assets/img/admin-bg.png" class="bgimage">
    <li class="left-li">
        <?php
            require $_SERVER['DOCUMENT_ROOT'] . "/web_nghe_nhac/app/pages/admin/left_side.php";
            ?>
    </li>
    <div class="main">
        <h1>Xin chào admin!</h1>
    </div>
    
    <div class="report" style="display:none; color: #fff;">
        <div class="searchBar">
            <!-- Chọn mốc thời gian -->
            <label class="label" for="date">Từ mốc</label>
            <input type="month" id="start-month" name="month-year">

            <label class="label" for="date">Đến mốc</label>
            <input type="month" id="end-month" name="month-year">


            <!-- Chọn gói dịch vụ -->
            <label class="label" for="package">Gói:</label>
            <select id="package" class="package">
                <option value="all">All</option>
                <option value="mini">Mini</option>
                <option value="individual">Individual</option>
                <option value="student">Student</option>
            </select>

            <button id="search-btn" class="search-btn">Tra cứu</button>
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
            <h3>Tổng doanh thu: <span id="total-revenue">0</span> VND</h3>
        </div>
    </div>
</body>
<!-- <script src="/web_nghe_nhac/public/assets/script/admin.js"></script> -->
<script src="/web_nghe_nhac/public/assets/script/report.js"></script>

</html>