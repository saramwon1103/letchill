<?php

include './../core/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT taikhoan.MaTaiKhoan, nguoidung.TenNguoiDung, goidichvu.TenGoi, lichsumua.NgayBatDau, lichsumua.NgayKetThuc
            from taikhoan join nguoidung on taikhoan.MaNguoiDung = nguoidung.MaNguoiDung
            join lichsumua on lichsumua.MaTaiKhoan = taikhoan.MaTaiKhoan
            join goidichvu on goidichvu.MaGoi = lichsumua.MaGoi";

    $data = db_query($sql);
    echo json_encode($data);
}
