<?php
include_once '/xampp/htdocs/web_nghe_nhac/public/assets/models/song_manage_model.php';

class SongManageController {
    private $songManageModel;

    public function __construct($db) {
        $this->songManageModel = new SongManageModel($db);
    }

    public function searchSongs($query) {
        return $this->songManageModel->searchSongs($query);
    }

    public function getAllSongs() {
        return $this->songManageModel->getAllSongs();
    }

    public function deleteSongsByIds($songIds) {
        return $this->songManageModel->deleteSongsByIds($songIds);
    }

    public function getSongById($songId) {
        return $this->songManageModel->getSongById($songId);
    }

    public function getMaNgheSy($tenNgheSy) {
        return $this->songManageModel->getMaNgheSy($tenNgheSy);
    }

    public function updateSong($MaBaiHat, $TenBaiHat, $LoiBaiHat, $MaTheLoai, $MaNgheSy) {
        return $this->songManageModel->updateSong($MaBaiHat, $TenBaiHat, $LoiBaiHat, $MaTheLoai, $MaNgheSy);
    }
    
}

if (isset($_GET['action']) && $_GET['action'] === 'search' && isset($_GET['tuKhoa'])) {
    $tuKhoa = $_GET['tuKhoa'];
    $database = new Database();
    $db = $database->getConnection();
    $controller = new SongManageController($db);

    $songs = $controller->searchSongs($tuKhoa); // Phương thức trả về mảng bài hát

    echo json_encode($songs);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'getAll') {
    $database = new Database();
    $db = $database->getConnection();
    $controller = new SongManageController($db);

    $songs = $controller->getAllSongs(); // Phương thức trả về tất cả mảng bài hát

    echo json_encode($songs);
    exit;
}

//Hàm thêm bài hát
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'addSong') {
    header('Content-Type: application/json; charset=utf-8');
    // Lấy thông tin từ form
    $tenBaiHat = $_POST['name-bh'] ?? '';
    $tenNgheSy = $_POST['name-artist'] ?? '';
    $maTheLoai = $_POST['theloai'] ?? '';
    $loiBaiHat = $_POST['lyrics'] ?? '';

    // Xử lý file nhạc
    $fileBaiHat = $_FILES['upload-bai-hat'] ?? '';
    $anhBaiHat = $_FILES['file-upload'] ?? '';

    // Lấy tên ảnh bài hát upload nếu có
    $imageFileName = null; // Chỉ lưu tên file
    if ($anhBaiHat && $anhBaiHat['error'] === 0) {
        $imageFileName = basename($anhBaiHat['name']);
    }

    // Lấy tên file bài hát upload nếu có
    $songFileName = null; // Chỉ lưu tên file
    if ($fileBaiHat && $fileBaiHat['error'] === 0) {
        $songFileName = basename($fileBaiHat['name']);
    }

    // Gọi model để xử lý
    $database = new Database();
    $db = $database->getConnection();
    $model = new SongManageModel($db);
    $maNgheSy = $model->getMaNgheSy($tenNgheSy);

    if (!$maNgheSy) {
        echo json_encode(['success' => false, 'message' => 'Nghệ sỹ không tồn tại.']);
        exit;
    }
    $result = $model->addSong(
        $tenBaiHat,
        $songFileName,
        $imageFileName,
        $loiBaiHat,
        $maTheLoai,
        $maNgheSy
    );

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Thêm bài hát thành công!']);

        $imageUploadDir = realpath(__DIR__ . '/../../img/data-songs-image') ?: __DIR__ . '/../../img/data-songs-image';
        $imagePathUpload = $imageUploadDir . '/' . $imageFileName;
        if (!move_uploaded_file($anhBaiHat['tmp_name'], $imagePathUpload)) {
            echo json_encode(["success" => false, "message" => "Không thể lưu file ảnh upload."]);
            exit;
        }

        $songUploadDir = realpath(__DIR__ . '/../../../song') ?: __DIR__ . '/../../../song';
        $songPathUpload = $songUploadDir . '/' . $songFileName;
        if (!move_uploaded_file($fileBaiHat['tmp_name'], $songPathUpload)) {
            echo json_encode(["success" => false, "message" => "Không thể lưu file nhạc upload."]);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Thêm bài hát thất bại.']);
    }
}

//Hàm xóa bài hát
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deleteSongs') {
    header('Content-Type: application/json; charset=utf-8');

    // Lấy danh sách mã bài hát từ `$_POST`
    /*$songIds = $_POST['songIds'] ?? [];*/

    $songIds = isset($_POST['songIds']) ? json_decode($_POST['songIds'], true) : [];

    if (!is_array($songIds)) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
        exit;
    }

    if (empty($songIds)) {
        echo json_encode(['success' => false, 'message' => 'Không có bài hát nào được chọn.']);
        exit;
    }

    // Kết nối cơ sở dữ liệu
    $database = new Database();
    $db = $database->getConnection();
    $model = new SongManageModel($db);

    // Lấy thông tin file của các bài hát
    $files = $model->getFilesBySongIds($songIds);

    if (empty($files)) {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin bài hát.']);
        exit;
    }

    $imageUploadDir = realpath(__DIR__ . '/../../img/data-songs-image') ?: __DIR__ . '/../../img/data-songs-image';
    $songUploadDir = realpath(__DIR__ . '/../../../song') ?: __DIR__ . '/../../../song';

    // Xóa file ảnh và file nhạc
    foreach ($files as $file) {
        $imageFilePath = $imageUploadDir . '/' . $file['AnhBaiHat'];
        $songFilePath = $songUploadDir . '/' . $file['FileBaiHat'];

        // Xóa file ảnh nếu tồn tại
        if (!empty($file['AnhBaiHat']) && file_exists($imageFilePath)) {
            unlink($imageFilePath);
        }

        // Xóa file nhạc nếu tồn tại
        if (!empty($file['FileBaiHat']) && file_exists($songFilePath)) {
            unlink($songFilePath);
        }
    }

    // Xóa các đánh giá liên quan đến mã bài hát (tránh lỗi khóa ngoại)
    $deleteReviewsResult = $model->deleteReviewsBySongIds($songIds);

    //Thực hiện xóa bài hát
    $result = $model->deleteSongsByIds($songIds);


    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Xóa bài hát thành công!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Xóa bài hát thất bại.']);
    }
    exit;
}

// Lấy thông tin bài hát theo ID
if (isset($_GET['action']) && $_GET['action'] === 'getSongById' && isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();
    $controller = new SongManageController($db);
    
    $songId = intval($_GET['id']); // Đảm bảo id là số nguyên
    $song = $controller->getSongById($songId); // Hàm mới trong Controller
    echo json_encode($song);
    exit;
}

//Sửa bài hát
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateSong') {
    // Lấy dữ liệu từ form
    $maBaiHat = $_POST['maBaiHat'] ?? null; // ID bài hát
    $tenBaiHat = $_POST['tenBaiHat'] ?? '';
    $loiBaiHat = $_POST['loiBaiHat'] ?? '';
    $maTheLoai = $_POST['theloai'] ?? null; // Mã thể loại
    $tenNgheSy = $_POST['tenNgheSy'] ?? ''; // Tên nghệ sỹ

    // Kiểm tra các giá trị cần thiết và in ra giá trị nào bị thiếu
    $missingFields = [];
    if (!$maBaiHat) $missingFields[] = 'maBaiHat';
    if (!$tenBaiHat) $missingFields[] = 'tenBaiHat';
    if (!$maTheLoai) $missingFields[] = 'maTheLoai';
    if (!$tenNgheSy) $missingFields[] = 'tenNgheSy';

    if (!empty($missingFields)) {
        echo json_encode([
            'success' => false,
            'message' => 'Thiếu thông tin cần thiết: ' . implode(', ', $missingFields),
            'missing_fields' => $missingFields // Trả về danh sách các trường thiếu nếu cần
        ]);
        exit;
    }

    try {
        $database = new Database();
        $db = $database->getConnection();
        $controller = new SongManageController($db);
        // Lấy mã nghệ sỹ từ tên nghệ sỹ
        $maNgheSy = $controller->getMaNgheSy($tenNgheSy);

        if (!$maNgheSy) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy mã nghệ sỹ!']);
            exit;
        }
    
        // Gọi hàm cập nhật bài hát
        $result = $controller->updateSong($maBaiHat, $tenBaiHat, $loiBaiHat, $maTheLoai, $maNgheSy);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Cập nhật bài hát thành công!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cập nhật bài hát thất bại!']);
        }
    } catch (Exception $e) {
        error_log("Lỗi: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra trong quá trình xử lý!']);
    }
}
?>