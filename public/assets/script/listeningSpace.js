// const audioPlayer = document.getElementById('audioPlayer');
const progressContainer = document.getElementById('progressContainer');
const progress = document.getElementById('progress');
const currentTime = document.getElementById('currentTime');
const duration = document.getElementById('duration');

const mainControlBtn = document.getElementById('mainControlBtn');
const mainControlIcon = document.getElementById('mainControlIcon');
const lyricsBtn = document.getElementById('lyricsBtn');
const lyricsIcon = document.getElementById('lyricsIcon');
const backIcon = document.getElementById('backIcon');
const nextIcon = document.getElementById('nextIcon');
const returnBtn = document.getElementById('returnBtn');
const returnIcon = document.getElementById('returnIcon');

const infoBtn = document.getElementById('info');
const infoIcon = document.getElementById('infoIcon');
const volumnContainer = document.getElementById('volumnContainer');
const outputBtn = document.getElementById('output');
const outputIcon = document.getElementById('outputIcon');

let songs = [];  // Khởi tạo mảng chứa bài hát
let currentSongIndex = 0;  // Chỉ số bài hát hiện tại

// Sau khi dữ liệu đã được tải xong
if (songs.length > 0) {
    displaySong(songs[currentSongIndex]); // Hiển thị bài hát đầu tiên
}


// Load danh sách bài hát từ API
async function loadSongs() {
    try {
        const response = await fetch('/web_nghe_nhac/app/pages/song.php');
        if (!response.ok) {
            throw new Error(`Lỗi tải API: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();
        console.log('Phản hồi từ API:', data); // Kiểm tra phản hồi từ API

        const songs = data.results || []; // Đảm bảo danh sách có giá trị mặc định
        console.log('Danh sách bài hát:', songs);

        if (songs.length === 0) {
            console.error('Danh sách bài hát không có dữ liệu.');
            return;
        }

        // Lưu danh sách bài hát vào biến toàn cục
        window.songs = songs;

        // Hiển thị bài hát đầu tiên
        displaySong(songs[0]);
    } catch (error) {
        console.error('Lỗi khi tải danh sách bài hát:', error);
    }
}

// Play or Resume the song
mainControlBtn.addEventListener('click', () => {
    if (audioPlayer.paused) {
        audioPlayer.play();
        mainControlIcon.setAttribute('icon', 'material-symbols:pause-rounded');
    } else {
        audioPlayer.pause();
        mainControlIcon.setAttribute('icon', 'solar:play-bold');
    }
});

// Cập nhật thời lượng khi bài hát sẵn sàng
audioPlayer.addEventListener('loadedmetadata', () => {
    const durationMinutes = Math.floor(audioPlayer.duration / 60);
    const durationSeconds = Math.floor(audioPlayer.duration % 60);
    document.getElementById('duration').textContent = `${durationMinutes}:${durationSeconds < 10 ? '0' : ''}${durationSeconds}`;
});


// Update progress
audioPlayer.addEventListener('timeupdate', () => {
    const current = audioPlayer.currentTime;
    const totalDuration = audioPlayer.duration;

    if (isNaN(totalDuration)) {
        console.error('Thời lượng không hợp lệ:', totalDuration);
        return; // Nếu thời gian không hợp lệ, không cập nhật
    }

    // Cập nhật thanh tiến trình
    const progressPercent = (current / totalDuration) * 100;
    document.getElementById('progress').style.width = `${progressPercent}%`;

    // Hiển thị thời gian hiện tại
    const currentMinutes = Math.floor(current / 60);
    const currentSeconds = Math.floor(current % 60);
    document.getElementById('currentTime').textContent =
        `${currentMinutes}:${currentSeconds < 10 ? '0' : ''}${currentSeconds}`;

    // Hiển thị thời lượng bài hát
    const durationMinutes = Math.floor(totalDuration / 60);
    const durationSeconds = Math.floor(totalDuration % 60);
    document.getElementById('duration').textContent =
        `${durationMinutes}:${durationSeconds < 10 ? '0' : ''}${durationSeconds}`;
});


document.getElementById('progressContainer').addEventListener('click', (e) => {
    const width = e.target.clientWidth;
    const clickX = e.offsetX;
    const duration = audioPlayer.duration;

    if (isNaN(duration)) {
        console.error("Thời gian không hợp lệ");
        return;
    }

    audioPlayer.currentTime = (clickX / width) * duration;
});

//Change color button
lyricsBtn.addEventListener('click', () => {
    lyricsIcon.classList.toggle('icon-active');
});

returnBtn.addEventListener('click', () => {
    returnIcon.classList.toggle('icon-active');
})

infoBtn.addEventListener('click', () => {
    infoIcon.classList.toggle('icon-active');
});

outputBtn.addEventListener('click', () => {
    outputIcon.classList.toggle('icon-active');
    if (outputIcon.classList.contains('icon-active')) {
        audioPlayer.muted = false;
        volumnContainer.style.backgroundColor = '';
    } else {
        audioPlayer.muted = true;
        volumnContainer.style.backgroundColor = '#5c5c5c';
    }
})

// Change volumn when change column slider
volumnContainer.addEventListener('input', (e) => {
    const volumnValue = e.target.value / 100;
    audioPlayer.volume = volumnValue;
    audioPlayer.muted = false;
    if (volumnValue === 0) {
        outputIcon.classList.remove('icon-active');
    } else {
        outputIcon.classList.add('icon-active');
    }

});

//hiện ẩn lời
$(document.body).ready(function () {
    $('#lyricsBtn').click(function () {
        $('.wrapperSlider').toggle();
        $('.wrapper-lyric').toggle();
    });
});

//hien an thong tin bai hat
$(document.body).ready(function () {
    $('#info').click(function () {
        $('.rightBar').toggle();
    })

});

window.currentSongIndex = 0; // Đặt giá trị ban đầu


nextBtn.addEventListener('click', () => {
    console.log('Next button clicked');

    if (typeof currentPlaylist !== 'undefined' && currentPlaylist) {
        // Đang phát từ playlist
        console.log('Đang phát từ playlist.');

        if (!Array.isArray(currentSongs) || currentSongs.length === 0) {
            console.error('Danh sách bài hát trong playlist trống hoặc không hợp lệ:', currentSongs);
            return;
        }

        if (currentIndex + 1 < currentSongs.length) {
            currentIndex++;
            playSong(currentSongs, currentIndex); // Phát bài hát tiếp theo trong playlist
            highlightSongPlaying(currentIndex);  // Làm nổi bật bài hát hiện tại
        } else {
            console.log('Playlist đã phát hết.');
            togglePlayPauseUI(false); // Cập nhật UI dừng phát
            isPlaying = false;
            highlightSongPlaying(null); // Hủy làm nổi bật bài hát
        }
    } else {
        // Đang phát từ danh sách bài hát thông thường
        console.log('Đang phát từ danh sách bài hát thông thường.');

        if (!Array.isArray(window.songs) || window.songs.length === 0) {
            console.error('Danh sách bài hát trống hoặc không hợp lệ:', window.songs);
            return;
        }

        if (typeof window.currentSongIndex !== 'number') {
            console.error('currentSongIndex không hợp lệ:', window.currentSongIndex);
            window.currentSongIndex = 0; // Reset về giá trị hợp lệ
        }

        // Chuyển sang bài hát tiếp theo
        window.currentSongIndex = (window.currentSongIndex + 1) % window.songs.length;

        displaySong(window.songs[window.currentSongIndex]); // Hiển thị bài hát
        audioPlayer.src = `/web_nghe_nhac/public/song/${window.songs[window.currentSongIndex].FileBaiHat}`; 
        audioPlayer.play(); // Phát bài hát
        mainControlIcon.setAttribute('icon', 'material-symbols:pause-rounded');
    }
});

backBtn.addEventListener('click', () => {
    console.log('Back button clicked');

    if (typeof currentPlaylist !== 'undefined' && currentPlaylist) {
        // Đang phát từ playlist
        console.log('Đang phát từ playlist.');

        if (!Array.isArray(currentSongs) || currentSongs.length === 0) {
            console.error('Danh sách bài hát trong playlist trống hoặc không hợp lệ:', currentSongs);
            return;
        }

        if (currentIndex > 0) {
            currentIndex--;
            playSong(currentSongs, currentIndex); // Phát bài hát trước đó trong playlist
            highlightSongPlaying(currentIndex);  // Làm nổi bật bài hát hiện tại
        } else {
            console.log('Đây là bài hát đầu tiên trong playlist.');
        }
    } else {
        // Đang phát từ danh sách bài hát thông thường
        console.log('Đang phát từ danh sách bài hát thông thường.');

        if (!Array.isArray(window.songs) || window.songs.length === 0) {
            console.error('Danh sách bài hát trống hoặc không hợp lệ:', window.songs);
            return;
        }

        if (typeof window.currentSongIndex !== 'number') {
            console.error('currentSongIndex không hợp lệ:', window.currentSongIndex);
            window.currentSongIndex = 0; // Reset về giá trị hợp lệ
        }

        // Chuyển sang bài hát trước đó
        window.currentSongIndex = (window.currentSongIndex - 1 + window.songs.length) % window.songs.length;

        displaySong(window.songs[window.currentSongIndex]); // Hiển thị bài hát
        audioPlayer.src = `/web_nghe_nhac/public/song/${window.songs[window.currentSongIndex].FileBaiHat}`; 
        audioPlayer.play(); // Phát bài hát
        mainControlIcon.setAttribute('icon', 'material-symbols:pause-rounded');
    }
});


let isRepeating = false;

returnBtn.addEventListener('click', () => {
    isRepeating = !isRepeating; // Bật/tắt chế độ lặp lại
    returnIcon.classList.toggle('icon-active', isRepeating);
});

// Lặp lại bài hát khi kết thúc
audioPlayer.addEventListener('ended', () => {
    if (isRepeating) {
        audioPlayer.currentTime = 0;
        audioPlayer.play();
    } else {
        // Chuyển sang bài tiếp theo nếu không ở chế độ repeat
        nextBtn.click();
    }
});

