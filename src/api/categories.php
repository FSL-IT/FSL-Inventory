<?php
require_once '../config/database.php';
require_once '../core/auth.php';
requireLogin();

header('Content-Type: application/json');

try {
    $db = Database::getConnection();
    $stmt = $db->query("SELECT id, name FROM categories ORDER BY name ASC");
    $categories = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $categories]);
} catch (Exception $e) {
    error_log("Categories API Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to load categories.']);
}