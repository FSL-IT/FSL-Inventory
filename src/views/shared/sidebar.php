<nav id="main_sidebar" class="sidebar-wrapper flex-shrink-0">
  <!-- Logo Section -->
  <div class="sidebar-brand d-flex align-items-center gap-3">
    <div class="brand-icon">IT</div>
    <div>
      <div class="fw-bold text-white fs-6 lh-1">AssetTrack</div>
      <div class="small mt-1" style="color: rgba(255,255,255,0.4); font-size: 11px;">PO Inventory System</div>
    </div>
  </div>
  
  <div class="px-4 mt-3 mb-2">
    <span class="sidebar-role-badge"><?= strtoupper(htmlspecialchars($_SESSION['role'])) ?></span>
  </div>

  <!-- Navigation Links -->
  <div class="flex-grow-1 overflow-auto mt-2 pb-4">
    
    <div class="sidebar-section-title">Main</div>
    <a href="/fsl-inventory/src/views/dashboard.php" class="sidebar-nav-item">
      <i class="bi bi-grid-fill fs-5"></i> Dashboard
    </a>
    <a href="/fsl-inventory/src/views/assets.php" class="sidebar-nav-item">
      <i class="bi bi-list-task fs-5"></i> Inventory
      <span class="sidebar-badge">229</span>
    </a>

    <<div class="sidebar-section-title mt-2">Categories</div>
    <div class="sidebar-nav-item" id="btn_toggle_categories">
      <i class="bi bi-filter-left fs-5"></i> Filter by category
      <i class="bi bi-caret-right-fill ms-auto small transition" id="icon_category_caret"></i>
    </div>
    
    <!-- This will be populated by app.js fetching from database -->
    <div class="category-menu d-none" id="menu_categories">
        <div class="text-muted small ps-3 py-2">Loading...</div>
    </div>

    <!-- Admin Section -->
    <?php if (isAdmin()): ?>
      <div class="sidebar-section-title mt-2">Admin</div>
      <a href="/fsl-inventory/src/views/admin/audit_logs.php" class="sidebar-nav-item">
        <i class="bi bi-clock-history fs-5"></i> Audit History
        <span class="sidebar-badge bg-secondary">14</span>
      </a>
      <a href="/fsl-inventory/src/views/admin/users.php" class="sidebar-nav-item">
        <i class="bi bi-person-fill fs-5"></i> User Management
      </a>
      <a href="/fsl-inventory/src/views/admin/categories.php" class="sidebar-nav-item">
        <i class="bi bi-hexagon fs-5"></i> Category Mgmt
      </a>
      <a href="#" class="sidebar-nav-item">
        <i class="bi bi-upload fs-5"></i> Export / Import
      </a>
    <?php endif; ?>
  </div>

  <!-- Logout Fixed at Bottom -->
  <div class="mt-auto border-top" style="border-color: rgba(255,255,255,0.05) !important;">
    <a href="/fsl-inventory/src/api/logout.php" class="sidebar-nav-item py-4 text-muted" style="border-radius: 0; margin: 0;">
      <i class="bi bi-box-arrow-left fs-5"></i> Logout
    </a>
  </div>
</nav>