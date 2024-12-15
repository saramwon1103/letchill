<?php
require_once '../core/functions.php'; 

header('Content-Type: application/json');

try {
    // Lấy tham số MaBaiHat từ GET
    $MaBaiHat = isset($_GET['MaBaiHat']) ? $_GET['MaBaiHat'] : null;

    if (!$MaBaiHat) {
        echo json_encode(['success' => false, 'message' => 'No song ID provided']);
        exit;
    }

    $query = "SELECT danhgia.DiemDG, danhgia.BinhLuan, nguoidung.TenNguoiDung
              FROM danhgia
              JOIN nguoidung ON danhgia.MaNguoiDung = nguoidung.MaNguoiDung
              WHERE danhgia.MaBaiHat = ?";
    $reviews = db_query($query, [$MaBaiHat]);

    if ($reviews === false) {
        throw new Exception('Lỗi khi truy vấn dữ liệu');
    }

    // Trả về kết quả dưới dạng JSON
    echo json_encode(['success' => true, 'reviews' => $reviews]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

