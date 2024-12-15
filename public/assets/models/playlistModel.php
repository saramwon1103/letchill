<?php
include_once '/xampp/htdocs/web_nghe_nhac/public/assets/php/config/config.php';

class PlaylistModel {
    private $db;
    
    //Hàm khởi tạo
    public function __construct($db) {
        $this->db = $db;
    }

    // Phương thức lấy tất cả danh sách phát SELECT d.MaDSP, d.TenDSP, d.MoTa, d.MaNguoiDung, d.LoaiDSP, d.AnhDSP, c.NgayThem FROM danhsachphat d JOIN ct_danhsachphat c ON d.MaDSP = c.MaDSP
    public function getAllPlaylists() {
        try {
            $query = "SELECT * FROM danhsachphat";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lỗi truy vấn: " . $e->getMessage();
            return [];
        }
    }

    // Phương thức đếm số bài hát trong danh sách phát
    public function countSongsInPlaylist($maDSP) {
        $query = "SELECT COUNT(*) AS song_count FROM ct_danhsachphat WHERE MaDSP = :maDSP";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maDSP', $maDSP);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['song_count'];
    }

    public function getPlaylistById($maDSP) {
        $query = "SELECT * FROM danhsachphat WHERE MaDSP = :maDSP";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maDSP', $maDSP);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSongsFromPlaylist($maDSP) {
        $query = "SELECT b.MaBaiHat, b.TenBaiHat, b.AnhBaiHat, b.FileBaiHat, b.LoiBaiHat, ct.NgayThem, n.TenNgheSy, n.MaNgheSy
                  FROM baihat b
                  JOIN ct_danhsachphat ct ON b.MaBaiHat = ct.MaBaiHat
                  JOIN nghesy n ON n.MaNgheSy = b.MaNgheSy
                  WHERE ct.MaDSP = :maDSP";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maDSP', $maDSP);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Hàm lấy mã danh sách phát lớn nhất
    public function getMaxPlaylistId () {
        try {
            $query = "SELECT MAX(MaDSP) AS max_id FROM danhsachphat";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['max_id'] ?? 0;
        } catch(Exception $e) {
            error_log("Lỗi khi lấy mã danh sách phát lớn nhất: " . $e->getMessage());
            return 0;
        }
    }

    //Thêm danh sách phát mới với mã tự động gán = max + 1
    public function addPlaylist($tenDSP, $moTa, $imagePath = null) {
        try {
            $maxId = $this->getMaxPlaylistId();
            $newId = $maxId + 1;
            $loaiDSP = "Playlist";
            $maNguoiDung = 0;

            $query = "INSERT INTO danhsachphat (MaDSP, TenDSP, MoTa, MaNguoiDung, LoaiDSP, AnhDSP) VALUES (:newId, :tenDSP, :moTa, :maNguoiDung, :loaiDSP, :imagePath)";
            
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':newId', $newId);
            $stmt->bindParam(':tenDSP', $tenDSP);
            $stmt->bindParam(':moTa', $moTa);
            $stmt->bindParam(':maNguoiDung', $maNguoiDung);
            $stmt->bindParam(':loaiDSP', $loaiDSP);
            $stmt->bindParam(':imagePath', $imagePath);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Lỗi trong addPlaylist: " . $e->getMessage());
            return false;
        }
    }

    public function getReviewsBySongId($maBaiHat) {
        $query = "SELECT n.TenNguoiDung AS TenNguoiDung, d.DiemDG AS DiemDG, d.BinhLuan AS BinhLuan
                FROM danhgia d
                INNER JOIN nguoidung n ON d.MaNguoiDung = n.MaNguoiDung
                INNER JOIN baihat b ON b.MaBaiHat = d.MaBaiHat
                WHERE b.MaBaiHat = :maBaiHat";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maBaiHat', $maBaiHat, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm bài hát theo tên
    public function findSongByName($songName) {
        $query = "SELECT MaBaiHat FROM baihat WHERE TenBaiHat = :songName LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':songName', $songName);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm bài hát vào playlist
    public function addSongToPlaylist($maDSP, $maBaiHat) {
        $query = "INSERT INTO ct_danhsachphat (MaDSP, MaBaiHat) VALUES (:maDSP, :maBaiHat)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maDSP', $maDSP);
        $stmt->bindParam(':maBaiHat', $maBaiHat);
        return $stmt->execute();
    }

    // Hàm xóa bài hát khỏi playlist
    public function deleteSongFromPlaylist($songId, $maDSP) {
        $sql = "DELETE FROM ct_danhsachphat WHERE MaDSP = :maDSP AND MaBaiHat = :songId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':maDSP', $maDSP, PDO::PARAM_INT);
        $stmt->bindParam(':songId', $songId, PDO::PARAM_INT);

        return $stmt->execute(); // Trả về true nếu xóa thành công
    }

    // Hàm xóa playlist
    public function deletePlaylistAndDetails($maDSP) {
        try {
            // Bắt đầu transaction
            $this->db->beginTransaction();
    
            // Xóa dữ liệu trong bảng ct_danhsachphat
            $sqlDetails = "DELETE FROM ct_danhsachphat WHERE MaDSP = :maDSP";
            $stmtDetails = $this->db->prepare($sqlDetails);
            $stmtDetails->bindParam(':maDSP', $maDSP, PDO::PARAM_INT);
            if (!$stmtDetails->execute()) {
                throw new Exception("Không thể xóa chi tiết danh sách phát.");
            }
    
            // Xóa dữ liệu trong bảng danhsachphat
            $sqlPlaylist = "DELETE FROM danhsachphat WHERE MaDSP = :maDSP";
            $stmtPlaylist = $this->db->prepare($sqlPlaylist);
            $stmtPlaylist->bindParam(':maDSP', $maDSP, PDO::PARAM_INT);
            if (!$stmtPlaylist->execute()) {
                throw new Exception("Không thể xóa danh sách phát.");
            }
    
            // Commit transaction nếu mọi thứ thành công
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Rollback nếu có lỗi
            $this->db->rollBack();
            return false;
        }
    }
    

    // Hàm tìm file ảnh playlist theo mã playlist
    public function findImgPlaylistById($playlistId) {
        $query = "SELECT AnhDSP FROM danhsachphat WHERE MaDSP = :maDSP";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':maDSP', $playlistId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

}

?>