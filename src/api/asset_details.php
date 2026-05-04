<?php
require_once '../config/database.php';
require_once '../core/auth.php';
requireLogin();

header('Content-Type: application/json');

$assetId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$assetId) {
    echo json_encode(['success' => false, 'message' => 'Invalid asset ID.']);
    exit;
}

try {
    $db = Database::getConnection();
    
    // Fetch Main Asset Details
    $query = "
        SELECT 
            a.id, a.serial_number, a.description, a.remarks, a.status, a.po_id, a.category_id,
            c.name as category_name, 
            po.po_number, po.date_received, po.date_endorsed,
            v.name as vendor_name,
            l.name as location_name,
            p_own.name as owner_name
        FROM assets a
        LEFT JOIN categories c ON a.category_id = c.id
        LEFT JOIN purchase_orders po ON a.po_id = po.id
        LEFT JOIN vendors v ON po.vendor_id = v.id
        LEFT JOIN locations l ON a.location_id = l.id
        LEFT JOIN process_owners p_own ON a.owner_id = p_own.id
        WHERE a.id = :id AND a.deleted_at IS NULL
        LIMIT 1
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute(['id' => $assetId]);
    $asset = $stmt->fetch();

    if ($asset) {
        // Fetch Sibling Serials (Same PO, Same Category) to populate the chip grid
        $siblingQuery = "
            SELECT id, serial_number 
            FROM assets 
            WHERE po_id = :po_id AND category_id = :cat_id AND deleted_at IS NULL
        ";
        $sibStmt = $db->prepare($siblingQuery);
        $sibStmt->execute([
            'po_id' => $asset['po_id'],
            'cat_id' => $asset['category_id']
        ]);
        $asset['sibling_serials'] = $sibStmt->fetchAll();

        // Calculate total quantity for this specific PO/Category batch
        $asset['total_quantity'] = count($asset['sibling_serials']);

        echo json_encode(['success' => true, 'data' => $asset]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Asset not found.']);
    }

} catch (Exception $e) {
    error_log("Asset Details API Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}