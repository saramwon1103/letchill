<?php
// Thông tin kết nối
$servername = "localhost";
$username = "root";
$password = ""; // Để trống nếu bạn đang dùng XAMPP và chưa đặt mật khẩu
$database = "letchill_data"; // Thay bằng tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
echo "Kết nối thành công";

// Truy vấn dữ liệu từ bảng
$sql = "SELECT * FROM baihat"; // Thay 'tentable' bằng tên bảng của bạn
$result = $conn->query($sql);

// Kiểm tra và hiển thị dữ liệu
if ($result->num_rows > 0) {
    // Lặp qua từng hàng dữ liệu và hiển thị
    while($row = $result->fetch_assoc()) {
       // echo "ID: " . $row["id"] . " - Tên: " . $row["name"] . "<br>"; // Thay 'id' và 'name' bằng tên cột của bạn
    }
} else {
    echo "Không có dữ liệu";
}

// Đóng kết nối
$conn->close();
?>