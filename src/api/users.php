<?php
require_once '../config/database.php';
require_once '../core/auth.php';
requireLogin();
requireAdmin(); // CRITICAL: Only admins can access this API

header('Content-Type: application/json');

try {
    $db = Database::getConnection();

    // Handle POST Request (Add New User)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);

        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');
        $role = $data['role'] ?? 'user';

        if (empty($username) || empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
            exit;
        }

        // Check if username already exists
        $stmt = $db->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Username already exists.']);
            exit;
        }

        // Hash the password securely
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $stmt = $db->prepare("INSERT INTO users (username, password_hash, role) VALUES (:username, :password_hash, :role)");
        $stmt->execute([
            'username' => $username,
            'password_hash' => $passwordHash,
            'role' => $role
        ]);

        echo json_encode(['success' => true, 'message' => 'User created successfully.']);
        exit;
    }

    // Handle DELETE Request (Soft Delete / Deactivate)
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if (!$userId || $userId === $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID or cannot delete yourself.']);
            exit;
        }

        $stmt = $db->prepare("UPDATE users SET deleted_at = CURRENT_TIMESTAMP WHERE id = :id");
        $stmt->execute(['id' => $userId]);

        echo json_encode(['success' => true, 'message' => 'User deactivated.']);
        exit;
    }

    // Handle GET Request (Fetch all active users)
    $query = "
        SELECT id, username, role, created_at 
        FROM users 
        WHERE deleted_at IS NULL 
        ORDER BY role ASC, username ASC
    ";
    
    $stmt = $db->query($query);
    $users = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $users]);

} catch (Exception $e) {
    error_log("Users API Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database operation failed.']);
}