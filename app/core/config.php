<?php
// Kiểm tra môi trường (localhost hoặc host)
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_ADDR'] === '127.0.0.1') {
    // Cấu hình cho môi trường localhost
    define('DB_HOST', 'localhost');    // Máy chủ cơ sở dữ liệu
    define('DB_NAME', 'letchill_data');  // Tên cơ sở dữ liệu
    define('DB_USER', 'root');         // Tên người dùng MySQL
    define('DB_PASS', '');             // Mật khẩu
} else {
    // Cấu hình cho môi trường host (server)
    define('DB_HOST', 'ftpupload.net');    // Máy chủ cơ sở dữ liệu 
    define('DB_NAME', 'if0_37866434_letchill_data');  // Tên cơ sở dữ liệu
    define('DB_USER', 'if0_37866434');  // Tên người dùng MySQL
    define('DB_PASS', 'wO2cKy7iKjYqc');  // Mật khẩu
}

// Các tùy chọn khác (nếu cần thiết)
define('DB_CHARSET', 'utf8'); // Đặt mã hóa ký tự
