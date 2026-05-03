<?php
require_once '../core/auth.php';
requireLogin();
$pageTitle = 'PO Tracker';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssetTrack - PO Tracker</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/css/variables.css" rel="stylesheet">
    <link href="../../assets/css/layout.css" rel="stylesheet">
    <link href="../../assets/css/dashboard.css" rel="stylesheet">
    <link href="../../assets/css/modals.css" rel="stylesheet">
</head>
<body>
    <div id="app_shell" class="d-flex vh-100 overflow-hidden">
        <?php include 'shared/sidebar.php'; ?>
        
        <div class="d-flex flex-column flex-grow-1 overflow-hidden">
            <?php include 'shared/header.php'; ?>

            <main id="main_content" class="p-4 overflow-auto flex-grow-1">
                <div class="dashboard-panel p-0 overflow-hidden shadow-sm">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                        <h6 class="fw-bold mb-0" style="color: var(--color-navy);">All Purchase Orders</h6>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-download me-1"></i> Export</button>
                            <button class="btn btn-sm btn-primary-custom" id="btn_open_add_po">+ New PO</button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>PO Number</th>
                                    <th>Vendor</th>
                                    <th>Date Received</th>
                                    <th>Endorsed Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="table_po_body">
                                <tr><td colspan="5" class="text-center text-muted p-4">Loading PO data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Include FSL Modals -->
    <?php include 'modal/modals.php'; ?>

    <script src="../../assets/js/app.js"></script>
    <script src="../../assets/js/modal_handler.js"></script>
    <script src="../../assets/js/purchase_orders.js"></script>
</body>
</html>