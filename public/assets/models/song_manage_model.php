<?php
include_once '/xampp/htdocs/web_nghe_nhac/public/assets/php/config/config.php';
class SongManageModel {
    private $db;
    
    //Hàm khởi tạo
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Phương thức lấy tất cả danh sách phát
    public function getAllSongs() {
        try {
            $query = "SELECT b.MaBaiHat, b.TenBaiHat, b.AnhBaiHat, t.TenTheLoai, n.TenNgheSy FROM baihat b INNER JOIN theloai t ON b.MaTheLoai = t.MaTheLoai
                                                                                                            INNER JOIN nghesy n ON b.MaNgheSy = n.MaNgheSy ";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lỗi truy vấn: " . $e->getMessage();
            return [];
        }
    }

    public function searchSongs($query) {
        try {
            $sql = "SELECT b.MaBaiHat, b.TenBaiHat, b.AnhBaiHat, t.TenTheLoai, n.TenNgheSy FROM baihat b
                    INNER JOIN theloai t ON b.MaTheLoai = t.MaTheLoai
                    INNER JOIN nghesy n ON b.MaNgheSy = n.MaNgheSy
                    WHERE b.TenBaiHat LIKE ? OR n.TenNgheSy LIKE ?";
            $stmt = $this->db->prepare($sql);
            $searchTerm = "%" . $query . "%";
            $stmt->bindValue(1, $searchTerm, PDO::PARAM_STR);
            $stmt->bindValue(2, $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
            $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $songs;
        } catch (PDOException $e) {
            echo "Lỗi truy vấn: " . $e->getMessage();
            return [];
        }
    }

    public function addSong($TenBaiHat, $FileBaiHat, $AnhBaiHat, $LoiBaiHat, $MaTheLoai, $MaNgheSy) {
        try {
            $NgayDang = date("Y-m-d");

            $query = "INSERT INTO baihat (TenBaiHat, FileBaiHat, AnhBaiHat, LoiBaiHat, NgayDang, MaTheLoai, MaNgheSy) VALUES (:tenBaiHat, :fileBaiHat, :anhBaiHat, :loiBaiHat, :ngayDang, :maTheLoai, :maNgheSy)";
            
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':tenBaiHat', $TenBaiHat);
            $stmt->bindParam(':fileBaiHat', $FileBaiHat);
            $stmt->bindParam(':anhBaiHat', $AnhBaiHat);
            $stmt->bindParam(':loiBaiHat', $LoiBaiHat);
            $stmt->bindParam(':ngayDang', $NgayDang);
            $stmt->bindParam(':maTheLoai', $MaTheLoai);
            $stmt->bindParam(':maNgheSy', $MaNgheSy);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Lỗi trong addPlaylist: " . $e->getMessage());
            return false;
        }
    }

    // Lấy mã nghệ sỹ từ tên nghệ sỹ
    public function getMaNgheSy($tenNgheSy) {
        $sql = "SELECT MaNgheSy FROM nghesy WHERE TenNgheSy = :tenNgheSy LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tenNgheSy', $tenNgheSy);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['MaNgheSy'] ?? null; // Trả về MaNgheSy hoặc null nếu không tìm thấy
    }
    
    
    //Hàm xóa bài hát
    public function deleteSongsByIds($songIds) {
        $placeholders = implode(',', array_fill(0, count($songIds), '?'));
        $sql = "DELETE FROM baihat WHERE MaBaiHat IN ($placeholders)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($songIds);
    }

    public function deleteReviewsBySongIds($songIds) {
        try {
            $placeholders = implode(',', array_fill(0, count($songIds), '?'));
            $query = "DELETE FROM danhgia WHERE MaBaiHat IN ($placeholders)";
            $stmt = $this->db->prepare($query);

            foreach ($songIds as $index => $songId) {
                $stmt->bindValue($index + 1, $songId, PDO::PARAM_INT);
            }

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Lỗi khi xóa đánh giá: " . $e->getMessage());
            return false;
        }
    }


    public function updateSong($MaBaiHat, $TenBaiHat, $LoiBaiHat, $MaTheLoai, $MaNgheSy) {
        try {

            $query = "UPDATE baihat
                        SET TenBaiHat = :tenBaiHat,
                            LoiBaiHat = :loiBaiHat,
                            MaTheLoai = :maTheLoai,
                            MaNgheSy = :maNgheSy
                        WHERE MaBaiHat = :maBaiHat";
            
            $stmt = $this->db->prepare($query);

            $stmt->bindParam(':maBaiHat', $MaBaiHat);
            $stmt->bindParam(':tenBaiHat', $TenBaiHat);
            $stmt->bindParam(':loiBaiHat', $LoiBaiHat);
            $stmt->bindParam(':maTheLoai', $MaTheLoai);
            $stmt->bindParam(':maNgheSy', $MaNgheSy);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Lỗi khi cập nhật bài hát: " . $e->getMessage());
            return false;
        }
    }

    public function getSongById($id) {
    $query = "SELECT b.TenBaiHat, t.TenTheLoai, b.LoiBaiHat, n.TenNgheSy, n.MaNgheSy, t.MaTheLoai FROM baihat b
                    INNER JOIN theloai t ON b.MaTheLoai = t.MaTheLoai
                    INNER JOIN nghesy n ON b.MaNgheSy = n.MaNgheSy 
              WHERE b.MaBaiHat = :id";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $song = $stmt->fetch(PDO::FETCH_ASSOC);
    return $song ? $song : null; // Trả về bài hát hoặc null nếu không tìm thấy
    }

// Hàm lấy FileBaiHat và AnhBaiHat theo danh sách mã bài hát
public function getFilesBySongIds($songIds) {
    $placeholders = implode(',', array_fill(0, count($songIds), '?'));
    $query = "SELECT MaBaiHat, FileBaiHat, AnhBaiHat FROM baihat WHERE MaBaiHat IN ($placeholders)";

    $stmt = $this->db->prepare($query);
    $stmt->execute($songIds);

    $files = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $files[] = $row;
    }
    return $files;
}

}


?>