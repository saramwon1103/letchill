<?php
session_start();?>
<!--kết nối csdl-->
<?php
// Thông tin kết nối
$servername = "localhost";
$username = "root";
$password = ""; // Để trống nếu bạn đang dùng XAMPP và chưa đặt mật khẩu
$database = "letchill_data"; // Thay bằng tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $database);
?>


<!-- Lấy mã người dùng từ email và mật khẩu, tên đăng nhập -->
<?php
// $email = 'minhanh.nguyen@gmail.com';
 $email = $_SESSION['email'];
//$password = $_SESSION['password'];
$sqlMaNguoiDung = "SELECT nguoidung.MaNguoiDung 
FROM taikhoan 
JOIN nguoidung ON taikhoan.MaNguoiDung = nguoidung.MaNguoiDung 
WHERE nguoidung.Email = '" . $email . "'";

$resultMaNguoiDung = mysqli_query($conn, $sqlMaNguoiDung);
$rowMaNguoiDung = mysqli_fetch_assoc($resultMaNguoiDung);

// Lấy thông tin người dùng từ mã người dùng
$MaNguoiDung = null;  // Khởi tạo biến trước khi sử dụng
if (isset($rowMaNguoiDung['MaNguoiDung'])) {
    $MaNguoiDung = $rowMaNguoiDung['MaNguoiDung'];
} else {
    echo "Không có mã người dùng trong kết quả.";
}

?>


<?php
function AddImageSong($name_image_Song)
{
    $base_url = "../img/data-songs-image/"; // Đường dẫn gốc đến thư mục chứa ảnh
    return $base_url . $name_image_Song; // Trả về đường dẫn đầy đủ
}

function AddImageArtist($name_image_Artist)
{
    $base_url = "../img/data-artists-image/"; // Đư��ng d��n gốc đến thư mục chứa ảnh
    return $base_url . $name_image_Artist; // Trả về đư��ng d��n đầy đ��
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profile-info.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <title>Profile-info</title>
    <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
</head>

<body>
    
<?php
require $_SERVER['DOCUMENT_ROOT'] . "/web_nghe_nhac/app/pages/includes/header.php";
?>

    <div class="main"> <!-- div phần thân -->
    <?php
require $_SERVER['DOCUMENT_ROOT'] . "/web_nghe_nhac/app/pages/includes/left_side.php";
?>
        <div id="artist"> <!--div nghệ sĩ-->
            <div id="main-artist">

                <!--Lấy thông tin người dùng-->
                <?php
                $sql = "  
                   SELECT nguoidung.MaNguoiDung, nguoidung.TenNguoiDung, nguoidung.AnhNguoiDung, danhsachphat.TenDSP 
                    from danhsachphat join nguoidung on danhsachphat.MaNguoiDung = nguoidung.MaNguoiDung
                    WHERE nguoidung.MaNguoiDung = '" . $MaNguoiDung . "'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_array($result, 1);
                ?>
                <!--Lay so luong dsp cua nguoi dung -->
                <?php
                $sql1 = "SELECT COUNT(*) as Dem FROM danhsachphat WHERE MaNguoiDung = '" . $MaNguoiDung . "'";
                $result1 = mysqli_query($conn, $sql1);
                $row1 = mysqli_fetch_array($result1, 1);
                ?>

                <!-- Lấy số lượng người theo dõi của người dùng -->
                <?php
                $sql2 = "SELECT COUNT(*) as DemNguoiTheoDoi FROM theodoi WHERE MaNguoiDung = '" . $MaNguoiDung . "'";
                $result2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_array($result2, 1);
                ?>

                <span id="avatar-profile" style=" background-color:transparent; background-image: url(' <?php echo AddImageSong($row['AnhNguoiDung']); ?> ');">
                    <span style="cursor: pointer; color: white; font-size: 16px; font-weight: 600; display:none" id="button-change-avt">
                        <svg style="margin-left: 20px" width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.2999 19.1879L19.9285 10.5592C18.4769 9.95278 17.1586 9.06725 16.0482 7.95285C14.9333 6.84224 14.0473 5.52345 13.4407 4.07135L4.81203 12.7C4.13886 13.3732 3.80169 13.7104 3.51236 14.0814C3.17098 14.5194 2.878 14.9931 2.63853 15.4942C2.43669 15.9189 2.28619 16.3715 1.98519 17.2745L0.396193 22.038C0.323059 22.2561 0.312208 22.4903 0.36486 22.7142C0.417513 22.9382 0.531581 23.143 0.694243 23.3056C0.856905 23.4683 1.06171 23.5824 1.28564 23.635C1.50958 23.6877 1.74376 23.6768 1.96186 23.6037L6.72536 22.0147C7.62953 21.7137 8.08103 21.5632 8.50569 21.3614C9.00892 21.1218 9.47986 20.8305 9.91853 20.4875C10.2895 20.1982 10.6267 19.861 11.2999 19.1879ZM22.3225 8.16519C23.1829 7.30485 23.6662 6.13798 23.6662 4.92127C23.6662 3.70457 23.1829 2.53769 22.3225 1.67735C21.4622 0.817013 20.2953 0.333679 19.0786 0.333679C17.8619 0.333679 16.695 0.817013 15.8347 1.67735L14.7999 2.71219L14.8442 2.84169C15.354 4.30093 16.1885 5.62534 17.2849 6.71502C18.4071 7.84414 19.7779 8.69514 21.2877 9.20002L22.3225 8.16519Z" fill="white" fill-opacity="0.75" />
                        </svg>
                        <br>Chọn ảnh
                    </span>
                </span>


                <div class="info-artist">
                    <div id="artist-text">Hồ sơ<br></div>
                    <div id="artist-name"><b><?php echo $row["TenNguoiDung"] ?></b><br></div>
                    <div id="artist-follower"><span style="color: #8B8B8B;" ; id="artist-count-playlist"><?php echo $row1["Dem"] ?> danh sách phát. &nbsp</span><?php echo $row2["DemNguoiTheoDoi"] ?> người đang theo dõi </div>
                </div>
            </div>
            <div id="play">
                <button id="threedots"><i class="fa-solid fa-ellipsis"></i></button>
            </div>


            <div id="listsong">
                <div id="listsong-title">
                    <span id="sharp-title">Danh sách phát</span>
                </div>

                <div id="songs">
                    <?php
                    // Lặp qua từng bản ghi
                    while ($row) {
                        // In ra tên và hình ảnh của danh sách phát
                        echo '<div id="song1">';
                        echo '<span id="heart-icon2" style="background-color:transparent; background-image: url(\'' . AddImageSong($row['AnhNguoiDung']) . '\');"></span>';
                        echo '<span id="namesong">' . $row["TenDSP"] . '</span>'; // Tên bài hát
                        echo '</div>';
                        $row = $result->fetch_assoc();
                    }
                    ?>
                </div>

            </div>

            <div id="artist-follow">
                <div id="artist-following-title">Nghệ sĩ đang theo dõi</div>
                <div id="artist-following-list">

                    <!-- Lấy thông tin nghệ sĩ mà người này theo dõi -->
                    <?php
                    $sqlNgheSi = "select nghesy.MaNgheSy,nghesy.TenNgheSy, nghesy.AnhNgheSy
                        from nghesy join theodoi on nghesy.MaNgheSy = theodoi.MaNgheSy
                        WHERE theodoi.MaNguoiDung = '" . $MaNguoiDung . "' ";
                    $resultNgheSi = mysqli_query($conn, $sqlNgheSi);
                    $rowNgheSi = mysqli_fetch_assoc($resultNgheSi);

                    while ($rowNgheSi) {
                        // In ra hình ảnh và tên của nghệ sĩ
                        echo '<div id="artist-following-each">';
                        echo '<span><img src="' . AddImageArtist($rowNgheSi["AnhNgheSy"]) . '" alt=""><br></span>';
                        echo '<span id="name-artist-following">' . $rowNgheSi["TenNgheSy"] . '</span>';
                        echo '</div>';
                        $rowNgheSi = $resultNgheSi->fetch_assoc();
                    }

                    ?>
                </div>
            </div>
        </div>
        <?php
require $_SERVER['DOCUMENT_ROOT'] . "/web_nghe_nhac/app/pages/includes/right_side.php";
?>
    <?php
require $_SERVER['DOCUMENT_ROOT'] . "/web_nghe_nhac/app/pages/includes/listeningSpace.php";
?>
    <script>
        document.getElementById('progress-slider').addEventListener('input', function() {
            const value = this.value;
            const max = this.max;
            const percentage = (value / max) * 100;

            // Update the background of the slider based on the value
            this.style.background = `linear-gradient(90deg, #1DB954 ${percentage}%, #ddd ${percentage}%)`;
        });
        document.getElementById('volume').addEventListener('input', function() {
            const value = this.value;
            const max = this.max;
            const percentage = (value / max) * 100;

            // Update the background of the slider based on the value
            this.style.background = `linear-gradient(90deg, #1DB954 ${percentage}%, #ddd ${percentage}%)`;
        });

        //UP file ảnh người dùng
    </script>
</body>

</html>