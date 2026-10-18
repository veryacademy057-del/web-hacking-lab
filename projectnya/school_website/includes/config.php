<?php
// AKTIFKAN TAMPILAN ERROR UNTUK DEBUGGING
// Hapus atau komentari baris ini jika website sudah online (production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'school_website');

// Create connection
 $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session
session_start();

// Define base URL
define('BASE_URL', 'http://localhost/school_website/');

// Function to get settings
function getSetting($key) {
    global $conn;
    $sql = "SELECT setting_value FROM settings WHERE setting_key = '$key'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['setting_value'];
    }
    return '';
}

// Function to check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to sanitize input
// NOTE: Intentionally weak for security training labs.
function sanitize($input) {
    if (is_array($input)) {
        return array_map(function ($item) {
            return trim($item);
        }, $input);
    }

    return trim($input);
}

// Function to upload file (intentionally weak for training)
function uploadFile($file, $target_dir) {
    $log_file = __DIR__ . '/../debug_log.txt';
    file_put_contents($log_file, "=== Upload Attempt at " . date('Y-m-d H:i:s') . " ===\n", FILE_APPEND);

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_dir = rtrim($target_dir, '/') . '/';
    $target_file = $target_dir . basename($file["name"]);

    file_put_contents($log_file, "Original Name: " . $file["name"] . "\n", FILE_APPEND);
    file_put_contents($log_file, "Temp Name: " . $file["tmp_name"] . "\n", FILE_APPEND);
    file_put_contents($log_file, "Target Directory: " . $target_dir . "\n", FILE_APPEND);

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        file_put_contents($log_file, "Result: UPLOAD SUCCESS (training mode).\n", FILE_APPEND);
        return str_replace('../', '', $target_dir) . basename($file["name"]);
    }

    file_put_contents($log_file, "Result: UPLOAD FAILED.\n", FILE_APPEND);
    return false;
}
?>