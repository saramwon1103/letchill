//Hiện ảnh playlist khi upload ảnh trong lúc thêm danh sách phát
document.getElementById('file-upload').addEventListener('change', (e) => {
    const imageFile = e.target.files[0];

    if (imageFile) {
        const imagePreview = URL.createObjectURL(imageFile); // Tạo URL tạm thời từ file
        const chooseImageButton = document.getElementById('choose-image');

        // Thay đổi ảnh hiển thị trên nút chọn ảnh
        chooseImageButton.innerHTML = `<img src="${imagePreview}" alt="Preview" style="border: 1px solid rgba(255, 255, 255, 0.2);">`;
    }
});

document.getElementById("search-button").addEventListener("click", function() {
    const searchInput = document.getElementById("search-input").value;

    // Kiểm tra nếu input rỗng
    if (!searchInput.trim()) {
        alert("Vui lòng nhập từ khóa tìm kiếm.");
        return;
    }

    // Gửi yêu cầu AJAX tới server
    fetch(`/web_nghe_nhac/public/assets/php/control/song_manage_control.php?action=search&tuKhoa=${encodeURIComponent(searchInput)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Có lỗi kết nối xảy ra khi tìm kiếm.");
            }
            return response.json(); // Chuyển đổi JSON trả về
        })
        .then(songs => {
            // Hiển thị danh sách bài hát
            const songsListDiv = document.getElementById("songs-list");
            songsListDiv.innerHTML = ""; // Xóa nội dung cũ

            if (songs.length === 0) {
                songsListDiv.innerHTML = "<p>Không tìm thấy bài hát nào.</p>";
                return;
            }

            songs.forEach((song, index) => {
                const songDiv = `
                    <div id="song">
                        <input id="checkbox" type="checkbox">
                        <span id="stt">${index + 1}</span>
                        <img id="img" src="/web_nghe_nhac/public/assets/img/data-songs-image/${song.AnhBaiHat}" alt="${song.TenBaiHat}">
                        <span id="title">
                            <span id="tenBH">${song.TenBaiHat}</span>
                            <span id="tenNS">${song.TenNgheSy}</span>
                        </span>
                        <span id="type">${song.TenTheLoai}</span>
                    </div>
                `;
                songsListDiv.innerHTML += songDiv;
            });
        })
        .catch(error => {
            console.error(error);
            alert("Không thể thực hiện tìm kiếm. Vui lòng thử lại sau.");
        });
});

document.getElementById('search-input').addEventListener('input', function() {
    if (this.value === '') {
        // Gửi yêu cầu lấy toàn bộ bài hát khi input rỗng
        fetch(`/web_nghe_nhac/public/assets/php/control/song_manage_control.php?action=getAll`)
            .then(response => response.json())
            .then(songs => {
                // Hiển thị danh sách bài hát
                const songsListDiv = document.getElementById("songs-list");
                songsListDiv.innerHTML = ""; // Xóa nội dung cũ

                songs.forEach((song, index) => {
                    const songDiv = `
                        <div class="song">
                            <input class="checkbox" type="checkbox">
                            <span class="stt">${index + 1}</span>
                            <img class="img" src="/web_nghe_nhac/public/assets/img/data-songs-image/${song.AnhBaiHat}" alt="${song.TenBaiHat}">
                            <span class="title">
                                <span class="tenBH">${song.TenBaiHat}</span>
                                <span class="tenNS">${song.TenNgheSy}</span>
                            </span>
                            <span class="type">${song.TenTheLoai}</span>
                        </div>
                    `;
                    songsListDiv.innerHTML += songDiv;
                });
            })
            .catch(error => console.error('Error:', error));
    }
});
//Thêm bài hát
document.getElementById('form-them-bh').addEventListener('submit', function (event) {
    event.preventDefault(); // Ngăn form tự reload trang

    // Lấy dữ liệu từ các input
    const imagefileInput = document.getElementById('file-upload');
    const songFileInput = document.getElementById('upload-bai-hat');
    const tenBaiHat = document.getElementById('them-ten-bai-hat').value.trim();
    const tenNgheSy = document.getElementById('them-ten-nghe-sy').value.trim();
    const maTheLoai = document.getElementById('select-theloai').value;
    const lyrics = document.querySelector('textarea[name="lyrics"]').value.trim();

    if (!imagefileInput.files[0] || !songFileInput.files[0] || !tenBaiHat || !tenNgheSy || !maTheLoai || !lyrics) {
        alert('Vui lòng nhập đầy đủ thông tin.');
        return;
    }
    const imageFileUpload = imagefileInput.files[0];
    const songFileUpload = songFileInput.files[0];


    // Log dữ liệu lên console
    console.log('Tên bài hát:', tenBaiHat);
    console.log('Tên nghệ sỹ:', tenNgheSy);
    console.log('File ảnh:', imageFileUpload.name);
    console.log('File nhạc:', songFileUpload.name);
    console.log('Mã thể loại:', maTheLoai);
    console.log('Lyrics:', lyrics);

    // Đóng gói dữ liệu gửi lên server
    const formData = new FormData();
    formData.append('action', 'addSong');
    formData.append('file-upload', imagefileInput.files[0]);
    formData.append('upload-bai-hat', songFileInput.files[0]);
    formData.append('name-bh', tenBaiHat);
    formData.append('name-artist', tenNgheSy);
    formData.append('theloai', maTheLoai);
    formData.append('lyrics', lyrics);
    // Gửi dữ liệu qua Fetch API
    fetch('/web_nghe_nhac/public/assets/php/control/song_manage_control.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text())  // Đọc phản hồi dưới dạng text
    .then(textResponse => {
        console.log('Response từ findSongByName:', textResponse);
        return JSON.parse(textResponse);  // Chuyển từ text thành JSON và trả về
    })
    .then(data => {
    // Hiển thị dữ liệu JSON đã được chuyển đổi
    console.log('Dữ liệu JSON nhận được từ server:', data);
        if (data.success) {
            alert(data.message);
            location.reload(); // Reload lại trang sau khi thêm bài hát thành công
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

document.addEventListener("DOMContentLoaded", function () {
    // Đóng popup khi nhấn nút Cancel
    document.getElementById("cancel-button-admin").addEventListener("click", function () {
        const popup = document.getElementById("xoa-bh-admin");
        const overlay = document.getElementById("overlay");
        popup.style.display = "none";
        overlay.style.display = "none";
    });

    // Mở popup khi nhấn nút Xóa bài hát
    document.getElementById("button-xoa-bai-hat").addEventListener("click", function () {
        const selectedCheckboxes = document.querySelectorAll('.checkbox:checked');

        if (selectedCheckboxes.length === 0) {
            alert('Vui lòng chọn ít nhất một bài hát để xóa.');
            return;
        }

        const popup = document.getElementById("xoa-bh-admin");
        const overlay = document.getElementById("overlay");

        popup.style.display = "flex";
        overlay.style.display = "block";

        // Gán danh sách ID bài hát vào thuộc tính data
        const selectedSongIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
        popup.setAttribute("data-song-ids", JSON.stringify(selectedSongIds));
    });

    // Gửi yêu cầu xóa bài hát khi nhấn nút Xóa trong popup
    document.getElementById("xoa-bh-submit-admin").addEventListener("click", function () {
        const popup = document.getElementById("xoa-bh-admin");
        const selectedSongIds = JSON.parse(popup.getAttribute("data-song-ids"));

        console.log('Danh sách mã bài hát gửi đi:', selectedSongIds);

        const formData = new FormData();
        formData.append('action', 'deleteSongs');
        formData.append('songIds', JSON.stringify(selectedSongIds));

        console.log('Dữ liệu gửi đi:', formData); // Debug FormData

        fetch('/web_nghe_nhac/public/assets/php/control/song_manage_control.php', {
            method: 'POST',
            body: formData,
        })
            .then(response => response.text())
            .then(textResponse => {
                console.log('Phản hồi từ server:', textResponse); // Log phản hồi từ server

                try {
                    const data = JSON.parse(textResponse); // Thử phân tích JSON
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Reload lại trang nếu thành công
                    } else {
                        alert('Có lỗi xảy ra: ' + data.message);
                    }
                } catch (error) {
                    console.error('Lỗi phân tích JSON:', error);
                }
            })
            .catch(error => {
                console.error('Lỗi khi gửi request:', error);
            });

        // Đóng popup
        const overlay = document.getElementById("overlay");
        popup.style.display = "none";
        overlay.style.display = "none";
    });
});


let selectedMaBaiHat = null; // Khai báo biến toàn cục 1 mã bài hát được chọn
//Hiện giá trị bài hát lên form
document.getElementById("button-cap-nhat-bai-hat").addEventListener("click", async function () {
    const checkboxes = document.querySelectorAll("#songs-list .checkbox");
    const selectedCheckboxes = Array.from(checkboxes).filter((checkbox) => checkbox.checked);

    if (selectedCheckboxes.length === 0) {
        alert("Vui lòng chọn một bài hát để cập nhật!");
    } else if (selectedCheckboxes.length > 1) {
        alert("Chỉ được chọn 1 bài hát để cập nhật!");
    } else {
        const selectedSongId = selectedCheckboxes[0].value;

        // Lấy thông tin bài hát từ server
        const response = await fetch(`/web_nghe_nhac/public/assets/php/control/song_manage_control.php?action=getSongById&id=${selectedSongId}`);
        const songData = await response.json();

        if (songData) {
            // Hiển thị popup
            const overlay = document.getElementById("overlay");
            const popup = document.getElementById("cap-nhat-bh-popup");
            popup.style.display = "flex";
            overlay.style.display = "block"

            // Điền dữ liệu vào form
            document.getElementById("cap-nhat-id-bh").value = selectedSongId;
            document.getElementById("cap-nhat-ten-bai-hat").value = songData.TenBaiHat;
            document.getElementById("cap-nhat-ten-nghe-sy").value = songData.TenNgheSy;
            document.getElementById("cap-nhat-select-theloai").value = songData.MaTheLoai;
            document.getElementById("cap-nhat-loi-bai-hat").value = songData.LoiBaiHat;

            selectedMaBaiHat = selectedSongId;
        }
    }
});

// Khi nhấn nút cập nhật
document.getElementById("cap-nhat-submit").addEventListener("click", async function (event) {
    event.preventDefault();

    const formData = new FormData(document.forms["form-cap-nhat-bh"]);

    const maTheLoai = document.getElementById("cap-nhat-select-theloai").value;
    const tenBaiHat = document.getElementById("cap-nhat-ten-bai-hat").value;
    const tenNgheSy = document.getElementById("cap-nhat-ten-nghe-sy").value;
    const loiBaiHat = document.getElementById("cap-nhat-loi-bai-hat").value;    

    // Thêm dữ liệu cần thiết vào formData
    formData.append("theloai", maTheLoai); // Mã thể loại
    formData.append("maBaiHat", selectedMaBaiHat); // Mã bài hát
    formData.append("tenBaiHat", tenBaiHat); // Tên bài hát
    formData.append("tenNgheSy", tenNgheSy); // Tên nghệ sỹ
    formData.append("loiBaiHat", loiBaiHat); // Lời bài hát
    formData.append("action", "updateSong");

    // Debug log
    console.log("Form Data trước khi gửi:");
    console.log("selectedMaBaiHat:", selectedMaBaiHat);
    console.log("Tên bài hát:", tenBaiHat);
    console.log("Lời bài hát:", loiBaiHat);
    console.log("Mã thể loại:", maTheLoai);
    console.log("Tên nghệ sỹ:", tenNgheSy);

    // Gửi yêu cầu cập nhật
    try {
        const response = await fetch("/web_nghe_nhac/public/assets/php/control/song_manage_control.php", {
            method: "POST",
            body: formData,
        });

        // Đọc phản hồi từ server dưới dạng text
        const textResponse = await response.text();
        console.log('Phản hồi từ server:', textResponse);

        // Parse lại thành JSON để kiểm tra kết quả
        const result = JSON.parse(textResponse);

        if (result.success) {
            alert("Cập nhật bài hát thành công!");
            const popup = document.getElementById("cap-nhat-bh-popup");
            const overlay = document.getElementById("overlay");
            popup.style.display = "none";
            overlay.style.display = "none"
            location.reload();
        } else {
            alert("Cập nhật thất bại: " + result.message);
        }
    } catch (error) {
        console.error("Lỗi kết nối:", error);
        alert("Có lỗi xảy ra khi kết nối tới máy chủ.");
    }
});


// Đóng popup cập nhật bài hát khi nhấn nút quay lại
document.getElementById("cap-nhat-return").addEventListener("click", function () {
    const popup = document.getElementById("cap-nhat-bh-popup");
    const overlay = document.getElementById("overlay");
    popup.style.display = "none";
    overlay.style.display = "none"
});
// Đóng popup thêm bài hát khi nhấn nút quay lại
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("them-bh-return").addEventListener("click", function () {
        const popup = document.getElementById("them-bh-popup");
        const overlay = document.getElementById("overlay");
        popup.style.display = "none";
        overlay.style.display = "none";
    });
});
// Mở popup khi nhấn nút thêm bài hát
document.getElementById("thembaihat-div").addEventListener("click", function () {
    const popup = document.getElementById("them-bh-popup");
    const overlay = document.getElementById("overlay");
    popup.style.display = "flex";
    overlay.style.display = "block"
});







