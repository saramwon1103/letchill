// thông báo
const notificationIcon = document.getElementById('notificationIcon');
const notificationList = document.getElementById('notificationPopup');
const closePopup = document.getElementById('closePopup');

// Ẩn popup khi nhấn vào nút đóng
closePopup.addEventListener('click', () => {
    notificationList.style.display = 'none'; // Sửa đây để sử dụng notificationList
});

$(document).ready(function() {
    // Lắng nghe sự kiện click vào biểu tượng thông báo
    $('.notif').click(function() {
        $('.notification-popup').show(); 
    });

    // Lấy thông báo từ cơ sở dữ liệu
    $.ajax({
        url: '/web_nghe_nhac/app/pages/noti.php', // Đảm bảo đường dẫn chính xác
        type: 'GET',
        dataType: 'json',
        success: function (notifications) {
            const notificationList = $('.popup-content');
            notificationList.find('.notification-item').remove(); // Xóa các thông báo cũ

            // Kiểm tra nếu có thông báo
            if (notifications.length > 0) {
                notifications.forEach(notification => {
                    // Kiểm tra trạng thái của thông báo (0: chưa đọc, 1: đã đọc)
                    const notificationClass = notification.TrangThai === 0 ? 'notification-item unread' : 'notification-item';
                    const unreadIndicator = notification.TrangThai === 0 ? '<span class="unread-indicator"></span>' : '';

                    // Tạo HTML cho mỗi thông báo
                    const notificationHTML = `
                        <div class="${notificationClass}" data-id="${notification.MaThongBao}">
                            <p class="title">${notification.TieuDe} ${unreadIndicator}</p>
                            <p class="message">${notification.NoiDung}</p>
                            <p class="time">${notification.ThoiGian}</p>
                        </div>
                    `;
                    notificationList.append(notificationHTML);
                });
            } else {
                notificationList.append('<p>Không có thông báo nào.</p>');
            }
        },
        error: function (xhr, status, error) {
            console.error('Không thể tải thông báo.', status, error);
            console.log(xhr.responseText);  // In thông báo lỗi chi tiết nếu có
        }
    });

    // Cập nhật trạng thái thông báo khi người dùng nhấp vào thông báo
    $(document).on('click', '.notification-item', function () {
        const notificationElement = $(this);
        const notificationId = notificationElement.data('id');

        // Gửi yêu cầu POST để cập nhật trạng thái thông báo
        $.ajax({
            url: '/web_nghe_nhac/app/pages/noti.php',
            type: 'POST',
            data: { MaThongBao: notificationId },
            success: function(response) {
                const data = JSON.parse(response);
                if (data.status === 'success') {
                    // Loại bỏ chấm xanh và thay đổi lớp
                    notificationElement.removeClass('unread').addClass('read');
                    notificationElement.find('.unread-indicator').remove();
                } else {
                    console.error('Không thể cập nhật trạng thái thông báo.');
                }
            },
            error: function() {
                console.error('Không thể cập nhật trạng thái thông báo.');
            }
        });
    });
});

// tìm kiếm
const searchInput = document.getElementById('search-input');
const searchesContainer = document.getElementById('searches-container');

$(document.body).ready(function () {
    $('#search-input').click(function () {
        $('.wrapperSlider').toggle();
        $('.searches-container').toggle();
    });
});

// Hàm xóa lịch sử tìm kiếm
function clearSearchHistory() {
    const searchItems = document.getElementById("search-items");
    searchItems.innerHTML = "";  // Xóa tất cả các mục trong danh sách tìm kiếm
}
// Hàm tìm kiếm
function performSearch(keyword) {
    if (keyword.length === 0) {
        document.getElementById('search-items').innerHTML = ''; // Clear search results if keyword is empty
        return;
    }

    // Gửi yêu cầu AJAX tới search.php
    $.ajax({
        url: '/web_nghe_nhac/app/pages/search.php',
        type: 'GET',
        data: { keyword: keyword },
        success: function(response) {
            const results = JSON.parse(response);

            // Clear previous results
            const searchItems = document.getElementById('search-items');
            searchItems.innerHTML = '';

            // Hiển thị các bài hát
            results.songs.forEach(song => {
                // Tạo phần tử div cho mỗi bài hát
                const songItem = document.createElement('div');
                songItem.className = 'search-item';
                songItem.innerHTML = `
                    <img src="/web_nghe_nhac/public/assets/img/data-songs-image/${song.AnhBaiHat}" alt="${song.TenBaiHat}">
                    <h3>${song.TenBaiHat}</h3>
                `;

                // Thêm sự kiện click để lấy thông tin chi tiết bài hát
                songItem.addEventListener('click', () => {
                    console.log(`Loading song: ${song.MaBaiHat}`);
                    
                    // Gửi yêu cầu AJAX tới song.php để lấy thông tin chi tiết bài hát
                    $.ajax({
                        url: '/web_nghe_nhac/app/pages/song.php',
                        type: 'GET',
                        data: { id: song.MaBaiHat },
                        success: function(response) {
                            if (response.results && response.results.length > 0) {
                                // Hiển thị bài hát với displaySong
                                displaySong(response.results[0]);
                            } else {
                                console.error('Không tìm thấy thông tin bài hát.');
                            }
                        },
                        error: function() {
                            console.error('Không thể tải bài hát.');
                        }
                    });
                });

                searchItems.appendChild(songItem);
            });

            // Hiển thị các nghệ sĩ
            results.artists.forEach(artist => {
                const artistItem = document.createElement('div');
                artistItem.className = 'search-item';
                artistItem.innerHTML = `
                    <a href="/web_nghe_nhac/public/assets/php/artist_info.php?id=${artist.MaNgheSy}">
                        <img src="/web_nghe_nhac/public/assets/img/data-artists-image/${artist.AnhNgheSy}" alt="${artist.TenNgheSy}">
                        <h3>${artist.TenNgheSy}</h3>
                    </a>
                `;
                searchItems.appendChild(artistItem);
            });

            // Nếu không có kết quả
            if (results.songs.length === 0 && results.artists.length === 0) {
                searchItems.innerHTML = '<p>Không tìm thấy kết quả nào.</p>';
            }
        },
        error: function() {
            console.error('Không thể thực hiện tìm kiếm.');
        }
    });
}

