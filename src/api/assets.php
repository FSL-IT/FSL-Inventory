<?php
require_once '../config/database.php';
require_once '../core/auth.php';
requireLogin();

header('Content-Type: application/json');

$db = Database::getConnection();

// --- Handle POST Request (Add New Asset) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    $description = trim($data['description'] ?? '');
    $categoryId = filter_var($data['category_id'] ?? null, FILTER_VALIDATE_INT);
    $poId = filter_var($data['po_id'] ?? null, FILTER_VALIDATE_INT);
    $locationId = filter_var($data['location_id'] ?? null, FILTER_VALIDATE_INT);
    $ownerId = filter_var($data['owner_id'] ?? null, FILTER_VALIDATE_INT);
    $vendorId = filter_var($data['vendor_id'] ?? null, FILTER_VALIDATE_INT);
    $remarks = trim($data['remarks'] ?? '');
    $serialsRaw = trim($data['serials'] ?? '');

    if (empty($description) || !$categoryId || empty($serialsRaw)) {
        echo json_encode(['success' => false, 'message' => 'Description, Category, and at least one Serial Number are required.']);
        exit;
    }

    // Split textarea serials by newline and remove empty ones
    $serialArray = array_filter(array_map('trim', explode("\n", $serialsRaw)));

    if (empty($serialArray)) {
        echo json_encode(['success' => false, 'message' => 'Valid serial numbers are required.']);
        exit;
    }

    try {
        $db->beginTransaction();

        $stmt = $db->prepare("
            INSERT INTO assets (serial_number, description, po_id, category_id, location_id, owner_id, remarks, status) 
            VALUES (:serial, :description, :po_id, :category_id, :location_id, :owner_id, :remarks, 'active')
        ");

        $insertedCount = 0;
        foreach ($serialArray as $serial) {
            $stmt->execute([
                'serial' => $serial,
                'description' => $description,
                'po_id' => $poId ?: null, // null if empty
                'category_id' => $categoryId,
                'location_id' => $locationId ?: null,
                'owner_id' => $ownerId ?: null,
                'remarks' => $remarks
            ]);
            $insertedCount++;
        }

        $db->commit();
        echo json_encode(['success' => true, 'message' => "$insertedCount asset(s) added successfully."]);
        exit;

    } catch (PDOException $e) {
        $db->rollBack();
        // Check for duplicate serial number error (SQLSTATE 23000)
        if ($e->getCode() == 23000) {
            echo json_encode(['success' => false, 'message' => 'One or more of these serial numbers already exists in the system.']);
        } else {
            error_log("Add Asset Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Failed to add assets due to database error.']);
        }
        exit;
    }
}

// --- Handle PUT Request (Edit Remarks) ---
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $remarks = trim($data['remarks'] ?? '');

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Asset ID required.']);
        exit;
    }

    try {
        $stmt = $db->prepare("UPDATE assets SET remarks = :remarks WHERE id = :id");
        $stmt->execute(['remarks' => $remarks, 'id' => $id]);
        
        echo json_encode(['success' => true, 'message' => 'Remarks updated successfully.']);
        exit;
    } catch (Exception $e) {
        error_log("Update Remarks Error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Failed to update remarks.']);
        exit;
    }
}

// --- Handle GET Request (Fetch Inventory Table) ---
try {
    $query = "
        SELECT 
            a.id, a.serial_number, a.description, a.status, 
            c.name as category_name, po.po_number, l.name as location_name 
        FROM assets a
        LEFT JOIN categories c ON a.category_id = c.id
        LEFT JOIN purchase_orders po ON a.po_id = po.id
        LEFT JOIN locations l ON a.location_id = l.id
        WHERE a.deleted_at IS NULL
        ORDER BY a.created_at DESC
    "; 

    $stmt = $db->query($query);
    $assets = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $assets]);
} catch (Exception $e) {
    error_log("Assets API Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to load assets.']);
}   