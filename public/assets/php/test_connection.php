<?php
include_once 'config/config.php';

$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "Kết nối cơ sở dữ liệu thành công!";
} else {
    echo "Không thể kết nối cơ sở dữ liệu.";
}
