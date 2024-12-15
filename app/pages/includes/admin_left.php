<head>
    <link rel="stylesheet" href="../../../public/assets/css/admin_left.css">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

</head>

<body>
    <div class="adminleft">
        <div class="controlBar">
            <div class="manageSong"> Quản lý bài hát

            </div>

            <div class="manageAccount">
                <div class="content">
                    <span id="account">Quản lý tài khoản</span>

                    <div class="figure">
                        <p id="figure-addAccount">
                            <iconify-icon icon="line-md:plus-circle-filled" width="1.2em"
                                height="1.2em"></iconify-icon>
                            Thêm tài khoản
                        </p>
                        <p id="figure-updateAccount">
                            <iconify-icon icon="mdi:pencil-circle" width="1.2em" height="1.2em"></iconify-icon>
                            Cập nhật tài khoản
                        </p>
                        <p id="figure-deleteAccount">
                            <iconify-icon icon="streamline:recycle-bin-2-solid" width="1.2em"
                                height="1.2em"></iconify-icon>
                            Xóa tài khoản
                        </p>
                    </div>
                </div>
            </div>

            <div class="Report">
                <span id="account">
                    <iconify-icon icon="carbon:report" width="1.2em" height="1.2em"
                        style="color: white"></iconify-icon>
                    Báo cáo
                </span>


            </div>

        </div>

        <!-- Thêm tài khoản -->
        <div class="addAccount" id="addaccount" style="display: none;">
            <form id="formaddAccount" action="" method="POST">
                <table>
                    <tr>
                        <!-- Icon quay lai -->
                        <span class="return-add" id="return"><iconify-icon icon="ic:round-arrow-back-ios-new" width="1.2em" height="1.2em" style="color: white"></iconify-icon></span>
                    </tr>

                    <tr>
                        <h1>Thêm tài khoản</h1>

                    </tr>
                    <tr>
                        <td><span style="width: 325px;">Tên người dùng</span></td>
                        <td><input type="text" name="username" required></td>
                    </tr>
                    <tr>
                        <td>Gói sử dụng</td>
                        <td>
                            <select id="pakage" name="pakage">
                                <option value="Mini">Mini</option>
                                <option value="Individual">Individual</option>
                                <option value="Student">Student</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Ngày Bắt đầu gói</td>
                        <td><input type="date" name="datestart" required></td>
                    </tr>
                    <tr>
                        <td>Ngày kết thúc gói</td>
                        <td><input type="date" name="datefinish" required></td>
                    </tr>
                    <tr>
                        <td><button id="add" type="submit">Tạo</button></td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="addAccount" id="updateaccount" style="display: none;">
            <form id="formaddAccount2" action="" method="post">

                <span class="return-update" id="return"><iconify-icon icon="ic:round-arrow-back-ios-new" width="1.2em" height="1.2em" style="color: white"></iconify-icon></span>

                <h1>Cập nhật tài khoản</h1>
                <div class="wrap-box">
                    <div class="box">
                        <span style="width: 325px;">Tên người dùng</span>
                        <input type="text" name="username" required readonly>
                    </div>

                    <div class="box">
                        <span style="width: 325px;">Gói sử dụng</span>
                        <select id="pakage" name="pakage" required disabled>
                            <option value="Mini">Mini</option>
                            <option value="Individual">Individual</option>
                            <option value="Student">Student</option>
                        </select>
                    </div>

                    <div class="box">
                        <span style="width: 325px;">Ngày Bắt đầu gói</span>
                        <input type="date" name="datestart" required>
                    </div>

                    <div class="box">
                        <span style="width: 325px;">Ngày kết thúc gói</span>
                        <input type="date" name="datefinish" required>
                    </div>

                    <div class="box">
                        <button id="add" type="submit">Cập nhật</button>
                    </div>
                </div>




            </form>



        </div> 
    </div>
    <!-- chèn script -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</body>

<script>
    // Hiển thị thêm tài khoản
    $(document).ready(function() {
        $('#figure-addAccount').click(function() {
            $('#addaccount').show();
        });

        $('.return-add').click(function() {
            $('#addaccount').hide();
        });
    });

    //Hiển thị cập nhật tài khoản
    $(document).ready(function() {
        $('#figure-updateAccount').click(function() {
            $('#updateaccount').show();

        })
        $('.return-update').click(function() {
            $('#updateaccount').hide();
        });
    })

    // CHỨC NĂNG THÊM TÀI KHOẢN
    $(document).ready(function() {
        $('#addaccount #formaddAccount').submit(function(event) {
            event.preventDefault(); // Ngăn không cho form submit mặc định
            console.log("Form submitted"); // Kiểm tra submit
            // Lấy dữ liệu từ form
            var username = $('#formaddAccount input[name="username"]').val();
            var pakage = $('#formaddAccount select[name="pakage"]').val();
            var datestart = $('#formaddAccount input[name="datestart"]').val();
            var datefinish = $('#formaddAccount input[name="datefinish"]').val();
            // Kiểm tra dữ liệu đầu vào
            if (username == "" || pakage == "" || datestart == "" || datefinish == "") {
                alert("Vui lòng nhập đ�� thông tin!");
                return false;
            }
            // Gửi dữ liệu đến server
            $.ajax({

                url: './includes/model_admin_left_add.php',
                type: 'POST',
                data: {
                    username: username,
                    pakage: pakage,
                    datestart: datestart,
                    datefinish: datefinish,
                    add: 'Tạo'
                },
                success: function(response) {
                    if (response) {
                        alert("Thêm tài khoản thành công!");
                        location.reload(); //reload lại trang để hiển thị thông tin mới

                    } else {
                        alert("Thêm tài khoản thất bại!");
                    }
                }
            });
        });
    });

    // CHỨC NĂNG CẬP NHẬT TÀI KHOẢN
</script>