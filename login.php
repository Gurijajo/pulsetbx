<?php
require_once 'includes/auth.php';

$auth = new Auth();
$error = '';

// Language support
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$_SESSION['lang'] = $lang;

// Current date/time and user
$currentDateTime = '2025-06-24 13:23:25';
$currentUser = 'Guram-jajanidze';

// Language translations
$translations = [
    'en' => [
        'login' => 'LOGIN',
        'access_account' => 'Access your PULSE Festival account',
        'username_or_email' => 'USERNAME OR EMAIL',
        'password' => 'PASSWORD',
        'login_button' => 'LOGIN',
        'dont_have_account' => 'Don\'t have an account?',
        'register' => 'REGISTER',
        'back_to_festival' => 'BACK TO FESTIVAL',
        'most_anticipated' => 'THE MOST UNFORGETTABLE NIGHT OF MUSIC IS COMING — DON’T MISS THE VIBE EVERYONE’S TALKING ABOUT!',
        'invalid_credentials' => 'Invalid username or password',
        'home' => 'HOME',
        'artists' => 'ARTISTS',
        'schedule' => 'SCHEDULE',
        'tickets' => 'TICKETS',
        'profile' => 'PROFILE',
        'logout' => 'LOGOUT'
    ],
    'ka' => [
        'login' => 'შესვლა',
        'access_account' => 'შედით თქვენს PULSE Festival ანგარიშზე',
        'username_or_email' => 'მომხმარებელი ან ელფოსტა',
        'password' => 'პაროლი',
        'login_button' => 'შესვლა',
        'dont_have_account' => 'არ გაქვთ ანგარიში?',
        'register' => 'რეგისტრაცია',
        'back_to_festival' => 'დაბრუნება ფესტივალზე',
        'most_anticipated' => 'მუსიკის ყველაზე დაუვიწყარი ღამე ახლოვდება — არ გამოტოვო ატმოსფერო, რომელზეც ყველა ლაპარაკობს!',
        'invalid_credentials' => 'არასწორი მომხმარებელი ან პაროლი',
        'home' => 'მთავარი',
        'artists' => 'არტისტები',
        'schedule' => 'განრიგი',
        'tickets' => 'ბილეთები',
        'profile' => 'პროფილი',
        'logout' => 'გასვლა'
    ]
];

$t = $translations[$lang];

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($auth->login($username, $password)) {
        header("Location: index.php");
        exit();
    } else {
        $error = $t['invalid_credentials'];
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['login']; ?> - PULSE Festival</title>
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
                        'festival-yellow': '#3bff44',
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
            --neon-highlight: #3bff44;
            --neon-purple: #ff00ff;
            --neon-blue: #00FFFF;
            --neon-green: #16FF00;
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
            background-image: 
                radial-gradient(circle at 20% 25%, rgba(255, 237, 0, 0.05) 0%, transparent 25%),
                radial-gradient(circle at 80% 80%, rgba(255, 0, 255, 0.05) 0%, transparent 30%);
        }
        
       
        
        .login-container {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.9));
            border: 1px solid #333;
            position: relative;
            overflow: hidden;
        }
        
        .login-container::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(45deg, #3bff44, transparent, #3bff44);
            z-index: -1;
            filter: blur(10px);
            opacity: 0.3;
        }
        
        /* Neon edge effect */
        .login-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 1px solid rgba(102, 255, 0, 0.3);
            pointer-events: none;
            animation: neonBorderPulse 2s infinite alternate;
        }
        
        .login-input {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid #333;
            transition: all 0.3s ease;
        }
        
        .login-input:focus {
            border-color: var(--neon-highlight);
            box-shadow: 0 0 15px rgba(102, 255, 0, 0.3);
        }
        
        .login-button {
            background-color: var(--neon-highlight);
            color: black;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            letter-spacing: 0.1em;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 0 10px rgba(102, 255, 0, 0.3);
        }
        
        .login-button:before {
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
        
        .login-button:hover:before {
            width: 100%;
        }
        
        .login-button:hover {
            color: black;
            box-shadow: 0 0 30px rgba(102, 255, 0, 0.3);
        }
        
        .scrolling-banner {
            background-color: var(--neon-highlight);
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
    <!-- Ambient neon effects -->
    <div class="neon-ambient neon-ambient-1"></div>
    <div class="neon-ambient neon-ambient-2"></div>
    
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
    <div class="flex-grow flex items-center justify-center p-4 xs:p-6 md:p-8 my-8 sm:my-12 relative">
        <div class="w-full max-w-md px-4">
            <div class="login-container p-6 sm:p-8 rounded-none">
                <div class="text-center mb-8 sm:mb-10">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white festival-font mb-2 glow"><?php echo $t['login']; ?></h1>
                    <p class="text-gray-400 main-font text-sm sm:text-base"><?php echo $t['access_account']; ?></p>
                </div>
                
                <?php if ($error): ?>
                    <div class="bg-red-900/70 border border-red-700 text-red-100 px-4 py-3 mb-6 text-sm sm:text-base">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="space-y-5 sm:space-y-6">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-2 festival-font text-festival-yellow"><?php echo $t['username_or_email']; ?></label>
                        <input type="text" name="username" required 
                               class="w-full px-3 sm:px-4 py-2.5 sm:py-3 login-input rounded-none focus:outline-none focus:border-festival-yellow text-sm sm:text-base">
                    </div>
                    
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-2 festival-font text-festival-yellow"><?php echo $t['password']; ?></label>
                        <input type="password" name="password" required 
                               class="w-full px-3 sm:px-4 py-2.5 sm:py-3 login-input rounded-none focus:outline-none focus:border-festival-yellow text-sm sm:text-base">
                    </div>
                    
                    <button type="submit" 
                            class="w-full login-button py-2.5 sm:py-3 px-4 rounded-none festival-font text-base sm:text-lg">
                        <?php echo $t['login_button']; ?>
                    </button>
                </form>
                
                <div class="text-center mt-6 sm:mt-8 space-y-3">
                    <p class="text-gray-400 main-font text-sm"><?php echo $t['dont_have_account']; ?></p>
                    <a href="register.php<?php echo $lang !== 'en' ? '?lang='.$lang : ''; ?>" class="inline-block border border-festival-yellow text-festival-yellow hover:bg-festival-yellow hover:text-black transition-colors festival-font py-2 px-6 rounded-none text-sm sm:text-base">
                        <?php echo $t['register']; ?>
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
            <div class="mt-8 text-center">
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
    
    <!-- Mobile date/time indicator for debugging -->
    <div class="fixed bottom-2 left-2 text-xs text-gray-700 opacity-50 md:hidden">
        <?php echo $currentDateTime; ?>
    </div>
</body>
</html>