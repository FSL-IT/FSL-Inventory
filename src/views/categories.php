<?php
require_once '../../core/auth.php';
requireLogin();
requireAdmin();
$pageTitle = 'Category Management';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssetTrack - Category Management</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../../assets/css/variables.css" rel="stylesheet">
    <link href="../../../assets/css/layout.css" rel="stylesheet">
    <link href="../../../assets/css/dashboard.css" rel="stylesheet">
    <link href="../../../assets/css/modals.css" rel="stylesheet">
</head>
<body>
    <div id="app_shell" class="d-flex vh-100 overflow-hidden">
        
        <?php include '../shared/sidebar.php'; ?>
        
        <div class="d-flex flex-column flex-grow-1 overflow-hidden">
            <?php include '../shared/header.php'; ?>

            <main id="main_content" class="p-4 overflow-auto flex-grow-1">
                <div class="row g-4">
                    <!-- Category List Panel -->
                    <div class="col-lg-8">
                        <div class="dashboard-panel p-0 overflow-hidden shadow-sm h-100">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                                <h6 class="fw-bold mb-0" style="color: var(--color-navy);">Active Categories</h6>
                                <span class="badge bg-secondary" id="category_count">0 Total</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-custom table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Category Name</th>
                                            <th>Date Added</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table_categories_body">
                                        <tr><td colspan="4" class="text-center text-muted p-4">Loading categories...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Add Category Form Panel -->
                    <div class="col-lg-4">
                        <div class="dashboard-panel shadow-sm border-0" style="background-color: var(--color-navy); color: white;">
                            <h6 class="fw-bold mb-3 border-bottom border-secondary pb-2"><i class="bi bi-plus-circle me-2 text-orange"></i>Add New Category</h6>
                            <p class="small text-muted mb-4">Categories added here will instantly become available in the Add Asset modal and the Sidebar filter.</p>
                            
                            <form id="form_add_category">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Category Name *</label>
                                    <input type="text" id="input_new_category" class="form-control custom-dark-textarea" placeholder="e.g. Server, Projector..." required>
                                </div>
                                <button type="submit" id="btn_save_category" class="btn custom-btn-orange w-100 mt-2">
                                    Save Category
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- External JS -->
    <script src="../../../assets/js/app.js"></script>
    <script src="../../../assets/js/categories.js"></script>
</body>
</html>