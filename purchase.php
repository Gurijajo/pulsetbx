<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth = new Auth();
$auth->requireLogin();

$ticket_id = $_GET['ticket_id'] ?? 0;
$error = '';
$success = '';

// Current date time updated
$currentDateTime = '2025-06-24 13:49:38';

$currentUser = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';;

// Language support
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$_SESSION['lang'] = $lang;

// Set maximum tickets per user
$max_tickets_per_user = 3;

// Language translations
$translations = [
    'en' => [
        'purchase_tickets' => 'PURCHASE TICKETS',
        'secure_spot' => 'Secure your spot at PULSE Festival 2025',
        'select_quantity' => 'SELECT QUANTITY',
        'ticket_singular' => 'ticket',
        'ticket_plural' => 'tickets',
        'payment_method' => 'PAYMENT METHOD',
        'credit_card' => 'Credit Card',
        'paypal' => 'PayPal',
        'apple_pay' => 'Apple Pay',
        'crypto' => 'Crypto',
        'tickets' => 'Tickets',
        'subtotal' => 'Subtotal',
        'processing_fee' => 'Processing Fee',
        'total' => 'TOTAL',
        'complete_purchase' => 'COMPLETE PURCHASE',
        'secure_payment' => 'Secure payment processing',
        'important_info' => 'IMPORTANT INFORMATION',
        'non_refundable' => 'Tickets are non-refundable',
        'one_ticket' => 'One ticket admits one person',
        'qr_code' => 'QR code will be provided after purchase',
        'minimum_age' => 'Minimum age: 18+ with valid ID',
        'tickets_available' => 'Tickets will be available in your profile',
        'available' => 'Available',
        'date' => 'Date',
        'venue' => 'Venue',
        'entry' => 'Entry',
        'gates_open' => 'Gates open at 18:00',
        'card_number' => 'CARD NUMBER',
        'expiration_date' => 'EXPIRATION DATE',
        'cvv' => 'CVV',
        'name_on_card' => 'NAME ON CARD',
        'paypal_email' => 'PAYPAL EMAIL',
        'confirm_payment' => 'CONFIRM PAYMENT',
        'wallet_address' => 'WALLET ADDRESS',
        'additional_tickets' => 'ADDITIONAL TICKET INFORMATION',
        'first_name' => 'FIRST NAME',
        'last_name' => 'LAST NAME',
        'id_number' => 'ID NUMBER',
        'ticket' => 'Ticket',
        'main_ticket' => 'Your ticket (Main ticket)',
        'secondary_ticket' => 'Additional ticket',
        'demo_notice' => 'This is a demo payment system. No real charges will be made.',
        'official' => 'OFFICIAL',
        'complete_your_order' => 'COMPLETE YOUR ORDER',
        'max_tickets_notice' => 'Maximum 3 tickets per order',
        'payment_info' => 'PAYMENT INFORMATION',
        'ticket_holder' => 'TICKET HOLDER',
        'all_fields_required' => 'All fields are required for each ticket holder',
        'home' => 'HOME',
        'artists' => 'ARTISTS',
        'schedule' => 'SCHEDULE',
        'tickets' => 'TICKETS',
        'profile' => 'PROFILE',
        'official' => 'OFFICIAL',
        'available' => 'Available',
        'date' => 'Date',
        'venue' => 'Venue',
        'entry' => 'Entry',
        'gates_open' => 'Gates open at 18:00',
        'ticket_singular' => 'ticket',
        'ticket_plural' => 'tickets', 
        'max_tickets_notice' => 'Maximum 3 tickets per order',
        'important_info' => 'IMPORTANT INFORMATION',
        'non_refundable' => 'Tickets are non-refundable',
        'one_ticket' => 'One ticket admits one person',
        'qr_code' => 'QR code will be provided after purchase',
        'minimum_age' => 'Minimum age: 18+ with valid ID',
        'tickets_available' => 'Tickets will be available in your profile'
    ],
    'ka' => [
        'purchase_tickets' => 'ბილეთების შეძენა',
        'secure_spot' => 'დაიკავეთ ადგილი PULSE ფესტივალზე 2025',
        'select_quantity' => 'აირჩიეთ რაოდენობა',
        'ticket_singular' => 'ბილეთი',
        'ticket_plural' => 'ბილეთები',
        'payment_method' => 'გადახდის მეთოდი',
        'credit_card' => 'საკრედიტო ბარათი',
        'paypal' => 'PayPal',
        'apple_pay' => 'Apple Pay',
        'crypto' => 'კრიპტო',
        'tickets' => 'ბილეთები',
        'subtotal' => 'ჯამი',
        'processing_fee' => 'მომსახურების საფასური',
        'total' => 'სულ',
        'complete_purchase' => 'შესყიდვის დასრულება',
        'secure_payment' => 'უსაფრთხო გადახდის დამუშავება',
        'important_info' => 'მნიშვნელოვანი ინფორმაცია',
        'non_refundable' => 'ბილეთები არ ექვემდებარება დაბრუნებას',
        'one_ticket' => 'ერთი ბილეთი ათავსებს ერთ პიროვნებას',
        'qr_code' => 'QR კოდი გაიცემა შეძენის შემდეგ',
        'minimum_age' => 'მინიმალური ასაკი: 18+ პირადობით',
        'tickets_available' => 'ბილეთები ხელმისაწვდომი იქნება თქვენს პროფილში',
        'available' => 'ხელმისაწვდომი',
        'date' => 'თარიღი',
        'venue' => 'ადგილი',
        'entry' => 'შესვლა',
        'gates_open' => 'კარები იღება 18:00-ზე',
        'card_number' => 'ბარათის ნომერი',
        'expiration_date' => 'ვადის გასვლის თარიღი',
        'cvv' => 'CVV',
        'name_on_card' => 'სახელი ბარათზე',
        'paypal_email' => 'PAYPAL ელფოსტა',
        'confirm_payment' => 'გადახდის დადასტურება',
        'wallet_address' => 'საფულის მისამართი',
        'additional_tickets' => 'დამატებითი ბილეთების ინფორმაცია',
        'first_name' => 'სახელი',
        'last_name' => 'გვარი',
        'id_number' => 'პირადი ნომერი',
        'ticket' => 'ბილეთი',
        'main_ticket' => 'თქვენი ბილეთი (მთავარი ბილეთი)',
        'secondary_ticket' => 'დამატებითი ბილეთი',
        'demo_notice' => 'ეს არის დემო გადახდის სისტემა. რეალური თანხები არ ჩამოიჭრება.',
        'official' => 'ოფიციალური',
        'complete_your_order' => 'დაასრულეთ თქვენი შეკვეთა',
        'max_tickets_notice' => 'მაქსიმუმ 3 ბილეთი ერთ შეკვეთაზე',
        'payment_info' => 'გადახდის ინფორმაცია',
        'ticket_holder' => 'ბილეთის მფლობელი',
        'all_fields_required' => 'ყველა ველის შევსება აუცილებელია თითოეული ბილეთის მფლობელისთვის',
        'home' => 'მთავარი',
        'artists' => 'არტისტები',
        'schedule' => 'განრიგი',
        'tickets' => 'ბილეთები',
        'profile' => 'პროფილი',
        'official' => 'ოფიციალური',
        'available' => 'ხელმისაწვდომი',
        'date' => 'თარიღი',
        'venue' => 'ადგილი',
        'entry' => 'შესვლა',
        'gates_open' => 'კარები იღება 18:00-ზე',
        'ticket_singular' => 'ბილეთი',
        'ticket_plural' => 'ბილეთები',
        'max_tickets_notice' => 'მაქსიმუმ 3 ბილეთი ერთ შეკვეთაზე',
        'important_info' => 'მნიშვნელოვანი ინფორმაცია',
        'non_refundable' => 'ბილეთები არ ექვემდებარება დაბრუნებას',
        'one_ticket' => 'ერთი ბილეთი ათავსებს ერთ პიროვნებას',
        'qr_code' => 'QR კოდი გაიცემა შეძენის შემდეგ',
        'minimum_age' => 'მინიმალური ასაკი: 18+ პირადობით',
        'tickets_available' => 'ბილეთები ხელმისაწვდომი იქნება თქვენს პროფილში'
    ]
];

$t = $translations[$lang];

// Get ticket information
$database = new Database();
$conn = $database->getConnection();

$query = "SELECT * FROM ticket_types WHERE id = ? AND is_active = 1";
$stmt = $conn->prepare($query);
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    header("Location: index.php");
    exit();
}

if ($_POST) {
    $quantity = (int)($_POST['quantity'] ?? 1);
    $payment_method = $_POST['payment_method'] ?? '';
    
    // Validate quantity
    if ($quantity <= 0 || $quantity > min($max_tickets_per_user, $ticket['available_quantity'])) {
        $error = 'Invalid quantity selected';
    } 
    // Validate payment method
    elseif (empty($payment_method)) {
        $error = 'Please select a payment method';
    }
    // Validate additional ticket information if quantity > 1
    elseif ($quantity > 1) {
        $valid = true;
        
        // Check if all additional ticket holders have their information provided
        for ($i = 1; $i < $quantity; $i++) {
            $first_name = $_POST['ticket_first_name_' . $i] ?? '';
            $last_name = $_POST['ticket_last_name_' . $i] ?? '';
            $id_number = $_POST['ticket_id_number_' . $i] ?? '';
            
            if (empty($first_name) || empty($last_name) || empty($id_number)) {
                $valid = false;
                $error = $t['all_fields_required'];
                break;
            }
        }
        
        if ($valid) {
            // Create order
            $total_amount = $ticket['price'] * $quantity;
            $user_id = $_SESSION['user_id'];
            
            $query = "INSERT INTO orders (user_id, ticket_type_id, quantity, total_amount, payment_method, status) VALUES (?, ?, ?, ?, ?, 'approved')";
            $stmt = $conn->prepare($query);
            
            if ($stmt->execute([$user_id, $ticket_id, $quantity, $total_amount, $payment_method])) {
                $order_id = $conn->lastInsertId();
                
                // Get user information for main ticket holder
                $query = "SELECT first_name, last_name, id_number FROM users WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$user_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Create main ticket holder (purchaser)
                $query = "INSERT INTO ticket_holders (order_id, first_name, last_name, id_number, is_main) 
                          VALUES (?, ?, ?, ?, 1)";
                $stmt = $conn->prepare($query);
                
                // Use session data for the main ticket holder if available
                $firstName = $user['first_name'] ?? $_SESSION['first_name'] ?? explode('-', $currentUser)[0] ?? '';
                $lastName = $user['last_name'] ?? $_SESSION['last_name'] ?? explode('-', $currentUser)[1] ?? '';
                $idNumber = $user['id_number'] ?? $_SESSION['id_number'] ?? '123456789';
                
                $stmt->execute([
                    $order_id,
                    $firstName,
                    $lastName,
                    $idNumber
                ]);
                
                $main_holder_id = $conn->lastInsertId();
                
                // Generate QR code for main ticket holder
                $main_qr_code = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-" . $order_id . "-" . $main_holder_id . "-" . time();
                
                // Update main ticket holder with QR code
                $query = "UPDATE ticket_holders SET qr_code = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$main_qr_code, $main_holder_id]);
                
                // Add additional ticket holders if quantity > 1
                for ($i = 1; $i < $quantity; $i++) {
                    $first_name = $_POST['ticket_first_name_' . $i] ?? '';
                    $last_name = $_POST['ticket_last_name_' . $i] ?? '';
                    $id_number = $_POST['ticket_id_number_' . $i] ?? '';
                    
                    $query = "INSERT INTO ticket_holders (order_id, first_name, last_name, id_number, is_main) 
                              VALUES (?, ?, ?, ?, 0)";
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$order_id, $first_name, $last_name, $id_number]);
                    
                    $holder_id = $conn->lastInsertId();
                    
                    // Generate unique QR code for this ticket holder
                    $qr_code = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-" . $order_id . "-" . $holder_id . "-" . time();
                    
                    // Update ticket holder with QR code
                    $query = "UPDATE ticket_holders SET qr_code = ? WHERE id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$qr_code, $holder_id]);
                }
                
                // Update available quantity
                $query = "UPDATE ticket_types SET available_quantity = available_quantity - ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$quantity, $ticket_id]);
                
                header("Location: profile.php?success=1");
                exit();
            } else {
                $error = 'Order failed. Please try again.';
            }
        }
    } 
    else {
        // Single ticket purchase
        $total_amount = $ticket['price'] * $quantity;
        $user_id = $_SESSION['user_id'];
        
        $query = "INSERT INTO orders (user_id, ticket_type_id, quantity, total_amount, payment_method, status) VALUES (?, ?, ?, ?, ?, 'approved')";
        $stmt = $conn->prepare($query);
        
        if ($stmt->execute([$user_id, $ticket_id, $quantity, $total_amount, $payment_method])) {
            $order_id = $conn->lastInsertId();
            
            // Get user information for main ticket holder
            $query = "SELECT first_name, last_name, id_number FROM users WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Create ticket holder
            $query = "INSERT INTO ticket_holders (order_id, first_name, last_name, id_number, is_main) 
                      VALUES (?, ?, ?, ?, 1)";
            $stmt = $conn->prepare($query);
            
            // Use session data for the main ticket holder if available
            $firstName = $user['first_name'] ?? $_SESSION['first_name'] ?? explode('-', $currentUser)[0] ?? '';
            $lastName = $user['last_name'] ?? $_SESSION['last_name'] ?? explode('-', $currentUser)[1] ?? '';
            $idNumber = $user['id_number'] ?? $_SESSION['id_number'] ?? '123456789';
            
            $stmt->execute([
                $order_id,
                $firstName,
                $lastName,
                $idNumber
            ]);
            
            $holder_id = $conn->lastInsertId();
            
            // Generate QR code for ticket holder
            $qr_code = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=TICKET-" . $order_id . "-" . $holder_id . "-" . time();
            
            // Update ticket holder with QR code
            $query = "UPDATE ticket_holders SET qr_code = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$qr_code, $holder_id]);
            
            // Update available quantity
            $query = "UPDATE ticket_types SET available_quantity = available_quantity - ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$quantity, $ticket_id]);
            
            header("Location: profile.php?success=1");
            exit();
        } else {
            $error = 'Order failed. Please try again.';
        }
    }
}

// Maximum quantity a user can purchase (minimum of available tickets and max_tickets_per_user)
$max_quantity = min($max_tickets_per_user, $ticket['available_quantity']);
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['purchase_tickets']; ?> - PULSE Festival</title>
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
        
        .purchase-container {
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid #333;
            position: relative;
            overflow: hidden;
        }
        
        .ticket-preview {
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid #444;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .input-field {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid #333;
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 10px rgba(59, 255, 68, 0.3);
        }
        
        .payment-label {
            position: relative;
            background: rgba(0, 0, 0, 0.4);
            transition: all 0.3s ease;
            cursor: pointer;
            overflow: hidden;
            z-index: 1;
        }
        
        .payment-input:checked + .payment-label {
            background: rgba(59, 255, 68, 0.15);
            border: 1px solid var(--primary-color);
        }
        
        .purchase-button {
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
        
        .purchase-button:before {
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
        
        .purchase-button:hover:before {
            width: 100%;
        }
        
        .purchase-button:hover {
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
                    
                    <!-- Language Switcher -->
                    <div class="flex items-center space-x-2">
                        <a href="?lang=en&ticket_id=<?php echo $ticket_id; ?>" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> hover:text-festival-yellow transition-colors text-sm festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?lang=ka&ticket_id=<?php echo $ticket_id; ?>" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> hover:text-festival-yellow transition-colors text-sm festival-font font-bold">ქარ</a>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <span class="text-white festival-font hidden lg:inline-block truncate max-w-[120px]">
                        <?php echo htmlspecialchars($currentUser); ?>
                    </span>
                    <a href="profile.php" class="bg-transparent px-4 py-2 rounded-none border border-festival-yellow text-festival-yellow hover:bg-festival-yellow hover:text-black transition-colors festival-font"><?php echo $t['profile']; ?></a>
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
                    
                    <div class="mobile-menu-item py-3 flex items-center space-x-4">
                        <a href="?lang=en&ticket_id=<?php echo $ticket_id; ?>" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?lang=ka&ticket_id=<?php echo $ticket_id; ?>" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">ქარ</a>
                    </div>
                    
                    <div class="pt-4 border-t border-neutral-800 mt-3">
                        <a href="profile.php" class="bg-transparent border border-festival-yellow py-2 px-4 block text-center text-festival-yellow hover:bg-festival-yellow hover:text-black transition-colors festival-font">
                            <?php echo $t['profile']; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Scrolling Banner -->
    <div class="scrolling-banner py-2 mt-16">
        <div class="scrolling-text whitespace-nowrap text-sm font-bold festival-font">
            THE MOST ANTICIPATED MUSIC EVENT • THE MOST ANTICIPATED MUSIC EVENT • THE MOST ANTICIPATED MUSIC EVENT • 
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 sm:py-12" x-data="purchaseForm()">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-center mb-4 text-festival-yellow festival-font"><?php echo $t['purchase_tickets']; ?></h1>
        <p class="text-center text-gray-400 mb-8 sm:mb-12 main-font"><?php echo $t['secure_spot']; ?></p>
        
        <?php if ($error): ?>
            <div class="bg-red-900/70 border border-red-700 text-red-100 px-4 py-3 rounded-none mb-8 max-w-2xl mx-auto flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>
        
        <div class="max-w-4xl mx-auto grid md:grid-cols-2 gap-6">
    <!-- Left side - Ticket information -->
    <div>
        <div class="ticket-preview p-5 sm:p-6 rounded-none mb-4">
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-xl font-bold text-festival-yellow festival-font">
                    <?php echo htmlspecialchars($lang === 'ka' && isset($ticket['name_ka']) ? $ticket['name_ka'] : $ticket['name']); ?>
                </h2>
                <div class="px-2 py-1 border border-festival-yellow text-festival-yellow text-xs font-bold">
                    <?php echo $t['official']; ?>
                </div>
            </div>
            
            <p class="text-gray-300 mb-6">
                <?php echo htmlspecialchars($lang === 'ka' && isset($ticket['description_ka']) ? $ticket['description_ka'] : $ticket['description']); ?>
            </p>
            
            <div class="flex justify-between items-center border-t border-gray-700 pt-4">
                <span class="text-2xl sm:text-3xl font-bold text-white festival-font">$<?php echo number_format($ticket['price'], 2); ?></span>
                <span class="text-gray-400 text-sm"><?php echo $t['available']; ?>: <?php echo $ticket['available_quantity']; ?></span>
            </div>
            
            <div class="mt-6 border-t border-gray-700 pt-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-400"><?php echo $t['date']; ?>:</span>
                    <span class="text-white"><?php echo $lang === 'ka' ? '28 ივლისი, 2025' : 'July 28, 2025'; ?></span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-400"><?php echo $t['venue']; ?>:</span>
                    <span class="text-white"><?php echo $lang === 'ka' ? 'PULSE არენა, თბილისი' : 'PULSE Arena, Tbilisi'; ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400"><?php echo $t['entry']; ?>:</span>
                    <span class="text-white"><?php echo $t['gates_open']; ?></span>
                </div>
            </div>
            
            <div class="mt-8 mb-4 text-center">
                <div class="text-xl sm:text-2xl md:text-3xl font-bold festival-font text-festival-yellow" x-text="quantity > 1 ? quantity + ' × <?php echo $t['ticket_plural']; ?>' : quantity + ' × <?php echo $t['ticket_singular']; ?>'">1 × <?php echo $t['ticket_singular']; ?></div>
                <div class="text-sm text-gray-400 mt-1"><?php echo $t['max_tickets_notice']; ?></div>
            </div>
        </div>
        
        <div class="bg-gray-900/50 border border-gray-800 rounded-none p-4 text-sm">
            <h3 class="font-bold mb-2 festival-font"><?php echo $t['important_info']; ?></h3>
            <ul class="list-disc list-inside space-y-1 text-gray-400">
                <li><?php echo $t['non_refundable']; ?></li>
                <li><?php echo $t['one_ticket']; ?></li>
                <li><?php echo $t['qr_code']; ?></li>
                <li><?php echo $t['minimum_age']; ?></li>
                <li><?php echo $t['tickets_available']; ?></li>
            </ul>
        </div>
    </div>
            
            <!-- Right side - Purchase form -->
            <div class="purchase-container p-5 sm:p-6 rounded-none">
                <h3 class="text-lg sm:text-xl font-bold mb-5 sm:mb-6 festival-font"><?php echo $t['complete_your_order']; ?></h3>
                
                <form method="POST" class="space-y-5 sm:space-y-6" id="purchase-form" @submit.prevent="validateForm">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-1 sm:mb-2 festival-font"><?php echo $t['select_quantity']; ?></label>
                        <select name="quantity" x-model="quantity" @change="updateTotal()" 
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 input-field rounded-none focus:outline-none focus:border-festival-yellow text-sm">
                            <?php for ($i = 1; $i <= $max_quantity; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> <?php echo $i > 1 ? $t['ticket_plural'] : $t['ticket_singular']; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <!-- Additional Ticket Information (compact version) -->
                    <div x-show="quantity > 1" x-transition class="bg-black/30 p-4 border border-gray-800 rounded-none">
                        <h4 class="font-bold mb-3 text-xs sm:text-sm text-festival-yellow festival-font"><?php echo $t['ticket_holder']; ?> #1: <?php echo htmlspecialchars($currentUser); ?> (<?php echo $t['main_ticket']; ?>)</h4>
                        
                        <template x-for="(holder, index) in Array.from({length: quantity - 1}, (_, i) => i + 1)" :key="index">
                            <div class="space-y-4 mb-3 pb-3 border-b border-gray-800">
                                <h5 class="text-xs sm:text-sm font-medium festival-font"><?php echo $t['ticket_holder']; ?> #<span x-text="index + 2"></span></h5>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="text" :name="'ticket_first_name_' + index" placeholder="<?php echo $t['first_name']; ?>" 
                                           class="input-field text-xs sm:text-sm rounded-none px-3 py-2">
                                    <input type="text" :name="'ticket_last_name_' + index" placeholder="<?php echo $t['last_name']; ?>" 
                                           class="input-field text-xs sm:text-sm rounded-none px-3 py-2">
                                </div>
                                <input type="text" :name="'ticket_id_number_' + index" placeholder="<?php echo $t['id_number']; ?>" 
                                       class="input-field text-xs sm:text-sm rounded-none px-3 py-2 w-full">
                            </div>
                        </template>
                    </div>
                    
                    <!-- Payment Method Selection -->
                    <div>
                        <label class="block text-xs sm:text-sm font-medium mb-2 festival-font"><?php echo $t['payment_method']; ?></label>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <input type="radio" name="payment_method" value="credit_card" id="credit_card" class="payment-input hidden" x-model="paymentMethod">
                                <label for="credit_card" class="payment-label h-full flex flex-col items-center justify-center px-4 py-3 rounded-none border border-gray-700">
                                    <i class="fas fa-credit-card text-lg sm:text-xl mb-1"></i>
                                    <span class="text-xs sm:text-sm"><?php echo $t['credit_card']; ?></span>
                                </label>
                            </div>
                            <div>
                                <input type="radio" name="payment_method" value="paypal" id="paypal" class="payment-input hidden" x-model="paymentMethod">
                                <label for="paypal" class="payment-label h-full flex flex-col items-center justify-center px-4 py-3 rounded-none border border-gray-700">
                                    <i class="fab fa-paypal text-lg sm:text-xl mb-1"></i>
                                    <span class="text-xs sm:text-sm"><?php echo $t['paypal']; ?></span>
                                </label>
                            </div>
                            <div>
                                <input type="radio" name="payment_method" value="apple_pay" id="apple_pay" class="payment-input hidden" x-model="paymentMethod">
                                <label for="apple_pay" class="payment-label h-full flex flex-col items-center justify-center px-4 py-3 rounded-none border border-gray-700">
                                    <i class="fab fa-apple-pay text-lg sm:text-xl mb-1"></i>
                                    <span class="text-xs sm:text-sm"><?php echo $t['apple_pay']; ?></span>
                                </label>
                            </div>
                            <div>
                                <input type="radio" name="payment_method" value="crypto" id="crypto" class="payment-input hidden" x-model="paymentMethod">
                                <label for="crypto" class="payment-label h-full flex flex-col items-center justify-center px-4 py-3 rounded-none border border-gray-700">
                                    <i class="fab fa-bitcoin text-lg sm:text-xl mb-1"></i>
                                    <span class="text-xs sm:text-sm"><?php echo $t['crypto']; ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Compact Payment Forms -->
                    <div x-show="paymentMethod !== ''" class="bg-black/30 p-4 border border-gray-800 rounded-none">
                        <h4 class="text-xs sm:text-sm font-bold mb-3 text-festival-yellow festival-font"><?php echo $t['payment_info']; ?></h4>
                        
                        <!-- Credit Card Payment Form -->
                        <div x-show="paymentMethod === 'credit_card'" class="space-y-3">
                            <input type="text" placeholder="<?php echo $t['card_number']; ?>" class="w-full px-3 py-2 input-field rounded-none text-xs sm:text-sm">
                            <div class="grid grid-cols-2 gap-3">
                                <input type="text" placeholder="<?php echo $t['expiration_date']; ?>" class="px-3 py-2 input-field rounded-none text-xs sm:text-sm">
                                <input type="text" placeholder="<?php echo $t['cvv']; ?>" class="px-3 py-2 input-field rounded-none text-xs sm:text-sm">
                            </div>
                            <input type="text" placeholder="<?php echo $t['name_on_card']; ?>" class="w-full px-3 py-2 input-field rounded-none text-xs sm:text-sm">
                        </div>
                        
                        <!-- PayPal Form -->
                        <div x-show="paymentMethod === 'paypal'" class="space-y-3">
                            <input type="email" placeholder="<?php echo $t['paypal_email']; ?>" class="w-full px-3 py-2 input-field rounded-none text-xs sm:text-sm">
                        </div>
                        
                        <!-- Apple Pay -->
                        <div x-show="paymentMethod === 'apple_pay'" class="text-center py-2">
                            <i class="fab fa-apple-pay text-2xl sm:text-3xl"></i>
                        </div>
                        
                        <!-- Crypto -->
                        <div x-show="paymentMethod === 'crypto'" class="space-y-3">
                            <select class="w-full px-3 py-2 input-field rounded-none text-xs sm:text-sm">
                                <option value="btc">Bitcoin</option>
                                <option value="eth">Ethereum</option>
                                <option value="usdt">USDT</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Price Summary -->
                    <div class="border-t border-gray-700 pt-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-400"><?php echo $t['subtotal']; ?>:</span>
                            <span x-text="'₾' + subtotal.toFixed(2)" class="text-white">$<?php echo number_format($ticket['price'], 2); ?></span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-400"><?php echo $t['processing_fee']; ?>:</span>
                            <span x-text="'₾' + processingFee.toFixed(2)" class="text-white">$5.00</span>
                        </div>
                        <div class="flex justify-between items-center text-lg sm:text-xl font-bold mt-4 pt-4 border-t border-gray-700">
                            <span><?php echo $t['total']; ?>:</span>
                            <span x-text="'₾' + total.toFixed(2)" class="text-festival-yellow">$<?php echo number_format($ticket['price'] + 5, 2); ?></span>
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="w-full purchase-button py-2.5 sm:py-3 px-4 rounded-none festival-font text-base sm:text-lg uppercase mt-4">
                        <?php echo $t['complete_purchase']; ?>
                    </button>
                    
                    <div class="text-center text-gray-400 flex items-center justify-center text-xs sm:text-sm">
                        <i class="fas fa-lock mr-2"></i>
                        <span><?php echo $t['secure_payment']; ?></span>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Additional info section -->
        <div class="max-w-4xl mx-auto mt-8 text-center">
            <p class="text-gray-500 text-sm">
                <?php echo $t['demo_notice']; ?>
            </p>
            <div class="flex justify-center mt-4 space-x-6">
                <i class="fab fa-cc-visa text-xl sm:text-2xl text-gray-500"></i>
                <i class="fab fa-cc-mastercard text-xl sm:text-2xl text-gray-500"></i>
                <i class="fab fa-cc-amex text-xl sm:text-2xl text-gray-500"></i>
                <i class="fab fa-cc-paypal text-xl sm:text-2xl text-gray-500"></i>
                <i class="fab fa-bitcoin text-xl sm:text-2xl text-gray-500"></i>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-neutral-900 py-8 sm:py-12 border-t border-neutral-800 mt-8 sm:mt-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold mb-3 sm:mb-4 text-festival-yellow festival-font">PULSE FESTIVAL</h2>
            <p class="text-gray-400 mb-5 sm:mb-6 text-sm sm:text-base">THE MOST ANTICIPATED MUSIC EVENT</p>
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

    <script>
        function purchaseForm() {
            return {
                quantity: 1,
                ticketPrice: <?php echo $ticket['price']; ?>,
                processingFee: 5.00,
                subtotal: <?php echo $ticket['price']; ?>,
                total: <?php echo $ticket['price'] + 5; ?>,
                paymentMethod: '',
                
                updateTotal() {
                    this.subtotal = this.quantity * this.ticketPrice;
                    this.total = this.subtotal + this.processingFee;
                },
                
                validateForm(e) {
                    // Check if payment method is selected
                    if (!this.paymentMethod) {
                        alert("Please select a payment method");
                        e.preventDefault();
                        return false;
                    }
                    
                    // Validate additional ticket holders if quantity > 1
                    if (this.quantity > 1) {
                        let valid = true;
                        
                        // Check each holder's information
                        for (let i = 1; i < this.quantity; i++) {
                            const firstName = document.querySelector(`[name="ticket_first_name_${i-1}"]`).value;
                            const lastName = document.querySelector(`[name="ticket_last_name_${i-1}"]`).value;
                            const idNumber = document.querySelector(`[name="ticket_id_number_${i-1}"]`).value;
                            
                            if (!firstName || !lastName || !idNumber) {
                                valid = false;
                                alert("Please fill in all ticket holder information");
                                break;
                            }
                        }
                        
                        if (!valid) {
                            e.preventDefault();
                            return false;
                        }
                    }
                    
                    // Submit the form if everything is valid
                    document.getElementById('purchase-form').submit();
                }
            }
        }
    </script>
</body>
</html>