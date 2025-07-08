<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

$auth = new Auth();
$auth->requireAdmin();

// Current date and time updated
$currentDateTime = '2025-07-08 08:32:51';
$currentUser = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

// Language support
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$_SESSION['lang'] = $lang;

// Language translations
$translations = [
    'en' => [
        'admin_dashboard' => 'ADMIN DASHBOARD',
        'ticket_orders' => 'TICKET ORDERS',
        'order_id' => 'Order ID',
        'user' => 'User',
        'ticket' => 'Ticket',
        'qty' => 'Qty',
        'amount' => 'Amount',
        'status' => 'Status',
        'date' => 'Date',
        'actions' => 'Actions',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'no_action' => 'No action needed',
        'home' => 'HOME',
        'artists' => 'ARTISTS',
        'schedule' => 'SCHEDULE',
        'tickets' => 'TICKETS',
        'merchandise' => 'MERCHANDISE',
        'users' => 'USERS',
        'profile' => 'PROFILE',
        'logout' => 'LOGOUT',
        'admin_panel' => 'ADMIN PANEL',
        'admin_menu' => 'ADMIN MENU',
        'manage_tickets' => 'Manage Tickets',
        'manage_merchandise' => 'Manage Merchandise',
        'manage_users' => 'Manage Users',
        'manage_orders' => 'Manage Orders',
        'most_anticipated' => 'THE MOST UNFORGETTABLE NIGHT OF MUSIC IS COMING — DONT MISS THE VIBE EVERYONES TALKING ABOUT!',
        'view_all_ticket_orders' => 'View All Ticket Orders',
        'quick_links' => 'Quick Links',
        'ticket_dashboard' => 'Ticket Dashboard',
        'merch_dashboard' => 'Merchandise Dashboard',
        'merch_orders' => 'Merchandise Orders',
        'user_management' => 'User Management',
        'settings' => 'Settings'
    ],
    'ka' => [
        'admin_dashboard' => 'ადმინ პანელი',
        'ticket_orders' => 'ბილეთების შეკვეთები',
        'order_id' => 'შეკვეთის ID',
        'user' => 'მომხმარებელი',
        'ticket' => 'ბილეთი',
        'qty' => 'რაოდ.',
        'amount' => 'თანხა',
        'status' => 'სტატუსი',
        'date' => 'თარიღი',
        'actions' => 'ქმედებები',
        'approve' => 'დამტკიცება',
        'reject' => 'უარყოფა',
        'no_action' => 'ქმედება არ არის საჭირო',
        'home' => 'მთავარი',
        'artists' => 'არტისტები',
        'schedule' => 'განრიგი',
        'tickets' => 'ბილეთები',
        'merchandise' => 'მერჩი',
        'users' => 'მომხმარებლები',
        'profile' => 'პროფილი',
        'logout' => 'გასვლა',
        'admin_panel' => 'ადმინ პანელი',
        'admin_menu' => 'ადმინისტრატორის მენიუ',
        'manage_tickets' => 'ბილეთების მართვა',
        'manage_merchandise' => 'მერჩის მართვა',
        'manage_users' => 'მომხმარებლების მართვა',
        'manage_orders' => 'შეკვეთების მართვა',
        'most_anticipated' => 'მუსიკის ყველაზე დაუვიწყარი ღამე ახლოვდება — არ გამოტოვო ატმოსფერო, რომელზეც ყველა ლაპარაკობს!',
        'view_all_ticket_orders' => 'ყველა ბილეთის შეკვეთა',
        'quick_links' => 'სწრაფი ბმულები',
        'ticket_dashboard' => 'ბილეთების პანელი',
        'merch_dashboard' => 'მერჩის პანელი',
        'merch_orders' => 'მერჩის შეკვეთები',
        'user_management' => 'მომხმარებლების მართვა',
        'settings' => 'პარამეტრები'
    ]
];

$t = $translations[$lang];

$orders = getAllOrders();

if ($_POST) {
    $order_id = $_POST['order_id'] ?? 0;
    $action = $_POST['action'] ?? '';
    
    if ($order_id && in_array($action, ['approve', 'reject'])) {
        $status = $action === 'approve' ? 'approved' : 'rejected';
        updateOrderStatus($order_id, $status);
        header("Location: dashboard.php" . ($lang !== 'en' ? "?lang=$lang" : ""));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['admin_dashboard']; ?> - PULSE Festival</title>
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
                        'festival-yellow': '#3bff44', // Changed to green
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
            --primary-color: #3bff44; /* Changed to green */
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
        
        .admin-container {
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid #333;
            position: relative;
            overflow: hidden;
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
        
        .action-button {
            transition: all 0.3s ease;
        }
        
        .action-button:hover {
            transform: translateY(-2px);
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
        
        .logo-image {
            height: 40px;
            width: auto;
        }
        
        @media (max-width: 640px) {
            .logo-image {
                height: 32px;
            }
        }
        
        .admin-card {
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid #333;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .admin-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        
        .admin-card-icon {
            background: rgba(59, 255, 68, 0.1);
            border: 1px solid rgba(59, 255, 68, 0.3);
        }
    </style>
</head>
<body class="bg-black text-white main-font min-h-screen">
    <!-- Navigation Bar - with mobile menu -->
    <nav class="fixed top-0 w-full z-50 bg-black backdrop-blur-sm border-b border-neutral-900" x-data="{ mobileMenu: false }">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a href="../index.php" class="flex items-center space-x-2 z-20">
                    <img src="../images/logo.jfif" alt="PULSE Festival Logo" class="logo-image">
                </a>
                <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                    <a href="../index.php#home" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['home']; ?></a>
                    <a href="../index.php#artists" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['artists']; ?></a>
                    <a href="../index.php#schedule" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['schedule']; ?></a>
                    <a href="../index.php#tickets" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['tickets']; ?></a>
                    <a href="../merch.php" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['merchandise']; ?></a>
                    
                    <!-- Language Switcher -->
                    <div class="flex items-center space-x-2">
                        <a href="?lang=en" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> hover:text-festival-yellow transition-colors text-sm festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?lang=ka" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> hover:text-festival-yellow transition-colors text-sm festival-font font-bold">ქარ</a>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <span class="text-white festival-font hidden lg:inline-block truncate max-w-[120px]">
                        <?php echo htmlspecialchars($currentUser); ?>
                    </span>
                    <span class="bg-festival-purple/20 border border-festival-purple px-3 py-1.5 rounded-none text-festival-purple festival-font text-sm">
                        <?php echo $t['admin_panel']; ?>
                    </span>
                    <a href="../profile.php" class="bg-transparent px-3 py-1.5 rounded-none border border-festival-yellow text-festival-yellow hover:bg-festival-yellow hover:text-black transition-colors festival-font text-sm"><?php echo $t['profile']; ?></a>
                    <a href="../logout.php" class="bg-red-600 px-3 py-1.5 rounded-none hover:bg-red-700 transition-colors festival-font text-sm"><?php echo $t['logout']; ?></a>
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
                    <div class="py-3 mb-3">
                        <span class="text-white festival-font">
                            <?php echo htmlspecialchars($currentUser); ?>
                        </span>
                        <span class="bg-festival-purple/20 border border-festival-purple px-2 py-1 rounded-none text-festival-purple festival-font text-xs ml-2">
                            <?php echo $t['admin_panel']; ?>
                        </span>
                    </div>
                    <a href="../index.php#home" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['home']; ?></a>
                    <a href="../index.php#artists" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['artists']; ?></a>
                    <a href="../index.php#schedule" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['schedule']; ?></a>
                    <a href="../index.php#tickets" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['tickets']; ?></a>
                    <a href="../merch.php" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['merchandise']; ?></a>
                    
                    <!-- Admin Links for Mobile -->
                    <div class="pt-3 border-t border-neutral-800 mt-2">
                        <div class="text-sm text-gray-400 mb-2"><?php echo $t['admin_menu']; ?></div>
                        <a href="admin_merch.php" class="mobile-menu-item py-2 text-sm"><?php echo $t['manage_merchandise']; ?></a>
                        <a href="admin_orders.php" class="mobile-menu-item py-2 text-sm"><?php echo $t['manage_orders']; ?></a>
                    </div>
                    
                    <div class="mobile-menu-item py-3 flex items-center space-x-4">
                        <a href="?lang=en" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?lang=ka" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">ქარ</a>
                    </div>
                    
                    <div class="pt-4 border-t border-neutral-800 mt-3 space-y-3">
                        <a href="../profile.php" class="bg-transparent border border-festival-yellow py-2 px-4 block text-center text-festival-yellow hover:bg-festival-yellow hover:text-black transition-colors festival-font">
                            <?php echo $t['profile']; ?>
                        </a>
                        <a href="../logout.php" class="bg-red-600 py-2 px-4 block text-center hover:bg-red-700 transition-colors festival-font">
                            <?php echo $t['logout']; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Scrolling Banner -->
    <div class="scrolling-banner py-2 mt-16">
        <div class="scrolling-text whitespace-nowrap text-sm font-bold festival-font">
            <?php echo $t['most_anticipated']; ?> • <?php echo $t['most_anticipated']; ?> • <?php echo $t['most_anticipated']; ?> • 
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 sm:py-12">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-center mb-8 sm:mb-12 text-festival-yellow festival-font"><?php echo $t['admin_dashboard']; ?></h1>
        
        <!-- Admin Quick Links Section -->
        <div class="max-w-6xl mx-auto mb-12">
            <h2 class="text-xl font-bold mb-6 festival-font"><?php echo $t['quick_links']; ?></h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
                <a href="admin_merch.php" class="admin-card p-6 rounded-none group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-none admin-card-icon flex items-center justify-center mr-4">
                            <i class="fas fa-tshirt text-festival-yellow text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold festival-font group-hover:text-festival-yellow transition-colors"><?php echo $t['merch_dashboard']; ?></h3>
                    </div>
                    
                </a>
                
                <a href="admin_orders.php" class="admin-card p-6 rounded-none group">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-none admin-card-icon flex items-center justify-center mr-4">
                            <i class="fas fa-shopping-cart text-festival-yellow text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold festival-font group-hover:text-festival-yellow transition-colors"><?php echo $t['merch_orders']; ?></h3>
                    </div>
                    
                </a>
                
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8">
                <h2 class="text-xl sm:text-2xl font-bold text-white festival-font mb-2 sm:mb-0"><?php echo $t['ticket_orders']; ?></h2>
                <div class="flex items-center">
                    <div class="text-sm text-gray-400 mr-4">
                        <?php echo $currentDateTime; ?>
                    </div>
                </div>
            </div>
            
            <div class="admin-container rounded-none p-1">
                <div class="overflow-x-auto bg-black/80">
                    <table class="w-full">
                        <thead class="bg-black">
                            <tr class="text-festival-yellow">
                                <th class="px-4 py-3 text-left festival-font"><?php echo $t['order_id']; ?></th>
                                <th class="px-4 py-3 text-left festival-font"><?php echo $t['user']; ?></th>
                                <th class="px-4 py-3 text-left festival-font hidden sm:table-cell"><?php echo $t['ticket']; ?></th>
                                <th class="px-4 py-3 text-left festival-font"><?php echo $t['qty']; ?></th>
                                <th class="px-4 py-3 text-left festival-font hidden xs:table-cell"><?php echo $t['amount']; ?></th>
                                <th class="px-4 py-3 text-left festival-font"><?php echo $t['status']; ?></th>
                                <th class="px-4 py-3 text-left festival-font hidden md:table-cell"><?php echo $t['date']; ?></th>
                                <th class="px-4 py-3 text-left festival-font"><?php echo $t['actions']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr class="border-t border-gray-800 hover:bg-gray-900/30 transition-colors">
                                    <td class="px-4 py-4">#<?php echo sprintf('%06d', $order['id']); ?></td>
                                    <td class="px-4 py-4">
                                        <div class="font-medium"><?php echo htmlspecialchars($order['username']); ?></div>
                                        <div class="text-xs text-gray-400 hidden xs:block"><?php echo htmlspecialchars($order['email']); ?></div>
                                    </td>
                                    <td class="px-4 py-4 hidden sm:table-cell"><?php echo htmlspecialchars($order['ticket_name']); ?></td>
                                    <td class="px-4 py-4"><?php echo $order['quantity']; ?></td>
                                    <td class="px-4 py-4 font-bold hidden xs:table-cell">$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td class="px-4 py-4">
                                        <span class="px-3 py-1 rounded-none text-xs font-bold
                                            <?php echo $order['status'] === 'approved' ? 'bg-green-900/40 text-green-300 border border-green-700' : 
                                                ($order['status'] === 'rejected' ? 'bg-red-900/40 text-red-300 border border-red-700' : 
                                                'bg-yellow-900/40 text-yellow-300 border border-yellow-700'); ?>">
                                            <?php echo strtoupper($order['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-sm hidden md:table-cell"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                    <td class="px-4 py-4">
                                        <?php if ($order['status'] === 'pending'): ?>
                                            <div class="flex flex-col xs:flex-row space-y-2 xs:space-y-0 xs:space-x-2">
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="action-button bg-green-600 text-white px-3 py-1 rounded-none text-xs hover:bg-green-500 transition-colors">
                                                        <i class="fas fa-check mr-1"></i><?php echo $t['approve']; ?>
                                                    </button>
                                                </form>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="action-button bg-red-600 text-white px-3 py-1 rounded-none text-xs hover:bg-red-500 transition-colors">
                                                        <i class="fas fa-times mr-1"></i><?php echo $t['reject']; ?>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-xs"><?php echo $t['no_action']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-gray-400">No orders found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-neutral-900 py-6 border-t border-neutral-800 mt-8 sm:mt-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold mb-2 text-festival-yellow festival-font">PULSE FESTIVAL</h2>
            <p class="text-gray-400 mb-4 text-sm"><?php echo $t['admin_panel']; ?></p>
            <div class="text-xs text-gray-500">
                &copy; 2025 PULSE Festival. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>