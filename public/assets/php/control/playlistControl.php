<?php
include_once '/xampp/htdocs/web_nghe_nhac/public/assets/php/config/config.php';
include_once '/xampp/htdocs/web_nghe_nhac/public/assets/models/playlistModel.php';

class PlaylistController {
    private $playlistModel;

    public function __construct($db) {
        $this->playlistModel = new PlaylistModel($db);
    }

    public function showAllPlaylists() {
        return $this->playlistModel->getAllPlaylists();
    }

    // Lấy thông tin chi tiết danh sách phát
    public function getPlaylistDetails($maDSP) {
        $songCount = $this->playlistModel->countSongsInPlaylist($maDSP);
        return $songCount;
    }

    public function getDefaultPlaylist() {
        // Lấy thông tin playlist mặc định (Playlist 1)
        return $this->playlistModel->getPlaylistById(1); // 1 là mã DSP mặc định
    }

    public function getPlaylistById($playlistId) {
        return $this->playlistModel->getPlaylistById($playlistId);
    }

    // Hàm lấy bài hát theo playlist
    public function getSongsByPlaylist($maDSP) {
        return $this->playlistModel->getSongsFromPlaylist($maDSP);
    }

    public function addPlaylist($tenDSP, $moTa) {
        return $this->playlistModel->addPlaylist($tenDSP, $moTa);
    }

    public function getMaxPlaylistId() {
        return $this->playlistModel->getMaxPlaylistId();
    }

    public function findSongByName($songName) {
        return $this->playlistModel->findSongByName($songName);
    }

}

// Xử lý click danh sách phát từ người dùng và hiện ảnh, thông tin
if (isset($_GET['action']) && $_GET['action'] === 'count' && isset($_GET['maDSP'])) {
    $maDSP = $_GET['maDSP'];
    $database = new Database();
    $db = $database->getConnection();
    $controller = new PlaylistController($db);

    echo $controller->getPlaylistDetails($maDSP);
    exit;
}

//Hiển thị toàn bộ danh sách phát
if (isset($_GET['action']) && $_GET['action'] === 'view') {
    $database = new Database();
    $db = $database->getConnection();
    $controller = new PlaylistController($db);

    $playlists = $controller->showAllPlaylists();
    include '/web_nghe_nhac/public/assets/php/views/playlistView.php';
}

//Hiển thị playlist mặc định
if (isset($_GET['action']) && $_GET['action'] === 'default') {
    $database = new Database();
    $db = $database->getConnection();
    $controller = new PlaylistController($db);

    // Lấy playlist đầu tiên
    $playlists = $controller->showAllPlaylists();
    if (!empty($playlists)) {
        $defaultPlaylist = $playlists[0]; // Playlist đầu tiên
        $maDSP = $defaultPlaylist['MaDSP'];
        $tenPlaylist = $defaultPlaylist['TenDSP'];
        $loaiDSP = $defaultPlaylist['LoaiDSP'];
        $imgPath = $defaultPlaylist['AnhDSP'];

        // Lấy số lượng bài hát trong playlist mặc định
        $songCount = $controller->getPlaylistDetails($maDSP);

        // Trả về dữ liệu mặc định dưới dạng JSON
        echo json_encode([
            'maDSP' => $maDSP,
            'tenPlaylist' => $tenPlaylist,
            'loaiDSP' => $loaiDSP,
            'imgPath' => "/web_nghe_nhac/public/assets/img/playlist/$imgPath",
            'songCount' => $songCount
        ]);
    } else {
        echo json_encode(['error' => 'Không có danh sách phát nào!']);
    }
    exit;
}

// Hiển thị playlist dựa trên ID
if (isset($_GET['action']) && $_GET['action'] === 'getPlaylist' && isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();
    $controller = new PlaylistController($db);

    // Lấy ID playlist từ URL
    $playlistId = $_GET['id'];

    // Tìm playlist theo ID
    $playlist = $controller->getPlaylistById($playlistId);
    if ($playlist) {
        $maDSP = $playlist['MaDSP'];
        $tenPlaylist = $playlist['TenDSP'];
        $loaiDSP = $playlist['LoaiDSP'];
        $imgPath = $playlist['AnhDSP'];

        // Lấy số lượng bài hát trong playlist
        $songCount = $controller->getPlaylistDetails($maDSP);

        // Trả về dữ liệu playlist dưới dạng JSON
        echo json_encode([
            'maDSP' => $maDSP,
            'tenPlaylist' => $tenPlaylist,
            'loaiDSP' => $loaiDSP,
            'imgPath' => "/web_nghe_nhac/public/assets/img/playlist/$imgPath",
            'songCount' => $songCount
        ]);
    } else {
        // Nếu không tìm thấy playlist
        echo json_encode(['error' => 'Không tìm thấy danh sách phát!']);
    }
    exit;
}

// Xử lý yêu cầu lấy danh sách bài hát của playlist
if (isset($_GET['action']) && $_GET['action'] === 'songs' && isset($_GET['maDSP'])) {
    $maDSP = $_GET['maDSP'];
    $database = new Database();
    $db = $database->getConnection();
    $controller = new PlaylistController($db);

    // Lấy danh sách bài hát
    $songs = $controller->getSongsByPlaylist($maDSP); // Phương thức trả về mảng bài hát

    // Trả về danh sách bài hát dưới dạng JSON
    echo json_encode($songs);
    exit;
}

// Xử lý yêu cầu 'action' lấy review
if (isset($_GET['action']) && $_GET['action'] === 'getReview' && isset($_GET['MaBaiHat'])) {
    $maBaiHat = $_GET['MaBaiHat']; // Đảm bảo lấy đúng tên biến

    // Kết nối database
    $database = new Database();
    $db = $database->getConnection();

    // Gọi PlaylistModel để lấy chi tiết đánh giá
    $playlistModel = new PlaylistModel($db);
    $reviews = $playlistModel->getReviewsBySongId($maBaiHat);

    // Nếu không có đánh giá nào, trả về giá trị mặc định
    if (empty($reviews)) {
        $reviews = [
            [
                "TenNguoiDung" => "(trống)",
                "BinhLuan" => "(trống)",
                "DiemDG" => 0
            ]
        ];
    }

    // Trả về kết quả JSON hợp lệ
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($reviews, JSON_UNESCAPED_UNICODE); // Đảm bảo không mã hóa ký tự Unicode
    exit;
}

//Thêm danh sách phát
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'addPlaylist') {
    header('Content-Type: application/json; charset=utf-8');
    // Nhận dữ liệu từ form
    $tenDSP = $_POST['nameList'] ?? '';
    $moTa = $_POST['scription'] ?? '';
    $imageFile = $_FILES['imageFile'] ?? null;

    // Xử lý ảnh upload nếu có
    $imageFileName = null; // Chỉ lưu tên file
    if ($imageFile && $imageFile['error'] === 0) {
        $imageFileName = basename($imageFile['name']); // Chỉ lấy tên file
        $imagePath = $imageFileName;


        // Lấy đường dẫn tương đối đến thư mục lưu ảnh
        $uploadDir = realpath(__DIR__ . '/../../img/playlist') ?: __DIR__ . '/../../img/playlist'; 
        $imagePathUpload = $uploadDir . '/' . $imageFileName;
        // Kiểm tra lỗi upload
        if (!move_uploaded_file($imageFile['tmp_name'], $imagePathUpload)) {
            echo json_encode(["success" => false, "message" => "Không thể lưu file upload."]);
            exit;
        }
    }

    try {
        // Kết nối database
        $database = new Database();
        $db = $database->getConnection();

        // Gọi PlaylistModel để thêm danh sách phát
        $playlistModel = new PlaylistModel($db);
        $result = $playlistModel->addPlaylist($tenDSP, $moTa, $imagePath);
        
        if ($result) {
            echo json_encode(["success" => true, "message" => "Thêm danh sách phát thành công."]);
        } else {
            echo json_encode(["success" => false, "message" => "Thêm danh sách phát thất bại."]);
        }
    } catch (Exception $e) {
        error_log("Lỗi xử lý: " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "Đã xảy ra lỗi: " . $e->getMessage()]); 
    }
    exit;
}

//Tìm mã bài hát bằng tên
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['songName'])) {
    $songName = $_GET['songName'];

    // Debug: In ra tên bài hát để kiểm tra
    error_log('Tên bài hát nhận được: ' . $songName);

    // Kết nối database
    $database = new Database();
    $db = $database->getConnection();

    // Gọi PlaylistModel để thêm danh sách phát
    $controller = new PlaylistController($db);
    $song = $controller->findSongByName($songName);

    if ($song) {
        echo json_encode(['success' => true, 'maBaiHat' => $song['MaBaiHat']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Có lỗi khi tìm mã bài hát bằng tên bài hát']);
    }
    exit;

}

//Thêm bài hát vào danh sách phát
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'addSongToPlaylist') {
    $maDSP = $_POST['maDSP'];
    $maBaiHat = $_POST['maBaiHat'];

    // Kiểm tra dữ liệu
    if (empty($maDSP) || empty($maBaiHat)) {
        echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
    }

    // Kết nối database
    $database = new Database();
    $db = $database->getConnection();

    // Gọi PlaylistModel để thêm danh sách phát
    $playlistModel = new PlaylistModel($db);
    $result = $playlistModel->addSongToPlaylist($maDSP, $maBaiHat);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Bài hát đã được thêm vào playlist']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Có lỗi khi thêm bài hát vào playlist']);
    }
    exit;
}

// Xóa bài hát khỏi danh sách phát
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deleteSongFromPlaylist') {
    header('Content-Type: application/json; charset=utf-8');

    // Kiểm tra dữ liệu trong $_POST
    if (isset($_POST['songId']) && isset($_POST['maDSP'])) {
        $songId = $_POST['songId'];
        $maDSP = $_POST['maDSP'];

        // Kết nối database
        $database = new Database();
        $db = $database->getConnection();

        $playlistModel = new PlaylistModel($db);
        $result = $playlistModel->deleteSongFromPlaylist($songId, $maDSP);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Bài hát đã được xóa.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Xóa bài hát thất bại.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Không nhận được dữ liệu.']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deletePlaylist') {
    header('Content-Type: application/json; charset=utf-8');

    if (isset($_POST['maDSP'])) {
        $maDSP = $_POST['maDSP'];

        // Kết nối database
        $database = new Database();
        $db = $database->getConnection();

        $playlistModel = new PlaylistModel($db);

        // Lấy thông tin file ảnh trước khi xóa
        $file = $playlistModel->findImgPlaylistById($maDSP);
        $imagePlaylistDir = realpath(__DIR__ . '/../../img/playlist') ?: __DIR__ . '/../../img/playlist';
        $imageFilePath = $file ? $imagePlaylistDir . '/' . $file['AnhDSP'] : null;

        // Bắt đầu quá trình xóa
        if ($playlistModel->deletePlaylistAndDetails($maDSP)) {
            if ($file && file_exists($imageFilePath)) {
                if (unlink($imageFilePath)) {
                    echo json_encode(['success' => true, 'message' => 'Danh sách phát và file ảnh đã được xóa.']);
                } else {
                    // Rollback nếu file không xóa được
                    echo json_encode(['success' => false, 'message' => 'Xóa file ảnh thất bại. Danh sách phát không bị xóa.']);
                }
            } else {
                echo json_encode(['success' => true, 'message' => 'Danh sách phát đã được xóa. Không tìm thấy file ảnh.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Xóa danh sách phát thất bại.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu.']);
    }
}




?>