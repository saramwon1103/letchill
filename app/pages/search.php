<?php
require_once '../core/functions.php';

// Lấy dữ liệu từ form tìm kiếm
if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];

    // Truy vấn bài hát với từ khóa
    $query_song = "SELECT MaBaiHat, TenBaiHat, AnhBaiHat FROM baihat WHERE TenBaiHat LIKE :keyword";
    $songs = db_query($query_song, ['keyword' => "%$keyword%"]);

    // Truy vấn nghệ sĩ với từ khóa
    $query_artist = "SELECT MaNgheSy, TenNgheSy, AnhNgheSy FROM nghesy WHERE TenNgheSy LIKE :keyword";
    $artists = db_query($query_artist, ['keyword' => "%$keyword%"]);

    // Kiểm tra và hiển thị kết quả
    $results = ['songs' => $songs, 'artists' => $artists];
    echo json_encode($results);
}
?>
