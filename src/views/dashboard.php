<?php
require_once '../core/auth.php';
requireLogin();

$pageTitle = 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssetTrack - Dashboard</title>
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

            <main id="main_content" class="p-4 overflow-auto">
                
                <!-- KPI Row -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="kpi-card">
                            <div class="kpi-icon-wrap kpi-icon-orange"><i class="bi bi-hexagon"></i></div>
                            <div class="text-muted text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.05em;">Total Assets</div>
                            <div class="kpi-value" id="kpi_total_assets">3,563</div>
                            <div class="small text-success mt-1">↑ 124 this month</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kpi-card">
                            <div class="kpi-icon-wrap kpi-icon-navy"><i class="bi bi-layout-text-window-reverse"></i></div>
                            <div class="text-muted text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.05em;">PO Records</div>
                            <div class="kpi-value" id="kpi_po_records">229</div>
                            <div class="small text-muted mt-1">Across 4 locations</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kpi-card">
                            <div class="kpi-icon-wrap kpi-icon-success"><i class="bi bi-check2"></i></div>
                            <div class="text-muted text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.05em;">Endorsed</div>
                            <div class="kpi-value" id="kpi_endorsed">218</div>
                            <div class="small text-success mt-1">95.2% rate</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="kpi-card">
                            <div class="kpi-icon-wrap kpi-icon-warning"><i class="bi bi-exclamation-circle"></i></div>
                            <div class="text-muted text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 0.05em;">Pending Endorse</div>
                            <div class="kpi-value" id="kpi_pending">11</div>
                            <div class="small text-danger mt-1">↓ Needs action</div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row g-3 mb-4">
                    <div class="col-lg-8">
                        <div class="dashboard-panel h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0" style="color: var(--color-navy);">Assets by category</h6>
                                <a href="#" style="color: var(--color-orange); font-size: 12px; text-decoration: none;">View all &rarr;</a>
                            </div>
                            <!-- Chart.js Canvas -->
                            <div style="height: 200px;">
                                <canvas id="chart_assets_bar"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="dashboard-panel h-100">
                            <h6 class="fw-bold mb-3" style="color: var(--color-navy);">Endorsement status</h6>
                            <div class="d-flex align-items-center justify-content-center" style="height: 200px;">
                                <canvas id="chart_endorse_donut"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent PO Entries Section -->
                <div class="card border-secondary mt-3" id="po_section">
                <div 
                    class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-black">Recent PO Entries</h5>
                    <button 
                    type="button" 
                    class="btn btn-outline-secondary btn-sm" 
                    id="btn_view_all">
                    View All &rarr;
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table table-white table-hover mb-0" id="po_table_main">
                        <thead>
                        <tr class="text-uppercase text-muted small">
                            <th>Description</th>
                            <th>PO Number</th>
                            <th>Qty</th>
                            <th>Location</th>
                            <th>Process Owner</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody id="recent_po_table">
                        <!-- Default Loading Spinner -->
                        <tr id="loading_spinner">
                            <td colspan="6" class="text-center py-4">
                            <div class="spinner-border text-warning" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Include FSL Modals -->
    <?php include 'modal/modals.php'; ?> <!-- Note the path matches [source: 5] -->

    <!-- External Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../assets/js/app.js"></script>
    <script src="../../assets/js/dashboard.js"></script>
    <script src="../../assets/js/modal_handler.js"></script>
</body>
</html>