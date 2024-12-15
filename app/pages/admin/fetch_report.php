<?php
require_once '../../core/functions.php';

header('Content-Type: application/json');
// Đọc dữ liệu JSON từ body
$data = json_decode(file_get_contents("php://input"), true);

// Lấy giá trị từ JSON
$startMonth = $data['start_month'] ?? null;
$endMonth = $data['end_month'] ?? null;
$package = $data['package'] ?? 'all';

// Điều kiện lọc truy vấn
$whereClauses = [];
$params = [];

if ($startMonth) {
    $whereClauses[] = "DATE_FORMAT(ls.ngaybatdau, '%Y-%m') >= :start_month";
    $params[':start_month'] = $startMonth;
}

if ($endMonth) {
    $whereClauses[] = "DATE_FORMAT(ls.ngaybatdau, '%Y-%m') <= :end_month";
    $params[':end_month'] = $endMonth;
}

if ($package !== 'all') {
    $whereClauses[] = "g.tengoi = :package";
    $params[':package'] = $package;
}

$whereSQL = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$query = "SELECT g.tengoi AS package_name, 
           DATE_FORMAT(ls.ngaybatdau, '%M %Y') AS time_period, 
           COUNT(DISTINCT ls.mataikhoan) AS subscribers, 
           SUM(g.gia) AS revenue 
    FROM lichsumua ls 
    JOIN goidichvu g ON ls.magoi = g.magoi 
    $whereSQL
    GROUP BY g.tengoi, DATE_FORMAT(ls.ngaybatdau, '%M %Y');
";

// Thực hiện truy vấn
$result = db_query($query, $params);

// Kiểm tra lỗi truy vấn
if ($result === false) {
    echo json_encode(['error' => 'Lỗi truy vấn cơ sở dữ liệu.']);
    exit;
}

// Nếu không có dữ liệu
if (empty($result)) {
    echo json_encode(['data' => [], 'total_revenue' => 0]);
    exit;
}

// Tính tổng doanh thu
$totalRevenue = array_sum(array_column($result, 'revenue'));

$response = [
    'data' => $result, 
    'total_revenue' => $totalRevenue,
];

// Chuyển đổi dữ liệu sang JSON và gửi về cho client
echo json_encode($response);
?>
