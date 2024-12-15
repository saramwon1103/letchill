<?php
include '../../core/functions.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_POST['TenGoi']) || !isset($_POST['MaTaiKhoan'])) {
        echo "Thiếu dữ liệu đầu vào.";
        exit();
    }

    // Lấy mã gói từ tên gói
    $sql1 = "SELECT goidichvu.MaGoi FROM goidichvu WHERE goidichvu.TenGoi = :TenGoi";
    $params1 = [':TenGoi' => $_POST['TenGoi']];
    $result1 = db_query($sql1, $params1);

    if ($result1 === false || count($result1) === 0) {
        echo "Gói không tồn tại.";
        exit();
    }

    $MaGoi = $result1[0]['MaGoi'];

    // Xóa trong bảng lịch sử mua gói
    $sql = "DELETE FROM lichsumua WHERE MaTaiKhoan = :MaTaiKhoan AND MaGoi = :MaGoi";
    $params = [':MaTaiKhoan' => $_POST['MaTaiKhoan'], ':MaGoi' => $MaGoi];
    $affectedRows = db_query($sql, $params);
}
