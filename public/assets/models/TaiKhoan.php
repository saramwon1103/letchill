<?php
class TaiKhoan
{
    private $conn;

    public function __construct()
    {
        // Kết nối với cơ sở dữ liệu (sử dụng file config nếu có)
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Kiểm tra kết nối
        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }
    }

    public function checkLogin($email, $password)
    {
        // Chuẩn bị câu lệnh SQL để tránh SQL injection
        $stmt = $this->conn->prepare("SELECT * FROM TaiKhoan WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);

        // Thực thi và lấy kết quả
        $stmt->execute();
        $result = $stmt->get_result();

        // Kiểm tra kết quả
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();  // Trả về thông tin người dùng
        } else {
            return false;  // Đăng nhập thất bại
        }
    }

    public function __destruct()
    {
        // Đóng kết nối
        $this->conn->close();
    }
}
?>
