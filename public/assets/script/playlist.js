let playingPlaylist = null;
let currentPlaylist = null; // Lưu trữ playlist hiện tại
let currentSongs = []; // Danh sách bài hát hiện tại
let currentIndex = -1; // Chỉ số bài hát hiện tại
let isPlaying = false; // Trạng thái phát nhạc
//songs: mảng bài hát truyền từ control


// Hàm phát nhạc từ bài hát cụ thể
function playSong(songs, index) {
    if (index < 0 || index >= songs.length) {
        console.error("Chỉ số bài hát không hợp lệ:", index);
        return;
    }

    playingPlaylist = currentPlaylist; // Cập nhật playlist đang phát
    currentIndex = index; // Cập nhật bài hát đang phát

    const song = songs[index];
    const maBaiHat = songs[index].MaBaiHat;
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

    // Gọi API để lấy đánh giá
    fetch(`/web_nghe_nhac/public/assets/php/control/playlistControl.php?action=getReview&MaBaiHat=${maBaiHat}`)
    .then(response => response.json()) // Chuyển phản hồi thành JSON
    .then(data => {
        // Nếu không có đánh giá, gán giá trị mặc định
        if (!Array.isArray(data) || data.length === 0) {
            data = [
                {
                    TenNguoiDung: "(trống)",
                    BinhLuan: "(trống)",
                    DiemDG: 0
                }
            ];
        }
        displayReview(data); // Hiển thị đánh giá
    })
    .catch(error => console.error(`Lỗi khi lấy đánh giá bài hát:`, error));

    audioPlayer.src = `/web_nghe_nhac/public/song/${song.FileBaiHat}`; 
    // Đặt thời gian phát lại về 0
    audioPlayer.currentTime = 0;
    audioPlayer.play();
    console.log(`Đang phát: ${song.TenBaiHat} - ${song.TenNgheSy}`);
    isPlaying = true;

    // Khi bài hát kết thúc, phát bài kế tiếp
    audioPlayer.onended = () => {
        if (isRepeating) {
            audioPlayer.currentTime = 0;
            audioPlayer.play();
        } else {
            if (currentIndex + 1 < songs.length) {
                currentIndex++;
                playSong(songs, currentIndex);
                highlightSongPlaying(currentIndex);
            } else {
                console.log("Danh sách phát đã hết.");
                togglePlayPauseUI(false);
                isPlaying = false;
                highlightSongPlaying(null);
            }
        }
    };
}

// Hàm cập nhật giao diện nút play/pause
function togglePlayPauseUI(isPlaying) {
    const playButton = document.getElementById('playbutton');
    const circleButton = document.getElementById('circle');

    if (isPlaying) {
        playButton.classList.remove('fa-play');
        playButton.classList.add('fa-pause');
        mainControlIcon.setAttribute('icon', 'material-symbols:pause-rounded');
    } else {
        playButton.classList.remove('fa-pause');
        playButton.classList.add('fa-play');
        mainControlIcon.setAttribute('icon', 'solar:play-bold');
    }
}

// Load thông tin playlist khi người dùng click
function updateArtistView(maDSP, tenDSP, loaiDSP, imgPath) {
    if (currentPlaylist !== maDSP) {
        currentPlaylist = maDSP; // Cập nhật playlist hiện tại
        currentIndex = -1;
        togglePlayPauseUI(false);

    // Cập nhật tiêu đề và ảnh nghệ sĩ
    document.getElementById('artist-text').innerHTML = loaiDSP === 'Playlist' ? 'Danh sách phát' : 'Nghệ sĩ';
    document.getElementById('artist-name').innerHTML = `<b>${tenDSP}</b><br>`;
    const avatarImg = document.querySelector('#avatar-artist img');
    avatarImg.src = imgPath;
    avatarImg.style.borderRadius = loaiDSP === 'Playlist' ? '10px' : '50%';

    if (loaiDSP === 'Playlist') {
        // Fetch số bài hát từ server
        fetch(`/web_nghe_nhac/public/assets/php/control/playlistControl.php?action=count&maDSP=${maDSP}`)
            .then(response => response.text())
            .then(songCount => {
                document.getElementById('artist-follower').innerHTML = `${songCount} bài hát`;
                document.getElementById('follow-button').style.setProperty("display", "none", "important");
            })
            .catch(error => console.error('Lỗi khi lấy số bài hát:', error));

    } else {
        // Hiển thị số người theo dõi
        document.getElementById('artist-follower').innerHTML = `123.456.789 người theo dõi`;
        
        // Hiện nút theo dõi
        document.getElementById('follow-button').style.display = "block";
    }   

    loadSongs(maDSP)
    
    }
}

function togglePlayPause() {
    const playButton = document.getElementById('playbutton');
    const circleButton = document.getElementById('circle');
    if (isPlaying) {
        console.log("Tạm dừng bài hát hiện tại.");
        audioPlayer.pause(); // Dừng nhạc
        isPlaying = false; // Cập nhật trạng thái
        togglePlayPauseUI(false); // Cập nhật giao diện nút
        if (currentPlaylist != playingPlaylist) {
            currentIndex = 0;
            playSong(currentSongs, currentIndex);
            isPlaying = true;
            togglePlayPauseUI(true); // Cập nhật giao diện nút
            highlightSongPlaying(currentIndex);
        }

    } else {
        if (currentIndex === -1) {
            // Phát từ đầu playlist nếu chưa phát bài nào
            currentIndex = 0;
            playSong(currentSongs, currentIndex);
            highlightSongPlaying(currentIndex);
        } else {
            // Tiếp tục phát từ thời điểm đã dừng
            if (audioPlayer.currentTime > 0 && audioPlayer.paused) {
                audioPlayer.play();
            } else {
                playSong(currentSongs, currentIndex);
            }
        }

        isPlaying = true; // Đặt trạng thái đang phát
        togglePlayPauseUI(true); // Cập nhật giao diện nút
    }
}

function handlePlaylistActions(songs, index) {

    // Cập nhật danh sách phát hiện tại và bài hát đang phát
    currentSongs = songs; 
    currentIndex = index; 
    playingPlaylist = currentPlaylist; // Đặt playlist đang phát trùng với playlist hiện tại

    highlightSongPlaying(index); //Cập nhật giao diện bài hát đang phát

    // Phát bài hát
    playSong(currentSongs, currentIndex);

    // Cập nhật giao diện
    togglePlayPauseUI(true);
    console.log(`Phát bài hát: ${songs[index].TenBaiHat} trong playlist mã: ${currentPlaylist}`);
}

function highlightSongPlaying(index) {
    // Xóa class "playing" khỏi tất cả bài hát
    const songElements = document.querySelectorAll('#songs div');
    songElements.forEach((songElement) => {
        songElement.classList.remove('playing');
    });

    // Thêm class "playing" cho bài hát đang phát
    const currentSongElement = document.getElementById(`song${index}`);
    if (currentSongElement) {
        currentSongElement.classList.add('playing');
    }
}

function updateRatingDisplay(rating) { //Cập nhật giao diện rate điểm
    for (let i = 1; i <= 5; i++) {
        const rateElement = document.getElementById(`rate${i}`);

        if (i <= rating) {
            // Đổi màu cho các điểm trong mức đánh giá
            rateElement.style.color = '#296265';
        } else {
            // Trả lại màu mặc định (trắng hoặc màu bạn muốn)
            rateElement.style.color = '#ffffff';
        }
    }
}

// Hàm hiển thị đánh giá
function displayReview(reviews) {
    const reviewContainer = document.getElementById('user-rate');
    reviewContainer.innerHTML = ''; // Xóa nội dung cũ

    // Lấy đánh giá đầu tiên
    const firstReview = reviews[0];
    const { TenNguoiDung, BinhLuan, DiemDG } = firstReview;

    // Cập nhật giao diện đánh giá
    const reviewHTML = `
        <span id="custom-name">${TenNguoiDung}<br></span>
        <div class="rating-container" style="margin-top: 5px; margin-bottom: 4px;">
            <span id="rate1"><i class="fa-solid fa-circle"></i></span>
            <span id="rate2"><i class="fa-solid fa-circle"></i></span>
            <span id="rate3"><i class="fa-solid fa-circle"></i></span>
            <span id="rate4"><i class="fa-solid fa-circle"></i></span>
            <span id="rate5"><i class="fa-solid fa-circle"></i></span>
        </div>
        <span id="rate-comment">${BinhLuan}</span>
    `;

    reviewContainer.innerHTML = reviewHTML;

    // Cập nhật màu sắc của điểm đánh giá
    updateRatingDisplay(DiemDG);
}

//Load playlist mặc định
document.addEventListener('DOMContentLoaded', function() {
    // Gọi playlist mặc định từ server khi tải trang
    // Kiểm tra URL để lấy tham số 'id'
    const urlParams = new URLSearchParams(window.location.search);
    const playlistId = urlParams.get('id'); // Lấy giá trị của tham số 'id'

    // Xác định URL API tùy thuộc vào việc 'id' có tồn tại hay không
    const fetchUrl = playlistId
        ? `/web_nghe_nhac/public/assets/php/control/playlistControl.php?action=getPlaylist&id=${playlistId}`
        : '/web_nghe_nhac/public/assets/php/control/playlistControl.php?action=default';

        fetch(fetchUrl)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
            } else {
                // Cập nhật giao diện với playlist mặc định
                updateArtistView(data.maDSP, data.tenPlaylist, data.loaiDSP, data.imgPath);

                // Cập nhật số bài hát
                document.getElementById('artist-follower').innerHTML = `${data.songCount} bài hát`;
            }
        })
        .catch(error => console.error('Lỗi khi tải playlist mặc định:', error));
        // Ẩn nút theo dõi
        document.getElementById('follow-button').style.display = "none";
});

//Click plus-button hiện popup thêm danh sách phát
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('plus-button').addEventListener('click', showClick);
});
function showClick() {
        const createNewList = document.getElementById('create-newlist');
        const overlay = document.getElementById('overlay');
        createNewList.style.display = 'block'; // Hiển thị div
        overlay.style.display = 'block'; 
};
//Click return ẩnn popup thêm danh sách phát
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('return').addEventListener('click', hideClick);
});
function hideClick() {
        const createNewList = document.getElementById('create-newlist');
        const overlay = document.getElementById('overlay');
        createNewList.style.display = 'none'; // Hiển thị div
        overlay.style.display = 'none'; 
};

//Click info button hiện thanh info
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('info').addEventListener('click', infoClickShow);
});
function infoClickShow() {
        const infoDiv = document.getElementsByClassName("rightBar")[0]; //Lấy infoDiv
        const artistDiv = document.getElementById('artist');
        artistDiv.style.maxWidth = '680px'; // Thu hẹp div artist 
        infoDiv.style.width = '330px'; // Hiển thị div info
        infoDiv.style.border = '1px solid rgba(255, 255, 255, 0.15)';
        infoDiv.style.padding = '24px 25px';
};
//Click info button ẩn thanh info
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('exit-lbl').addEventListener('click', infoClickHide);
});
function infoClickHide() {
        const infoDiv = document.getElementsByClassName("rightBar")[0];
        const artistDiv = document.getElementById('artist');
        artistDiv.style.maxWidth = '1030px'; // mở rộng div artist
        infoDiv.style.width = '0px'; // Đóng div info
        infoDiv.style.border = '0px';
        infoDiv.style.padding = '0px';
};

//Hàm thêm danh sách phát
document.getElementById('newlist-form').addEventListener('submit', async (e) => {
    e.preventDefault(); // Ngăn form gửi đi mặc định

    // Lấy giá trị tên danh sách phát
    const nameListInput = document.querySelector('input[name="name-list"]');
    const nameList = nameListInput.value.trim();

    // Lấy giá trị mô tả
    const scriptionInput = document.querySelector('textarea[name="scription"]');
    const scription = scriptionInput.value.trim();

    // Lấy file ảnh
    const fileUpload = document.getElementById('file-upload');
    const imageFile = fileUpload.files[0];

    // Log dữ liệu lên console
    console.log('Tên danh sách phát:', nameList);
    console.log('Mô tả:', scription);
    console.log('File ảnh:', imageFile ? imageFile.name : 'Chưa chọn file');

    // Kiểm tra nếu tên danh sách phát trống
    if (!nameList) {
        alert('Vui lòng nhập tên danh sách phát!');
        nameListInput.focus();
        return;
    }

    // Kiểm tra nếu mô tả trống
    if (!scription) {
        alert('Vui lòng nhập mô tả!');
        scriptionInput.focus();
        return;
    }

    // Kiểm tra nếu chưa upload ảnh
    if (!imageFile) {
        alert('Vui lòng chọn ảnh!');
        fileUpload.click();
        return;
    }

    // Nếu đã nhập đầy đủ, tạo FormData để gửi đến server
    const formData = new FormData();
    formData.append('nameList', nameList); // Tên danh sách phát
    formData.append('scription', scription); // Mô tả
    formData.append('imageFile', imageFile); // File ảnh
    formData.append('action', 'addPlaylist'); // Thêm tham số action vào

    // Hiển thị dữ liệu FormData
    console.log('Dữ liệu FormData chuẩn bị gửi:');
    formData.forEach((value, key) => {
        if (key === 'imageFile') {
            console.log(`${key}: ${value.name}`); // Chỉ hiển thị tên file ảnh
        } else {
            console.log(`${key}: ${value}`);
        }
    });

    try {
        // Gửi dữ liệu đến server
        const response = await fetch('/web_nghe_nhac/public/assets/php/control/playlistControl.php', {
            method: 'POST',
            body: formData,
        });

    // Kiểm tra nội dung phản hồi
    const textResponse = await response.text(); // Lấy phản hồi thô dạng text
    console.log('Phản hồi từ server:', textResponse);

    // Chuyển đổi sang JSON
    const result = JSON.parse(textResponse);
        if (result.success) {
            alert('Thêm danh sách phát thành công!');
            window.location.href = "/web_nghe_nhac/public/assets/php/views/playlistView.php";
        } else {
            alert('Đã xảy ra lỗi: ' + result.message);
        }
    } catch (error) {
        console.error('Lỗi khi gửi yêu cầu:', error);
        alert('Đã xảy ra lỗi, vui lòng thử lại sau!');
    }
});
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

//Thêm bài hát vào danh sách phát
document.getElementById('form-them-bh').addEventListener('submit', function(event) {
    event.preventDefault(); // Ngừng hành động mặc định của form

    const tenBaiHat = document.getElementById('tenbh').value; // Lấy tên bài hát nhập từ form
    const maDSP = currentPlaylist; // Mã danh sách phát hiện tại (ví dụ như mã playlist)

    // Encode tên bài hát để gửi qua URL
    const encodedSongName = encodeURIComponent(tenBaiHat);

    // In ra dữ liệu trước khi gửi
    console.log('Dữ liệu gửi để tìm bài hát:', tenBaiHat);

    // URL tìm bài hát bằng phương thức GET
    const findSongUrl = `/web_nghe_nhac/public/assets/php/control/playlistControl.php?songName=${encodedSongName}`;


    fetch(findSongUrl)
        .then(response => response.text()) // Đọc phản hồi dưới dạng text
        .then(textResponse => {
            console.log('Response từ findSongByName:', textResponse);
            return JSON.parse(textResponse); // Chuyển từ text thành JSON và trả về
        })
        .then(data => {
            if (data.success) {
                // Bài hát đã tìm thấy, thêm vào playlist
                const maBaiHat = data.maBaiHat;

                const formData = new FormData();
                formData.append('action', 'addSongToPlaylist');
                formData.append('maDSP', maDSP);
                formData.append('maBaiHat', maBaiHat);

                // In ra dữ liệu để kiểm tra
                console.log('Dữ liệu gửi (FormData):', [...formData.entries()]);

                // Gửi yêu cầu thêm bài hát vào playlist
                return fetch('/web_nghe_nhac/public/assets/php/control/playlistControl.php', {
                    method: 'POST',
                    body: formData
                });
                } else {
                    // Không tìm thấy bài hát, hiển thị lỗi
                    alert('Không tìm thấy bài hát: ' + tenBaiHat);
                    throw new Error('Không tìm thấy bài hát');
                }
        })

    .then(response => response.text())  // Đọc phản hồi dưới dạng text
    .then(textResponse => {
        console.log('Response từ addSongToPlaylist:', textResponse);
        return JSON.parse(textResponse);  // Chuyển từ text thành JSON và trả về
    })
    .then(data => {
        if (data.success) {
            // Sau khi thêm bài hát thành công, load lại danh sách bài hát trong playlist
            alert('Thêm bài hát thành công!');
            loadSongs(maDSP); // Hàm load lại danh sách bài hát
        } else {
            alert('Có lỗi khi thêm bài hát.');
        }
    })
    .catch(error => {
        console.error('Lỗi khi thêm bài hát:', error);
        alert('Có lỗi xảy ra.');
    });
});

// Hàm tải lại danh sách bài hát trong playlist
function loadSongs(maDSP) {
    // Fetch danh sách bài hát từ server
    fetch(`/web_nghe_nhac/public/assets/php/control/playlistControl.php?action=songs&maDSP=${maDSP}`)
        .then(response => response.json())
        .then(songs => {
            currentSongs = songs; // Lưu danh sách bài hát
            const songsContainer = document.getElementById('songs');
            songsContainer.innerHTML = ''; // Xóa danh sách cũ

            // Duyệt qua danh sách bài hát và tạo HTML
            songs.forEach((song, index) => {
                const songDiv = document.createElement('div');
                songDiv.id = `song${index}`;
                songDiv.innerHTML = `
                    <span id="stt">${index + 1}</span>
                    <span><img src="/web_nghe_nhac/public/assets/img/data-songs-image/${song.AnhBaiHat}" alt="${song.TenBaiHat}"></span>
                    <span id="namesong">
                        ${song.TenBaiHat}<br>
                        <span id="author">${song.TenNgheSy}</span>
                    </span>
                    <span id="date">${formatDate(song.NgayThem)}</span>
                    <span id="delete-icon" data-song-id="${song.MaBaiHat}">
                            <i class="fa-solid fa-trash"></i>
                    </span>
                `;

                // Gán sự kiện click cho từng bài hát
                songDiv.addEventListener('click', () => {
                    handlePlaylistActions(songs, index); // Gán click cho từng bài hát trong playlist
                });

                // Thêm sự kiện riêng cho icon thùng rác
                const deleteIcon = songDiv.querySelector('#delete-icon');
                deleteIcon.addEventListener('click', (event) => {
                    event.stopPropagation(); // Ngăn sự kiện lan truyền đến songDiv
                    confirmDeleteSong(song.MaBaiHat, currentPlaylist); // Hiển thị form xác nhận xóa
                });

                songsContainer.appendChild(songDiv);
            });

        })
        .catch(error => console.error('Lỗi khi lấy danh sách bài hát:', error));
}

// Hàm định dạng ngày thành dd/mm/yyyy
function formatDate(dateString) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0'); // Thêm '0' nếu < 10
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Tháng +1 và thêm '0' nếu < 10
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}


//Click info button ẩn thanh info
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('add-song-button').addEventListener('click', addSongShow);
});
function addSongShow() {
        const addSongForm = document.getElementById('them-bh-playlist');
        const overlay = document.getElementById('overlay');
        addSongForm.style.display = 'flex'; // mở rộng div artist
        overlay.style.display = 'block';
};

//Click info button ẩn thanh info
document.addEventListener('DOMContentLoaded', function() {
    const returnButtons = document.querySelectorAll('#them-bh-playlist #return');
    returnButtons.forEach(button => {
        button.addEventListener('click', addSongHide);
    });
});
function addSongHide() {
        const addSongForm = document.getElementById('them-bh-playlist');
        const overlay = document.getElementById('overlay');
        addSongForm.style.display = 'none'; // mở rộng div artist
        overlay.style.display = 'none';
};


function confirmDeleteSong(songId, maDSP) {
    maDSP = currentPlaylist; // Lấy mã danh sách phát hiện tại từ biến currentPlaylist

    const deleteForm = document.getElementById('xoa-bh-playlist');
    deleteForm.style.display = 'flex'; // Hiển thị form xác nhận xóa

    const overlay = document.getElementById('overlay');
    overlay.style.display = 'block';
    

    const cancelButton = document.getElementById('cancel-button');
    const deleteButton = document.getElementById('xoa-bh-submit');

    // Hủy bỏ xóa bài hát)
    cancelButton.addEventListener('click', () => {
        deleteForm.style.display = 'none';
        const overlay = document.getElementById('overlay');
        overlay.style.display = 'none';
    });

    // Xác nhận xóa bài hát
    deleteButton.addEventListener('click', (event) => {
        event.preventDefault(); // Ngăn chặn gửi form mặc định

        // Sử dụng FormData để gửi dữ liệu
        const formData = new FormData();
        formData.append('action', 'deleteSongFromPlaylist'); // Thêm action vào FormData
        formData.append('songId', songId);
        formData.append('maDSP', maDSP);

        // Gửi yêu cầu xóa bài hát qua fetch API
        fetch(`/web_nghe_nhac/public/assets/php/control/playlistControl.php`, {
            method: 'POST',
            body: formData, // Gửi FormData thay vì JSON
        })
            .then(response => response.text()) // Đọc phản hồi dưới dạng text
            .then(textResponse => {
                console.log('Response từ xóa bài hát:', textResponse);
                return JSON.parse(textResponse); // Chuyển từ text thành JSON và trả về
            })
            .then(data => {
                if (data.success) {
                    alert('Xóa bài hát thành công!');
                    loadSongs(maDSP); // Reload lại danh sách bài hát
                } else {
                    alert('Xóa bài hát thất bại: ' + data.message);
                }
                deleteForm.style.display = 'none';
            })
            .catch(error => {
                console.error('Lỗi khi xóa bài hát:', error);
                alert('Đã xảy ra lỗi, vui lòng thử lại sau.');
            });
    });
}

//Hàm xóa playlist
document.getElementById("delete-playlist-button").addEventListener("click", () => {
    const popup = document.getElementById("popup-xoa-playlist");
    const overlay = document.getElementById('overlay');

    popup.style.display = "flex"; // Hiển thị form xác nhận xóa
    overlay.style.display = 'block';

    // Nút cancel
    document.getElementById("cancel-button-playlist").addEventListener("click", () => {
        popup.style.display = "none"; // Ẩn form
        overlay.style.display = 'none';
    });

    // Nút xóa
    document.getElementById("form-xoa-playlist").addEventListener("submit", (event) => {
        event.preventDefault(); // Ngăn hành vi mặc định của form

        const formData = new FormData();
        formData.append('action', 'deletePlaylist'); // Thêm action vào FormData
        formData.append('maDSP', currentPlaylist); // Gửi mã danh sách phát hiện tại

        // Gửi yêu cầu POST đến server
        fetch(`/web_nghe_nhac/public/assets/php/control/playlistControl.php`, {
            method: "POST",
            body: formData
        })
            .then(response => response.text()) // Đọc phản hồi dưới dạng text
            .then(textResponse => {
                console.log('Response từ xóa danh sách phát:', textResponse);
                return JSON.parse(textResponse); // Chuyển từ text thành JSON và trả về
            })
            .then((data) => {
                if (data.success) {
                    alert("Danh sách phát đã được xóa thành công!");
                    popup.style.display = "none"; // Ẩn form
                    // Tải lại hoặc chuyển hướng
                    location.reload(); // Tùy chỉnh theo nhu cầu
                } else {
                    alert("Xóa danh sách phát thất bại: " + data.message);
                }
            })
            .catch((error) => {
                console.error("Lỗi khi xóa danh sách phát:", error);
                alert("Đã xảy ra lỗi, vui lòng thử lại sau.");
            });
    });
});



