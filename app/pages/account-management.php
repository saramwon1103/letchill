<?php
header('Access-Control-Allow-Origin: *'); // Cho phép tất cả các domain
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Các phương thức cho phép
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Các header cho phép
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/assets/css/account-management.css">
    <link rel="stylesheet" href="../../public/assets/css/admin_left_side.css">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    <title>Document</title>
</head>

<body>
    <!-- header -->
    <?php
    include 'admin/header.php'
    ?>

    <main>
        <!-- Định dạng mcolumn;nền của trang -->
        <div class="mainLight"></div>
        </div>

        <!-- màn hình chính -->
        <div class="main-Space">
            <!--Chèn controlbar vào -->
            <?php
            // Hiển thị menu chức năng
            include_once '../pages/admin/left_side.php';
            ?>




        </div>

        <div class="accountSpace">
            <div class="findSong">

                <div class="boxInput">
                    <input id="boxFind" type="text">
                    <iconify-icon class="search-ic" icon="material-symbols:search" width="1.2em" height="1.2em"
                        style="color: white"></iconify-icon>
                </div>


                <button id="find" type="submit">
                    Tìm Kiếm
                </button>

            </div>


            <!-- Tạo bảng -->
            <div class="tableAccount">
                <table>
                    <colgroup>
                        <col style="width: 34px;">
                        <col style="width: 180px;">
                        <col style="width: 340px;">
                        <col style="width: 240px;">
                        <col style="width: 240px;">
                        <col style="width: 240px;">

                    </colgroup>
                    <thead>

                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Mã tài khoản</th>
                            <th>Tên người dùng</th>
                            <th>Gói đang dùng</th>
                            <th>Ngày bắt đầu gói</th>
                            <th>Ngày kết thúc gói</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Dùng .js để load dữ liệu vào -->



                    </tbody>
                </table>


            </div>

        </div>

        </div>
    </main>

</body>

</html>

<script>
    //load dữ liệu từ csdl lên bảng tài khoản
    $(document).ready(function() {
        // code để load dữ liệu từ csdl lên bảng tài khoản
        $.ajax({
            url: "./model-account-management.php",
            type: "GET",
            success: function(response) {
                // duyệt data vào table trong thẻ div tableaccount
                var data = JSON.parse(response);
                var table = $(".tableAccount table tbody");
                for (var i = 0; i < data.length; i++) {
                    var row = $("<tr></tr>");
                    row.append("<td><input type='checkbox' id='checkbox'></td>");
                    row.append("<td>" + data[i].MaTaiKhoan + "</td>");
                    row.append("<td>" + data[i].TenNguoiDung + "</td>");
                    row.append("<td>" + data[i].TenGoi + "</td>");
                    row.append("<td>" + data[i].NgayBatDau + "</td>");
                    row.append("<td>" + data[i].NgayKetThuc + "</td>");
                    table.append(row);
                }


            }
        });
    });


    //khi chọn checkboxAll thì tất cả các checkbox còn lại điều được chon
    $("#selectAll").change(function() {
        $(".tableAccount table tbody input[type='checkbox']").prop("checked", $(this).prop("checked"));
    });

    // CẬP NHẬT TÀI KHOẢN

    var rowData;
    //lắng nghe sự kiện từ checkbox của hàng của table, khi nhấn vào #figure-updateAccount thì mở hộp thoại cập nhật và hiển thị thông tin của hàng mà checkbox được chọn 
    $(document).on("change", ".tableAccount table tbody input[type='checkbox']", function() {
        if ($(this).prop("checked")) {


            // code để lấy thông tin của hàng mà checkbox đang chọn
            // Lấy hàng chứa checkbox
            var currentRow = $(this).closest("tr");

            rowData = currentRow.find("td").map(function() {
                return $(this).text().trim();
            }).get(); // Chuyển kết quả thành mảng

            // In ra mảng chứa dữ liệu từng cột
            // alert("ID: " + rowData[1] + "\nTên tài khoản: " + rowData[2] + "\nEmail: " + rowData[3] + rowData[4]);
        }
    });

    // Cập nhật tài khoản
    $(document).on('click', "#figure-updateAccount", function() {
        // hiển thị nội dung tài khoản lên upddateAccount formaddacconut 
        // code để hiển thị thông tin và gán giá trị cho các input của form addaccout
        $('  #formaddAccount2 input[type="text"]').val(rowData[2]);
        $('  #formaddAccount2 select').val(rowData[3]);
        $(' #formaddAccount2 input[type="date"][name="datestart"]').val(rowData[4]);
        $(' #formaddAccount2 input[type="date"][name="datefinish"]').val(rowData[5]);
    });

    //sự kiện cập nhật
    $(document).on('submit', '#formaddAccount2', function(event) {

        event.preventDefault(); //ngan reloai trang
        var MaTaiKhoan = rowData[1];
        var TenNguoiDung = $('#formaddAccount2 input[name="username"]').val();
        var TenGoi = $('#formaddAccount2 select[name="pakage"]').val();
        var datestart = $('#formaddAccount2 input[name="datestart"]').val();
        var datefinish = $('#formaddAccount2 input[name="datefinish"]').val();



        // Gửi dữ liệu bằng AJAX mà không chuyển trang
        $.ajax({
            url: "./includes/model_admin_left_update.php", // Đường dẫn tới file xử lý PHP
            type: "POST",
            data: {
                MaNguoiDung: MaTaiKhoan,
                username: TenNguoiDung,
                pakage: TenGoi,
                datestart: datestart,
                datefinish: datefinish

            },
            success: function(response) {
                alert("Cập nhật tài khoản thành công!\n" + response);
                // Bạn có thể thực hiện các hành động khác sau khi xóa thành công (ví dụ: cập nhật giao diện).
                // Cập nhật giao diện bảng bằng cách xóa hàng của bảng
                location.reload();


            },
            error: function(xhr, status, error) {
                alert("Có lỗi xảy ra: " + error);
            }
        });
    });


    //XÓA TÀI KHOẢN

    $(document).on('click', '#figure-deleteAccount', function() {
        var MaTaiKhoan = rowData[1];
        var TenNguoiDung = rowData[2];
        var TenGoi = rowData[3];

        // Gửi dữ liệu bằng AJAX mà không chuyển trang
        $.ajax({
            url: "./includes/model_admin_left_delete.php", // Đường dẫn tới file xử lý PHP
            type: "POST",
            data: {
                MaTaiKhoan: MaTaiKhoan,
                TenNguoiDung: TenNguoiDung,
                TenGoi: TenGoi
            },
            success: function(response) {
                alert("Xóa tài khoản thành công!\n" + response);
                // Bạn có thể thực hiện các hành động khác sau khi xóa thành công (ví dụ: cập nhật giao diện).
                // Cập nhật giao diện bảng bằng cách xóa hàng của bảng
                location.reload();


            },
            error: function(xhr, status, error) {
                alert("Có lỗi xảy ra: " + error);
            }
        });
    });


    //CHỨC NĂNG TÌM KIẾM 
    $(document).ready(function() {
        $('#boxFind').on('input', function() {
            // Lấy giá trị từ ô tìm kiếm
            var text = $(this).val().toLowerCase().trim();

            // Nếu ô tìm kiếm trống, hiển thị lại toàn bộ bảng
            if (text === '') {
                $('.tableAccount table tbody tr').show();
                return;
            }

            // Ẩn tất cả các hàng trong bảng
            $('.tableAccount table tbody tr').hide();

            // Hiển thị các hàng có ô (cell) chứa từ khóa
            $('.tableAccount table tbody tr').filter(function() {
                var found = false;
                $(this).find('td').each(function() {
                    if ($(this).text().toLowerCase().includes(text)) {
                        found = true; // Nếu tìm thấy từ khóa trong ô, đánh dấu
                        return false; // Dừng kiểm tra các ô khác trong hàng
                    }
                });
                return found; // Chỉ hiển thị hàng nếu có từ khóa
            }).show();
        });
    });
</script>