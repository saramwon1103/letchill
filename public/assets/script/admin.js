// Lắng nghe sự kiện click vào các link "left-menu"
const toggles = document.querySelectorAll('.left-menu');

toggles.forEach(toggle => {
    toggle.addEventListener('click', function(event) {

        // Tìm phần tử <ul> con của mục hiện tại và chuyển đổi trạng thái hiển thị
        const submenu = this.nextElementSibling; // lấy phần tử <ul> ngay sau <a>

        // Kiểm tra trạng thái hiển thị hiện tại và thay đổi
        if (submenu && submenu.classList.contains('left-submenu')) {
            // Kiểm tra tất cả các submenu khác để ẩn chúng
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
