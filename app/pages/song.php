<?php
require_once '../core/functions.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kiểm tra nếu có tham số id
$id = $_GET['id'] ?? null;

if ($id) {
    // Truy vấn lấy bài hát theo id
    $query = "SELECT b.*, n.TenNgheSy, n.MaNgheSy, n.AnhNgheSy 
              FROM baihat b 
              JOIN nghesy n ON b.MaNgheSy = n.MaNgheSy 
              WHERE b.MaBaiHat = :id";
    $params = [':id' => $id];
    $result = db_query($query, $params);

    echo json_encode(['results' => $result ?: []]);
} else {
    // Truy vấn lấy toàn bộ bài hát
    $query = "SELECT b.*, n.TenNgheSy, n.MaNgheSy, n.AnhNgheSy 
              FROM baihat b 
              JOIN nghesy n ON b.MaNgheSy = n.MaNgheSy 
              ORDER BY b.MaBaiHat";
    $result = db_query($query);

    echo json_encode(['results' => $result ?: []]);
}
?>
