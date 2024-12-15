let list = document.querySelector('.centerSpace .sliderContainer #slider .imgList');
let items = document.querySelectorAll('.centerSpace .sliderContainer #slider .itemImg');
let nameSong = document.getElementById('nameInfo');
let authorSong = document.getElementById('authodInfo');
let dots = document.querySelectorAll('.centerSpace .sliderContainer #slider .dots li');

let countItem = items.length;
let itemActive = 0;

let songNames = [
    'Ngáo ngơ',
    'Đừng làm trái tim anh đau',
    'New Woman',
    'Seenderella',
    'Mộng Yu'
];

let authorNames = [
    'Erik, Jsol, Orange, HIEUTHUHAI, Anh Tú Atus',
    'Sơn Tùng MTP',
    'Lisa ft.Rosalia',
    'Chi Xê',
    'AMEE'
];

// const scrollable = document.getElementsByClassName('scrollable'); scroll;
// let isDown = false;
// let startX, scrollLeft, startY, scrollTop;
// let isDragging = false;

// scrollable.addEventListener('mousedown', (e) => {
//     isDown = true;
//     scrollable.classList.add('active');

//     startX = e.pageX - scrollable.offsetLeft;
//     startY = e.pageY - scrollable.offsetTop;

//     scrollLeft = scrollable.scrollLeft;
//     scrollTop = scrollable.scrollTop;
// });

// scrollable.addEventListener('mousemove', (e) => {
//     if (!isDown) return;
//     e.preventDefault();

//     const x = e.pageX - scrollable.offsetLeft;
//     const y = e.pageY - scrollable.offsetTop;
//     const walkX = (x - startX) * 1;
//     const walkY = (y - startY) * 1;

//     scrollable.scrollLeft = scrollLeft - walkX;
//     scrollable.scrollTop = scrollTop - walkY;
// });

// scrollable.addEventListener('mouseup', () => {
//     isDown = false;
// });

// scrollable.addEventListener('mouseleave', () => {
//     isDown = false;
// });


let refreshSlider = setInterval(() => nextSlider(), 3000);

function reloadSlider() {
    let checkLeft = items[itemActive].offsetLeft;
    list.style.transform = `translateX(${-checkLeft}px)`;

    nameSong.innerText = songNames[itemActive];
    authorSong.innerText = authorNames[itemActive];

    let lastActiveDot = document.querySelector('#slider .dots li.active');
    if (lastActiveDot) lastActiveDot.classList.remove('active');
    dots[itemActive].classList.add('active');
}

function nextSlider() {
    itemActive++;
    if (itemActive >= countItem) itemActive = 0;
    reloadSlider();
}

dots.forEach((li, key) => {
    li.addEventListener('click', function () {
        itemActive = key;
        reloadSlider();
        clearInterval(refreshSlider);
        refreshSlider = setInterval(() => nextSlider(), 3000);
    });
});