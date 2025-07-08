<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

$auth = new Auth();
$auth->requireAdmin();  // Make sure only admins can access this page

$error = '';
$success = '';

// Current date time updated
$currentDateTime = '2025-07-08 08:37:01';
$currentUser = 'Guram-jajanidze';

// Language support
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$_SESSION['lang'] = $lang;

// Language translations
$translations = [
    'en' => [
        'merchandise_management' => 'MERCHANDISE MANAGEMENT',
        'add_new' => 'Add New Merchandise',
        'edit' => 'Edit Merchandise',
        'all_merch' => 'All Merchandise',
        'name' => 'Name',
        'name_en' => 'Name (English)',
        'name_ka' => 'Name (Georgian)',
        'desc' => 'Description',
        'desc_en' => 'Description (English)',
        'desc_ka' => 'Description (Georgian)',
        'price' => 'Price',
        'quantity' => 'Available Quantity',
        'category' => 'Category',
        'size_options' => 'Size Options',
        'color_options' => 'Color Options',
        'image' => 'Image',
        'current_image' => 'Current image',
        'upload_new' => 'Upload new image to replace',
        'active' => 'Active',
        'add' => 'Add Merchandise',
        'update' => 'Update Merchandise',
        'cancel' => 'Cancel',
        'stock' => 'Stock',
        'status' => 'Status',
        'actions' => 'Actions',
        'delete_confirm' => 'Are you sure you want to delete this item?',
        'empty_result' => 'No merchandise found',
        'select_category' => '-- Select Category --',
        'clothing' => 'Clothing',
        'accessories' => 'Accessories',
        'collectibles' => 'Collectibles',
        'souvenirs' => 'Souvenirs',
        'admin_panel' => 'ADMIN PANEL',
        'dashboard' => 'Dashboard',
        'users' => 'Users',
        'tickets' => 'Tickets',
        'merchandise' => 'Merchandise',
        'orders' => 'Orders',
        'settings' => 'Settings',
        'logout' => 'Logout',
        'home' => 'HOME',
        'artists' => 'ARTISTS',
        'schedule' => 'SCHEDULE',
        'profile' => 'PROFILE',
        'most_anticipated' => 'THE MOST UNFORGETTABLE NIGHT OF MUSIC IS COMING — DONT MISS THE VIBE EVERYONES TALKING ABOUT!'
    ],
    'ka' => [
        'merchandise_management' => 'მერჩის მართვა',
        'add_new' => 'მერჩის დამატება',
        'edit' => 'მერჩის რედაქტირება',
        'all_merch' => 'ყველა მერჩი',
        'name' => 'დასახელება',
        'name_en' => 'დასახელება (ინგლისურად)',
        'name_ka' => 'დასახელება (ქართულად)',
        'desc' => 'აღწერა',
        'desc_en' => 'აღწერა (ინგლისურად)',
        'desc_ka' => 'აღწერა (ქართულად)',
        'price' => 'ფასი',
        'quantity' => 'ხელმისაწვდომი რაოდენობა',
        'category' => 'კატეგორია',
        'size_options' => 'ზომის ვარიანტები',
        'color_options' => 'ფერის ვარიანტები',
        'image' => 'სურათი',
        'current_image' => 'მიმდინარე სურათი',
        'upload_new' => 'ატვირთეთ ახალი სურათი შესაცვლელად',
        'active' => 'აქტიური',
        'add' => 'მერჩის დამატება',
        'update' => 'მერჩის განახლება',
        'cancel' => 'გაუქმება',
        'stock' => 'მარაგი',
        'status' => 'სტატუსი',
        'actions' => 'მოქმედებები',
        'delete_confirm' => 'დარწმუნებული ხართ, რომ გსურთ ამ ნივთის წაშლა?',
        'empty_result' => 'მერჩი ვერ მოიძებნა',
        'select_category' => '-- აირჩიეთ კატეგორია --',
        'clothing' => 'ტანსაცმელი',
        'accessories' => 'აქსესუარები',
        'collectibles' => 'კოლექციები',
        'souvenirs' => 'სუვენირები',
        'admin_panel' => 'ადმინ პანელი',
        'dashboard' => 'მთავარი',
        'users' => 'მომხმარებლები',
        'tickets' => 'ბილეთები',
        'merchandise' => 'მერჩი',
        'orders' => 'შეკვეთები',
        'settings' => 'პარამეტრები',
        'logout' => 'გასვლა',
        'home' => 'მთავარი',
        'artists' => 'არტისტები',
        'schedule' => 'განრიგი',
        'profile' => 'პროფილი',
        'most_anticipated' => 'მუსიკის ყველაზე დაუვიწყარი ღამე ახლოვდება — არ გამოტოვო ატმოსფერო, რომელზეც ყველა ლაპარაკობს!',
    ]
];

$t = $translations[$lang];

// Database connection
$database = new Database();
$conn = $database->getConnection();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new merchandise
    if (isset($_POST['add_merch'])) {
        $name = $_POST['name'] ?? '';
        $name_ka = $_POST['name_ka'] ?? '';
        $description = $_POST['description'] ?? '';
        $description_ka = $_POST['description_ka'] ?? '';
        $price = floatval($_POST['price'] ?? 0);
        $available_quantity = intval($_POST['available_quantity'] ?? 0);
        $category = $_POST['category'] ?? '';
        $size_options = isset($_POST['size_options']) ? json_encode(explode(',', $_POST['size_options'])) : '[]';
        $color_options = isset($_POST['color_options']) ? json_encode(explode(',', $_POST['color_options'])) : '[]';
        
        // Handle image upload
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload_dir = '../uploads/merch/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_name = time() . '_' . $_FILES['image']['name'];
            $upload_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image = $upload_path;
            } else {
                $error = 'Failed to upload image';
            }
        }
        
        if (empty($error)) {
            $query = "INSERT INTO merch_items (name, name_ka, description, description_ka, price, image, available_quantity, category, size_options, color_options, is_active) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
            $stmt = $conn->prepare($query);
            
            if ($stmt->execute([$name, $name_ka, $description, $description_ka, $price, $image, $available_quantity, $category, $size_options, $color_options])) {
                $success = 'Merchandise added successfully';
            } else {
                $error = 'Failed to add merchandise';
            }
        }
    }
    
    // Update merchandise
    if (isset($_POST['update_merch'])) {
        $id = intval($_POST['id'] ?? 0);
        $name = $_POST['name'] ?? '';
        $name_ka = $_POST['name_ka'] ?? '';
        $description = $_POST['description'] ?? '';
        $description_ka = $_POST['description_ka'] ?? '';
        $price = floatval($_POST['price'] ?? 0);
        $available_quantity = intval($_POST['available_quantity'] ?? 0);
        $category = $_POST['category'] ?? '';
        $size_options = isset($_POST['size_options']) ? json_encode(explode(',', $_POST['size_options'])) : '[]';
        $color_options = isset($_POST['color_options']) ? json_encode(explode(',', $_POST['color_options'])) : '[]';
        $is_active = isset($_POST['is_active']) && $_POST['is_active'] == '1' ? 1 : 0;
        
        // Check if new image is uploaded
        $image_sql = '';
        $params = [$name, $name_ka, $description, $description_ka, $price, $available_quantity, $category, $size_options, $color_options, $is_active];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $upload_dir = '../uploads/merch/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_name = time() . '_' . $_FILES['image']['name'];
            $upload_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_sql = ', image = ?';
                $params[] = $upload_path;
            } else {
                $error = 'Failed to upload image';
            }
        }
        
        if (empty($error)) {
            $params[] = $id;  // Add ID as the last parameter
            
            $query = "UPDATE merch_items SET name = ?, name_ka = ?, description = ?, description_ka = ?, 
                      price = ?, available_quantity = ?, category = ?, size_options = ?, color_options = ?, 
                      is_active = ? $image_sql WHERE id = ?";
            $stmt = $conn->prepare($query);
            
            if ($stmt->execute($params)) {
                $success = 'Merchandise updated successfully';
            } else {
                $error = 'Failed to update merchandise';
            }
        }
    }
    
    // Delete merchandise
    if (isset($_POST['delete_merch'])) {
        $id = intval($_POST['id'] ?? 0);
        
        $query = "DELETE FROM merch_items WHERE id = ?";
        $stmt = $conn->prepare($query);
        
        if ($stmt->execute([$id])) {
            $success = 'Merchandise deleted successfully';
        } else {
            $error = 'Failed to delete merchandise';
        }
    }
}

// Get all merchandise for listing
$query = "SELECT * FROM merch_items ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$all_merch = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get merchandise by ID for editing
$edit_merch = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $query = "SELECT * FROM merch_items WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id]);
    $edit_merch = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['merchandise_management']; ?> - PULSE Festival</title>
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
        
        .input-field {
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid #333;
            transition: all 0.3s ease;
            color: white;
        }
        
        .input-field:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 10px rgba(59, 255, 68, 0.3);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: black;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            letter-spacing: 0.1em;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            letter-spacing: 0.1em;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background-color: var(--primary-color);
            color: black;
        }
        
        .btn-danger {
            background-color: #FF4444;
            color: white;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            letter-spacing: 0.1em;
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        @media (max-width: 640px) {
            .logo-image {
                height: 32px;
            }
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
                        <a href="?<?php echo isset($_GET['edit']) ? 'edit='.$_GET['edit'].'&' : ''; ?>lang=en" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> hover:text-festival-yellow transition-colors text-sm festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?<?php echo isset($_GET['edit']) ? 'edit='.$_GET['edit'].'&' : ''; ?>lang=ka" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> hover:text-festival-yellow transition-colors text-sm festival-font font-bold">ქარ</a>
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
                        <a href="?<?php echo isset($_GET['edit']) ? 'edit='.$_GET['edit'].'&' : ''; ?>lang=en" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?<?php echo isset($_GET['edit']) ? 'edit='.$_GET['edit'].'&' : ''; ?>lang=ka" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">ქარ</a>
                    </div>
                    
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

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 admin-sidebar absolute pt-20 hidden md:block">
            <nav class="mt-4 px-4">
                <a href="dashboard.php" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:text-white">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span class="ml-3 festival-font"><?php echo $t['dashboard']; ?></span>
                </a>
                <a href="admin_merch.php" class="sidebar-link active flex items-center px-4 py-3 text-white">
                    <i class="fas fa-tshirt w-6"></i>
                    <span class="ml-3 festival-font"><?php echo $t['merchandise']; ?></span>
                </a>
                <a href="admin_orders.php" class="sidebar-link flex items-center px-4 py-3 text-gray-300 hover:text-white">
                    <i class="fas fa-shopping-cart w-6"></i>
                    <span class="ml-3 festival-font"><?php echo $t['orders']; ?></span>
                </a>
            </nav>
        </aside>

        <!-- Main content -->
        <main class="w-full md:ml-64 p-6 pt-24">
            <div class="container mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl md:text-3xl font-bold festival-font text-festival-yellow"><?php echo $t['merchandise_management']; ?></h1>
                    <div class="text-sm text-gray-400">
                        <?php echo $currentDateTime; ?>
                    </div>
                </div>
                
                <?php if ($error): ?>
                    <div class="bg-red-900/70 border border-red-700 text-red-100 px-4 py-3 rounded-none mb-6">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="bg-green-900/70 border border-green-700 text-green-100 px-4 py-3 rounded-none mb-6">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span><?php echo htmlspecialchars($success); ?></span>
                    </div>
                <?php endif; ?>
                
                <div class="grid md:grid-cols-3 gap-6">
                    <!-- Merchandise Form -->
                    <div class="md:col-span-1">
                        <div class="admin-container rounded-none p-5">
                            <h2 class="text-lg font-bold mb-4 festival-font"><?php echo $edit_merch ? $t['edit'] : $t['add_new']; ?></h2>
                            
                            <form action="admin_merch.php<?php echo $lang !== 'en' ? '?lang='.$lang : ''; ?>" method="POST" enctype="multipart/form-data">
                                <?php if ($edit_merch): ?>
                                    <input type="hidden" name="id" value="<?php echo $edit_merch['id']; ?>">
                                <?php endif; ?>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1 festival-font"><?php echo $t['name_en']; ?></label>
                                    <input type="text" name="name" value="<?php echo $edit_merch ? htmlspecialchars($edit_merch['name']) : ''; ?>" required class="w-full px-3 py-2 input-field rounded-none">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1 festival-font"><?php echo $t['name_ka']; ?></label>
                                    <input type="text" name="name_ka" value="<?php echo $edit_merch ? htmlspecialchars($edit_merch['name_ka']) : ''; ?>" class="w-full px-3 py-2 input-field rounded-none">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1 festival-font"><?php echo $t['desc_en']; ?></label>
                                    <textarea name="description" rows="3" class="w-full px-3 py-2 input-field rounded-none"><?php echo $edit_merch ? htmlspecialchars($edit_merch['description']) : ''; ?></textarea>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1 festival-font"><?php echo $t['desc_ka']; ?></label>
                                    <textarea name="description_ka" rows="3" class="w-full px-3 py-2 input-field rounded-none"><?php echo $edit_merch ? htmlspecialchars($edit_merch['description_ka']) : ''; ?></textarea>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1 festival-font"><?php echo $t['price']; ?> ($)</label>
                                    <input type="number" name="price" step="0.01" value="<?php echo $edit_merch ? $edit_merch['price'] : ''; ?>" required class="w-full px-3 py-2 input-field rounded-none">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1 festival-font"><?php echo $t['quantity']; ?></label>
                                    <input type="number" name="available_quantity" value="<?php echo $edit_merch ? $edit_merch['available_quantity'] : ''; ?>" required class="w-full px-3 py-2 input-field rounded-none">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1 festival-font"><?php echo $t['category']; ?></label>
                                    <select name="category" required class="w-full px-3 py-2 input-field rounded-none">
                                        <option value=""><?php echo $t['select_category']; ?></option>
                                        <option value="clothing" <?php echo $edit_merch && $edit_merch['category'] === 'clothing' ? 'selected' : ''; ?>><?php echo $t['clothing']; ?></option>
                                        <option value="accessories" <?php echo $edit_merch && $edit_merch['category'] === 'accessories' ? 'selected' : ''; ?>><?php echo $t['accessories']; ?></option>
                                        <option value="collectibles" <?php echo $edit_merch && $edit_merch['category'] === 'collectibles' ? 'selected' : ''; ?>><?php echo $t['collectibles']; ?></option>
                                        <option value="souvenirs" <?php echo $edit_merch && $edit_merch['category'] === 'souvenirs' ? 'selected' : ''; ?>><?php echo $t['souvenirs']; ?></option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1 festival-font"><?php echo $t['size_options']; ?> (comma separated)</label>
                                    <input type="text" name="size_options" value="<?php echo $edit_merch ? htmlspecialchars(implode(',', json_decode($edit_merch['size_options'] ?? '[]', true) ?: [])) : ''; ?>" placeholder="S,M,L,XL" class="w-full px-3 py-2 input-field rounded-none">
                                    <p class="text-xs text-gray-500 mt-1">Leave empty if not applicable</p>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1 festival-font"><?php echo $t['color_options']; ?> (comma separated)</label>
                                    <input type="text" name="color_options" value="<?php echo $edit_merch ? htmlspecialchars(implode(',', json_decode($edit_merch['color_options'] ?? '[]', true) ?: [])) : ''; ?>" placeholder="#000000,#ffffff,#ff0000" class="w-full px-3 py-2 input-field rounded-none">
                                    <p class="text-xs text-gray-500 mt-1">Use hex color codes, leave empty if not applicable</p>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1 festival-font"><?php echo $t['image']; ?></label>
                                    <input type="file" name="image" <?php echo $edit_merch ? '' : 'required'; ?> class="w-full px-3 py-2 bg-transparent">
                                    <?php if ($edit_merch && $edit_merch['image']): ?>
                                        <div class="mt-2">
                                            <p class="text-xs text-gray-500 mb-1"><?php echo $t['current_image']; ?>:</p>
                                            <img src="<?php echo htmlspecialchars($edit_merch['image']); ?>" alt="Current image" class="h-20 w-auto">
                                            <p class="text-xs text-gray-500 mt-1"><?php echo $t['upload_new']; ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($edit_merch): ?>
                                    <div class="mb-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_active" value="1" <?php echo $edit_merch['is_active'] ? 'checked' : ''; ?> class="mr-2">
                                            <span class="text-sm"><?php echo $t['active']; ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex justify-between mt-6">
                                    <?php if ($edit_merch): ?>
                                        <button type="submit" name="update_merch" class="px-4 py-2 btn-primary rounded-none">
                                            <?php echo $t['update']; ?>
                                        </button>
                                        <a href="admin_merch.php<?php echo $lang !== 'en' ? '?lang='.$lang : ''; ?>" class="px-4 py-2 btn-secondary rounded-none">
                                            <?php echo $t['cancel']; ?>
                                        </a>
                                    <?php else: ?>
                                        <button type="submit" name="add_merch" class="px-4 py-2 btn-primary rounded-none">
                                            <?php echo $t['add']; ?>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Merchandise List -->
                    <div class="md:col-span-2">
                        <div class="admin-container rounded-none">
                            <h2 class="text-lg font-bold p-4 border-b border-gray-800 festival-font"><?php echo $t['all_merch']; ?></h2>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="bg-gray-900">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"><?php echo $t['image']; ?></th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"><?php echo $t['name']; ?></th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"><?php echo $t['price']; ?></th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"><?php echo $t['stock']; ?></th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider hidden sm:table-cell"><?php echo $t['category']; ?></th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider hidden sm:table-cell"><?php echo $t['status']; ?></th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider"><?php echo $t['actions']; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-800">
                                        <?php if (empty($all_merch)): ?>
                                            <tr>
                                                <td colspan="7" class="px-4 py-3 text-center text-gray-400"><?php echo $t['empty_result']; ?></td>
                                            </tr>
                                        <?php endif; ?>
                                        
                                        <?php foreach ($all_merch as $item): ?>
                                            <tr class="hover:bg-gray-900/50 transition-colors">
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <?php if ($item['image']): ?>
                                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Product image" class="h-12 w-12 object-cover bg-gray-800">
                                                    <?php else: ?>
                                                        <div class="h-12 w-12 bg-gray-800 flex items-center justify-center">
                                                            <i class="fas fa-image text-gray-600"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="text-sm font-medium"><?php echo htmlspecialchars($item['name']); ?></div>
                                                    <?php if (!empty($item['name_ka'])): ?>
                                                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars($item['name_ka']); ?></div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">$<?php echo number_format($item['price'], 2); ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <?php if ($item['available_quantity'] <= 0): ?>
                                                        <span class="text-red-500"><?php echo $item['available_quantity']; ?></span>
                                                    <?php elseif ($item['available_quantity'] <= 5): ?>
                                                        <span class="text-yellow-500"><?php echo $item['available_quantity']; ?></span>
                                                    <?php else: ?>
                                                        <span class="text-green-500"><?php echo $item['available_quantity']; ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap hidden sm:table-cell">
                                                    <span class="inline-block px-2 py-1 text-xs bg-gray-800 rounded-none">
                                                        <?php echo ucfirst($item['category']); ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap hidden sm:table-cell">
                                                    <?php if ($item['is_active']): ?>
                                                        <span class="inline-block px-2 py-1 text-xs bg-green-900/40 border border-green-600 rounded-none text-green-300">Active</span>
                                                    <?php else: ?>
                                                        <span class="inline-block px-2 py-1 text-xs bg-gray-900/40 border border-gray-600 rounded-none text-gray-400">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="flex space-x-2">
                                                        <a href="?edit=<?php echo $item['id']; ?><?php echo $lang !== 'en' ? '&lang='.$lang : ''; ?>" class="text-blue-400 hover:text-blue-300 action-button">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="admin_merch.php<?php echo $lang !== 'en' ? '?lang='.$lang : ''; ?>" method="POST" onsubmit="return confirm('<?php echo $t['delete_confirm']; ?>');" class="inline">
                                                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                            <button type="submit" name="delete_merch" class="text-red-400 hover:text-red-300 action-button">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Footer -->
    <footer class="bg-neutral-900 py-6 border-t border-neutral-800 mt-8">
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