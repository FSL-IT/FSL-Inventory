<?php
require_once '../config/database.php';
require_once '../core/auth.php';
requireLogin();

header('Content-Type: application/json');

try {
    $db = Database::getConnection();

    // 1. Total Assets
    $stmt = $db->query("SELECT COUNT(*) as total FROM assets WHERE deleted_at IS NULL");
    $totalAssets = $stmt->fetch()['total'];

    // 2. Endorsed / Active (combining active and deployed)
    $stmt = $db->query("SELECT COUNT(*) as active_total FROM assets WHERE status IN ('active', 'deployed') AND deleted_at IS NULL");
    $activeAssets = $stmt->fetch()['active_total'];

    // 3. Pending / Defective
    $stmt = $db->query("SELECT COUNT(*) as attention_total FROM assets WHERE status IN ('defective', 'in_repair') AND deleted_at IS NULL");
    $attentionAssets = $stmt->fetch()['attention_total'];

    // 4. Total POs
    $stmt = $db->query("SELECT COUNT(*) as total_pos FROM purchase_orders");
    $totalPOs = $stmt->fetch()['total_pos'];

    // 5. Assets Grouped by Category (For the Bar Chart)
    $stmt = $db->query("
        SELECT c.name as category, COUNT(a.id) as count 
        FROM categories c 
        LEFT JOIN assets a ON c.id = a.category_id AND a.deleted_at IS NULL 
        GROUP BY c.id 
        ORDER BY count DESC 
        LIMIT 8
    ");
    $categoriesData = $stmt->fetchAll();

    // 6. Recent POs (For the Dashboard Table)
    $stmt = $db->query("
        SELECT po.po_number, v.name as vendor_name, po.date_received, po.date_endorsed 
        FROM purchase_orders po 
        LEFT JOIN vendors v ON po.vendor_id = v.id 
        ORDER BY po.created_at DESC 
        LIMIT 5
    ");
    $recentPOs = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'kpis' => [
            'total_assets' => $totalAssets,
            'active_assets' => $activeAssets,
            'attention_assets' => $attentionAssets,
            'total_pos' => $totalPOs
        ],
        'charts' => [
            'categories' => $categoriesData
        ],
        'recent_pos' => $recentPOs
    ]);

} catch (Exception $e) {
    error_log("Dashboard API Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to load dashboard data.']);
}