<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth = new Auth();
$auth->requireLogin();

// Language support
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$_SESSION['lang'] = $lang;

// Current date/time - updated to match requested format
$currentDateTime = '2025-07-08 09:11:00';

// Get current user from session
$currentUser = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

$orders = getUserOrders($_SESSION['user_id']);
$merchOrders = getUserMerchOrders($_SESSION['user_id']);
$success = $_GET['success'] ?? false;

// Language translations
$translations = [
    'en' => [
        'my_profile' => 'MY PROFILE',
        'welcome_back' => 'Welcome back',
        'my_tickets' => 'MY TICKETS',
        'my_merchandise' => 'MY MERCHANDISE',
        'current_date' => 'Current Date',
        'no_tickets' => 'You haven\'t purchased any tickets yet for the festival.',
        'no_merchandise' => 'You haven\'t purchased any merchandise yet.',
        'buy_tickets' => 'BUY TICKETS',
        'buy_more_tickets' => 'BUY MORE TICKETS',
        'shop_merchandise' => 'SHOP MERCHANDISE',
        'shop_more_merchandise' => 'SHOP MORE MERCHANDISE',
        'order_number' => 'Order Number',
        'quantity' => 'Quantity',
        'price_per_ticket' => 'Price per ticket',
        'total_amount' => 'Total amount',
        'purchase_date' => 'Purchase date',
        'present_qr' => 'Present this QR code at the festival entrance',
        'download_qr' => 'Download QR',
        'processing_order' => 'Your order is being processed. Please wait for admin approval.',
        'processing_time' => 'Processing usually takes 1-2 business days',
        'order_rejected' => 'Unfortunately, your order was rejected.',
        'contact_support' => 'Please contact customer support for assistance',
        'success_message' => 'Ticket purchased successfully! Your QR code is ready.',
        'ticket_holder' => 'TICKET HOLDER',
        'main_ticket' => 'Main Ticket',
        'additional_ticket' => 'Additional Ticket',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'id_number' => 'ID Number',
        'logout' => 'LOGOUT',
        'home' => 'HOME',
        'artists' => 'ARTISTS',
        'schedule' => 'SCHEDULE',
        'tickets' => 'TICKETS',
        'merchandise' => 'MERCH',
        'approved' => 'APPROVED',
        'pending' => 'PENDING',
        'rejected' => 'REJECTED',
        'processing' => 'PROCESSING',
        'shipped' => 'SHIPPED',
        'delivered' => 'DELIVERED',
        'cancelled' => 'CANCELLED',
        'festival_tagline' => 'THE MOST UNFORGETTABLE NIGHT OF MUSIC IS COMING — DONT MISS THE VIBE EVERYONES TALKING ABOUT!',
        'shipping_address' => 'Shipping Address',
        'payment_method' => 'Payment Method',
        'items' => 'Items',
        'size' => 'Size',
        'color' => 'Color',
        'price' => 'Price',
        'subtotal' => 'Subtotal',
        'shipping' => 'Shipping',
        'total' => 'Total',
        'order_status' => 'Order Status',
        'view_tickets' => 'View Tickets',
        'view_merchandise' => 'View Merchandise',
        'tabs_section' => 'My Orders'
    ],
    'ka' => [
        'my_profile' => 'ჩემი პროფილი',
        'welcome_back' => 'კეთილი იყოს თქვენი დაბრუნება',
        'my_tickets' => 'ჩემი ბილეთები',
        'my_merchandise' => 'ჩემი მერჩი',
        'current_date' => 'მიმდინარე თარიღი',
        'no_tickets' => 'თქვენ ჯერ არ შეგიძენიათ ბილეთები ფესტივალზე.',
        'no_merchandise' => 'თქვენ ჯერ არ შეგიძენიათ მერჩი.',
        'buy_tickets' => 'ბილეთების ყიდვა',
        'buy_more_tickets' => 'მეტი ბილეთის ყიდვა',
        'shop_merchandise' => 'მერჩის ყიდვა',
        'shop_more_merchandise' => 'მეტი მერჩის ყიდვა',
        'order_number' => 'შეკვეთის ნომერი',
        'quantity' => 'რაოდენობა',
        'price_per_ticket' => 'ფასი ერთ ბილეთზე',
        'total_amount' => 'ჯამური თანხა',
        'purchase_date' => 'შეძენის თარიღი',
        'present_qr' => 'წარადგინეთ ეს QR კოდი ფესტივალის შესასვლელში',
        'download_qr' => 'QR-ის ჩამოტვირთვა',
        'processing_order' => 'თქვენი შეკვეთა მუშავდება. გთხოვთ დაელოდოთ ადმინის დამტკიცებას.',
        'processing_time' => 'დამუშავება ჩვეულებრივ 1-2 სამუშაო დღეს გრძელდება',
        'order_rejected' => 'სამწუხაროდ, თქვენი შეკვეთა უარყოფილია.',
        'contact_support' => 'გთხოვთ დაუკავშირდეთ მომხმარებელთა მხარდაჭერას დახმარებისთვის',
        'success_message' => 'ბილეთი წარმატებით შეძენილია! თქვენი QR კოდი მზადაა.',
        'ticket_holder' => 'ბილეთის მფლობელი',
        'main_ticket' => 'მთავარი ბილეთი',
        'additional_ticket' => 'დამატებითი ბილეთი',
        'first_name' => 'სახელი',
        'last_name' => 'გვარი',
        'id_number' => 'პირადი ნომერი',
        'logout' => 'გასვლა',
        'home' => 'მთავარი',
        'artists' => 'არტისტები',
        'schedule' => 'განრიგი',
        'tickets' => 'ბილეთები',
        'merchandise' => 'მერჩი',
        'approved' => 'დამტკიცებული',
        'pending' => 'მიმდინარე',
        'rejected' => 'უარყოფილი',
        'processing' => 'მუშავდება',
        'shipped' => 'გაგზავნილი',
        'delivered' => 'მიწოდებული',
        'cancelled' => 'გაუქმებული',
        'festival_tagline' => 'მუსიკის ყველაზე დაუვიწყარი ღამე ახლოვდება — არ გამოტოვო ატმოსფერო, რომელზეც ყველა ლაპარაკობს!',
        'shipping_address' => 'მიწოდების მისამართი',
        'payment_method' => 'გადახდის მეთოდი',
        'items' => 'ნივთები',
        'size' => 'ზომა',
        'color' => 'ფერი',
        'price' => 'ფასი',
        'subtotal' => 'ჯამი',
        'shipping' => 'მიწოდება',
        'total' => 'სულ',
        'order_status' => 'შეკვეთის სტატუსი',
        'view_tickets' => 'ბილეთების ნახვა',
        'view_merchandise' => 'მერჩის ნახვა',
        'tabs_section' => 'ჩემი შეკვეთები'
    ]
];

$t = $translations[$lang];

// Function to get user merchandise orders
function getUserMerchOrders($userId) {
    global $conn;
    
    try {
        $database = new Database();
        $conn = $database->getConnection();
        
        $query = "SELECT mo.*, GROUP_CONCAT(
                    CONCAT(
                        COALESCE(mi.name, 'Unknown Item'), '|',
                        oi.quantity, '|',
                        COALESCE(oi.size, ''), '|',
                        COALESCE(oi.color, ''), '|',
                        oi.price_per_unit
                    ) SEPARATOR ';;'
                  ) as items
                  FROM merch_orders mo 
                  LEFT JOIN order_items oi ON mo.id = oi.order_id
                  LEFT JOIN merch_items mi ON oi.merch_id = mi.id
                  WHERE mo.user_id = ? 
                  GROUP BY mo.id
                  ORDER BY mo.created_at DESC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Parse items for each order
        foreach ($orders as &$order) {
            $order['parsed_items'] = [];
            if (!empty($order['items'])) {
                $items = explode(';;', $order['items']);
                foreach ($items as $item) {
                    $parts = explode('|', $item);
                    if (count($parts) >= 5) {
                        $order['parsed_items'][] = [
                            'name' => $parts[0],
                            'quantity' => (int)$parts[1],
                            'size' => $parts[2],
                            'color' => $parts[3],
                            'price' => (float)$parts[4]
                        ];
                    }
                }
            }
        }
        
        return $orders;
        
    } catch (Exception $e) {
        error_log("Error fetching merch orders: " . $e->getMessage());
        return [];
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['my_profile']; ?> - PULSE Festival</title>
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
            --primary-color: #3bff44;
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
        
        .ticket-card {
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid #333;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .ticket-card:hover {
            transform: translateY(-5px);
        }
        
        .qr-code-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
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
        
        .ticket-holder-tab, .main-tab {
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .ticket-holder-tab.active, .main-tab.active {
            border-bottom: 2px solid var(--primary-color);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
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
        
        .merch-item {
            background: rgba(40, 40, 40, 0.6);
            border: 1px solid #444;
            padding: 12px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body class="bg-black text-white main-font min-h-screen">
    <!-- Navigation Bar -->
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
                    <a href="merch.php" class="hover:text-festival-yellow transition-colors festival-font"><?php echo $t['merchandise']; ?></a>
                    
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
                    <a href="logout.php" class="bg-red-600 px-4 py-2 rounded-none hover:bg-red-700 transition-colors festival-font"><?php echo $t['logout']; ?></a>
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
                    </div>
                    <a href="index.php#home" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['home']; ?></a>
                    <a href="index.php#artists" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['artists']; ?></a>
                    <a href="index.php#schedule" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['schedule']; ?></a>
                    <a href="index.php#tickets" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['tickets']; ?></a>
                    <a href="merch.php" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font"><?php echo $t['merchandise']; ?></a>
                    
                    <div class="mobile-menu-item py-3 flex items-center space-x-4">
                        <a href="?lang=en" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?lang=ka" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">ქარ</a>
                    </div>
                    
                    <div class="pt-4 border-t border-neutral-800 mt-3">
                        <a href="logout.php" class="bg-red-600 py-2 px-4 block text-center hover:bg-red-700 transition-colors festival-font">
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
            <?php echo $t['festival_tagline']; ?> • <?php echo $t['festival_tagline']; ?> • <?php echo $t['festival_tagline']; ?> • 
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 sm:py-12" x-data="{ activeMainTab: 'tickets' }">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-center mb-6 sm:mb-8 text-festival-yellow festival-font"><?php echo $t['my_profile']; ?></h1>
        <p class="text-center text-gray-400 mb-8 sm:mb-12"><?php echo $t['welcome_back']; ?>, <span class="text-white"><?php echo htmlspecialchars($currentUser); ?></span></p>
        
        <?php if ($success): ?>
            <div class="bg-green-900/70 border border-green-700 text-green-100 px-4 py-3 rounded-none mb-8 max-w-2xl mx-auto flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span><?php echo $t['success_message']; ?></span>
            </div>
        <?php endif; ?>
        
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-white festival-font mb-2 sm:mb-0"><?php echo $t['tabs_section']; ?></h2>
                <div class="text-sm text-gray-400">
                    <span class="inline-block"><?php echo $t['current_date']; ?>: <?php echo $currentDateTime; ?></span>
                </div>
            </div>
            
            <!-- Main Tabs -->
            <div class="flex border-b border-gray-800 mb-6">
                <div 
                    class="main-tab cursor-pointer py-3 px-6 text-base transition-all" 
                    :class="activeMainTab === 'tickets' ? 'active text-festival-yellow' : 'text-gray-400'" 
                    @click="activeMainTab = 'tickets'">
                    <i class="fas fa-ticket-alt mr-2"></i><?php echo $t['view_tickets']; ?>
                </div>
                <div 
                    class="main-tab cursor-pointer py-3 px-6 text-base transition-all" 
                    :class="activeMainTab === 'merchandise' ? 'active text-festival-yellow' : 'text-gray-400'" 
                    @click="activeMainTab = 'merchandise'">
                    <i class="fas fa-shopping-bag mr-2"></i><?php echo $t['view_merchandise']; ?>
                </div>
            </div>
            
            <!-- Tickets Tab Content -->
            <div class="tab-content" :class="activeMainTab === 'tickets' ? 'active' : ''">
                <?php if (empty($orders)): ?>
                    <div class="ticket-card rounded-none p-6 sm:p-8 text-center">
                        <i class="fas fa-ticket-alt text-4xl sm:text-5xl text-festival-yellow mb-4 sm:mb-6 opacity-50"></i>
                        <p class="text-gray-400 mb-4 sm:mb-6 main-font"><?php echo $t['no_tickets']; ?></p>
                        <a href="index.php#tickets" class="inline-block bg-festival-yellow text-black px-6 sm:px-8 py-2.5 sm:py-3 rounded-none font-bold hover:bg-white transition-colors festival-font">
                            <?php echo $t['buy_tickets']; ?>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="grid md:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">
                        <?php foreach ($orders as $orderIndex => $order): 
                            $status_class = '';
                            $status_icon = '';
                            $status_text = '';
                            
                            if ($order['status'] === 'approved') {
                                $status_class = '';
                                $status_icon = '<i class="fas fa-check-circle mr-2"></i>';
                                $status_text = $t['approved'];
                            } elseif ($order['status'] === 'pending') {
                                $status_class = '';
                                $status_icon = '<i class="fas fa-clock mr-2"></i>';
                                $status_text = $t['pending'];
                            } else {
                                $status_class = '';
                                $status_icon = '<i class="fas fa-times-circle mr-2"></i>';
                                $status_text = $t['rejected'];
                            }
                            
                            // Get ticket holders for this order
                            $ticketHolders = getTicketHolders($order['id']);
                        ?>
                            <div class="ticket-card rounded-none p-5 sm:p-6 <?php echo $status_class; ?>" x-data="{ activeTab: 1 }">
                                <div class="flex justify-between items-start mb-4 sm:mb-6">
                                    <h3 class="text-lg sm:text-xl font-bold festival-font"><?php echo htmlspecialchars($order['ticket_name']); ?></h3>
                                    <span class="px-2 sm:px-3 py-1 rounded-none text-xs font-bold
                                        <?php echo $order['status'] === 'approved' ? 'bg-green-900/50 text-green-300 border border-green-700' : 
                                            ($order['status'] === 'rejected' ? 'bg-red-900/50 text-red-300 border border-red-700' : 
                                            'bg-yellow-900/50 text-yellow-300 border border-yellow-700'); ?>">
                                        <?php echo $status_icon . $status_text; ?>
                                    </span>
                                </div>
                                
                                <div class="space-y-2 mb-5 sm:mb-6 border-b border-gray-800 pb-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-400"><?php echo $t['order_number']; ?>:</span>
                                        <span class="text-white font-medium">#<?php echo sprintf('%06d', $order['id']); ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400"><?php echo $t['quantity']; ?>:</span>
                                        <span class="text-white font-medium"><?php echo $order['quantity']; ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400"><?php echo $t['price_per_ticket']; ?>:</span>
                                        <span class="text-white font-medium">₾<?php echo number_format($order['price'], 2); ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400"><?php echo $t['total_amount']; ?>:</span>
                                        <span class="text-festival-yellow font-bold">₾<?php echo number_format($order['total_amount'], 2); ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400"><?php echo $t['purchase_date']; ?>:</span>
                                        <span class="text-white font-medium"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></span>
                                    </div>
                                </div>
                                
                                <?php if ($order['status'] === 'approved'): ?>
                                    <!-- Ticket Holder Tabs for Multiple Tickets -->
                                    <?php if (count($ticketHolders) > 1): ?>
                                        <div class="flex border-b border-gray-800 mb-4">
                                            <?php foreach ($ticketHolders as $index => $holder): ?>
                                                <div 
                                                    class="ticket-holder-tab cursor-pointer py-2 px-4 text-sm text-center transition-all" 
                                                    :class="activeTab === <?php echo $index + 1; ?> ? 'active text-festival-yellow' : 'text-gray-400'" 
                                                    @click="activeTab = <?php echo $index + 1; ?>">
                                                    <?php echo $t['ticket_holder']; ?> #<?php echo $index + 1; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php foreach ($ticketHolders as $index => $holder): ?>
                                        <div 
                                            class="tab-content transition-all" 
                                            :class="activeTab === <?php echo $index + 1; ?> ? 'active' : ''">
                                            
                                            <div class="text-sm mb-4 space-y-2 border-b border-gray-800 pb-4">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-400"><?php echo $t['first_name']; ?>:</span>
                                                    <span class="text-white font-medium"><?php echo htmlspecialchars($holder['first_name']); ?></span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-400"><?php echo $t['last_name']; ?>:</span>
                                                    <span class="text-white font-medium"><?php echo htmlspecialchars($holder['last_name']); ?></span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-400"><?php echo $t['id_number']; ?>:</span>
                                                    <span class="text-white font-medium"><?php echo htmlspecialchars($holder['id_number']); ?></span>
                                                </div>
                                                <?php if ($holder['is_main']): ?>
                                                    <div class="text-right text-xs text-festival-green">
                                                        <?php echo $t['main_ticket']; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-right text-xs text-gray-400">
                                                        <?php echo $t['additional_ticket']; ?> #<?php echo $index; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="text-center qr-code-container rounded-none">
                                                <?php 
                                                // Get QR code URL or generate if not exists
                                                $qrCodeUrl = !empty($holder['qr_code']) ? $holder['qr_code'] : "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-" . $order['id'] . "-" . $holder['id'] . "-" . time();
                                                ?>
                                                <img src="<?php echo htmlspecialchars($qrCodeUrl); ?>" 
                                                    alt="QR Code" class="mx-auto mb-3 h-28 sm:h-32 w-28 sm:w-32">
                                                <p class="text-xs text-gray-300"><?php echo $t['present_qr']; ?></p>
                                                <div class="mt-3 flex justify-center">
                                                    <a href="<?php echo htmlspecialchars($qrCodeUrl); ?>" download="ticket-qrcode-<?php echo $order['id']; ?>-<?php echo $holder['id']; ?>.png" 
                                                    class="text-festival-green underline hover:text-green-400 text-sm">
                                                        <i class="fas fa-download mr-1"></i> <?php echo $t['download_qr']; ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                <?php elseif ($order['status'] === 'pending'): ?>
                                    <div class="text-center">
                                        <i class="fas fa-hourglass-half text-yellow-400 text-3xl sm:text-4xl mb-3"></i>
                                        <p class="text-sm"><?php echo $t['processing_order']; ?></p>
                                        <p class="text-xs text-gray-500 mt-2"><?php echo $t['processing_time']; ?></p>
                                    </div>
                                <?php elseif ($order['status'] === 'rejected'): ?>
                                    <div class="text-center">
                                        <i class="fas fa-ban text-red-500 text-3xl sm:text-4xl mb-3"></i>
                                        <p class="text-sm"><?php echo $t['order_rejected']; ?></p>
                                        <p class="text-xs text-gray-500 mt-2"><?php echo $t['contact_support']; ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-8 sm:mt-10 flex justify-center">
                        <a href="index.php#tickets" class="inline-block bg-transparent border-2 border-festival-yellow text-festival-yellow px-6 sm:px-8 py-2.5 sm:py-3 rounded-none hover:bg-festival-yellow hover:text-black transition-colors festival-font text-base sm:text-lg">
                            <?php echo $t['buy_more_tickets']; ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Merchandise Tab Content -->
            <div class="tab-content" :class="activeMainTab === 'merchandise' ? 'active' : ''">
                <?php if (empty($merchOrders)): ?>
                    <div class="ticket-card rounded-none p-6 sm:p-8 text-center">
                        <i class="fas fa-shopping-bag text-4xl sm:text-5xl text-festival-yellow mb-4 sm:mb-6 opacity-50"></i>
                        <p class="text-gray-400 mb-4 sm:mb-6 main-font"><?php echo $t['no_merchandise']; ?></p>
                        <a href="merch.php" class="inline-block bg-festival-yellow text-black px-6 sm:px-8 py-2.5 sm:py-3 rounded-none font-bold hover:bg-white transition-colors festival-font">
                            <?php echo $t['shop_merchandise']; ?>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="grid md:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">
                        <?php foreach ($merchOrders as $order): 
                            $status_class = '';
                            $status_icon = '';
                            $status_text = '';
                            
                            switch ($order['status']) {
                                case 'processing':
                                    $status_class = 'bg-blue-900/50 text-blue-300 border border-blue-700';
                                    $status_icon = '<i class="fas fa-cog fa-spin mr-2"></i>';
                                    $status_text = $t['processing'];
                                    break;
                                case 'shipped':
                                    $status_class = 'bg-purple-900/50 text-purple-300 border border-purple-700';
                                    $status_icon = '<i class="fas fa-shipping-fast mr-2"></i>';
                                    $status_text = $t['shipped'];
                                    break;
                                case 'delivered':
                                    $status_class = 'bg-green-900/50 text-green-300 border border-green-700';
                                    $status_icon = '<i class="fas fa-check-circle mr-2"></i>';
                                    $status_text = $t['delivered'];
                                    break;
                                case 'cancelled':
                                    $status_class = 'bg-red-900/50 text-red-300 border border-red-700';
                                    $status_icon = '<i class="fas fa-times-circle mr-2"></i>';
                                    $status_text = $t['cancelled'];
                                    break;
                                default:
                                    $status_class = 'bg-gray-900/50 text-gray-300 border border-gray-700';
                                    $status_icon = '<i class="fas fa-question-circle mr-2"></i>';
                                    $status_text = ucfirst($order['status']);
                            }
                        ?>
                            <div class="ticket-card rounded-none p-5 sm:p-6">
                                <div class="flex justify-between items-start mb-4 sm:mb-6">
                                    <h3 class="text-lg sm:text-xl font-bold festival-font"><?php echo $t['order_number']; ?> #<?php echo sprintf('%06d', $order['id']); ?></h3>
                                    <span class="px-2 sm:px-3 py-1 rounded-none text-xs font-bold <?php echo $status_class; ?>">
                                        <?php echo $status_icon . $status_text; ?>
                                    </span>
                                </div>
                                
                                <div class="space-y-2 mb-5 sm:mb-6 border-b border-gray-800 pb-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-400"><?php echo $t['purchase_date']; ?>:</span>
                                        <span class="text-white font-medium"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400"><?php echo $t['payment_method']; ?>:</span>
                                        <span class="text-white font-medium"><?php echo ucfirst($order['payment_method']); ?></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-400"><?php echo $t['total_amount']; ?>:</span>
                                        <span class="text-festival-yellow font-bold">₾<?php echo number_format($order['total_amount'], 2); ?></span>
                                    </div>
                                </div>
                                
                                <!-- Items List -->
                                <div class="mb-4">
                                    <h4 class="text-sm font-bold text-gray-300 mb-2"><?php echo $t['items']; ?>:</h4>
                                    <?php foreach ($order['parsed_items'] as $item): ?>
                                        <div class="merch-item rounded-none">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <p class="text-white font-medium text-sm"><?php echo htmlspecialchars($item['name']); ?></p>
                                                    <div class="text-xs text-gray-400 mt-1">
                                                        <?php if (!empty($item['size'])): ?>
                                                            <span><?php echo $t['size']; ?>: <?php echo htmlspecialchars($item['size']); ?></span>
                                                        <?php endif; ?>
                                                        <?php if (!empty($item['color'])): ?>
                                                            <span class="ml-2"><?php echo $t['color']; ?>: <?php echo htmlspecialchars($item['color']); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="text-right ml-3">
                                                    <p class="text-white text-sm">₾<?php echo number_format($item['price'], 2); ?></p>
                                                    <p class="text-xs text-gray-400">x<?php echo $item['quantity']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Shipping Address -->
                                <div class="text-sm border-t border-gray-800 pt-3">
                                    <span class="text-gray-400"><?php echo $t['shipping_address']; ?>:</span>
                                    <p class="text-white mt-1"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mt-8 sm:mt-10 flex justify-center">
                        <a href="merch.php" class="inline-block bg-transparent border-2 border-festival-yellow text-festival-yellow px-6 sm:px-8 py-2.5 sm:py-3 rounded-none hover:bg-festival-yellow hover:text-black transition-colors festival-font text-base sm:text-lg">
                            <?php echo $t['shop_more_merchandise']; ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-neutral-900 py-8 sm:py-12 border-t border-neutral-800 mt-8 sm:mt-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold mb-3 sm:mb-4 text-festival-yellow festival-font">PULSE FESTIVAL</h2>
            <p class="text-gray-400 mb-5 sm:mb-6 text-sm sm:text-base"><?php echo $t['festival_tagline']; ?></p>
            <div class="flex justify-center space-x-5 md:space-x-6">
                <i class="fab fa-facebook text-lg sm:text-xl lg:text-2xl hover:text-festival-yellow cursor-pointer transition-colors"></i>
                <i class="fab fa-instagram text-lg sm:text-xl lg:text-2xl hover:text-festival-yellow cursor-pointer transition-colors"></i>
                <i class="fab fa-twitter text-lg sm:text-xl lg:text-2xl hover:text-festival-yellow cursor-pointer transition-colors"></i>
                <i class="fab fa-youtube text-lg sm:text-xl lg:text-2xl hover:text-festival-yellow cursor-pointer transition-colors"></i>
            </div>
            
            <div class="mt-4 text-xs text-gray-600">
                &copy; 2025 PULSE Festival. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>