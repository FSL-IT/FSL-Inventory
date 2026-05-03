<?php
require_once '../config/database.php';

header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

// Get the raw POST data (since frontend sends JSON)
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
    exit();
}

try {
    $db = Database::getConnection();
    
    // Fetch user where deleted_at is null (active users only)
    $stmt = $db->prepare("SELECT id, username, password_hash, role FROM users WHERE username = :username AND deleted_at IS NULL LIMIT 1");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // Verify password using bcrypt
    if ($user && password_verify($password, $user['password_hash'])) {
        
        // Start session and regenerate ID to prevent session fixation
        session_start();
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        echo json_encode([
            'success' => true, 
            'message' => 'Login successful',
            'role' => $user['role']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials or inactive account.']);
    }

} catch (Exception $e) {
    error_log("Login Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred during authentication.']);
}