<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['loading_shown'])) {
    $_SESSION['loading_shown'] = true;
    header('Location: loading.html');
    exit();
}

$auth = new Auth();
$events = getEvents();
$artists = getArtists();

// Updated current date/time
$currentDateTime = '2025-06-24 12:20:48';

// Current user
$currentUser = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';;

// Language support
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$_SESSION['lang'] = $lang;

// Language translations
$translations = [
    'en' => [
        'home' => 'HOME',
        'artists' => 'ARTISTS',
        'schedule' => 'SCHEDULE',
        'tickets' => 'TICKETS',
        'merchandise' => 'MERCHANDISE',
        'profile' => 'PROFILE',
        'admin' => 'ADMIN',
        'login' => 'LOGIN',
        'register' => 'REGISTER',
        'logout' => 'LOGOUT',
        'this_music' => 'THIS MUSIC',
        'festival_will_be' => 'FESTIVAL WILL BE',
        'the_brightest' => 'THE BRIGHTEST',
        'days' => 'DAYS',
        'hours' => 'HOURS',
        'minutes' => 'MINUTES',
        'seconds' => 'SECONDS',
        'meet_lineup' => 'MEET OUR LINE-UP',
        'get_tickets' => 'GET YOUR TICKETS',
        'most_anticipated' => 'THE MOST UNFORGETTABLE NIGHT OF MUSIC IS COMING — DONT MISS THE VIBE EVERYONES TALKING ABOUT!',
        'event_schedule' => 'EVENT SCHEDULE',
        'buy_now' => 'BUY NOW',
        'login_to_buy' => 'LOGIN TO BUY',
        'our_music' => 'OUR MUSIC GIVES EMOTIONS AND ENERGIZES',
        'ticket_label' => 'TICKET', 
        'available' => 'Available',
        'merch_subtitle' => 'TAKE THE FESTIVAL HOME WITH YOU',
        'add_to_cart' => 'ADD TO CART',
        'view_details' => 'VIEW DETAILS',
        'size' => 'Size',
        'color' => 'Color',
        'select_options' => 'SELECT OPTIONS',
        'out_of_stock' => 'OUT OF STOCK',
        'view_all_merch' => 'VIEW ALL MERCHANDISE',
        'select_options' => 'SELECT OPTIONS',
        'add_to_cart' => 'ADD TO CART',
        'out_of_stock' => 'OUT OF STOCK',
        'merchandise' => 'MERCHANDISE',
        'merch_subtitle' => 'Official PULSE Festival 2025 Merchandise',
    ],
    'ka' => [
        'home' => 'მთავარი',
        'artists' => 'არტისტები',
        'schedule' => 'განრიგი',
        'tickets' => 'ბილეთები',
        'merchandise' => 'მერჩანდაიზი',
        'profile' => 'პროფილი',
        'admin' => 'ადმინი',
        'login' => 'შესვლა',
        'register' => 'რეგისტრაცია',
        'logout' => 'გასვლა',
        'this_music' => 'ეს მუსიკალური',
        'festival_will_be' => 'ფესტივალი იქნება',
        'the_brightest' => 'ყველაზე ნათელი',
        'days' => 'დღე',
        'hours' => 'საათი',
        'minutes' => 'წუთი',
        'seconds' => 'წამი',
        'meet_lineup' => 'გაიცანით ჩვენი შემადგენლობა',
        'get_tickets' => 'იყიდეთ ბილეთები',
        'most_anticipated' => 'მუსიკის ყველაზე დაუვიწყარი ღამე ახლოვდება — არ გამოტოვო ატმოსფერო, რომელზეც ყველა ლაპარაკობს!',
        'event_schedule' => 'ღონისძიების განრიგი',
        'buy_now' => 'ყიდვა ახლავე',
        'login_to_buy' => 'შესვლა ყიდვისთვის',
        'our_music' => 'ჩვენი მუსიკა გრძნობებს იწვევს და ენერგიას გვმატებს',
        'ticket_label' => 'ბილეთი',
        'available' => 'ხელმისაწვდომია',
        'merch_subtitle' => 'წაიღე ფესტივალის ნაწილი სახლში',
        'add_to_cart' => 'კალათაში დამატება',
        'view_details' => 'დეტალების ნახვა',
        'size' => 'ზომა',
        'color' => 'ფერი',
        'select_options' => 'არჩევა',
        'out_of_stock' => 'არ არის მარაგში',
        'view_all_merch' => 'ყველა მერჩის ნახვა',
        'select_options' => 'ვარიანტების არჩევა',
        'add_to_cart' => 'კალათაში დამატება',
        'out_of_stock' => 'არ არის მარაგში',
        'merchandise' => 'მერჩი',
        'merch_subtitle' => 'PULSE ფესტივალის 2025 ოფიციალური მერჩი'
    ]
];

$t = $translations[$lang];

// Calculate real countdown using updated current time
$festival_date = '2025-07-28 00:00:00';
$current_time = $currentDateTime; // Using the updated current time
$diff = strtotime($festival_date) - strtotime($current_time);

$days = floor($diff / (60 * 60 * 24));
$hours = floor(($diff % (60 * 60 * 24)) / (60 * 60));
$minutes = floor(($diff % (60 * 60)) / 60);
$seconds = $diff % 60;
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PULSE Music Festival</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'festival-green': '#16FF00 ',
                        'festival-purple': '#ff00ff',
                        'festival-blue': '#0F6292 ',
                        'festival-yellow': '#3bff44 ',
                        'festival-pink': '#E900FF',
                        'festival-cyan': '#00FFFF'
                    },
                    screens: {
                        'xs': '480px'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap');
        
        :root {
            --neon-highlight: #CCFF00;
            --neon-purple: #ff00ff;
            --neon-blue: #00FFFF;
        }
        
        .festival-font { 
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            letter-spacing: 0.03em;
        }
        
        .main-font { 
            font-family: 'Montserrat', sans-serif; 
        }
        
        .highlight-text {
            color: var(--neon-highlight);
        }
        
        body {
            background-color: #000;
            overflow-x: hidden;
        }
        
        .glow { text-shadow: 0 0 20px currentColor; }
        
        .neon-text {
            text-shadow: 0 0 5px rgba(204, 255, 0, 0.8),
                         0 0 10px rgba(204, 255, 0, 0.5),
                         0 0 15px rgba(204, 255, 0, 0.3);
        }
        
        .scrolling-text {
            animation: scroll 25s linear infinite;
        }
        
        .scrolling-text-reverse {
            animation: scroll-reverse 25s linear infinite;
        }
        
        @keyframes scroll {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        
        @keyframes scroll-reverse {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        .hero-bg {
            background-color: #000;
            position: relative;
            min-height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        /* Hero Image Container */
        .hero-image-container {
            width: 100%;
            height: 45vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 2;
            margin-top: 1rem;
            overflow: hidden;
        }
        
        @media (min-width: 768px) {
            .hero-image-container {
                height: 55vh;
            }
        }
        
        .hero-image {
            width: 95%;
            max-width: 600px;
            height: auto;
            object-fit: contain;
            opacity: 1;
            transition: all 0.5s ease;
        }
        
        @media (min-width: 768px) {
            .hero-image {
                width: 85%;
                max-width: 800px;
            }
        }
        
        /* Language-specific image display */
        .hero-image.hidden {
            opacity: 0;
            transform: scale(0.95);
            pointer-events: none;
            position: absolute;
        }
        
        /* Hero layout */
        .hero-content {
            width: 100%;
            padding: 0 1rem 3rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 10;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 1) 100%);
            flex: 1;
        }
        
        @media (min-width: 768px) {
            .hero-content {
                padding: 0 1rem 4rem;
            }
        }
        
        /* Modern Countdown Timer based on image */
        .countdown-container {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin: 1.5rem 0 2.5rem;
            position: relative;
        }
        
        @media (min-width: 768px) {
            .countdown-container {
                gap: 0.75rem;
                margin: 2rem 0 3.5rem;
            }
        }
        
        .countdown-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .countdown-number {
            font-size: 2rem;
            font-weight: 800;
            color: white;
            font-family: 'Rajdhani', sans-serif;
            position: relative;
        }
        
        @media (min-width: 480px) {
            .countdown-number {
                font-size: 2.5rem;
            }
        }
        
        @media (min-width: 768px) {
            .countdown-number {
                font-size: 3.5rem;
            }
        }
        
        .countdown-label {
            font-size: 0.65rem;
            color: #999;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            margin-top: -0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        @media (min-width: 480px) {
            .countdown-label {
                font-size: 0.7rem;
            }
        }
        
        @media (min-width: 768px) {
            .countdown-label {
                font-size: 0.8rem;
            }
        }
        
        .countdown-separator {
            font-size: 2rem;
            font-weight: 300;
            color: #666;
            align-self: flex-start;
            margin-top: 0;
        }
        
        @media (min-width: 480px) {
            .countdown-separator {
                font-size: 2.5rem;
            }
        }
        
        @media (min-width: 768px) {
            .countdown-separator {
                font-size: 3.5rem;
            }
        }
        
        
        /* Ribbon styling */
        .ribbon-container {
            position: absolute;
            width: 100%;
            overflow: hidden;
            pointer-events: none;
            height: 40px;
            transform: rotate(-5deg);
            z-index: 5;
        }
        
        @media (min-width: 768px) {
            .ribbon-container {
                height: 60px;
            }
        }
        
        .ribbon-container.ribbon-2 {
            transform: rotate(5deg);
        }
        
        .ribbon {
            background: var(--neon-purple);
            padding: 0.75rem 0;
            width: 120%;
            margin-left: -10%;
            transform: translateY(-50%);
            box-shadow: 0 0 20px rgba(255, 0, 255, 0.5);
        }
        
        .ribbon-2 .ribbon {
            background: var(--neon-blue);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
        }
        
        /* Artist Lineup styling based on image */
        .lineup-container {
            width: 100%;
            max-width: 600px;
            margin-bottom: 2rem;
            position: relative;
            padding: 0 1rem;
        }
        
        @media (min-width: 768px) {
            .lineup-container {
                margin-bottom: 3rem;
            }
        }
        
        .lineup-title {
            font-family: 'Rajdhani', sans-serif;
            text-transform: uppercase;
            font-size: 1.25rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            color: white;
            position: relative;
            padding-bottom: 0.5rem;
            display: inline-block;
        }
        
        .lineup-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 1px;
            background-color: #333;
        }
        
        .lineup-list {
            display: flex;
            flex-direction: column;
            width: 100%;
            gap: 0.75rem;
        }
        
        .lineup-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .lineup-item:hover {
            padding-left: 0.5rem;
        }
        
        .artist-name {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        @media (min-width: 480px) {
            .artist-name {
                font-size: 1.125rem;
            }
        }
        
        .artist-detail {
            font-family: 'Montserrat', sans-serif;
            font-size: 0.7rem;
            color: #666;
            margin-top: 0.25rem;
            font-weight: 400;
        }
        
        .lineup-icon {
            color: white;
            font-size: 1.25rem;
            opacity: 0.5;
            transition: all 0.3s ease;
        }
        
        .lineup-item:hover .lineup-icon {
            opacity: 1;
            transform: translateX(5px);
        }
        
        .cta-button {
            background-color: var(--neon-highlight);
            color: black;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            padding: 0.75rem 2rem;
            border-radius: 0;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
            z-index: 1;
            font-size: 1rem;
        }
        
        @media (min-width: 768px) {
            .cta-button {
                padding: 1rem 2.5rem;
                font-size: 1.125rem;
            }
        }
        
        .cta-button:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background-color: white;
            transition: all 0.3s ease;
            z-index: -1;
        }
        
        .cta-button:hover:before {
            width: 100%;
        }
        
        .cta-button:hover {
            color: black;
            box-shadow: 0 0 30px rgba(204, 255, 0, 0.6);
        }
        
        /* Schedule styling to exactly match the image */
        .schedule-container {
            display: flex;
            width: 100%;
            max-width: 900px;
            background: black;
            margin: 0 auto;
            color: white;
            font-family: 'Rajdhani', sans-serif;
            flex-direction: column;
        }
        
        @media (min-width: 768px) {
            .schedule-container {
                flex-direction: row;
            }
        }
        
        .schedule-sidebar {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 0;
            width: 100%;
            position: relative;
            border-bottom: 2px solid #00ff41;
        }
        
        @media (min-width: 768px) {
            .schedule-sidebar {
                flex-direction: column;
                width: 140px;
                border-bottom: none;
                padding: 2rem 0;
                border-right: 2px solid #00ff41;
            }
        }
        
        .schedule-sidebar-text {
            font-size: 1.5rem;
            font-weight: 700;
            white-space: nowrap;
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }
        
        @media (min-width: 768px) {
            .schedule-sidebar-text {
                font-size: 2rem;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-90deg);
            }
        }
        
        .schedule-vertical-line {
            display: none;
        }
        
        @media (min-width: 768px) {
            .schedule-vertical-line {
                display: block;
                position: absolute;
                top: 0;
                right: 0;
                width: 2px;
                height: 100%;
                background-color: #00ff41;
            }
        }
        
        .schedule-content {
            flex: 1;
            padding: 1.5rem 1rem;
        }
        
        @media (min-width: 768px) {
            .schedule-content {
                padding: 2rem 1rem 2rem 3rem;
            }
        }
        
        .schedule-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        @media (min-width: 768px) {
            .schedule-header {
                margin-bottom: 3rem;
            }
        }
        
        .schedule-date {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: 0.05em;
        }
        
        @media (min-width: 768px) {
            .schedule-date {
                font-size: 3.5rem;
            }
        }
        
        .schedule-items {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .schedule-items {
                gap: 2.5rem;
            }
        }
        
        .schedule-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        
        @media (min-width: 480px) {
            .schedule-item {
                flex-wrap: nowrap;
                gap: 1rem;
            }
        }
        
        @media (min-width: 768px) {
            .schedule-item {
                gap: 2rem;
            }
        }
        
        .schedule-time {
            font-size: 1.5rem;
            font-weight: 600;
            width: 80px;
            text-align: left;
        }
        
        @media (min-width: 480px) {
            .schedule-time {
                width: 100px;
            }
        }
        
        @media (min-width: 768px) {
            .schedule-time {
                font-size: 2rem;
                width: 150px;
            }
        }
        
        .schedule-number {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.2rem;
            font-weight: 700;
        }
        
        @media (min-width: 768px) {
            .schedule-number {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }
        
        .schedule-number-1, .schedule-number-5 {
            background-color: #E900FF; /* magenta */
            color: black;
        }
        
        .schedule-number-2, .schedule-number-6 {
            background-color: #16FF00 ; /* green */
            color: black;
        }
        
        .schedule-number-3 {
            background-color: #0F6292 ; /* blue */
            color: white;
        }
        
        .schedule-number-4 {
            background-color: #FFED00 ; /* yellow */
            color: black;
        }
        
        .schedule-artist {
            font-size: 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            width: 100%;
            margin-top: 0.25rem;
            margin-left: 0;
        }
        
        @media (min-width: 480px) {
            .schedule-artist {
                width: auto;
                margin-top: 0;
                margin-left: 0.5rem;
                font-size: 1.75rem;
            }
        }
        
        @media (min-width: 768px) {
            .schedule-artist {
                font-size: 2rem;
                margin-left: 0;
            }
        }
        
        /* Additional Mobile Optimizations */
        .mobile-menu-backdrop {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(4px);
        }
        
        .mobile-menu-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .mobile-menu-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }
                .logo-image {
            height: 40px;
            width: auto;
        }
        
        @media (max-width: 640px) {
            .logo-image {
                height: 32px;
            }
        }
                .merch-item-hover:hover .merch-overlay {
            opacity: 1;
        }
        
        .merch-overlay {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
    </style>
</head>
<body class="bg-black text-white main-font">
    <!-- Navigation - Updated to match profile.php navigation with improved mobile responsiveness -->
        <nav class="fixed top-0 w-full z-50 bg-black backdrop-blur-sm border-b border-neutral-900" x-data="{ mobileMenu: false }">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="index.php" class="flex items-center space-x-2 z-20">
                    <img src="./images/logo.jfif" alt="PULSE Festival Logo" class="logo-image">
                </a>
                <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                    <a href="#home" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['home']; ?></a>
                    <a href="#artists" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['artists']; ?></a>
                    <a href="#schedule" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['schedule']; ?></a>
                    <a href="#tickets" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['tickets']; ?></a>
                    <a href="#merchandise" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['merchandise']; ?></a>
                    
                    <!-- Language Switcher -->
                    <div class="flex items-center space-x-2">
                        <a href="?lang=en" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> hover:text-festival-yellow transition-colors text-sm festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?lang=ka" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> hover:text-festival-yellow transition-colors text-sm festival-font font-bold">ქარ</a>
                    </div>
                </div>
                <div class="flex items-center">
                    <!-- Auth buttons for desktop -->
                    <div class="hidden md:flex items-center space-x-4">
                        <?php if ($auth->isLoggedIn()): ?>
                            <span class="text-white festival-font hidden lg:inline-block truncate max-w-[120px]">
                                <?php echo htmlspecialchars($currentUser); ?>
                            </span>
                            <a href="profile.php" class="bg-transparent px-3 py-1.5 sm:px-4 sm:py-2 rounded-none border border-festival-yellow text-festival-yellow hover:bg-festival-yellow hover:text-black transition-colors festival-font text-sm sm:text-base"><?php echo $t['profile']; ?></a>
                            <?php if ($auth->isAdmin()): ?>
                                <a href="admin/dashboard.php" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['admin']; ?></a>
                            <?php endif; ?>
                            <a href="logout.php" class="bg-red-600 px-3 py-1.5 sm:px-4 sm:py-2 rounded-none hover:bg-red-700 transition-colors festival-font text-sm sm:text-base"><?php echo $t['logout']; ?></a>
                        <?php else: ?>
                            <a href="login.php" class="bg-festival-yellow text-black px-3 py-1.5 sm:px-4 sm:py-2 rounded-none hover:bg-white transition-colors font-bold festival-font text-sm sm:text-base"><?php echo $t['login']; ?></a>
                            <a href="register.php" class="border border-festival-yellow text-festival-yellow px-3 py-1.5 sm:px-4 sm:py-2 rounded-none hover:bg-festival-yellow hover:text-black transition-colors festival-font text-sm sm:text-base"><?php echo $t['register']; ?></a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden z-20 p-2 text-white focus:outline-none">
                        <i class="fas" :class="mobileMenu ? 'fa-times' : 'fa-bars'"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Improved Mobile Menu with transitions -->
        <div 
            x-show="mobileMenu" 
            x-transition:enter="transition ease-out duration-200" 
            x-transition:enter-start="opacity-0 transform scale-95" 
            x-transition:enter-end="opacity-100 transform scale-100" 
            x-transition:leave="transition ease-in duration-150" 
            x-transition:leave-start="opacity-100 transform scale-100" 
            x-transition:leave-end="opacity-0 transform scale-95" 
            class="fixed inset-0 z-10 md:hidden"
            x-cloak>
            
            <div class="absolute inset-0 mobile-menu-backdrop" @click="mobileMenu = false"></div>
            
            <div class="absolute top-16 left-0 w-full bg-black border-t border-neutral-800 max-h-[calc(100vh-4rem)] overflow-y-auto">
                <div class="py-4 px-6 flex flex-col">
                    <a href="#home" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['home']; ?></a>
                    <a href="#artists" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['artists']; ?></a>
                    <a href="#schedule" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['schedule']; ?></a>
                    <a href="#tickets" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['tickets']; ?></a>
                    <a href="#merchandise" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['merchandise']; ?></a>
                    
                    <div class="mobile-menu-item py-3 flex items-center space-x-4">
                        <a href="?lang=en" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?lang=ka" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">ქარ</a>
                    </div>
                    
                    <?php if ($auth->isLoggedIn()): ?>
                        <div class="pt-4 flex flex-col space-y-3 mt-2 border-t border-neutral-800">
                            <span class="text-white festival-font">
                                <?php echo htmlspecialchars($currentUser); ?>
                            </span>
                            <a href="profile.php" class="bg-transparent py-2 px-4 text-center border border-festival-yellow text-festival-yellow hover:bg-festival-yellow hover:text-black transition-colors festival-font">
                                <?php echo $t['profile']; ?>
                            </a>
                            <?php if ($auth->isAdmin()): ?>
                                <a href="admin/dashboard.php" class="bg-festival-purple/20 border border-festival-purple py-2 px-4 text-center text-festival-purple hover:bg-festival-purple/30 transition-colors festival-font">
                                    <?php echo $t['admin']; ?>
                                </a>
                            <?php endif; ?>
                            <a href="logout.php" class="bg-red-600 py-2 px-4 text-center hover:bg-red-700 transition-colors festival-font">
                                <?php echo $t['logout']; ?>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="pt-4 flex flex-col space-y-3 mt-2 border-t border-neutral-800">
                            <a href="login.php" class="bg-festival-yellow text-black py-2 px-4 text-center hover:bg-white transition-colors font-bold festival-font">
                                <?php echo $t['login']; ?>
                            </a>
                            <a href="register.php" class="border border-festival-yellow text-festival-yellow py-2 px-4 text-center hover:bg-festival-yellow hover:text-black transition-colors festival-font">
                                <?php echo $t['register']; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Scrolling Banner -->
    <div class="bg-festival-yellow text-black py-2 overflow-hidden mt-16">
        <div class="scrolling-text whitespace-nowrap text-sm font-bold festival-font">
            <?php echo $t['most_anticipated']; ?> • <?php echo $t['most_anticipated']; ?> • <?php echo $t['most_anticipated']; ?> • <?php echo $t['most_anticipated']; ?> • <?php echo $t['most_anticipated']; ?>
        </div>
    </div>

    <!-- Hero Section -->
    <section id="home" class="hero-bg">
        <!-- Hero Image Container -->
        <div class="hero-image-container">
            <img src="./images/heroimage.jfif" alt="Festival Hero Image" class="hero-image <?php echo $lang === 'ka' ? 'hidden' : ''; ?>">
            <img src="./images/heroimageka.jfif" alt="ფესტივალის მთავარი სურათი" class="hero-image <?php echo $lang === 'en' ? 'hidden' : ''; ?>">
        </div>
        
        <!-- Main hero content -->
        <div class="hero-content">
            <!-- Countdown Timer styled like the image -->
            <div class="countdown-container" x-data="countdown()">
                <div class="countdown-item">
                    <span class="countdown-number" x-text="padNumber(days)"><?php echo str_pad($days, 2, '0', STR_PAD_LEFT); ?></span>
                    <span class="countdown-label"><?php echo $t['days']; ?></span>
                </div>
                <div class="countdown-separator">.</div>
                <div class="countdown-item">
                    <span class="countdown-number" x-text="padNumber(hours)"><?php echo str_pad($hours, 2, '0', STR_PAD_LEFT); ?></span>
                    <span class="countdown-label"><?php echo $t['hours']; ?></span>
                </div>
                <div class="countdown-separator">.</div>
                <div class="countdown-item">
                    <span class="countdown-number" x-text="padNumber(minutes)"><?php echo str_pad($minutes, 2, '0', STR_PAD_LEFT); ?></span>
                    <span class="countdown-label"><?php echo $t['minutes']; ?></span>
                </div>
                <div class="countdown-separator">.</div>
                <div class="countdown-item">
                    <span class="countdown-number" x-text="padNumber(seconds)"><?php echo str_pad($seconds, 2, '0', STR_PAD_LEFT); ?></span>
                    <span class="countdown-label"><?php echo $t['seconds']; ?></span>
                </div>
            </div>
            
            <!-- CTA Button -->
            <a href="#tickets" class="cta-button">
                <?php echo $t['get_tickets']; ?>
            </a>
        </div>
    </section>


<!-- Artists Section -->
<section id="artists" class="py-16 md:py-20 bg-black">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-center mb-10 md:mb-16 text-festival-yellow festival-font"><?php echo $t['artists']; ?></h2>
        
        <!-- Fixed layout: 3 artists on top, 3 on bottom, centered -->
        <div class="max-w-6xl mx-auto">
            <!-- First row: First 3 artists -->
            <div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 gap-6 lg:gap-8 mb-6 lg:mb-8">
                <?php foreach (array_slice($artists, 0, 3) as $index => $artist): ?>
                    <div class="group cursor-pointer transform transition-all duration-300 hover:scale-105">
                        <div class="bg-neutral-900 rounded-none overflow-hidden border border-neutral-800 hover:border-festival-yellow relative">
                            <div class="h-48 sm:h-56 md:h-64 bg-gradient-to-br from-neutral-800 to-black flex items-center justify-center overflow-hidden">
                                <?php if (!empty($artist['image_url'])): ?>
                                    <img 
                                        src="<?php echo htmlspecialchars(ltrim($artist['image_url'], '/')); ?>" 
                                        alt="<?php echo htmlspecialchars($artist['name']); ?>"
                                        class="w-full h-full object-cover object-center hover:scale-110 transition-transform duration-500"
                                    >
                                <?php else: ?>
                                    <div class="absolute inset-0 opacity-40 bg-noise"></div>
                                    <span class="text-4xl font-bold festival-font relative z-10">
                                        <?php echo strtoupper(substr($artist['name'], 0, 2)); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="p-4 sm:p-6">
                                <h3 class="text-lg sm:text-xl font-bold mb-2 festival-font"><?php echo htmlspecialchars($artist['name']); ?></h3>
                                <p class="text-gray-400 text-xs sm:text-sm"><?php echo htmlspecialchars($artist['bio'] ?? 'Electronic Music Artist'); ?></p>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="text-xs bg-festival-yellow text-black px-2 py-1 festival-font font-bold">
                                    <?php echo sprintf('%02d', $index + 1); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Second row: Next 3 artists -->
            <div class="grid grid-cols-1 xs:grid-cols-2 md:grid-cols-3 gap-6 lg:gap-8">
                <?php foreach (array_slice($artists, 3, 3) as $index => $artist): ?>
                    <div class="group cursor-pointer transform transition-all duration-300 hover:scale-105">
                        <div class="bg-neutral-900 rounded-none overflow-hidden border border-neutral-800 hover:border-festival-yellow relative">
                            <div class="h-48 sm:h-56 md:h-64 bg-gradient-to-br from-neutral-800 to-black flex items-center justify-center overflow-hidden">
                                <?php if (!empty($artist['image_url'])): ?>
                                    <img 
                                        src="<?php echo htmlspecialchars(ltrim($artist['image_url'], '/')); ?>" 
                                        alt="<?php echo htmlspecialchars($artist['name']); ?>"
                                        class="w-full h-full object-cover object-center hover:scale-110 transition-transform duration-500"
                                    >
                                <?php else: ?>
                                    <div class="absolute inset-0 opacity-40 bg-noise"></div>
                                    <span class="text-4xl font-bold festival-font relative z-10">
                                        <?php echo strtoupper(substr($artist['name'], 0, 2)); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="p-4 sm:p-6">
                                <h3 class="text-lg sm:text-xl font-bold mb-2 festival-font"><?php echo htmlspecialchars($artist['name']); ?></h3>
                                <p class="text-gray-400 text-xs sm:text-sm"><?php echo htmlspecialchars($artist['bio'] ?? 'Electronic Music Artist'); ?></p>
                            </div>
                            <div class="absolute top-4 right-4">
                                <span class="text-xs bg-festival-yellow text-black px-2 py-1 festival-font font-bold">
                                    <?php echo sprintf('%02d', $index + 4); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

    <!-- Schedule Section - Improved mobile responsiveness -->
    <section id="schedule" class="py-16 md:py-20 bg-black border-t border-neutral-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-center mb-10 md:mb-16 text-festival-yellow festival-font"><?php echo $t['schedule']; ?></h2>
            
            <!-- Schedule container based on the image -->
            <div class="schedule-container">
                <div class="schedule-sidebar">
                    <div class="schedule-sidebar-text">EVENT SCHEDULE</div>
                    <div class="schedule-vertical-line"></div>
                </div>
                
                <div class="schedule-content">
                    <div class="schedule-header">
                        <div class="schedule-date">28 JULY</div>
                    </div>
                    
                    <div class="schedule-items">
                        <!-- Schedule items based directly on the image -->
                        <div class="schedule-item">
                            <div class="schedule-time">00:00</div>
                            <div class="schedule-number schedule-number-1">01</div>
                            <div class="schedule-artist">LEBLANC</div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">01:00</div>
                            <div class="schedule-number schedule-number-2">02</div>
                            <div class="schedule-artist">SEBASTIAN</div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">03:00</div>
                            <div class="schedule-number schedule-number-3">03</div>
                            <div class="schedule-artist">PEGGY GOU</div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">04:00</div>
                            <div class="schedule-number schedule-number-4">04</div>
                            <div class="schedule-artist">VINI VICI</div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">05:00</div>
                            <div class="schedule-number schedule-number-1">05</div>
                            <div class="schedule-artist">KEINEMUSIK</div>
                        </div>
                        <div class="schedule-item">
                            <div class="schedule-time">06:00</div>
                            <div class="schedule-number schedule-number-2">06</div>
                            <div class="schedule-artist">DEBORAH DE LUCA</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tickets Section -->
   <section id="tickets" class="py-16 md:py-20 bg-black border-t border-neutral-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-center mb-10 md:mb-16 text-festival-yellow festival-font"><?php echo $t['tickets']; ?></h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 max-w-6xl mx-auto">
                <?php foreach (getTicketTypes() as $index => $ticket): ?>
                    <div class="bg-neutral-900 rounded-none p-6 sm:p-8 border border-neutral-800 hover:border-festival-yellow transition-colors">
                        <div class="text-sm text-festival-yellow mb-4 festival-font"><?php echo $t['ticket_label']; ?> <?php echo sprintf('%02d', $index + 1); ?></div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4 text-white festival-font">
                            <?php echo htmlspecialchars($lang === 'ka' && isset($ticket['name_ka']) ? $ticket['name_ka'] : $ticket['name']); ?>
                        </h3>
                        <div class="text-3xl sm:text-4xl font-bold mb-3 sm:mb-4 festival-font">₾<?php echo number_format($ticket['price'], 0); ?></div>
                        <p class="text-gray-400 text-sm mb-4 sm:mb-6">
                            <?php echo htmlspecialchars($lang === 'ka' && isset($ticket['description_ka']) ? $ticket['description_ka'] : $ticket['description']); ?>
                        </p>
                        <div class="mb-5 sm:mb-6">
                            <div class="text-sm text-gray-400"><?php echo $t['available']; ?>: <?php echo $ticket['available_quantity']; ?>/<?php echo $ticket['total_quantity']; ?></div>
                            <div class="w-full bg-neutral-800 h-1 mt-2">
                                <div class="bg-festival-yellow h-1" style="width: <?php echo ($ticket['available_quantity'] / $ticket['total_quantity']) * 100; ?>%"></div>
                            </div>
                        </div>
                        
                        <?php if ($auth->isLoggedIn()): ?>
                            <a href="purchase.php?ticket_id=<?php echo $ticket['id']; ?>" 
                               class="block w-full bg-festival-yellow text-black text-center py-2.5 sm:py-3 rounded-none font-bold hover:bg-white transition-colors festival-font">
                                <?php echo $t['buy_now']; ?>
                            </a>
                        <?php else: ?>
                            <a href="login.php" 
                               class="block w-full bg-neutral-800 text-center py-2.5 sm:py-3 rounded-none font-bold hover:bg-neutral-700 transition-colors festival-font">
                                <?php echo $t['login_to_buy']; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="merchandise" class="py-16 md:py-20 bg-black border-t border-neutral-900">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-center mb-3 md:mb-4 text-festival-yellow festival-font"><?php echo $t['merchandise']; ?></h2>
        <p class="text-center text-gray-400 mb-10 md:mb-16"><?php echo $t['merch_subtitle']; ?></p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 max-w-6xl mx-auto">
            <?php 
            // Call function without passing $conn - it will create its own connection
            $featured_merch = getMerchandiseForHomepage();
            foreach ($featured_merch as $item): 
            ?>
                <div class="group bg-neutral-900 rounded-none overflow-hidden border border-neutral-800 hover:border-festival-yellow transition-all duration-300">
                    <div class="h-64 sm:h-72 bg-neutral-800 flex items-center justify-center overflow-hidden relative">
                        <?php if (!empty($item['image'])): ?>
                            <img 
                                src="<?php echo getMerchImagePath($item['image']); ?>"  
                                alt="<?php echo htmlspecialchars($lang === 'ka' && isset($item['name_ka']) ? $item['name_ka'] : $item['name']); ?>"
                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700"
                                onerror="this.src='./images/merch-placeholder.jpg';this.onerror='';"
                            >
                        <?php else: ?>
                            <div class="absolute inset-0 opacity-40 bg-noise"></div>
                            <span class="text-4xl font-bold festival-font relative z-10">
                                <?php echo strtoupper(substr($item['name'], 0, 2)); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if (!$item['in_stock']): ?>
                            <div class="absolute inset-0 bg-black bg-opacity-70 flex items-center justify-center">
                                <span class="bg-red-600 text-white px-4 py-2 festival-font font-bold">
                                    <?php echo $t['out_of_stock']; ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-5 sm:p-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-bold festival-font truncate pr-2">
                                <?php echo htmlspecialchars($lang === 'ka' && isset($item['name_ka']) ? $item['name_ka'] : $item['name']); ?>
                            </h3>
                            <div class="text-xl font-bold text-festival-yellow festival-font">
                                $<?php echo number_format($item['price'], 2); ?>
                            </div>
                        </div>
                        
                        <?php if ($item['in_stock']): ?>
                            <?php if ($item['has_options']): ?>
                                <a href="merch.php" class="block w-full bg-festival-yellow text-black text-center py-2.5 font-bold hover:bg-white transition-colors festival-font mt-4">
                                    <?php echo $t['select_options']; ?>
                                </a>
                            <?php else: ?>
                                <form action="merch.php" method="POST" class="w-full">
                                    <input type="hidden" name="merch_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" name="add_to_cart" class="block w-full bg-festival-yellow text-black text-center py-2.5 font-bold hover:bg-white transition-colors festival-font mt-4">
                                        <?php echo $t['add_to_cart']; ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <button disabled class="block w-full bg-neutral-800 text-neutral-500 text-center py-2.5 font-bold cursor-not-allowed festival-font mt-4">
                                <?php echo $t['out_of_stock']; ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-10 md:mt-12">
            <a href="merch.php" class="inline-block bg-transparent border border-festival-yellow text-festival-yellow px-8 py-3 font-bold hover:bg-festival-yellow hover:text-black transition-colors festival-font">
                <?php echo $t['view_all_merch']; ?>
            </a>
        </div>
    </div>
</section>
    
    

    <!-- Footer -->
    <footer class="bg-neutral-900 py-8 md:py-12 border-t border-neutral-800">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold mb-3 sm:mb-4 text-festival-yellow festival-font">PULSE FESTIVAL</h2>
            <p class="text-gray-400 mb-5 sm:mb-6 text-sm sm:text-base"><?php echo $t['most_anticipated']; ?></p>
            <div class="flex justify-center space-x-5 md:space-x-6">
                <i class="fab fa-facebook text-xl sm:text-2xl hover:text-festival-yellow cursor-pointer transition-colors"></i>
                <i class="fab fa-instagram text-xl sm:text-2xl hover:text-festival-yellow cursor-pointer transition-colors"></i>
                <i class="fab fa-twitter text-xl sm:text-2xl hover:text-festival-yellow cursor-pointer transition-colors"></i>
                <i class="fab fa-youtube text-xl sm:text-2xl hover:text-festival-yellow cursor-pointer transition-colors"></i>
            </div>
            
            <div class="mt-5 text-xs text-gray-600">
                &copy; 2025 PULSE Festival. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        function countdown() {
            return {
                days: <?php echo $days; ?>,
                hours: <?php echo $hours; ?>,
                minutes: <?php echo $minutes; ?>,
                seconds: <?php echo $seconds; ?>,
                
                init() {
                    this.updateCountdown();
                    setInterval(() => {
                        this.updateCountdown();
                    }, 1000);
                },
                
                updateCountdown() {
                    const targetDate = new Date('2025-07-28T00:00:00Z');
                    const now = new Date();
                    const diff = targetDate - now;
                    
                    if (diff > 0) {
                        this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                    } else {
                        this.days = this.hours = this.minutes = this.seconds = 0;
                    }
                },
                
                padNumber(number) {
                    return String(number).padStart(2, '0');
                }
            }
        }
    </script>
</body>
</html>