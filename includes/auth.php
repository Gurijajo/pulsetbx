<?php
session_start();
require_once __DIR__ . '/../config/database.php';

class Auth {
    private $conn;
    private $session_lifetime = 1800; // 30 minutes in seconds
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        
        // Set secure session parameters
        ini_set('session.cookie_httponly', 1); // Prevent JavaScript access to session cookie
        ini_set('session.use_only_cookies', 1); // Force sessions to only use cookies
        
        // Check if session has expired
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $this->session_lifetime)) {
            $this->logout();
        }
        
        // Update last activity time
        $_SESSION['last_activity'] = time();
    }
    
    public function register($username, $email, $password, $first_name, $last_name, $phone, $id_number = '') {
        $query = "INSERT INTO users (username, email, password, first_name, last_name, phone, id_number) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        return $stmt->execute([$username, $email, $hashed_password, $first_name, $last_name, $phone, $id_number]);
    }
    
    public function login($username, $password) {
        $query = "SELECT id, username, email, password, first_name, last_name, is_admin FROM users WHERE username = ? OR email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username, $username]);
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['last_activity'] = time(); // Set initial last activity time
                return true;
            }
        }
        return false;
    }
    
    public function logout() {
        // Clear all session variables
        $_SESSION = array();
        
        // Destroy the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();
        
        header("Location: login.php");
        exit();
    }
    
    public function isLoggedIn() {
        // Check if session exists and hasn't expired
        if (isset($_SESSION['user_id']) && isset($_SESSION['last_activity'])) {
            // Update last activity time
            $_SESSION['last_activity'] = time();
            return true;
        }
        return false;
    }
    
    public function isAdmin() {
        return $this->isLoggedIn() && isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
    }
    
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header("Location: login.php");
            exit();
        }
    }
    
    public function requireAdmin() {
        $this->requireLogin();
        if (!$this->isAdmin()) {
            header("Location: index.php");
            exit();
        }
    }
    
    // Method to check if session is about to expire (e.g., within 5 minutes)
    public function isSessionAboutToExpire() {
        if (isset($_SESSION['last_activity'])) {
            $remaining = $this->session_lifetime - (time() - $_SESSION['last_activity']);
            return $remaining < 300; // Less than 5 minutes remaining
        }
        return false;
    }
    
    // Method to extend session duration
    public function extendSession() {
        $_SESSION['last_activity'] = time();
        return true;
    }
}
?>