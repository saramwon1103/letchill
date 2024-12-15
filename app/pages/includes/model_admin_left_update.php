<?php
include '../../core/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Kiểm tra và chuyển định dạng ngày từ dd/mm/yyyy sang yyyy-mm-dd
    $datestart = strtotime($_POST['datestart']) ? date('Y-m-d', strtotime($_POST['datestart'])) : null;
    $datefinish = strtotime($_POST['datefinish']) ? date('Y-m-d', strtotime($_POST['datefinish'])) : null;

    // Kiểm tra nếu ngày không hợp lệ
    if ($datestart == null || $datefinish == null) {
        echo "Ngày không hợp lệ!";
        echo $datestart;
        exit;
    }

    $MaNguoiDung = $_POST['MaNguoiDung'];
    // Lấy Mã gói của tên gói
    $sql2 = "SELECT goidichvu.MaGoi
             FROM goidichvu
             WHERE goidichvu.TenGoi= :pakage";
    $params2 = [':pakage' => $_POST['pakage']];
    $result2 = db_query($sql2, $params2);

    $MaGoi = ($result2 && count($result2) > 0) ? $result2[0]['MaGoi'] : null;

    // Insert dữ liệu
    if ($MaNguoiDung != null && $MaGoi != null) {
        $sql3 = "UPDATE lichsumua
          SET /*MaGoi = :magoi,*/ NgayBatDau = :ngaybatdau, NgayKetThuc = :ngayketthuc
          WHERE MaTaiKhoan = :manguoidung and MaGoi = :magoi ";

        // Lưu các giá trị vào tham số
        $params3 = [
            ':magoi' => $MaGoi,
            ':manguoidung' => $MaNguoiDung,
            ':ngaybatdau' => $datestart,
            ':ngayketthuc' => $datefinish
        ];

        if (db_query($sql3, $params3)) {
            // Sau khi thêm thành công
            echo $datestart;
        } else {
            echo 'error';
        }
    }
}
