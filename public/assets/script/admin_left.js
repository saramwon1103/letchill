
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

    console.log("File admin_left.js được tải thành công!");
