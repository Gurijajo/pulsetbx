<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

$auth = new Auth();
$auth->requireAdmin();

// Current date and time updated
$currentDateTime = '2025-07-08 08:26:24';
$currentUser = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guram-jajanidze';

// Language support
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$_SESSION['lang'] = $lang;

// Language translations
$translations = [
    'en' => [
        'admin_dashboard' => 'ADMIN DASHBOARD',
        'merch_orders' => 'MERCHANDISE ORDERS',
        'order_id' => 'Order ID',
        'user' => 'User',
        'items' => 'Items',
        'amount' => 'Amount',
        'status' => 'Status',
        'date' => 'Date',
        'actions' => 'Actions',
        'view_details' => 'View Details',
        'process' => 'Process',
        'ship' => 'Ship',
        'deliver' => 'Deliver',
        'cancel' => 'Cancel',
        'no_action_needed' => 'No action needed',
        'home' => 'HOME',
        'artists' => 'ARTISTS',
        'schedule' => 'SCHEDULE',
        'tickets' => 'TICKETS',
        'merchandise' => 'MERCH',
        'profile' => 'PROFILE',
        'logout' => 'LOGOUT',
        'admin_panel' => 'ADMIN PANEL',
        'most_anticipated' => 'THE MOST UNFORGETTABLE NIGHT OF MUSIC IS COMING — DONT MISS THE VIBE EVERYONES TALKING ABOUT!',
        'back_to_orders' => 'Back to Orders List',
        'order_details' => 'ORDER DETAILS',
        'customer_info' => 'Customer Information',
        'shipping_address' => 'Shipping Address',
        'payment_method' => 'Payment Method',
        'update_status' => 'Update Status',
        'order_items' => 'Order Items',
        'product' => 'Product',
        'price' => 'Price',
        'quantity' => 'Quantity',
        'options' => 'Options',
        'subtotal' => 'Subtotal',
        'shipping' => 'Shipping',
        'total' => 'Total',
        'status_updated' => 'Order status updated successfully',
        'size' => 'Size',
        'color' => 'Color',
        'no_orders' => 'No orders found',
        'dashboard' => 'DASHBOARD',
        'orders' => 'ORDERS',
    ],
    'ka' => [
        'admin_dashboard' => 'ადმინ პანელი',
        'merch_orders' => 'მერჩის შეკვეთები',
        'order_id' => 'შეკვეთის ID',
        'user' => 'მომხმარებელი',
        'items' => 'ნივთები',
        'amount' => 'თანხა',
        'status' => 'სტატუსი',
        'date' => 'თარიღი',
        'actions' => 'ქმედებები',
        'view_details' => 'დეტალები',
        'process' => 'დამუშავება',
        'ship' => 'გაგზავნა',
        'deliver' => 'მიწოდება',
        'cancel' => 'გაუქმება',
        'no_action_needed' => 'ქმედება არ არის საჭირო',
        'home' => 'მთავარი',
        'artists' => 'არტისტები',
        'schedule' => 'განრიგი',
        'tickets' => 'ბილეთები',
        'merchandise' => 'მერჩი',
        'profile' => 'პროფილი',
        'logout' => 'გასვლა',
        'admin_panel' => 'ადმინ პანელი',
        'most_anticipated' => 'მუსიკის ყველაზე დაუვიწყარი ღამე ახლოვდება — არ გამოტოვო ატმოსფერო, რომელზეც ყველა ლაპარაკობს!',
        'back_to_orders' => 'დაბრუნება შეკვეთების სიაში',
        'order_details' => 'შეკვეთის დეტალები',
        'customer_info' => 'კლიენტის ინფორმაცია',
        'shipping_address' => 'მიწოდების მისამართი',
        'payment_method' => 'გადახდის მეთოდი',
        'update_status' => 'სტატუსის განახლება',
        'order_items' => 'შეკვეთის ნივთები',
        'product' => 'პროდუქტი',
        'price' => 'ფასი',
        'quantity' => 'რაოდენობა',
        'options' => 'პარამეტრები',
        'subtotal' => 'შუალედური ჯამი',
        'shipping' => 'მიწოდება',
        'total' => 'ჯამი',
        'status_updated' => 'შეკვეთის სტატუსი წარმატებით განახლდა',
        'size' => 'ზომა',
        'color' => 'ფერი',
        'no_orders' => 'შეკვეთები ვერ მოიძებნა',
        'dashboard' => 'დაშბორდი',
        'orders' => 'შეკვეთები',
    ]
];

$t = $translations[$lang];

// Database connection
$database = new Database();
$conn = $database->getConnection();

// Handle status updates
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    
    $valid_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    if (in_array($status, $valid_statuses)) {
        $query = "UPDATE merch_orders SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        
        if ($stmt->execute([$status, $order_id])) {
            $success = $t['status_updated'];
        }
    }
}

// Get single order details if viewing a specific order
$order = null;
$order_items = [];
$customer = null;

if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
    
    // Get order details
    $query = "SELECT * FROM merch_orders WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($order) {
        // Get order items
        $query = "SELECT oi.*, mi.name, mi.name_ka, mi.image 
                  FROM order_items oi 
                  JOIN merch_items mi ON oi.merch_id = mi.id 
                  WHERE oi.order_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$order_id]);
        $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get customer information
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$order['user_id']]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} else {
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 15;
$offset = ($page - 1) * $per_page;
    
    // Get total count
    $count_query = "SELECT COUNT(*) as total FROM merch_orders";
    $count_stmt = $conn->prepare($count_query);
    $count_stmt->execute();
    $count_result = $count_stmt->fetch(PDO::FETCH_ASSOC);
    $total_orders = $count_result['total'];
    $total_pages = ceil($total_orders / $per_page);
    
    $query = "SELECT mo.*, u.username, u.email, 
          (SELECT COUNT(*) FROM order_items WHERE order_id = mo.id) as item_count
          FROM merch_orders mo 
          JOIN users u ON mo.user_id = u.id 
          ORDER BY mo.created_at DESC
          LIMIT $per_page OFFSET $offset";
$stmt = $conn->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['admin_panel']; ?> - <?php echo $t['orders']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
                        'xs': '480px',
                        'sm': '640px',
                        'md': '768px',
                        'lg': '1024px',
                        'xl': '1280px',
                        '2xl': '1536px'
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

        .status-pending {
            background-color: #ffb74d33;
            border-color: #ffb74d;
            color: #ffb74d;
        }
        
        .status-processing {
            background-color: #42a5f533;
            border-color: #42a5f5;
            color: #42a5f5;
        }
        
        .status-shipped {
            background-color: #9c27b033;
            border-color: #9c27b0;
            color: #9c27b0;
        }
        
        .status-delivered {
            background-color: #4caf5033;
            border-color: #4caf50;
            color: #4caf50;
        }
        
        .status-cancelled {
            background-color: #f4433633;
            border-color: #f44336;
            color: #f44336;
        }
        

        
        .sidebar-link {
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .sidebar-link:hover {
            background-color: rgba(59, 255, 68, 0.1);
            border-left-color: var(--primary-color);
        }
        
        .sidebar-link.active {
            background-color: rgba(59, 255, 68, 0.15);
            border-left-color: var(--primary-color);
        }
        
        /* Responsive table styles */
        .responsive-table {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Card view for mobile order items */
        @media (max-width: 639px) {
            .order-card {
                @apply border border-gray-800 p-4 mb-4;
            }
            .order-card-row {
                @apply flex justify-between py-1;
            }
            .order-card-label {
                @apply text-gray-400 text-sm;
            }
            .order-card-value {
                @apply text-right;
            }
        }
        
        /* Responsive order details grid */
        @media (max-width: 1023px) {
            .order-details-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
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
                        <a href="?<?php echo isset($_GET['order_id']) ? 'order_id='.$_GET['order_id'].'&' : ''; ?>lang=en" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> hover:text-festival-yellow transition-colors text-sm festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?<?php echo isset($_GET['order_id']) ? 'order_id='.$_GET['order_id'].'&' : ''; ?>lang=ka" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> hover:text-festival-yellow transition-colors text-sm festival-font font-bold">ქარ</a>
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
                    
                    <div class="mobile-menu-item py-3 flex items-center space-x-4">
                        <a href="?<?php echo isset($_GET['order_id']) ? 'order_id='.$_GET['order_id'].'&' : ''; ?>lang=en" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?<?php echo isset($_GET['order_id']) ? 'order_id='.$_GET['order_id'].'&' : ''; ?>lang=ka" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">ქარ</a>
                    </div>
                    
<!-- Admin Links for Mobile -->
<div class="pt-4 mt-3 border-t border-neutral-800">
    <div class="text-sm text-gray-400 mb-3 px-1 font-medium"><?php echo $t['admin_panel']; ?></div>
    <a href="dashboard.php" class="flex items-center space-x-3 py-2.5 px-3 text-sm text-gray-300 hover:bg-gray-900/30 transition-colors rounded-sm">
        <i class="fas fa-tachometer-alt w-5 text-center text-gray-400"></i>
        <span class="festival-font"><?php echo $t['dashboard']; ?></span>
    </a>
    <a href="admin_merch.php" class="flex items-center space-x-3 py-2.5 px-3 text-sm text-gray-300 hover:bg-gray-900/30 transition-colors rounded-sm">
        <i class="fas fa-tshirt w-5 text-center text-gray-400"></i>
        <span class="festival-font"><?php echo $t['merchandise']; ?></span>
    </a>
    <a href="admin_orders.php" class="flex items-center space-x-3 py-2.5 px-3 text-sm text-white bg-gray-800/60 border-l-2 border-festival-yellow rounded-sm">
        <i class="fas fa-shopping-cart w-5 text-center text-festival-yellow"></i>
        <span class="festival-font"><?php echo $t['orders']; ?></span>
    </a>
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

    <!-- Main Content -->
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 admin-sidebar absolute h-full pt-20 hidden md:block">
            <nav class="mt-4 px-4">
                <a href="dashboard.php" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:text-white">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span class="ml-3 festival-font"><?php echo $t['dashboard']; ?></span>
                </a>
                <a href="admin_merch.php" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:text-white">
                    <i class="fas fa-tshirt w-6"></i>
                    <span class="ml-3 festival-font"><?php echo $t['merchandise']; ?></span>
                </a>
                <a href="admin_orders.php" class="sidebar-link active flex items-center px-4 py-3 text-white">
                    <i class="fas fa-shopping-cart w-6"></i>
                    <span class="ml-3 festival-font"><?php echo $t['orders']; ?></span>
                </a>
            </nav>
        </aside>




        <!-- Main content area -->
        <main class="w-full md:ml-64 p-4 sm:p-6 pt-20 md:pt-24">
            <div class="container mx-auto">
                <?php if ($success): ?>
                    <div class="bg-green-900/50 border border-green-600 text-green-200 px-4 py-3 mb-6">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($order)): /* Single Order View */ ?>
                    <!-- Back Button -->
                    <div class="mb-4">
                        <a href="admin_orders.php<?php echo $lang !== 'en' ? '?lang='.$lang : ''; ?>" class="text-festival-yellow hover:underline">
                            <i class="fas fa-arrow-left mr-1"></i> <?php echo $t['back_to_orders']; ?>
                        </a>
                    </div>
                    
                    <!-- Order Details Grid - Responsive layout -->
                    <div class="order-details-grid lg:grid lg:grid-cols-3 lg:gap-6">
                        <!-- Order Details - 2/3 width on large screens -->
                        <div class="lg:col-span-2 mb-6 lg:mb-0">
                            <div class="admin-container rounded-none overflow-hidden">
                                <div class="bg-black/80 p-4 sm:p-6">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                                        <div>
                                            <h2 class="text-xl sm:text-2xl font-bold text-festival-yellow festival-font"><?php echo $t['order_details']; ?> #<?php echo $order['id']; ?></h2>
                                            <p class="text-gray-400 mt-1 text-sm"><?php echo date('F j, Y - H:i', strtotime($order['created_at'])); ?></p>
                                        </div>
                                        <div class="px-3 py-1 rounded-none border text-xs font-bold w-fit
                                            <?php echo 'status-'.$order['status']; ?>">
                                            <?php echo strtoupper($order['status']); ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Status Update Form -->
                                    <form action="admin_orders.php<?php echo $lang !== 'en' ? '?lang='.$lang : ''; ?>" method="POST" class="mt-6 mb-8">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <label class="text-sm font-medium w-full xs:w-auto xs:min-w-[120px] mb-1 xs:mb-0"><?php echo $t['update_status']; ?>:</label>
                                            <select name="status" class="bg-black/50 border border-gray-700 text-sm py-2 px-3 rounded-none w-full xs:w-auto mb-2 xs:mb-0">
                                                <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                                <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                                <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                            <button type="submit" name="update_status" class="bg-festival-yellow text-black px-4 py-2 text-sm festival-font hover:bg-white transition-colors w-full xs:w-auto">
                                                <?php echo $t['update_status']; ?>
                                            </button>
                                        </div>
                                    </form>
                                    
                                    <!-- Order Items -->
                                    <h3 class="text-lg font-bold mb-4 festival-font"><?php echo $t['order_items']; ?></h3>
                                    
                                    <!-- Desktop/Tablet View -->
                                    <div class="hidden sm:block responsive-table">
                                        <table class="w-full text-sm">
                                            <thead>
                                                <tr class="border-b border-gray-800">
                                                    <th class="py-2 text-left"><?php echo $t['product']; ?></th>
                                                    <th class="py-2 text-left"><?php echo $t['price']; ?></th>
                                                    <th class="py-2 text-left"><?php echo $t['quantity']; ?></th>
                                                    <th class="py-2 text-left hidden sm:table-cell"><?php echo $t['options']; ?></th>
                                                    <th class="py-2 text-right"><?php echo $t['subtotal']; ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($order_items as $item): ?>
                                                    <tr class="border-b border-gray-800">
                                                        <td class="py-3">
                                                            <div class="flex items-center">
                                                                <?php if (!empty($item['image'])): ?>
                                                                    <div class="w-12 h-12 bg-gray-900 mr-3 flex-shrink-0">
                                                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-full h-full object-cover">
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div>
                                                                    <?php echo htmlspecialchars($lang === 'ka' && !empty($item['name_ka']) ? $item['name_ka'] : $item['name']); ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="py-3">$<?php echo number_format($item['price_per_unit'], 2); ?></td>
                                                        <td class="py-3"><?php echo $item['quantity']; ?></td>
                                                        <td class="py-3 hidden sm:table-cell">
                                                            <?php if ($item['size']): ?>
                                                                <div><?php echo $t['size']; ?>: <?php echo htmlspecialchars($item['size']); ?></div>
                                                            <?php endif; ?>
                                                            <?php if ($item['color']): ?>
                                                                <div>
                                                                    <?php echo $t['color']; ?>: 
                                                                    <span class="inline-block w-3 h-3 rounded-full align-middle" style="background-color: <?php echo htmlspecialchars($item['color']); ?>"></span>
                                                                </div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="py-3 text-right font-medium">$<?php echo number_format($item['price_per_unit'] * $item['quantity'], 2); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Mobile Card View -->
                                    <div class="sm:hidden space-y-4">
                                        <?php foreach ($order_items as $item): ?>
                                            <div class="order-card">
                                                <div class="flex items-center mb-3">
                                                    <?php if (!empty($item['image'])): ?>
                                                        <div class="w-16 h-16 bg-gray-900 mr-3 flex-shrink-0">
                                                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-full h-full object-cover">
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="font-medium">
                                                            <?php echo htmlspecialchars($lang === 'ka' && !empty($item['name_ka']) ? $item['name_ka'] : $item['name']); ?>
                                                        </div>
                                                        <div class="text-xs text-gray-400">
                                                            <?php if ($item['size']): ?>
                                                                <?php echo $t['size']; ?>: <?php echo htmlspecialchars($item['size']); ?>
                                                            <?php endif; ?>
                                                            <?php if ($item['color']): ?>
                                                                <?php echo $item['size'] ? ' | ' : ''; ?><?php echo $t['color']; ?>: 
                                                                <span class="inline-block w-2 h-2 rounded-full align-middle" style="background-color: <?php echo htmlspecialchars($item['color']); ?>"></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="order-card-row">
                                                    <span class="order-card-label"><?php echo $t['price']; ?>:</span>
                                                    <span class="order-card-value">$<?php echo number_format($item['price_per_unit'], 2); ?></span>
                                                </div>
                                                <div class="order-card-row">
                                                    <span class="order-card-label"><?php echo $t['quantity']; ?>:</span>
                                                    <span class="order-card-value"><?php echo $item['quantity']; ?></span>
                                                </div>
                                                <div class="order-card-row border-t border-gray-700 pt-2 mt-1">
                                                    <span class="order-card-label"><?php echo $t['subtotal']; ?>:</span>
                                                    <span class="order-card-value font-bold">$<?php echo number_format($item['price_per_unit'] * $item['quantity'], 2); ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- Order Summary -->
                                    <div class="mt-6 pt-6 border-t border-gray-800">
                                        <div class="flex justify-end">
                                            <div class="w-full sm:w-64">
                                                <div class="flex justify-between mb-2">
                                                    <span class="text-gray-400"><?php echo $t['subtotal']; ?></span>
                                                    <span>$<?php echo number_format($order['total_amount'] - 10.00, 2); ?></span>
                                                </div>
                                                <div class="flex justify-between mb-2">
                                                    <span class="text-gray-400"><?php echo $t['shipping']; ?></span>
                                                    <span>$10.00</span>
                                                </div>
                                                <div class="flex justify-between pt-2 border-t border-gray-800 text-lg font-bold">
                                                    <span><?php echo $t['total']; ?></span>
                                                    <span class="text-festival-yellow">$<?php echo number_format($order['total_amount'], 2); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Customer Information - 1/3 width on large screens -->
                        <div>
                            <div class="admin-container rounded-none overflow-hidden">
                                <div class="bg-black/80 p-4 sm:p-6">
                                    <h3 class="text-lg font-bold mb-4 festival-font"><?php echo $t['customer_info']; ?></h3>
                                    <?php if ($customer): ?>
                                        <div class="mb-6">
                                            <div class="font-medium text-lg"><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></div>
                                            <div class="text-gray-400 mt-1"><?php echo htmlspecialchars($customer['email']); ?></div>
                                            <?php if (!empty($customer['phone'])): ?>
                                                <div class="text-gray-400"><?php echo htmlspecialchars($customer['phone']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <h3 class="text-lg font-bold mb-2 festival-font"><?php echo $t['shipping_address']; ?></h3>
                                    <div class="text-gray-200 mb-6 whitespace-pre-line">
                                        <?php echo htmlspecialchars($order['shipping_address']); ?>
                                    </div>
                                    
                                    <h3 class="text-lg font-bold mb-2 festival-font"><?php echo $t['payment_method']; ?></h3>
                                    <div class="text-gray-200">
                                        <?php echo ucfirst(htmlspecialchars($order['payment_method'])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: /* Orders List View */ ?>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-6">
                        <h1 class="text-2xl md:text-3xl font-bold festival-font text-festival-yellow"><?php echo $t['merch_orders']; ?></h1>
                        <div class="text-sm text-gray-400">
                            <?php echo $currentDateTime; ?>
                        </div>
                    </div>
                    
                    <div class="admin-container rounded-none p-1">
                        <!-- Desktop/Tablet View -->
                        <div class="overflow-x-auto bg-black/80 hidden xs:block">
                            <table class="w-full">
                                <thead class="bg-black">
                                    <tr class="text-festival-yellow">
                                        <th class="px-4 py-3 text-left festival-font"><?php echo $t['order_id']; ?></th>
                                        <th class="px-4 py-3 text-left festival-font"><?php echo $t['user']; ?></th>
                                        <th class="px-4 py-3 text-left festival-font hidden xs:table-cell"><?php echo $t['items']; ?></th>
                                        <th class="px-4 py-3 text-left festival-font hidden sm:table-cell"><?php echo $t['amount']; ?></th>
                                        <th class="px-4 py-3 text-left festival-font"><?php echo $t['status']; ?></th>
                                        <th class="px-4 py-3 text-left festival-font hidden md:table-cell"><?php echo $t['date']; ?></th>
                                        <th class="px-4 py-3 text-left festival-font"><?php echo $t['actions']; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($orders)): ?>
                                        <tr>
                                            <td colspan="7" class="px-4 py-6 text-center text-gray-400"><?php echo $t['no_orders']; ?></td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($orders as $order): ?>
                                            <tr class="border-t border-gray-800 hover:bg-gray-900/30 transition-colors">
                                                <td class="px-4 py-4">#<?php echo sprintf('%06d', $order['id']); ?></td>
                                                <td class="px-4 py-4">
                                                    <div class="font-medium"><?php echo htmlspecialchars($order['username']); ?></div>
                                                    <div class="text-xs text-gray-400 hidden xs:block"><?php echo htmlspecialchars($order['email']); ?></div>
                                                </td>
                                                <td class="px-4 py-4 hidden xs:table-cell"><?php echo $order['item_count']; ?></td>
                                                <td class="px-4 py-4 font-bold hidden sm:table-cell">$<?php echo number_format($order['total_amount'], 2); ?></td>
                                                <td class="px-4 py-4">
                                                    <span class="px-3 py-1 rounded-none text-xs font-bold border inline-block <?php echo 'status-' . $order['status']; ?>">
                                                        <?php echo strtoupper($order['status']); ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 text-sm hidden md:table-cell"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                                <td class="px-4 py-4">
                                                    <a href="?order_id=<?php echo $order['id']; ?><?php echo $lang !== 'en' ? '&lang='.$lang : ''; ?>" class="action-button bg-festival-yellow text-black px-3 py-1 rounded-none text-xs hover:bg-white transition-colors inline-block">
                                                        <?php echo $t['view_details']; ?>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div class="xs:hidden bg-black/80 p-4">
                            <?php if (empty($orders)): ?>
                                <div class="text-center text-gray-400 py-4"><?php echo $t['no_orders']; ?></div>
                            <?php else: ?>
                                <div class="space-y-4">
                                    <?php foreach ($orders as $order): ?>
                                        <div class="border border-gray-800 p-4 hover:bg-gray-900/30 transition-colors">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <div class="font-medium">#<?php echo sprintf('%06d', $order['id']); ?></div>
                                                    <div class="text-xs text-gray-400"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></div>
                                                </div>
                                                <span class="px-2 py-1 rounded-none text-xs font-bold border <?php echo 'status-' . $order['status']; ?>">
                                                    <?php echo strtoupper($order['status']); ?>
                                                </span>
                                            </div>
                                            
                                            <div class="text-sm mb-3">
                                                <div class="font-medium"><?php echo htmlspecialchars($order['username']); ?></div>
                                                <div class="text-xs text-gray-400"><?php echo htmlspecialchars($order['email']); ?></div>
                                            </div>
                                            
                                            <div class="flex justify-between text-sm mb-4">
                                                <span><?php echo $t['items']; ?>: <?php echo $order['item_count']; ?></span>
                                                <span class="font-bold">$<?php echo number_format($order['total_amount'], 2); ?></span>
                                            </div>
                                            
                                            <a href="?order_id=<?php echo $order['id']; ?><?php echo $lang !== 'en' ? '&lang='.$lang : ''; ?>" class="action-button bg-festival-yellow text-black py-2 px-4 rounded-none text-xs hover:bg-white transition-colors block text-center">
                                                <?php echo $t['view_details']; ?>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (isset($total_pages) && $total_pages > 1): ?>
                        <div class="flex justify-center mt-6">
                            <div class="flex flex-wrap justify-center gap-1">
                                <?php if ($page > 1): ?>
                                    <a href="?page=<?php echo $page - 1; ?><?php echo $lang !== 'en' ? '&lang='.$lang : ''; ?>" class="px-3 py-1 border border-gray-700 text-sm hover:border-festival-yellow hover:text-festival-yellow">
                                        &laquo;
                                    </a>
                                <?php endif; ?>
                                
                                <?php
                                $start = max(1, $page - 2);
                                $end = min($total_pages, $start + 4);
                                if ($end - $start < 4) $start = max(1, $end - 4);
                                
                                for ($i = $start; $i <= $end; $i++):
                                ?>
                                    <a href="?page=<?php echo $i; ?><?php echo $lang !== 'en' ? '&lang='.$lang : ''; ?>" 
                                       class="px-3 py-1 border text-sm <?php echo $i == $page ? 'border-festival-yellow text-festival-yellow' : 'border-gray-700 hover:border-festival-yellow hover:text-festival-yellow'; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if ($page < $total_pages): ?>
                                    <a href="?page=<?php echo $page + 1; ?><?php echo $lang !== 'en' ? '&lang='.$lang : ''; ?>" class="px-3 py-1 border border-gray-700 text-sm hover:border-festival-yellow hover:text-festival-yellow">
                                        &raquo;
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </main>
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