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
                <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h3 class="text-white fw-bold mb-1" style="font-family: 'Syne', sans-serif;">
                    Inventory Search
                    </h3>
                    <div class="text-muted small">Manage and track all physical IT assets</div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary custom-input">
                    <i class="bi bi-download me-1"></i> Export
                    </button>
                    <button type="button" class="btn custom-btn px-4" id="btn_add_asset">
                    <i class="bi bi-plus-lg me-1"></i> Add Asset
                    </button>
                </div>
                </div>

                <!-- Search & Filter Toolbar -->
                <div class="card bg-dark border-secondary mb-4">
                <div class="card-body p-3">
                    <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                        <span class="input-group-text custom-input border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input 
                            type="text" 
                            id="search_input"
                            class="form-control custom-input border-start-0" 
                            placeholder="Search by serial, description, or PO..." />
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center gap-2 overflow-auto">
                        <span class="badge rounded-pill custom-input px-3 py-2 border border-warning text-warning" style="cursor: pointer;">
                        All Locations
                        </span>
                        <span class="badge rounded-pill custom-input px-3 py-2" style="cursor: pointer;">
                        Science Hub 1
                        </span>
                        <span class="badge rounded-pill custom-input px-3 py-2" style="cursor: pointer;">
                        Science Hub 2
                        </span>
                    </div>
                    </div>
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