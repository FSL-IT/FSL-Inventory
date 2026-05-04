<?php
require_once '../config/database.php';
require_once '../core/auth.php';
requireLogin();

header('Content-Type: application/json');
$db = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireAdmin(); // Only admins can add categories
    
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);
    $name = trim($data['name'] ?? '');

    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Category name is required.']);
        exit;
    }

    try {
        $stmt = $db->prepare("INSERT INTO categories (name) VALUES (:name)");
        $stmt->execute(['name' => $name]);
        
        if (ob_get_length()) ob_clean();
        echo json_encode(['success' => true, 'message' => 'Category added successfully.']);
        exit;
    } catch (PDOException $e) {
        if (ob_get_length()) ob_clean();
        // 23000 is the SQLSTATE for unique constraint violation
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'message' => 'Category already exists.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add category.']);
        }
        exit;
    }
}

try {
    $stmt = $db->query("SELECT id, name FROM categories ORDER BY name ASC");
    $categories = $stmt->fetchAll();

    if (ob_get_length()) ob_clean();
    echo json_encode(['success' => true, 'data' => $categories]);
} catch (Exception $e) {
    error_log("Categories API Error: " . $e->getMessage());
    if (ob_get_length()) ob_clean();
    echo json_encode(['success' => false, 'message' => 'Failed to load categories.']);
}