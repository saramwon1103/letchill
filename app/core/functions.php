<?php
// Bao gồm tệp cấu hình chứa thông tin cơ sở dữ liệu
require_once 'config.php';

// Kết nối đến cơ sở dữ liệu
function db_connect()
{
    try {
        // DSN (Data Source Name) chứa thông tin kết nối
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

        // Tạo kết nối PDO
        $pdo = new PDO($dsn, DB_USER, DB_PASS);

        // Thiết lập chế độ báo lỗi của PDO
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    } catch (PDOException $e) {
        // Nếu xảy ra lỗi, thông báo và trả về null
        echo 'Kết nối thất bại: ' . $e->getMessage();
        return null;
    }
}

// Hàm thực hiện câu truy vấn SQL
function db_query($query, $params = [])
{
    $pdo = db_connect(); // Kết nối đến database
    if ($pdo === null) {
        return false; // Nếu không kết nối được, trả về false
    }

    try {
        // Chuẩn bị câu truy vấn
        $stmt = $pdo->prepare($query);

        // Thực thi câu truy vấn với tham số
        $stmt->execute($params);

        // Nếu câu lệnh SELECT, trả về kết quả
        if (strpos($query, 'SELECT') === 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Nếu không phải SELECT, trả về true
        return true;
    } catch (PDOException $e) {
        // Nếu có lỗi trong câu truy vấn
        echo 'Lỗi truy vấn: ' . $e->getMessage();
        return false;
    }
}

function AddImageSong($name_image_Song)
{
    $base_url = "../public/assets/img/data-songs-image"; // Đường dẫn gốc đến thư mục chứa ảnh
    return $base_url . $name_image_Song; // Trả về đường dẫn đầy đủ

}
