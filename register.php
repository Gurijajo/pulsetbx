<?php
require_once 'includes/auth.php';

$auth = new Auth();
$error = '';
$success = '';

// Current Date and Time (UTC)
$currentDateTime = '2025-06-24 13:37:06';

// Current user
$currentUser = 'Guram-jajanidze';

// Language support
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$_SESSION['lang'] = $lang;

// Language translations
$translations = [
    'en' => [
        'register' => 'REGISTER',
        'create_account' => 'Create your PULSE Festival account',
        'first_name' => 'FIRST NAME',
        'last_name' => 'LAST NAME',
        'username' => 'USERNAME',
        'email' => 'EMAIL',
        'phone' => 'PHONE (OPTIONAL)',
        'password' => 'PASSWORD',
        'confirm_password' => 'CONFIRM PASSWORD',
        'min_chars' => 'Must be at least 6 characters',
        'register_button' => 'REGISTER',
        'already_account' => 'Already have an account?',
        'login' => 'LOGIN',
        'back_to_festival' => 'BACK TO FESTIVAL',
        'most_anticipated' => 'THE MOST ANTICIPATED MUSIC EVENT',
        'passwords_mismatch' => 'Passwords do not match',
        'password_too_short' => 'Password must be at least 6 characters',
        'registration_success' => 'Registration successful! You can now login.',
        'registration_failed' => 'Registration failed. Username or email may already exist.',
        'home' => 'HOME',
        'artists' => 'ARTISTS',
        'schedule' => 'SCHEDULE',
        'tickets' => 'TICKETS',
        'profile' => 'PROFILE',
        'logout' => 'LOGOUT',
        'id_number' => 'ID NUMBER'
    ],
    'ka' => [
        'register' => 'რეგისტრაცია',
        'create_account' => 'შექმენით თქვენი PULSE Festival ანგარიში',
        'first_name' => 'სახელი',
        'last_name' => 'გვარი',
        'username' => 'მომხმარებლის სახელი',
        'email' => 'ელფოსტა',
        'phone' => 'ტელეფონი (არასავალდებულო)',
        'password' => 'პაროლი',
        'confirm_password' => 'დაადასტურეთ პაროლი',
        'min_chars' => 'უნდა იყოს მინიმუმ 6 სიმბოლო',
        'register_button' => 'რეგისტრაცია',
        'already_account' => 'უკვე გაქვთ ანგარიში?',
        'login' => 'შესვლა',
        'back_to_festival' => 'დაბრუნება ფესტივალზე',
        'most_anticipated' => 'ყველაზე მოლოდინის ღირსი მუსიკალური ღონისძიება',
        'passwords_mismatch' => 'პაროლები არ ემთხვევა',
        'password_too_short' => 'პაროლი უნდა იყოს მინიმუმ 6 სიმბოლო',
        'registration_success' => 'რეგისტრაცია წარმატებით დასრულდა! შეგიძლიათ შეხვიდეთ სისტემაში.',
        'registration_failed' => 'რეგისტრაცია ვერ მოხერხდა. მომხმარებლის სახელი ან ელფოსტა შესაძლოა უკვე არსებობდეს.',
        'home' => 'მთავარი',
        'artists' => 'არტისტები',
        'schedule' => 'განრიგი',
        'tickets' => 'ბილეთები',
        'profile' => 'პროფილი',
        'logout' => 'გასვლა',
        'id_number' => 'პირადი ნომერი'
    ]
];

$t = $translations[$lang];

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $id_number = $_POST['id_number'] ?? '';
    
    if ($password !== $confirm_password) {
        $error = $t['passwords_mismatch'];
    } elseif (strlen($password) < 6) {
        $error = $t['password_too_short'];
    } else {
        if ($auth->register($username, $email, $password, $first_name, $last_name, $phone,$id_number)) {
            $success = $t['registration_success'];
        } else {
            $error = $t['registration_failed'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['register']; ?> - PULSE Festival</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'festival-green': '#16FF00',
                        'festival-purple': '#ff00ff',
                        'festival-blue': '#0F6292',
                        'festival-yellow': '#3bff44', // Changed to the requested green color
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
            --primary-color: #3bff44; /* Changed to the requested green color */
        }
        
        .festival-font { 
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            letter-spacing: 0.03em;
        }
        
        .main-font { 
            font-family: 'Montserrat', sans-serif; 
        }
        
        body {
            background-color: #000;
            overflow-x: hidden;
        }
        
        .register-container {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.9));
            border: 1px solid #333;
            position: relative;
            overflow: hidden;
        }
        
        .register-input {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid #333;
            transition: all 0.3s ease;
        }
        
        .register-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 10px rgba(59, 255, 68, 0.3);
        }
        
        .register-button {
            background-color: var(--primary-color);
            color: black;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            letter-spacing: 0.1em;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .register-button:before {
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
        
        .register-button:hover:before {
            width: 100%;
        }
        
        .register-button:hover {
            color: black;
        }
        
        .scrolling-banner {
            background-color: var(--primary-color);
            color: black;
            overflow: hidden;
        }
        
        .scrolling-text {
            animation: scroll 25s linear infinite;
        }
        
        @keyframes scroll {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
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
        
        /* Mobile menu styling */
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
    </style>
</head>
<body class="bg-black text-white main-font min-h-screen flex flex-col">
    <!-- Navigation with logo image -->
    <nav class="fixed top-0 w-full z-50 bg-black backdrop-blur-sm border-b border-neutral-900" x-data="{ mobileMenu: false }">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="index.php" class="flex items-center space-x-2 z-20">
                    <img src="./images/logo.jfif" alt="PULSE Festival Logo" class="logo-image">
                </a>
                <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                    <a href="index.php#home" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['home']; ?></a>
                    <a href="index.php#artists" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['artists']; ?></a>
                    <a href="index.php#schedule" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['schedule']; ?></a>
                    <a href="index.php#tickets" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['tickets']; ?></a>
                    
                    <!-- Language Switcher -->
                    <div class="flex items-center space-x-2">
                        <a href="?lang=en" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> hover:text-festival-yellow transition-colors text-sm festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?lang=ka" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> hover:text-festival-yellow transition-colors text-sm festival-font font-bold">ქარ</a>
                    </div>
                </div>
                
                <!-- Mobile Menu Button -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden z-20 p-2 text-white focus:outline-none">
                    <i class="fas" :class="mobileMenu ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
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
                    <a href="index.php#home" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['home']; ?></a>
                    <a href="index.php#artists" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['artists']; ?></a>
                    <a href="index.php#schedule" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['schedule']; ?></a>
                    <a href="index.php#tickets" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['tickets']; ?></a>
                    
                    <div class="mobile-menu-item py-3 flex items-center space-x-4">
                        <a href="?lang=en" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?lang=ka" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">ქარ</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Scrolling Banner -->
    <div class="scrolling-banner py-2 overflow-hidden mt-16">
        <div class="scrolling-text whitespace-nowrap text-sm font-bold festival-font">
            <?php echo $t['most_anticipated']; ?> • <?php echo $t['most_anticipated']; ?> • <?php echo $t['most_anticipated']; ?> • 
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="flex-grow flex items-center justify-center p-4 xs:p-6 py-6 sm:py-8">
        <div class="w-full max-w-2xl px-0 xs:px-4">
            <div class="register-container p-4 xs:p-6 sm:p-8 rounded-none">
                <div class="text-center mb-6 sm:mb-10">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white festival-font mb-2"><?php echo $t['register']; ?></h1>
                    <p class="text-gray-400 main-font text-sm sm:text-base"><?php echo $t['create_account']; ?></p>
                </div>
                
                <?php if ($error): ?>
                    <div class="bg-red-900/70 border border-red-700 text-red-100 px-4 py-3 mb-6 text-sm sm:text-base">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="bg-green-900/70 border border-green-700 text-green-100 px-4 py-3 mb-6 text-sm sm:text-base">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="space-y-4 sm:space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium mb-1 sm:mb-2 festival-font text-festival-yellow"><?php echo $t['first_name']; ?></label>
                            <input type="text" name="first_name" required 
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 register-input rounded-none focus:outline-none focus:border-festival-yellow text-sm">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium mb-1 sm:mb-2 festival-font text-festival-yellow"><?php echo $t['last_name']; ?></label>
                            <input type="text" name="last_name" required 
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 register-input rounded-none focus:outline-none focus:border-festival-yellow text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1 sm:mb-2 festival-font text-festival-yellow"><?php echo $t['username']; ?></label>
                        <input type="text" name="username" required 
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 register-input rounded-none focus:outline-none focus:border-festival-yellow text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1 sm:mb-2 festival-font text-festival-yellow"><?php echo $t['email']; ?></label>
                        <input type="email" name="email" required 
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 register-input rounded-none focus:outline-none focus:border-festival-yellow text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1 sm:mb-2 festival-font text-festival-yellow"><?php echo $t['phone']; ?></label>
                        <input type="tel" name="phone" 
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 register-input rounded-none focus:outline-none focus:border-festival-yellow text-sm">
                    </div>

                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1 sm:mb-2 festival-font text-festival-yellow"><?php echo $t['id_number']; ?></label>
                        <input type="number" name="id_number" 
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 register-input rounded-none focus:outline-none focus:border-festival-yellow text-sm">
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium mb-1 sm:mb-2 festival-font text-festival-yellow"><?php echo $t['password']; ?></label>
                            <input type="password" name="password" required minlength="6"
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 register-input rounded-none focus:outline-none focus:border-festival-yellow text-sm">
                            <p class="text-xs text-gray-500 mt-1"><?php echo $t['min_chars']; ?></p>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium mb-1 sm:mb-2 festival-font text-festival-yellow"><?php echo $t['confirm_password']; ?></label>
                            <input type="password" name="confirm_password" required 
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 register-input rounded-none focus:outline-none focus:border-festival-yellow text-sm">
                        </div>
                    </div>
                    
                    <div class="pt-2 sm:pt-4">
                        <button type="submit" 
                            class="w-full register-button py-2.5 sm:py-3 px-4 rounded-none festival-font text-base sm:text-lg">
                            <?php echo $t['register_button']; ?>
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-6 sm:mt-8 space-y-3">
                    <p class="text-gray-400 main-font text-sm"><?php echo $t['already_account']; ?></p>
                    <a href="login.php<?php echo $lang !== 'en' ? '?lang='.$lang : ''; ?>" class="inline-block border border-festival-yellow text-festival-yellow hover:bg-festival-yellow hover:text-black transition-colors festival-font py-2 px-6 rounded-none text-sm sm:text-base">
                        <?php echo $t['login']; ?>
                    </a>
                </div>
                
                <div class="mt-6 pt-6 border-t border-neutral-800">
                    <a href="index.php" class="flex items-center justify-center text-gray-400 hover:text-festival-yellow transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span class="festival-font text-sm"><?php echo $t['back_to_festival']; ?></span>
                    </a>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="mt-6 text-center">
                <div class="flex justify-center space-x-6 mb-4">
                    <i class="fab fa-facebook text-lg sm:text-xl hover:text-festival-yellow cursor-pointer transition-colors"></i>
                    <i class="fab fa-instagram text-lg sm:text-xl hover:text-festival-yellow cursor-pointer transition-colors"></i>
                    <i class="fab fa-twitter text-lg sm:text-xl hover:text-festival-yellow cursor-pointer transition-colors"></i>
                    <i class="fab fa-youtube text-lg sm:text-xl hover:text-festival-yellow cursor-pointer transition-colors"></i>
                </div>
                <div class="text-xs text-gray-500">
                    PULSE Festival © 2025
                </div>
            </div>
        </div>
    </div>
</body>
</html>