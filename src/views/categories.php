<?php
// Fix: Go up only one directory level to reach src/core/
require_once '../core/auth.php';

// Ensure the user is logged in before rendering the page
requireLogin();

$pageTitle = 'Manage Categories';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssetTrack - <?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/css/variables.css" rel="stylesheet">
    <link href="../../assets/css/layout.css" rel="stylesheet">
    <link href="../../assets/css/components.css" rel="stylesheet">
    <link href="../../assets/css/modals.css" rel="stylesheet">
</head>
<body>
    <div id="app_shell" class="d-flex vh-100 overflow-hidden">
        
        <!-- Fix: Include shared components from the same 'views' directory -->
        <?php include 'shared/sidebar.php'; ?>

        <div class="d-flex flex-column flex-grow-1 overflow-hidden">
            
            <?php include 'shared/header.php'; ?>

            <main id="main_content" class="p-4 overflow-auto">
                
                <!-- Page Header Wrapper -->
                <div class="d-flex justify-content-between align-items-end mb-4">
                  <div>
                    <h3 class="text-brand-navy fw-bold mb-1" style="font-family: 'Syne', sans-serif;">
                      Manage Categories
                    </h3>
                    <div class="text-muted small">Add, edit, and organize inventory categories</div>
                  </div>
                  <div class="d-flex gap-2">
                    <button type="button" class="btn custom-btn px-4" id="btn_add_category">
                      <i class="bi bi-plus-lg me-1"></i> Add Category
                    </button>
                  </div>
                </div>

                <!-- Main Table Container (To be handled by categories.js) -->
                <div class="card bg-dark border-secondary mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0" id="category_table_main">
                                <thead>
                                    <tr class="text-uppercase text-muted small">
                                        <th>Category Name</th>
                                        <th>Total Assets</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="category_table_body">
                                    <!-- Dynamic content injected here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Include Modals -->
    <?php include 'modal/modal_category.php'; ?>
    <?php include 'modal/modal_confirm.php'; ?>

    <!-- External Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/app.js"></script>
    <script src="../../assets/js/categories.js"></script>
    <script src="../../assets/js/modal_handler.js"></script>
</body>
</html>