<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        // Use the absolute project path to avoid double-URL errors
        header('Location: /fsl-inventory/src/views/auth/login.php');
        exit();
    }
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        // If they aren't an admin, block access completely
        header('HTTP/1.1 403 Forbidden');
        exit('Access Denied: You must be an administrator to view this page.');
    }
}