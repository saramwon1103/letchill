<?php
include '../../core/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Kiểm tra và chuyển định dạng ngày từ dd/mm/yyyy sang yyyy-mm-dd
    $datestart = strtotime($_POST['datestart']) ? date('Y-m-d', strtotime($_POST['datestart'])) : null;
    $datefinish = strtotime($_POST['datefinish']) ? date('Y-m-d', strtotime($_POST['datefinish'])) : null;

    // Kiểm tra nếu ngày không hợp lệ
    if ($datestart == null || $datefinish == null) {
        echo "Ngày không hợp lệ!";
        exit;
    }

    // Lấy Mã tài khoản ~ Mã người dùng
    $sql1 = "SELECT nguoidung.MaNguoiDung
             FROM nguoidung
             WHERE nguoidung.TenNguoiDung = :username";
    $params1 = [':username' => $_POST['username']];
    $result1 = db_query($sql1, $params1);

    $MaNguoiDung = ($result1 && count($result1) > 0) ? $result1[0]['MaNguoiDung'] : null;

    // Lấy Mã gói của tên gói
    $sql2 = "SELECT goidichvu.MaGoi
             FROM goidichvu
             WHERE goidichvu.TenGoi= :pakage";
    $params2 = [':pakage' => $_POST['pakage']];
    $result2 = db_query($sql2, $params2);

    $MaGoi = ($result2 && count($result2) > 0) ? $result2[0]['MaGoi'] : null;

    // Insert dữ liệu
    if ($MaNguoiDung != null && $MaGoi != null) {
        $sql3 = "INSERT INTO lichsumua (MaGoi, MaTaiKhoan, NgayBatDau, NgayKetThuc)
                 VALUES (:magoi, :manguoidung, :ngaybatdau, :ngayketthuc)";
        $params3 = [
            ':magoi' => $MaGoi,
            ':manguoidung' => $MaNguoiDung,
            ':ngaybatdau' => $datestart,
            ':ngayketthuc' => $datefinish
        ];

        if (db_query($sql3, $params3)) {
            // Sau khi thêm thành công
            echo 'success';
        } else {
            echo 'error';
        }
    }
}
