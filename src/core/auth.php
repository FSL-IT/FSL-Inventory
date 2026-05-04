<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isApiRequest() {
    return strpos($_SERVER['REQUEST_URI'], '/api/') !== false || 
           (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
}

function requireLogin() {
    if (!isLoggedIn()) {
        if (isApiRequest()) {
            header('HTTP/1.1 401 Unauthorized');
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Session expired. Please log in again.']);
            exit();
        } else {
            $basePath = '/' . explode('/', trim($_SERVER['REQUEST_URI'], '/'))[0];
            header("Location: $basePath/src/views/auth/login.php");
            exit();
        }
    }
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        if (isApiRequest()) {
            header('HTTP/1.1 403 Forbidden');
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Access Denied: Admins only.']);
            exit();
        } else {
            header('HTTP/1.1 403 Forbidden');
            exit('Access Denied: You must be an administrator.');
        }
    }
}