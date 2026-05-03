<?php
require_once '../core/auth.php';
requireLogin();
$pageTitle = 'Inventory';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssetTrack - Inventory</title>
    <!-- Use Bootstrap Bundle JS for the Dropdown in header -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/css/variables.css" rel="stylesheet">
    <link href="../../assets/css/layout.css" rel="stylesheet">
    <link href="../../assets/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <div id="app_shell" class="d-flex vh-100 overflow-hidden">
        <?php include 'shared/sidebar.php'; ?>
        
        <div class="d-flex flex-column flex-grow-1 overflow-hidden">
            <?php include 'shared/header.php'; ?>

            <main id="main_content" class="p-4 overflow-auto">
                <div class="dashboard-panel p-0 overflow-hidden shadow-sm">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                        <h6 class="fw-bold mb-0" style="color: var(--color-navy);">All Assets</h6>
                        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-download me-1"></i> Export CSV</button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Serial #</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>PO Number</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="table_inventory_body">
                                <tr><td colspan="6" class="text-center text-muted p-4">Loading inventory data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="../../assets/js/assets.js"></script>
</body>
</html>