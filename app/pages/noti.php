<?php
// Bật hiển thị lỗi nếu có
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Thiết lập tiêu đề Content-Type là application/json
header('Content-Type: application/json');

// Kết nối cơ sở dữ liệu
require_once '../core/functions.php';

// Truy vấn lấy thông báo từ cơ sở dữ liệu
$query = "SELECT MaThongBao, TieuDe, NoiDung, ThoiGian, TrangThai FROM thongbao ORDER BY TrangThai ASC"; 
$result = db_query($query); // Thực thi câu truy vấn

// Kiểm tra nếu có kết quả
if ($result) {
    // Mảng để chứa thông báo
    $notifications = [];
    
    // Lặp qua kết quả và tạo mảng thông báo
    foreach ($result as $row) {
        $notifications[] = [
            'MaThongBao' => $row['MaThongBao'],
            'TieuDe' => $row['TieuDe'],
            'NoiDung' => $row['NoiDung'],
            'ThoiGian' => $row['ThoiGian'],
            'TrangThai' => $row['TrangThai']
        ];
    }

    // Trả về dữ liệu dưới dạng JSON
    echo json_encode($notifications);
} else {
    // Nếu không có kết quả, trả về mảng trống
    echo json_encode([]);
}


// Xử lý cập nhật trạng thái thông báo khi có POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['MaThongBao'])) {
        $maThongBao = $_POST['MaThongBao'];

        // Cập nhật trạng thái thông báo
        $query = "UPDATE thongbao SET TrangThai = 1 WHERE MaThongBao = ?";

        $update_result = db_query($query, [$maThongBao]);

        if ($update_result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update notification']);
        }
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing MaThongBao']);
        exit;
    }
}
?>

