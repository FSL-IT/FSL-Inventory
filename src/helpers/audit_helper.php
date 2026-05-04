<?php
require_once __DIR__ . '/../config/database.php';

function logAudit($userId, $action, $tableName, $recordId, $changes) {
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO audit_logs (user_id, action, table_name, record_id, changes, ip_address) 
            VALUES (:user_id, :action, :table_name, :record_id, :changes, :ip_address)
        ");
        
        $stmt->execute([
            'user_id' => $userId,
            'action' => $action, // 'INSERT', 'UPDATE', 'DELETE'
            'table_name' => $tableName,
            'record_id' => $recordId,
            'changes' => json_encode($changes),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
        ]);
    } catch (Exception $e) {
        // Silently log error so it doesn't break the main application flow
        error_log("Audit Log Failed: " . $e->getMessage());
    }
}