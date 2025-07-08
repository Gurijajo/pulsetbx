<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$auth = new Auth();
$auth->requireLogin();

$error = '';
$success = '';

// Enable error reporting for development
if (defined('DEV_MODE') && DEV_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Current date time updated
$currentDateTime = '2025-07-08 06:55:12';

$currentUser = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

// Language support
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$_SESSION['lang'] = $lang;

// Set maximum items per order
$max_items_per_order = 5;

// Database connection with error handling
try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if (!$conn) {
        throw new Exception("Database connection failed");
    }
    
    // Test connection
    $conn->query("SELECT 1");
    
} catch (Exception $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    $error = $lang === 'ka' ? 'მონაცემთა ბაზასთან კავშირის შეცდომა' : 'Database connection error';
    $conn = null;
}

// Language translations (keeping your existing translations)
$translations = [
    'en' => [
        'merch_shop' => 'MERCH SHOP',
        'official_festival' => 'Official PULSE Festival Merchandise',
        'shop_now' => 'SHOP NOW',
        'all_categories' => 'All Categories',
        'clothing' => 'Clothing',
        'accessories' => 'Accessories',
        'collectibles' => 'Collectibles',
        'souvenirs' => 'Souvenirs',
        'filter_by' => 'FILTER BY',
        'sort_by' => 'SORT BY',
        'price_low_high' => 'Price: Low to High',
        'price_high_low' => 'Price: High to Low',
        'newest' => 'Newest First',
        'bestsellers' => 'Bestsellers',
        'add_to_cart' => 'ADD TO CART',
        'add_to_wishlist' => 'ADD TO WISHLIST',
        'select_size' => 'SELECT SIZE',
        'select_color' => 'SELECT COLOR',
        'quantity' => 'QUANTITY',
        'in_stock' => 'In Stock',
        'out_of_stock' => 'Out of Stock',
        'last_items' => 'Last items!',
        'cart' => 'CART',
        'your_cart' => 'Your Cart',
        'subtotal' => 'Subtotal',
        'shipping' => 'Shipping',
        'total' => 'TOTAL',
        'checkout' => 'CHECKOUT',
        'continue_shopping' => 'CONTINUE SHOPPING',
        'empty_cart' => 'Your cart is empty',
        'start_shopping' => 'START SHOPPING',
        'shipping_information' => 'SHIPPING INFORMATION',
        'full_name' => 'FULL NAME',
        'street_address' => 'STREET ADDRESS',
        'city' => 'CITY',
        'postal_code' => 'POSTAL CODE',
        'country' => 'COUNTRY',
        'phone' => 'PHONE',
        'payment_method' => 'PAYMENT METHOD',
        'credit_card' => 'Credit Card',
        'paypal' => 'PayPal',
        'apple_pay' => 'Apple Pay',
        'crypto' => 'Crypto',
        'card_number' => 'CARD NUMBER',
        'expiration_date' => 'EXPIRATION DATE',
        'cvv' => 'CVV',
        'name_on_card' => 'NAME ON CARD',
        'paypal_email' => 'PAYPAL EMAIL',
        'confirm_payment' => 'CONFIRM PAYMENT',
        'wallet_address' => 'WALLET ADDRESS',
        'complete_order' => 'COMPLETE ORDER',
        'secure_payment' => 'Secure payment processing',
        'important_info' => 'IMPORTANT INFORMATION',
        'shipping_time' => 'Shipping takes 1-2 business days',
        'international_shipping' => 'International shipping not available',
        'size_guide' => 'See size guide for reference',
        'quality_guarantee' => 'Quality guarantee on all products',
        'order_tracking' => 'Order tracking will be provided',
        'home' => 'HOME',
        'artists' => 'ARTISTS',
        'schedule' => 'SCHEDULE',
        'tickets' => 'TICKETS',
        'merchandise' => 'MERCH',
        'profile' => 'PROFILE',
        'official' => 'OFFICIAL',
        'demo_notice' => 'This is a demo payment system. No real charges will be made.',
        'payment_info' => 'PAYMENT INFORMATION',
        'remove' => 'Remove',
        'size' => 'Size',
        'color' => 'Color',
        'products' => 'Products',
        'order_details' => 'ORDER DETAILS',
        'order_successful' => 'Order Successful!',
        'thank_you' => 'Thank you for your purchase',
        'view_order' => 'VIEW ORDER',
        'view_all_orders' => 'VIEW ALL ORDERS',
        'most_anticipated' => 'THE MOST UNFORGETTABLE NIGHT OF MUSIC IS COMING — DONT MISS THE VIBE EVERYONES TALKING ABOUT!',
    ],
    'ka' => [
        'merch_shop' => 'მერჩი',
        'official_festival' => 'PULSE ფესტივალის ოფიციალური მერჩი',
        'shop_now' => 'შოპინგი',
        'all_categories' => 'ყველა კატეგორია',
        'clothing' => 'ტანსაცმელი',
        'accessories' => 'აქსესუარები',
        'collectibles' => 'კოლექციები',
        'souvenirs' => 'სუვენირები',
        'filter_by' => 'ფილტრი',
        'sort_by' => 'დალაგება',
        'price_low_high' => 'ფასი: დაბალი-მაღალი',
        'price_high_low' => 'ფასი: მაღალი-დაბალი',
        'newest' => 'უახლესი',
        'bestsellers' => 'ბესტსელერები',
        'add_to_cart' => 'კალათაში დამატება',
        'add_to_wishlist' => 'სურვილების სიაში დამატება',
        'select_size' => 'ზომის არჩევა',
        'select_color' => 'ფერის არჩევა',
        'quantity' => 'რაოდენობა',
        'in_stock' => 'მარაგშია',
        'out_of_stock' => 'არ არის მარაგში',
        'last_items' => 'ბოლო ერთეულები!',
        'cart' => 'კალათა',
        'your_cart' => 'თქვენი კალათა',
        'subtotal' => 'ჯამი',
        'shipping' => 'მიწოდება',
        'total' => 'სულ',
        'checkout' => 'შეკვეთა',
        'continue_shopping' => 'შოპინგის გაგრძელება',
        'empty_cart' => 'თქვენი კალათა ცარიელია',
        'start_shopping' => 'დაიწყეთ შოპინგი',
        'shipping_information' => 'მიწოდების ინფორმაცია',
        'full_name' => 'სახელი გვარი',
        'street_address' => 'მისამართი',
        'city' => 'ქალაქი',
        'postal_code' => 'საფოსტო კოდი',
        'country' => 'ქვეყანა',
        'phone' => 'ტელეფონი',
        'payment_method' => 'გადახდის მეთოდი',
        'credit_card' => 'საკრედიტო ბარათი',
        'paypal' => 'PayPal',
        'apple_pay' => 'Apple Pay',
        'crypto' => 'კრიპტო',
        'card_number' => 'ბარათის ნომერი',
        'expiration_date' => 'ვადის გასვლის თარიღი',
        'cvv' => 'CVV',
        'name_on_card' => 'სახელი ბარათზე',
        'paypal_email' => 'PAYPAL ელფოსტა',
        'confirm_payment' => 'გადახდის დადასტურება',
        'wallet_address' => 'საფულის მისამართი',
        'complete_order' => 'შეკვეთის დასრულება',
        'secure_payment' => 'უსაფრთხო გადახდის დამუშავება',
        'important_info' => 'მნიშვნელოვანი ინფორმაცია',
        'shipping_time' => 'მიწოდება გრძელდება 1-2 სამუშაო დღე',
        'international_shipping' => 'საერთაშორისო მიწოდება არ არის ხელმისაწვდომი',
        'size_guide' => 'იხილეთ ზომის სახელმძღვანელო',
        'quality_guarantee' => 'ხარისხის გარანტია ყველა პროდუქტზე',
        'order_tracking' => 'შეკვეთის თვალთვალი იქნება უზრუნველყოფილი',
        'home' => 'მთავარი',
        'artists' => 'არტისტები',
        'schedule' => 'განრიგი',
        'tickets' => 'ბილეთები',
        'merchandise' => 'მერჩი',
        'profile' => 'პროფილი',
        'official' => 'ოფიციალური',
        'demo_notice' => 'ეს არის დემო გადახდის სისტემა. რეალური თანხები არ ჩამოიჭრება.',
        'remove' => 'წაშლა',
        'size' => 'ზომა',
        'color' => 'ფერი',
        'products' => 'პროდუქტები',
        'order_details' => 'შეკვეთის დეტალები',
        'order_successful' => 'შეკვეთა წარმატებით შესრულდა!',
        'thank_you' => 'მადლობა შეკვეთისთვის',
        'view_order' => 'შეკვეთის ნახვა',
        'view_all_orders' => 'ყველა შეკვეთის ნახვა',
        'most_anticipated' => 'მუსიკის ყველაზე დაუვიწყარი ღამე ახლოვდება — არ გამოტოვო ატმოსფერო, რომელზეც ყველა ლაპარაკობს!',
    ]
];

$t = $translations[$lang];

// Initialize variables
$all_merch = [];
$filtered_merch = [];

// Get all active merchandise only if database connection exists
if ($conn) {
    try {
        $query = "SELECT * FROM merch_items WHERE is_active = 1 ORDER BY id DESC";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Failed to prepare merchandise query: " . implode(', ', $conn->errorInfo()));
        }
        
        $stmt->execute();
        $all_merch = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        error_log("Merch Query Error: " . $e->getMessage());
        $error = $lang === 'ka' ? 'პროდუქტების ჩატვირთვის შეცდომა' : 'Error loading products';
    }
}

// Handle cart functionality
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Validate required session data
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $error = $lang === 'ka' ? 'მომხმარებლის სესია არ არის ვალიდური' : 'Invalid user session';
    header("Location: login.php");
    exit();
}

// Add to cart
if (isset($_POST['add_to_cart']) && $conn) {
    $merch_id = (int)$_POST['merch_id'];
    $quantity = max(1, (int)($_POST['quantity'] ?? 1)); // Ensure minimum quantity of 1
    $size = !empty($_POST['size']) ? trim($_POST['size']) : null;
    $color = !empty($_POST['color']) ? trim($_POST['color']) : null;
    
    try {
        // Validate item exists and has stock
        $query = "SELECT * FROM merch_items WHERE id = ? AND is_active = 1";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Failed to prepare item validation query");
        }
        
        $stmt->execute([$merch_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$item) {
            throw new Exception("Item not found or not active");
        }
        
        if ($item['available_quantity'] < $quantity) {
            $error = $lang === 'ka' ? 'არასაკმარისი მარაგი' : 'Insufficient stock available';
        } else {
            // Check if we already have this item with same size and color
            $found = false;
            foreach ($_SESSION['cart'] as &$cart_item) {
                if ($cart_item['id'] == $merch_id && $cart_item['size'] == $size && $cart_item['color'] == $color) {
                    $new_quantity = $cart_item['quantity'] + $quantity;
                    if ($new_quantity <= $item['available_quantity']) {
                        $cart_item['quantity'] = $new_quantity;
                        $found = true;
                    } else {
                        $error = $lang === 'ka' ? 'მაქსიმალური რაოდენობა გადაჭარბებულია' : 'Maximum quantity exceeded';
                    }
                    break;
                }
            }
            
            if (!$found && empty($error)) {
                $_SESSION['cart'][] = [
                    'id' => $merch_id,
                    'name' => $item['name'],
                    'name_ka' => $item['name_ka'],
                    'price' => (float)$item['price'],
                    'image' => $item['image'],
                    'quantity' => $quantity,
                    'size' => $size,
                    'color' => $color
                ];
            }
            
            if (empty($error)) {
                $success = $lang === 'ka' ? 'პროდუქტი წარმატებით დაემატა კალათაში' : 'Item added to cart successfully';
            }
        }
        
    } catch (Exception $e) {
        error_log("Add to Cart Error: " . $e->getMessage());
        $error = $lang === 'ka' ? 'კალათაში დამატების შეცდომა' : 'Error adding item to cart';
    }
}

// Remove from cart
if (isset($_POST['remove_from_cart'])) {
    $index = (int)$_POST['cart_index'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
        $success = $lang === 'ka' ? 'პროდუქტი წაიშალა კალათიდან' : 'Item removed from cart';
    }
}

// Update cart quantity
if (isset($_POST['update_cart'])) {
    $index = (int)$_POST['cart_index'];
    $quantity = max(1, (int)$_POST['quantity']); // Ensure minimum quantity of 1
    
    if (isset($_SESSION['cart'][$index]) && $quantity > 0) {
        $_SESSION['cart'][$index]['quantity'] = $quantity;
        $success = $lang === 'ka' ? 'კალათა განახლდა' : 'Cart updated';
    }
}

// Process checkout
if (isset($_POST['checkout']) && $conn) {
    // Sanitize and validate input
    $shipping_name = trim($_POST['shipping_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');
    
    // Enhanced validation
    if (empty($shipping_name)) {
        $error = $lang === 'ka' ? 'სახელი სავალდებულოა' : 'Name is required';
    } elseif (empty($address)) {
        $error = $lang === 'ka' ? 'მისამართი სავალდებულოა' : 'Address is required';
    } elseif (empty($city)) {
        $error = $lang === 'ka' ? 'ქალაქი სავალდებულოა' : 'City is required';
    } elseif (empty($country)) {
        $error = $lang === 'ka' ? 'ქვეყანა სავალდებულოა' : 'Country is required';
    } elseif (empty($payment_method)) {
        $error = $lang === 'ka' ? 'გადახდის მეთოდი სავალდებულოა' : 'Payment method is required';
    } elseif (empty($_SESSION['cart'])) {
        $error = $lang === 'ka' ? 'თქვენი კალათა ცარიელია' : 'Your cart is empty';
    } else {
        $total_amount = 0;
        $shipping_fee = 10.00; // Fixed shipping fee
        
        // Calculate total
        foreach ($_SESSION['cart'] as $item) {
            $total_amount += (float)$item['price'] * (int)$item['quantity'];
        }
        
        $total_amount += $shipping_fee;
        $shipping_address = $address . ", " . $city;
        if (!empty($postal_code)) {
            $shipping_address .= ", " . $postal_code;
        }
        $shipping_address .= ", " . $country;
        
        $user_id = (int)$_SESSION['user_id'];
        
        try {
            // Begin transaction
            $conn->beginTransaction();
            
            // Verify all items are still available before processing
            foreach ($_SESSION['cart'] as $item) {
                $check_query = "SELECT id, available_quantity FROM merch_items WHERE id = ? AND is_active = 1 FOR UPDATE";
                $check_stmt = $conn->prepare($check_query);
                
                if (!$check_stmt) {
                    throw new Exception("Failed to prepare stock check query");
                }
                
                $check_stmt->execute([$item['id']]);
                $merch_item = $check_stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$merch_item) {
                    throw new Exception("Item with ID {$item['id']} is no longer available");
                }
                
                if ($merch_item['available_quantity'] < $item['quantity']) {
                    throw new Exception("Insufficient stock for item with ID {$item['id']}. Available: {$merch_item['available_quantity']}, Requested: {$item['quantity']}");
                }
            }
            
            // Create order
            $query = "INSERT INTO merch_orders (user_id, total_amount, shipping_address, payment_method, status, created_at) 
                      VALUES (?, ?, ?, ?, 'processing', NOW())";
            $stmt = $conn->prepare($query);
            
            if (!$stmt) {
                throw new Exception("Failed to prepare order insertion: " . implode(', ', $conn->errorInfo()));
            }
            
            $result = $stmt->execute([$user_id, $total_amount, $shipping_address, $payment_method]);
            
            if (!$result) {
                throw new Exception("Failed to create order: " . implode(', ', $stmt->errorInfo()));
            }
            
            $order_id = $conn->lastInsertId();
            
            if (!$order_id) {
                throw new Exception("Failed to get order ID");
            }
            
            // Add order items and update inventory
            foreach ($_SESSION['cart'] as $item) {
                // Insert order item
                $item_query = "INSERT INTO order_items (order_id, merch_id, quantity, size, color, price_per_unit) 
                              VALUES (?, ?, ?, ?, ?, ?)";
                $item_stmt = $conn->prepare($item_query);
                
                if (!$item_stmt) {
                    throw new Exception("Failed to prepare order item insertion");
                }
                
                $item_result = $item_stmt->execute([
                    $order_id, 
                    $item['id'], 
                    $item['quantity'], 
                    $item['size'], 
                    $item['color'], 
                    $item['price']
                ]);
                
                if (!$item_result) {
                    throw new Exception("Failed to add order item: " . implode(', ', $item_stmt->errorInfo()));
                }
                
                // Update inventory
                $update_query = "UPDATE merch_items SET available_quantity = available_quantity - ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_query);
                
                if (!$update_stmt) {
                    throw new Exception("Failed to prepare inventory update");
                }
                
                $update_result = $update_stmt->execute([$item['quantity'], $item['id']]);
                
                if (!$update_result) {
                    throw new Exception("Failed to update inventory: " . implode(', ', $update_stmt->errorInfo()));
                }
            }
            
            // Commit transaction
            $conn->commit();
            
            // Clear cart and set success
            $_SESSION['cart'] = [];
            $_SESSION['order_success'] = true;
            $_SESSION['last_order_id'] = $order_id;
            
            // Redirect to success page
            header("Location: merch.php?order_success=1&order_id=" . $order_id);
            exit();
            
        } catch (Exception $e) {
            // Rollback transaction on error
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            
            // Log the specific error for debugging
            error_log("Merch Order Error [User: " . ($_SESSION['user_name'] ?? 'Unknown') . ", ID: $user_id]: " . $e->getMessage());
            
            // Show user-friendly error message
            if (strpos($e->getMessage(), 'stock') !== false || strpos($e->getMessage(), 'available') !== false) {
                $error = $lang === 'ka' ? 'ზოგიერთი პროდუქტი აღარ არის მარაგში. გთხოვთ, განაახლოთ კალათა.' : 
                         'Some items are no longer in stock. Please update your cart.';
            } else {
                $error = $lang === 'ka' ? 'შეკვეთის დამუშავებისას დაფიქსირდა შეცდომა. გთხოვთ, სცადოთ მოგვიანებით.' : 
                         'An error occurred while processing your order. Please try again later.';
            }
            
            // For development environment, show the actual error
            if (defined('DEV_MODE') && DEV_MODE) {
                $error .= " Error: " . $e->getMessage();
            }
        }
    }
}

// Calculate cart totals
$cart_subtotal = 0;
$shipping_fee = !empty($_SESSION['cart']) ? 10.00 : 0; // $10 shipping fee if cart not empty

foreach ($_SESSION['cart'] as $item) {
    $cart_subtotal += (float)$item['price'] * (int)$item['quantity'];
}

$cart_total = $cart_subtotal + $shipping_fee;

// Filter by category
$category = $_GET['category'] ?? '';
$filtered_merch = $all_merch;

if (!empty($category) && $category !== 'all') {
    $filtered_merch = array_filter($all_merch, function($item) use ($category) {
        return isset($item['category']) && $item['category'] === $category;
    });
}

// Sort products
$sort = $_GET['sort'] ?? '';
if (!empty($sort) && !empty($filtered_merch)) {
    switch ($sort) {
        case 'price_asc':
            usort($filtered_merch, function($a, $b) {
                return (float)$a['price'] <=> (float)$b['price'];
            });
            break;
        case 'price_desc':
            usort($filtered_merch, function($a, $b) {
                return (float)$b['price'] <=> (float)$a['price'];
            });
            break;
        case 'newest':
            // Already sorted by ID DESC
            break;
        case 'bestsellers':
            // Would need additional data for bestsellers
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['merch_shop']; ?> - PULSE Festival</title>
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
        
        .merch-card {
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid #333;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .merch-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(59, 255, 68, 0.15);
            border-color: rgba(59, 255, 68, 0.4);
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
        
        .category-button {
            transition: all 0.3s ease;
        }
        
        .category-button.active {
            background-color: var(--primary-color);
            color: black;
        }
        
        .action-button {
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
        
        .action-button:before {
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
        
        .action-button:hover:before {
            width: 100%;
        }
        
        .action-button:hover {
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
        
        /* Cart badge */
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--primary-color);
            color: black;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }
        
        /* Stock indicator */
        .stock-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 2px 8px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .in-stock {
            background-color: var(--primary-color);
            color: black;
        }
        
        .low-stock {
            background-color: #FFA500;
            color: black;
        }
        
        .out-of-stock {
            background-color: #FF4444;
            color: white;
        }
        
        /* Size and color options */
        .size-option, .color-option {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #444;
            margin-right: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .size-option:hover, .size-option.selected {
            border-color: var(--primary-color);
            background-color: rgba(59, 255, 68, 0.1);
        }
        
        .color-option {
            border-radius: 50%;
        }
        
        .color-option:hover, .color-option.selected {
            transform: scale(1.15);
            box-shadow: 0 0 0 2px var(--primary-color);
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
    </style>
</head>
<body class="bg-black text-white main-font min-h-screen" x-data="{ showCart: false, showFilters: false, activeTab: 'shop', selectedItem: null }">
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
                    <a href="merch.php" class="text-festival-yellow transition-colors festival-font"><?php echo $t['merchandise']; ?></a>
                    
                    <!-- Language Switcher -->
                    <div class="flex items-center space-x-2">
                        <a href="?lang=en<?php echo !empty($category) ? '&category='.$category : ''; ?><?php echo !empty($sort) ? '&sort='.$sort : ''; ?>" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> hover:text-festival-yellow transition-colors text-sm festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?lang=ka<?php echo !empty($category) ? '&category='.$category : ''; ?><?php echo !empty($sort) ? '&sort='.$sort : ''; ?>" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> hover:text-festival-yellow transition-colors text-sm festival-font font-bold">ქარ</a>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <button @click="showCart = !showCart" class="relative p-2" aria-label="Cart">
                        <i class="fas fa-shopping-cart text-white"></i>
                        <?php if (!empty($_SESSION['cart'])): ?>
                        <span class="cart-badge"><?php echo count($_SESSION['cart']); ?></span>
                        <?php endif; ?>
                    </button>
                    <a href="profile.php" class="bg-transparent px-4 py-2 rounded-none border border-festival-yellow text-festival-yellow hover:bg-festival-yellow hover:text-black transition-colors festival-font"><?php echo $t['profile']; ?></a>
                </div>
                
                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button @click="showCart = !showCart" class="relative p-2 mr-2" aria-label="Cart">
                        <i class="fas fa-shopping-cart text-white"></i>
                        <?php if (!empty($_SESSION['cart'])): ?>
                        <span class="cart-badge"><?php echo count($_SESSION['cart']); ?></span>
                        <?php endif; ?>
                    </button>
                    <button @click="mobileMenu = !mobileMenu" class="z-20 p-2 text-white focus:outline-none">
                        <i class="fas" :class="mobileMenu ? 'fa-times' : 'fa-bars'"></i>
                    </button>
                </div>
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
                    <a href="merch.php" @click="mobileMenu = false" class="mobile-menu-item py-3 festival-font text-festival-yellow"><?php echo $t['merchandise']; ?></a>
                    
                    <div class="mobile-menu-item py-3 flex items-center space-x-4">
                        <a href="?lang=en<?php echo !empty($category) ? '&category='.$category : ''; ?><?php echo !empty($sort) ? '&sort='.$sort : ''; ?>" class="<?php echo $lang === 'en' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">EN</a>
                        <span class="text-gray-600">|</span>
                        <a href="?lang=ka<?php echo !empty($category) ? '&category='.$category : ''; ?><?php echo !empty($sort) ? '&sort='.$sort : ''; ?>" class="<?php echo $lang === 'ka' ? 'text-festival-yellow' : 'text-gray-400'; ?> festival-font font-bold">ქარ</a>
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
            <?php echo $t['most_anticipated']; ?> • <?php echo $t['most_anticipated']; ?> • <?php echo $t['most_anticipated']; ?> • <?php echo $t['most_anticipated']; ?> • <?php echo $t['most_anticipated']; ?>
        </div>
    </div>

    <?php if (isset($_GET['order_success'])): ?>
    <!-- Order Success Message -->
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80 backdrop-blur-sm">
        <div class="bg-gray-900 border border-green-500 p-6 max-w-md w-full text-center">
            <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-2xl text-black"></i>
            </div>
            <h3 class="text-2xl font-bold mb-2 festival-font text-festival-yellow"><?php echo $t['order_successful']; ?></h3>
            <p class="text-gray-300 mb-6"><?php echo $t['thank_you']; ?></p>
            
            <div class="flex justify-center space-x-4">
                <a href="profile.php?order_id=<?php echo $_SESSION['last_order_id'] ?? ''; ?>" class="action-button py-2 px-6 uppercase">
                    <?php echo $t['view_order']; ?>
                </a>
                <a href="profile.php#orders" class="bg-transparent border border-festival-yellow text-festival-yellow py-2 px-6 uppercase hover:bg-festival-yellow hover:text-black transition-colors festival-font">
                    <?php echo $t['view_all_orders']; ?>
                </a>
            </div>
            
            <a href="merch.php" class="mt-6 block text-gray-400 hover:text-white">
                <?php echo $t['continue_shopping']; ?> →
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 sm:py-12 mt-4">
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-center mb-4 text-festival-yellow festival-font"><?php echo $t['merch_shop']; ?></h1>
        <p class="text-center text-gray-400 mb-8 sm:mb-12 main-font"><?php echo $t['official_festival']; ?></p>

        <?php if ($error): ?>
            <div class="bg-red-900/70 border border-red-700 text-red-100 px-4 py-3 rounded-none mb-8 max-w-2xl mx-auto flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-900/70 border border-green-700 text-green-100 px-4 py-3 rounded-none mb-8 max-w-2xl mx-auto flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
        <?php endif; ?>

        <!-- Category filters for desktop -->
        <div class="hidden md:block mb-8">
            <div class="flex justify-center space-x-4 mb-8">
                <a href="?lang=<?php echo $lang; ?>" class="category-button px-4 py-2 border <?php echo empty($category) ? 'active' : 'border-gray-700 text-white hover:border-festival-yellow'; ?> festival-font">
                    <?php echo $t['all_categories']; ?>
                </a>
                <a href="?lang=<?php echo $lang; ?>&category=clothing" class="category-button px-4 py-2 border <?php echo $category === 'clothing' ? 'active' : 'border-gray-700 text-white hover:border-festival-yellow'; ?> festival-font">
                    <?php echo $t['clothing']; ?>
                </a>
                <a href="?lang=<?php echo $lang; ?>&category=accessories" class="category-button px-4 py-2 border <?php echo $category === 'accessories' ? 'active' : 'border-gray-700 text-white hover:border-festival-yellow'; ?> festival-font">
                    <?php echo $t['accessories']; ?>
                </a>
                <a href="?lang=<?php echo $lang; ?>&category=collectibles" class="category-button px-4 py-2 border <?php echo $category === 'collectibles' ? 'active' : 'border-gray-700 text-white hover:border-festival-yellow'; ?> festival-font">
                    <?php echo $t['collectibles']; ?>
                </a>
            </div>
            
            <!-- Sort options -->
            <div class="flex justify-between items-center px-4">
                <div>
                    <span class="text-sm text-gray-400"><?php echo count($filtered_merch); ?> <?php echo $t['products']; ?></span>
                </div>
                
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-400"><?php echo $t['sort_by']; ?>:</span>
                    <select onchange="window.location.href='?lang=<?php echo $lang; ?><?php echo !empty($category) ? '&category='.$category : ''; ?>&sort='+this.value" class="input-field text-sm py-1 px-2">
                        <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>><?php echo $t['newest']; ?></option>
                        <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>><?php echo $t['price_low_high']; ?></option>
                        <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>><?php echo $t['price_high_low']; ?></option>
                        <option value="bestsellers" <?php echo $sort === 'bestsellers' ? 'selected' : ''; ?>><?php echo $t['bestsellers']; ?></option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Mobile filter button -->
        <div class="md:hidden mb-6">
            <button @click="showFilters = !showFilters" class="w-full py-3 px-4 border border-gray-700 flex items-center justify-between">
                <span class="festival-font"><?php echo $t['filter_by']; ?></span>
                <i class="fas" :class="showFilters ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </button>
            
            <div x-show="showFilters" x-transition class="border border-gray-700 border-t-0 p-4 bg-gray-900/50">
                <!-- Categories -->
                <div class="mb-4">
                    <h3 class="text-sm font-bold mb-2 festival-font"><?php echo $t['all_categories']; ?></h3>
                    <div class="space-y-2">
                        <a href="?lang=<?php echo $lang; ?>" class="block text-sm <?php echo empty($category) ? 'text-festival-yellow' : 'text-gray-400'; ?>">
                            <?php echo $t['all_categories']; ?>
                        </a>
                        <a href="?lang=<?php echo $lang; ?>&category=clothing" class="block text-sm <?php echo $category === 'clothing' ? 'text-festival-yellow' : 'text-gray-400'; ?>">
                            <?php echo $t['clothing']; ?>
                        </a>
                        <a href="?lang=<?php echo $lang; ?>&category=accessories" class="block text-sm <?php echo $category === 'accessories' ? 'text-festival-yellow' : 'text-gray-400'; ?>">
                            <?php echo $t['accessories']; ?>
                        </a>
                        <a href="?lang=<?php echo $lang; ?>&category=collectibles" class="block text-sm <?php echo $category === 'collectibles' ? 'text-festival-yellow' : 'text-gray-400'; ?>">
                            <?php echo $t['collectibles']; ?>
                        </a>
                    </div>
                </div>
                
                <!-- Sort options -->
                <div>
                    <h3 class="text-sm font-bold mb-2 festival-font"><?php echo $t['sort_by']; ?></h3>
                    <select onchange="window.location.href='?lang=<?php echo $lang; ?><?php echo !empty($category) ? '&category='.$category : ''; ?>&sort='+this.value" class="input-field text-sm py-2 px-3 w-full">
                        <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>><?php echo $t['newest']; ?></option>
                        <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>><?php echo $t['price_low_high']; ?></option>
                        <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>><?php echo $t['price_high_low']; ?></option>
                        <option value="bestsellers" <?php echo $sort === 'bestsellers' ? 'selected' : ''; ?>><?php echo $t['bestsellers']; ?></option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            <?php foreach ($filtered_merch as $item): ?>
                <?php 
                    // Stock status
                    $stock_status = '';
                    $stock_class = '';
                    
                    if ($item['available_quantity'] <= 0) {
                        $stock_status = $t['out_of_stock'];
                        $stock_class = 'out-of-stock';
                    } elseif ($item['available_quantity'] <= 5) {
                        $stock_status = $t['last_items'];
                        $stock_class = 'low-stock';
                    } else {
                        $stock_status = $t['in_stock'];
                        $stock_class = 'in-stock';
                    }
                    
                    // Parse size and color options
                    $size_options = json_decode($item['size_options'] ?? '[]', true) ?: [];
                    $color_options = json_decode($item['color_options'] ?? '[]', true) ?: [];
                ?>
                <div class="merch-card rounded-none overflow-hidden">
                    <div class="relative">
                        <img src="<?php echo getMerchImagePath($item['image']); ?>"  alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-full h-48 sm:h-56 object-cover object-center">
                        <span class="stock-indicator <?php echo $stock_class; ?>"><?php echo $stock_status; ?></span>
                    </div>
                    
                    <div class="p-4">
                        <h3 class="font-bold text-white mb-1 festival-font">
                            <?php echo htmlspecialchars($lang === 'ka' && !empty($item['name_ka']) ? $item['name_ka'] : $item['name']); ?>
                        </h3>
                        <div class="text-lg font-bold text-festival-yellow mb-2">$<?php echo number_format($item['price'], 2); ?></div>
                        <p class="text-gray-400 text-sm mb-4 line-clamp-2">
                            <?php echo htmlspecialchars($lang === 'ka' && !empty($item['description_ka']) ? $item['description_ka'] : $item['description']); ?>
                        </p>
                        
                        <button 
                            @click="selectedItem = <?php echo $item['id']; ?>" 
                            class="w-full action-button py-2 text-center festival-font"
                            <?php if ($item['available_quantity'] <= 0): ?>disabled<?php endif; ?>
                        >
                            <?php echo $t['add_to_cart']; ?>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($filtered_merch)): ?>
            <div class="text-center py-12">
                <i class="fas fa-search text-4xl text-gray-600 mb-4"></i>
                <p class="text-xl text-gray-400">No products found in this category</p>
            </div>
        <?php endif; ?>
        
        <!-- Item detail modal -->
        <?php foreach ($filtered_merch as $item): ?>
            <?php 
                // Parse size and color options
                $size_options = json_decode($item['size_options'] ?? '[]', true) ?: [];
                $color_options = json_decode($item['color_options'] ?? '[]', true) ?: [];
            ?>
            <div 
                x-show="selectedItem === <?php echo $item['id']; ?>" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4"
                x-cloak
            >
                <div @click.away="selectedItem = null" class="bg-gray-900 max-w-3xl w-full p-6 border border-gray-700 max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-festival-yellow festival-font">
                            <?php echo htmlspecialchars($lang === 'ka' && !empty($item['name_ka']) ? $item['name_ka'] : $item['name']); ?>
                        </h3>
                        <button @click="selectedItem = null" class="text-gray-400 hover:text-white">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="md:flex gap-6">
                        <div class="md:w-1/2 mb-4 md:mb-0">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-full h-auto object-cover object-center">
                        </div>
                        
                        <div class="md:w-1/2">
                            <form action="merch.php" method="POST" class="space-y-6" x-data="{ selectedSize: '', selectedColor: '', quantity: 1 }">
                                <input type="hidden" name="merch_id" value="<?php echo $item['id']; ?>">
                                
                                <p class="text-gray-300">
                                    <?php echo htmlspecialchars($lang === 'ka' && !empty($item['description_ka']) ? $item['description_ka'] : $item['description']); ?>
                                </p>
                                
                                <div class="text-2xl font-bold text-festival-yellow">
                                    $<?php echo number_format($item['price'], 2); ?>
                                </div>
                                
                                <?php if (!empty($size_options)): ?>
                                <div>
                                    <label class="block text-sm font-medium mb-2 festival-font"><?php echo $t['select_size']; ?></label>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($size_options as $index => $size): ?>
                                            <div class="size-option" 
                                                 :class="{ 'selected': selectedSize === '<?php echo $size; ?>' }"
                                                 @click="selectedSize = '<?php echo $size; ?>'">
                                                <?php echo $size; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <input type="hidden" name="size" x-bind:value="selectedSize">
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($color_options)): ?>
                                <div>
                                    <label class="block text-sm font-medium mb-2 festival-font"><?php echo $t['select_color']; ?></label>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($color_options as $index => $color): ?>
                                            <div class="color-option" 
                                                 style="background-color: <?php echo $color; ?>;"
                                                 :class="{ 'selected': selectedColor === '<?php echo $color; ?>' }"
                                                 @click="selectedColor = '<?php echo $color; ?>'">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <input type="hidden" name="color" x-bind:value="selectedColor">
                                </div>
                                <?php endif; ?>
                                
                                <div>
                                    <label class="block text-sm font-medium mb-2 festival-font"><?php echo $t['quantity']; ?></label>
                                    <select name="quantity" x-model="quantity" class="input-field py-2 px-3 rounded-none w-24">
                                        <?php for ($i = 1; $i <= min(5, $item['available_quantity']); $i++): ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                
                                <div class="pt-4">
                                    <button type="submit" name="add_to_cart" class="action-button w-full py-3 text-center festival-font">
                                        <?php echo $t['add_to_cart']; ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <!-- Shopping Cart Sidebar -->
        <div 
            x-show="showCart" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-full"
            class="fixed inset-0 z-50 overflow-hidden"
            x-cloak
        >
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute inset-0 bg-black bg-opacity-50" @click="showCart = false"></div>
                
                <div class="absolute inset-y-0 right-0 max-w-full flex">
                    <div class="w-screen max-w-md">
                        <div class="h-full flex flex-col bg-gray-900 shadow-xl overflow-y-auto">
                            <div class="flex-1 py-6 overflow-y-auto px-4">
                                <div class="flex items-start justify-between mb-6">
                                    <h2 class="text-xl font-bold text-white festival-font"><?php echo $t['your_cart']; ?></h2>
                                    <button @click="showCart = false" class="text-gray-400 hover:text-white">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                
                                <?php if (empty($_SESSION['cart'])): ?>
                                    <div class="text-center py-10">
                                        <div class="inline-block p-3 rounded-full bg-gray-800 mb-4">
                                            <i class="fas fa-shopping-cart text-2xl text-gray-500"></i>
                                        </div>
                                        <p class="text-lg text-gray-400 mb-6"><?php echo $t['empty_cart']; ?></p>
                                        <button @click="showCart = false" class="action-button px-6 py-3 festival-font">
                                            <?php echo $t['start_shopping']; ?>
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <div class="divide-y divide-gray-700">
                                        <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                                            <div class="py-4 flex">
                                                <div class="w-20 h-20 flex-shrink-0 bg-gray-800">
                                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="w-full h-full object-cover object-center">
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <div class="flex justify-between">
                                                        <h3 class="text-sm font-medium text-white">
                                                            <?php echo htmlspecialchars($lang === 'ka' && !empty($item['name_ka']) ? $item['name_ka'] : $item['name']); ?>
                                                        </h3>
                                                        <p class="text-sm font-medium text-festival-yellow"><?php echo '$' . number_format($item['price'], 2); ?></p>
                                                    </div>
                                                    <?php if ($item['size'] || $item['color']): ?>
                                                        <div class="mt-1 flex text-xs text-gray-400">
                                                            <?php if ($item['size']): ?>
                                                                <span class="mr-2"><?php echo $t['size']; ?>: <?php echo htmlspecialchars($item['size']); ?></span>
                                                            <?php endif; ?>
                                                            <?php if ($item['color']): ?>
                                                                <span><?php echo $t['color']; ?>: <span class="inline-block w-3 h-3 rounded-full" style="background-color: <?php echo htmlspecialchars($item['color']); ?>"></span></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="mt-2 flex items-center justify-between">
                                                        <form action="merch.php" method="POST" class="flex items-center">
                                                            <input type="hidden" name="cart_index" value="<?php echo $index; ?>">
                                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="10" class="input-field w-14 px-2 py-1 text-sm">
                                                            <button type="submit" name="update_cart" class="ml-2 text-xs text-gray-400 hover:text-white">
                                                                <i class="fas fa-sync-alt"></i>
                                                            </button>
                                                        </form>
                                                        <form action="merch.php" method="POST">
                                                            <input type="hidden" name="cart_index" value="<?php echo $index; ?>">
                                                            <button type="submit" name="remove_from_cart" class="text-xs text-gray-400 hover:text-white">
                                                                <?php echo $t['remove']; ?>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- Cart summary -->
                                    <div class="border-t border-gray-700 mt-6 pt-6">
                                        <div class="flex justify-between text-sm">
                                            <p class="text-gray-400"><?php echo $t['subtotal']; ?></p>
                                            <p class="text-white">$<?php echo number_format($cart_subtotal, 2); ?></p>
                                        </div>
                                        <div class="flex justify-between text-sm mt-2">
                                            <p class="text-gray-400"><?php echo $t['shipping']; ?></p>
                                            <p class="text-white">$<?php echo number_format($shipping_fee, 2); ?></p>
                                        </div>
                                                                                <div class="flex justify-between text-base mt-4 pt-4 border-t border-gray-700">
                                            <p class="font-medium"><?php echo $t['total']; ?></p>
                                            <p class="font-medium text-festival-yellow">$<?php echo number_format($cart_total, 2); ?></p>
                                        </div>
                                        
                                        <div class="mt-6">
                                            <button @click="activeTab = 'checkout'; showCart = false" class="w-full action-button py-3 text-center festival-font">
                                                <?php echo $t['checkout']; ?>
                                            </button>
                                        </div>
                                        <div class="mt-3">
                                            <button @click="showCart = false" class="w-full border border-gray-700 hover:border-white py-3 text-center festival-font text-white transition-colors">
                                                <?php echo $t['continue_shopping']; ?>
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkout Section -->
        <div x-show="activeTab === 'checkout'" x-cloak class="mt-12">
            <h2 class="text-2xl font-bold mb-6 festival-font text-center text-festival-yellow"><?php echo $t['checkout']; ?></h2>
            
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="text-center py-12">
                    <div class="inline-block p-4 rounded-full bg-gray-800 mb-4">
                        <i class="fas fa-shopping-cart text-3xl text-gray-500"></i>
                    </div>
                    <p class="text-xl text-gray-400 mb-6"><?php echo $t['empty_cart']; ?></p>
                    <button @click="activeTab = 'shop'" class="action-button px-8 py-3 festival-font">
                        <?php echo $t['start_shopping']; ?>
                    </button>
                </div>
            <?php else: ?>
                <form action="merch.php" method="POST" class="max-w-4xl mx-auto" x-data="{ paymentMethod: '' }">
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <!-- Left Column - Shipping Information -->
                        <div class="border border-gray-700 p-5">
                            <h3 class="text-lg font-bold mb-4 festival-font"><?php echo $t['shipping_information']; ?></h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium mb-1 festival-font"><?php echo $t['full_name']; ?></label>
                                    <input type="text" name="shipping_name" required class="w-full px-3 py-2 input-field rounded-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium mb-1 festival-font"><?php echo $t['street_address']; ?></label>
                                    <input type="text" name="address" required class="w-full px-3 py-2 input-field rounded-none">
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium mb-1 festival-font"><?php echo $t['city']; ?></label>
                                        <input type="text" name="city" required class="w-full px-3 py-2 input-field rounded-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium mb-1 festival-font"><?php echo $t['postal_code']; ?></label>
                                        <input type="text" name="postal_code" required class="w-full px-3 py-2 input-field rounded-none">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium mb-1 festival-font"><?php echo $t['country']; ?></label>
                                    <select name="country" required class="w-full px-3 py-2 input-field rounded-none disabled">
                                        <option value="Georgia">Georgia</option>
                                    </select>

                                </div>
                                <div>
                                    <label class="block text-xs font-medium mb-1 festival-font"><?php echo $t['phone']; ?></label>
                                    <input type="tel" name="phone" required class="w-full px-3 py-2 input-field rounded-none">
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <h3 class="text-lg font-bold mb-4 festival-font"><?php echo $t['payment_method']; ?></h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <input type="radio" name="payment_method" value="credit_card" id="checkout_credit_card" class="payment-input hidden" x-model="paymentMethod">
                                        <label for="checkout_credit_card" class="payment-label h-full flex flex-col items-center justify-center px-4 py-3 rounded-none border border-gray-700">
                                            <i class="fas fa-credit-card text-lg mb-1"></i>
                                            <span class="text-sm"><?php echo $t['credit_card']; ?></span>
                                        </label>
                                    </div>
                                    <div>
                                        <input type="radio" name="payment_method" value="paypal" id="checkout_paypal" class="payment-input hidden" x-model="paymentMethod">
                                        <label for="checkout_paypal" class="payment-label h-full flex flex-col items-center justify-center px-4 py-3 rounded-none border border-gray-700">
                                            <i class="fab fa-paypal text-lg mb-1"></i>
                                            <span class="text-sm"><?php echo $t['paypal']; ?></span>
                                        </label>
                                    </div>
                                    <div>
                                        <input type="radio" name="payment_method" value="apple_pay" id="checkout_apple_pay" class="payment-input hidden" x-model="paymentMethod">
                                        <label for="checkout_apple_pay" class="payment-label h-full flex flex-col items-center justify-center px-4 py-3 rounded-none border border-gray-700">
                                            <i class="fab fa-apple-pay text-lg mb-1"></i>
                                            <span class="text-sm"><?php echo $t['apple_pay']; ?></span>
                                        </label>
                                    </div>
                                    <div>
                                        <input type="radio" name="payment_method" value="crypto" id="checkout_crypto" class="payment-input hidden" x-model="paymentMethod">
                                        <label for="checkout_crypto" class="payment-label h-full flex flex-col items-center justify-center px-4 py-3 rounded-none border border-gray-700">
                                            <i class="fab fa-bitcoin text-lg mb-1"></i>
                                            <span class="text-sm"><?php echo $t['crypto']; ?></span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Payment Forms -->
                                <div class="mt-4">
                                    <!-- Credit Card Form -->
                                    <div x-show="paymentMethod === 'credit_card'" x-transition class="space-y-3 p-4 bg-gray-900/50 border border-gray-700">
                                        <div>
                                            <label class="block text-xs font-medium mb-1 festival-font"><?php echo $t['card_number']; ?></label>
                                            <input type="text" placeholder="4242 4242 4242 4242" class="w-full px-3 py-2 input-field rounded-none">
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium mb-1 festival-font"><?php echo $t['expiration_date']; ?></label>
                                                <input type="text" placeholder="MM/YY" class="w-full px-3 py-2 input-field rounded-none">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium mb-1 festival-font"><?php echo $t['cvv']; ?></label>
                                                <input type="text" placeholder="123" class="w-full px-3 py-2 input-field rounded-none">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium mb-1 festival-font"><?php echo $t['name_on_card']; ?></label>
                                            <input type="text" class="w-full px-3 py-2 input-field rounded-none">
                                        </div>
                                    </div>
                                    
                                    <!-- PayPal Form -->
                                    <div x-show="paymentMethod === 'paypal'" x-transition class="p-4 bg-gray-900/50 border border-gray-700">
                                        <label class="block text-xs font-medium mb-1 festival-font"><?php echo $t['paypal_email']; ?></label>
                                        <input type="email" class="w-full px-3 py-2 input-field rounded-none">
                                    </div>
                                    
                                    <!-- Apple Pay -->
                                    <div x-show="paymentMethod === 'apple_pay'" x-transition class="p-4 bg-gray-900/50 border border-gray-700 text-center">
                                        <i class="fab fa-apple-pay text-4xl"></i>
                                    </div>
                                    
                                    <!-- Crypto -->
                                    <div x-show="paymentMethod === 'crypto'" x-transition class="p-4 bg-gray-900/50 border border-gray-700">
                                        <div>
                                            <label class="block text-xs font-medium mb-1 festival-font">Cryptocurrency</label>
                                            <select class="w-full px-3 py-2 input-field rounded-none">
                                                <option value="btc">Bitcoin (BTC)</option>
                                                <option value="eth">Ethereum (ETH)</option>
                                                <option value="usdt">USDT</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column - Order Summary -->
                        <div class="border border-gray-700 p-5">
                            <h3 class="text-lg font-bold mb-4 festival-font"><?php echo $t['order_details']; ?></h3>
                            
                            <div class="divide-y divide-gray-700">
                                <?php foreach ($_SESSION['cart'] as $item): ?>
                                    <div class="py-3 flex items-center">
                                        <div class="w-12 h-12 bg-gray-800 flex-shrink-0">
                                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="" class="w-full h-full object-cover">
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <div class="flex justify-between">
                                                <div>
                                                    <p class="text-sm font-medium">
                                                        <?php echo htmlspecialchars($lang === 'ka' && !empty($item['name_ka']) ? $item['name_ka'] : $item['name']); ?>
                                                    </p>
                                                    <div class="flex text-xs text-gray-400">
                                                        <span><?php echo $item['quantity']; ?> × $<?php echo number_format($item['price'], 2); ?></span>
                                                        <?php if ($item['size']): ?>
                                                            <span class="ml-2"><?php echo $t['size']; ?>: <?php echo $item['size']; ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <p class="text-sm font-medium">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="mt-6 pt-4 border-t border-gray-700">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-400"><?php echo $t['subtotal']; ?></span>
                                    <span>$<?php echo number_format($cart_subtotal, 2); ?></span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-400"><?php echo $t['shipping']; ?></span>
                                    <span>$<?php echo number_format($shipping_fee, 2); ?></span>
                                </div>
                                <div class="flex justify-between font-bold mt-4 pt-4 border-t border-gray-700">
                                    <span><?php echo $t['total']; ?></span>
                                    <span class="text-festival-yellow">$<?php echo number_format($cart_total, 2); ?></span>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <button type="submit" name="checkout" class="w-full action-button py-3 text-center festival-font">
                                    <?php echo $t['complete_order']; ?>
                                </button>
                            </div>
                            
                            <div class="mt-6 bg-gray-900/50 p-4 border border-gray-700">
                                <h4 class="font-bold text-sm mb-2 festival-font"><?php echo $t['important_info']; ?></h4>
                                <ul class="text-xs text-gray-400 space-y-1">
                                    <li>• <?php echo $t['shipping_time']; ?></li>
                                    <li>• <?php echo $t['international_shipping']; ?></li>
                                    <li>• <?php echo $t['size_guide']; ?></li>
                                    <li>• <?php echo $t['quality_guarantee']; ?></li>
                                    <li>• <?php echo $t['order_tracking']; ?></li>
                                </ul>
                            </div>
                            
                            <div class="text-center text-gray-400 flex items-center justify-center text-xs mt-6">
                                <i class="fas fa-lock mr-2"></i>
                                <span><?php echo $t['secure_payment']; ?></span>
                            </div>
                            <p class="text-center text-xs text-gray-600 mt-2">
                                <?php echo $t['demo_notice']; ?>
                            </p>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <!-- Important Information Section -->
        <div class="max-w-4xl mx-auto mt-16 p-6 border border-gray-700 bg-gray-900/20">
            <h3 class="text-xl font-bold mb-4 text-center text-festival-yellow festival-font"><?php echo $t['important_info']; ?></h3>
            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6 text-center">
                <div>
                    <div class="w-12 h-12 mx-auto bg-black/50 rounded-full flex items-center justify-center mb-3 border border-festival-yellow">
                        <i class="fas fa-truck text-festival-yellow"></i>
                    </div>
                    <p class="text-sm text-gray-300"><?php echo $t['shipping_time']; ?></p>
                </div>
                <div>
                    <div class="w-12 h-12 mx-auto bg-black/50 rounded-full flex items-center justify-center mb-3 border border-festival-yellow">
                        <i class="fas fa-globe text-festival-yellow"></i>
                    </div>
                    <p class="text-sm text-gray-300"><?php echo $t['international_shipping']; ?></p>
                </div>
                <div>
                    <div class="w-12 h-12 mx-auto bg-black/50 rounded-full flex items-center justify-center mb-3 border border-festival-yellow">
                        <i class="fas fa-ruler text-festival-yellow"></i>
                    </div>
                    <p class="text-sm text-gray-300"><?php echo $t['size_guide']; ?></p>
                </div>
                <div>
                    <div class="w-12 h-12 mx-auto bg-black/50 rounded-full flex items-center justify-center mb-3 border border-festival-yellow">
                        <i class="fas fa-medal text-festival-yellow"></i>
                    </div>
                    <p class="text-sm text-gray-300"><?php echo $t['quality_guarantee']; ?></p>
                </div>
                <div>
                    <div class="w-12 h-12 mx-auto bg-black/50 rounded-full flex items-center justify-center mb-3 border border-festival-yellow">
                        <i class="fas fa-map-marker-alt text-festival-yellow"></i>
                    </div>
                    <p class="text-sm text-gray-300"><?php echo $t['order_tracking']; ?></p>
                </div>
                <div>
                    <div class="w-12 h-12 mx-auto bg-black/50 rounded-full flex items-center justify-center mb-3 border border-festival-yellow">
                        <i class="fas fa-lock text-festival-yellow"></i>
                    </div>
                    <p class="text-sm text-gray-300"><?php echo $t['secure_payment']; ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-neutral-900 py-8 sm:py-12 border-t border-neutral-800 mt-16">
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
        document.addEventListener('alpine:init', () => {
            // Check for order success on page load
            <?php if (isset($_GET['order_success'])): ?>
            setTimeout(() => {
                window.location.href = 'merch.php';
            }, 5000); // Redirect after 5 seconds
            <?php endif; ?>
        });
    </script>
</body>
</html>
                