<?php
// Import hàm kết nối database
include $_SERVER['DOCUMENT_ROOT'] . '/web_nghe_nhac/app/core/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ POST
    $val_button_follow = $_POST['val_button_follow'] ?? null;
    $MaNguoiDung = filter_input(INPUT_POST, 'MaNguoiDung', FILTER_SANITIZE_NUMBER_INT);
    $MaNgheSi = filter_input(INPUT_POST, 'MaNgheSi', FILTER_SANITIZE_NUMBER_INT);

    // Kiểm tra dữ liệu hợp lệ
    if (!$val_button_follow || !$MaNguoiDung || !$MaNgheSi) {
        echo json_encode([
            'success' => false,
            'message' => 'Dữ liệu không hợp lệ'
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    try {
        // Thực hiện hành động dựa vào giá trị nút
        if ($val_button_follow === 'Theo dõi') {
            $sql1 = "INSERT INTO THEODOI (MaNguoiDung, MaNgheSy) VALUES (:MaNguoiDung, :MaNgheSy)";
            $params1 = [':MaNguoiDung' => $MaNguoiDung, ':MaNgheSy' => $MaNgheSi];

            db_query($sql1, $params1);

            $sql2 = "SELECT COUNT(DISTINCT theodoi.MaNguoiDung) as numberFollower
            FROM theodoi
            WHERE theodoi.MaNgheSy = :MaNgheSi";
            $params2 = [':MaNgheSi' => $MaNgheSi];
            $result2 = db_query($sql2, $params2);

            // Kiểm tra kết quả truy vấn
            $numberFollower = "";

            if (
                $result2 && is_array($result2)
            ) {
                // In ra kết quả truy vấn để kiểm tra
                //var_dump($result2); // In ra dữ liệu nhận được từ cơ sở dữ liệu

                $numberFollower = $result2[0]['numberFollower'] ?? 0; // Lấy số người theo dõi
            } else {
                $numberFollower = 0; // Mặc định là 0 nếu không có kết quả
            }

            echo json_encode([
                'success' => true,
                'message' => 'Đã theo dõi',
                'numberFollower' => $numberFollower
            ], JSON_UNESCAPED_UNICODE);
        } else {
            $sql1 = "DELETE FROM theodoi WHERE MaNguoiDung = :MaNguoiDung AND MaNgheSy = :MaNgheSy";
            $params1 = [':MaNguoiDung' => $MaNguoiDung, ':MaNgheSy' => $MaNgheSi];
            db_query($sql1, $params1);


            $sql2 = "SELECT COUNT(DISTINCT theodoi.MaNguoiDung) as numberFollower
            FROM theodoi
            WHERE theodoi.MaNgheSy = :MaNgheSi";
            $params2 = [':MaNgheSi' => $MaNgheSi];
            $result2 = db_query($sql2, $params2);

            $numberFollower = "";
            // Kiểm tra kết quả truy vấn
            if (
                $result2 && is_array($result2)
            ) {
                $numberFollower = $result2[0]['numberFollower'] ?? 0; // Lấy số người theo dõi
            } else {
                $numberFollower = 0; // Mặc định là 0 nếu không có kết quả
            }
            // In ra kết quả truy vấn để kiểm tra
            //var_dump($result2); // In ra dữ liệu nhận được từ cơ sở dữ liệu

            echo json_encode([
                'success' => true,
                'message' => 'Đã hủy theo dõi',
                'numberFollower' => $numberFollower
            ], JSON_UNESCAPED_UNICODE);
        }
    } catch (Exception $e) {
        // Xử lý lỗi
        echo json_encode([
            'success' => false,
            'message' => 'Lỗi khi thực hiện truy vấn: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
    exit();
}
