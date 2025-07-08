<?php
require_once __DIR__ . '/../config/database.php';
function generateQRCode($data) {
    // Using QR Server API instead of deprecated Google Charts API
    $qr_data = urlencode($data);
    return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . $qr_data;
}

function getTicketTypes() {
    $database = new Database();
    $conn = $database->getConnection();
    
    $query = "SELECT * FROM ticket_types WHERE is_active = 1 ORDER BY price ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getArtists() {
    $database = new Database();
    $conn = $database->getConnection();
    
    $query = "SELECT * FROM artists ORDER BY name ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEvents() {
    $database = new Database();
    $conn = $database->getConnection();
    
    $query = "SELECT e.*, a.name as artist_name FROM events e 
              LEFT JOIN artists a ON e.artist_id = a.id 
              ORDER BY e.event_date ASC, e.start_time ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserOrders($user_id) {
    $database = new Database();
    $conn = $database->getConnection();
    
    $query = "SELECT o.*, tt.name as ticket_name, tt.price 
              FROM orders o 
              JOIN ticket_types tt ON o.ticket_type_id = tt.id 
              WHERE o.user_id = ? 
              ORDER BY o.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute([$user_id]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTicketHolders($order_id) {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Check if we have ticket holders information in the database
    $query = "SELECT * FROM ticket_holders WHERE order_id = ? ORDER BY is_main DESC, id ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute([$order_id]);
    $holders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($holders)) {
        // Generate QR codes for each holder if needed
        foreach ($holders as &$holder) {
            if (empty($holder['qr_code'])) {
                $holder['qr_code'] = generateQRCode("TICKET-" . $order_id . "-" . $holder['id'] . "-" . $holder['id_number']);
            }
        }
        return $holders;
    }
    
    // If no ticket holders found, fetch the order details and create entries
    $query = "SELECT o.*, u.username, u.email, u.first_name, u.last_name, u.id_number 
              FROM orders o 
              JOIN users u ON o.user_id = u.id 
              WHERE o.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($order) {
        $quantity = $order['quantity'];
        $holders = [];
        
        // Create main ticket holder (the user who purchased)
        $main_holder = [
            'id' => 1, // This would be set by the database auto-increment in reality
            'order_id' => $order_id,
            'first_name' => $order['first_name'],
            'last_name' => $order['last_name'],
            'id_number' => $order['id_number'], // Now pulling from database
            'is_main' => true,
            'qr_code' => generateQRCode("TICKET-" . $order_id . "-1-" . $order['id_number'])
        ];
        $holders[] = $main_holder;
        
        // Insert the main holder into the database
        saveTicketHolder($order_id, $order['first_name'], $order['last_name'], $order['id_number'], true);
        
        // If order quantity > 1, try to get additional ticket holders
        if ($quantity > 1) {
            $query = "SELECT * FROM ticket_holders WHERE order_id = ? AND is_main = 0";
            $stmt = $conn->prepare($query);
            $stmt->execute([$order_id]);
            $additional_holders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // If additional holders exist in database
            if (!empty($additional_holders)) {
                foreach ($additional_holders as $holder) {
                    if (empty($holder['qr_code'])) {
                        $holder['qr_code'] = generateQRCode("TICKET-" . $order_id . "-" . $holder['id'] . "-" . $holder['id_number']);
                    }
                    $holders[] = $holder;
                }
            }
            // If not enough holders found for order quantity, create placeholder holders
            $needed = $quantity - count($holders);
            for ($i = 0; $i < $needed; $i++) {
                $holder_id = count($holders) + 1;
                $holders[] = [
                    'id' => $holder_id,
                    'order_id' => $order_id,
                    'first_name' => 'Guest',
                    'last_name' => $holder_id,
                    'id_number' => 'N/A',
                    'is_main' => false,
                    'qr_code' => generateQRCode("TICKET-" . $order_id . "-" . $holder_id . "-" . time())
                ];
            }
        }
        
        return $holders;
    }
    
    return [];
}

function getAllOrders() {
    $database = new Database();
    $conn = $database->getConnection();
    
    $query = "SELECT o.*, u.username, u.email, tt.name as ticket_name 
              FROM orders o 
              JOIN users u ON o.user_id = u.id 
              JOIN ticket_types tt ON o.ticket_type_id = tt.id 
              ORDER BY o.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateOrderStatus($order_id, $status) {
    $database = new Database();
    $conn = $database->getConnection();
    
    $qr_code = null;
    if ($status === 'approved') {
        $qr_code = generateQRCode("TICKET-" . $order_id . "-" . time());
    }
    
    $query = "UPDATE orders SET status = ?, qr_code = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    return $stmt->execute([$status, $qr_code, $order_id]);
}

function getMerchandiseForHomepage($limit = 6) {
    try {
        // Create database connection
        $database = new Database();
        $conn = $database->getConnection();
        
        // Get featured merchandise from the database - using integer cast for safety
        $limit = (int)$limit; // Ensure it's an integer
        $query = "SELECT * FROM merch_items WHERE is_active = 1 ORDER BY id DESC LIMIT $limit";
        $stmt = $conn->prepare($query);
        $stmt->execute(); // No parameters needed as we directly inserted the limit
        $merch_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Process items to include additional information
        foreach ($merch_items as &$item) {
            // Check if item has size or color options
            $size_options = json_decode($item['size_options'] ?? '[]', true) ?: [];
            $color_options = json_decode($item['color_options'] ?? '[]', true) ?: [];
            $item['has_options'] = !empty($size_options) || !empty($color_options);
            
            // Check if item is in stock
            $item['in_stock'] = $item['available_quantity'] > 0;
        }
        
        return $merch_items;
    } catch (Exception $e) {
        // If there's an error (such as the table not existing), return an empty array
        // or you can log the error: error_log($e->getMessage());
        return [];
    }
}

function getImagePath($imagePath, $defaultImage = 'images/placeholder.jpg') {
    // Return default if image path is empty
    if (empty($imagePath)) {
        return $defaultImage;
    }
    
    // Remove any leading "../" from the path
    $cleanPath = ltrim($imagePath, './');
    $cleanPath = preg_replace('/^\.\.\//', '', $cleanPath);
    
    // Ensure the path starts from the web root
    if (!str_starts_with($cleanPath, 'uploads/')) {
        // If it doesn't start with uploads/, try to extract the uploads part
        if (strpos($cleanPath, 'uploads/') !== false) {
            $cleanPath = substr($cleanPath, strpos($cleanPath, 'uploads/'));
        } else {
            // If no uploads path found, assume it's just a filename and prepend uploads/merch/
            $cleanPath = 'uploads/merch/' . basename($cleanPath);
        }
    }
    
    // Check if file exists
    if (file_exists($cleanPath)) {
        return $cleanPath;
    }
    
    // Return default image if file doesn't exist
    return $defaultImage;
}

/**
 * Get merch image path specifically
 * 
 * @param string $imagePath The stored image path
 * @return string The corrected image path for merch display
 */
function getMerchImagePath($imagePath) {
    return getImagePath($imagePath, 'images/merch-placeholder.jpg');
}

/**
 * Get full URL for image (useful for absolute URLs)
 * 
 * @param string $imagePath The stored image path
 * @param string $defaultImage Default image if path is empty or invalid
 * @return string The full URL for the image
 */
function getImageUrl($imagePath, $defaultImage = 'images/placeholder.jpg') {
    $cleanPath = getImagePath($imagePath, $defaultImage);
    
    // Get the current protocol and host
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $baseUrl = $protocol . $host;
    
    // Get the directory of the current script
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    if ($scriptDir !== '/') {
        $baseUrl .= $scriptDir;
    }
    
    return $baseUrl . '/' . $cleanPath;
}
?>

?>