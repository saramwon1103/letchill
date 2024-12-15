// Lắng nghe sự kiện click vào các link "left-menu"
const toggles = document.querySelectorAll('.left-menu');

toggles.forEach(toggle => {
    toggle.addEventListener('click', function(event) {
        // Kiểm tra nếu đây là mục "Quản lý tài khoản" để điều hướng
        if (this.id === "manage-accounts") {
            // Lấy submenu của "Quản lý tài khoản"
            const submenu = this.nextElementSibling;
            
            // Hiển thị submenu của "Quản lý tài khoản"
            if (submenu && submenu.classList.contains('left-submenu')) {
                submenu.style.display = 'block';
            }
        }
        
        // Nếu không phải "Quản lý tài khoản", tiếp tục điều hướng
        if (this.getAttribute('href') !== '#') {
            window.location.href = this.getAttribute('href');  // Điều hướng đến trang khác
        }
        
        // Ngăn không cho chuyển hướng khi nhấn vào các <a> mà chỉ hiển thị submenu
        event.preventDefault();

        // Tìm phần tử <ul> con của mục hiện tại và chuyển đổi trạng thái hiển thị
        const submenu = this.nextElementSibling; // lấy phần tử <ul> ngay sau <a>

        // Kiểm tra nếu phần tử kế tiếp là <ul> (submenu)
        if (submenu && submenu.classList.contains('left-submenu')) {
            // Ẩn tất cả các submenu khác
            const allSubmenus = document.querySelectorAll('.left-submenu');
            allSubmenus.forEach(item => {
                if (item !== submenu) {
                    item.style.display = 'none';  // Ẩn các submenu khác
                }
            });

            // Thay đổi trạng thái hiển thị của submenu hiện tại
            if (submenu.style.display === 'none' || submenu.style.display === '') {
                submenu.style.display = 'block';  // Hiển thị menu con
            } else {
                submenu.style.display = 'none';   // Ẩn menu con
            }
        }
    });
});
