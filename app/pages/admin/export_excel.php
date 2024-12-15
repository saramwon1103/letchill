<?php
require_once '../../core/functions.php'; // Hàm db_query nằm trong file này
require_once '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Tắt output buffer để tránh dữ liệu không mong muốn
if (ob_get_length()) {
    ob_end_clean();
}

// Đặt headers cho file Excel
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"report.xlsx\"");
header("Cache-Control: max-age=0");

// Lấy dữ liệu từ cơ sở dữ liệu
$startMonth = $_GET['start_month'] ?? null;
$endMonth = $_GET['end_month'] ?? null;
$package = $_GET['package'] ?? 'all';

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

$result = db_query($query, $params);

if (empty($result)) {
    die("Không có dữ liệu để xuất.");
}

// Tạo đối tượng Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Đặt tiêu đề cột
$sheet->setCellValue('A1', 'Tên gói')
      ->setCellValue('B1', 'Mốc thời gian')
      ->setCellValue('C1', 'Số người đăng ký')
      ->setCellValue('D1', 'Doanh thu');

// Đổ dữ liệu vào bảng
$rowIndex = 2;
foreach ($result as $row) {
    $sheet->setCellValue("A$rowIndex", $row['package_name'])
          ->setCellValue("B$rowIndex", $row['time_period'])
          ->setCellValue("C$rowIndex", $row['subscribers'])
          ->setCellValue("D$rowIndex", $row['revenue']);
    $rowIndex++;
}

// Ghi file Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
