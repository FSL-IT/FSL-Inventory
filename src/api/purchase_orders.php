<?php
require_once '../config/database.php';
require_once '../core/auth.php';
requireLogin();

header('Content-Type: application/json');

try {
    $db = Database::getConnection();

    // Handle POST Request (Add New PO)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);

        $poNumber = trim($data['po_number'] ?? '');
        $vendorId = filter_var($data['vendor_id'] ?? null, FILTER_VALIDATE_INT);
        $dateReceived = !empty($data['date_received']) ? $data['date_received'] : null;
        $dateEndorsed = !empty($data['date_endorsed']) ? $data['date_endorsed'] : null;

        if (empty($poNumber) || !$vendorId) {
            echo json_encode(['success' => false, 'message' => 'PO Number and Vendor are required.']);
            exit;
        }

        $stmt = $db->prepare("INSERT INTO purchase_orders (po_number, vendor_id, date_received, date_endorsed) VALUES (:po_number, :vendor_id, :date_received, :date_endorsed)");
        $stmt->execute([
            'po_number' => $poNumber,
            'vendor_id' => $vendorId,
            'date_received' => $dateReceived,
            'date_endorsed' => $dateEndorsed
        ]);

        echo json_encode(['success' => true, 'message' => 'Purchase Order created successfully.']);
        exit;
    }

    // Handle GET Request (Fetch POs for Tracker)
    $query = "
        SELECT 
            po.id, po.po_number, po.date_received, po.date_endorsed,
            v.name as vendor_name
        FROM purchase_orders po
        LEFT JOIN vendors v ON po.vendor_id = v.id
        ORDER BY po.created_at DESC
    ";
    
    $stmt = $db->query($query);
    $pos = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $pos]);

} catch (Exception $e) {
    error_log("Purchase Orders API Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database operation failed.']);
}