<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/header_main.css">
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/main.css">
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/listeningSpace.css">
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/main_cpm.css">
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/left_side.css">
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/right_side.css">
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/lyric.css">
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/search.css">
    <link rel="stylesheet" href="/web_nghe_nhac/public/assets/css/playlist.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!--icon FontAwesome-->
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
    <!--icon FontAwesome-->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap">
    <!--font Inter-->


    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>Let chill</title>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
</head>

<body>
    <!-- header -->
    <header>
        <div class="controlbar">
            <div class="logo">
                <a href="/web_nghe_nhac/app/pages/home.php"><img src="/web_nghe_nhac/public/assets/img/logo.svg"
                        alt="logo"></a>
            </div>

            <div class="searching">
                <a href="/web_nghe_nhac/app/pages/home.php">
                    <div class="home">
                        <iconify-icon icon="ic:round-home"></iconify-icon>
                    </div>
                </a>
                <div class="input-wrapper" id="search-input">
                    <iconify-icon icon="lucide:search" name="search"></iconify-icon>
                    <input id="search-bar" type="text" class="search-input" placeholder="Tìm kiếm..." name="keyword"
                        oninput="performSearch(this.value)">
                </div>

                <div class="voice-searching">
                    <iconify-icon icon="mingcute:mic-fill"></iconify-icon>
                </div>
            </div>

            <div class="general">
                <div class="notif" id="notificationIcon">
                    <iconify-icon icon="ri:notification-4-line"></iconify-icon>
                </div>
                <div class="package" id="window">
                    <a href="pack-info.php">
                        <iconify-icon icon="material-symbols:select-window-2"></iconify-icon>
                    </a>
                </div>
                <div class="account">
                    <a href="/web_nghe_nhac/public/assets/php/profile-info.php">
                        <iconify-icon icon="mdi:account"></iconify-icon>
                    </a>

                </div>
                <p class="op_50">
                    <a href="/web_nghe_nhac/app/pages/logout.php" style="color: inherit; text-decoration: none;">ĐĂNG
                        XUẤT</a>
                </p>
            </div>
        </div>
    </header>