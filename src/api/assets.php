<?php
require_once '../config/database.php';
require_once '../core/auth.php';
requireLogin();

header('Content-Type: application/json');

try {
    $db = Database::getConnection();
    
    // Fetch assets and JOIN names from related tables
    $query = "
        SELECT 
            a.id, 
            a.serial_number, 
            a.description, 
            a.status, 
            c.name as category_name, 
            po.po_number, 
            l.name as location_name 
        FROM assets a
        LEFT JOIN categories c ON a.category_id = c.id
        LEFT JOIN purchase_orders po ON a.po_id = po.id
        LEFT JOIN locations l ON a.location_id = l.id
        WHERE a.deleted_at IS NULL
        ORDER BY a.created_at DESC
        LIMIT 50
    "; // Hard limit for safety, real app would use pagination $_GET variables

    $stmt = $db->query($query);
    $assets = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $assets]);

} catch (Exception $e) {
    error_log("Assets API Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to load assets.']);
}