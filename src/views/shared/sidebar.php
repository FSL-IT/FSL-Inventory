<nav id="main_sidebar" class="sidebar-wrapper flex-shrink-0">
  <div class="sidebar-brand d-flex align-items-center gap-2">
    <div class="brand-icon">IT</div>
    <div>
      <div class="fw-bold text-white lh-1">AssetTrack</div>
      <div class="small" style="color: rgba(255,255,255,0.4); font-size: 10px;">PO Inventory System</div>
    </div>
  </div>
  
  <div class="px-3 mt-3 mb-2">
    <span class="sidebar-role-badge text-uppercase"><?= htmlspecialchars($_SESSION['role']) ?></span>
  </div>

  <div class="flex-grow-1 overflow-auto mt-2">
    <div class="sidebar-section-title">Main</div>
    <a href="/fsl-inventory/src/views/dashboard.php" class="sidebar-nav-item active">
      <i class="bi bi-grid-fill"></i> Dashboard
    </a>
    <a href="/fsl-inventory/src/views/assets.php" class="sidebar-nav-item">
      <i class="bi bi-box-seam"></i> Inventory
      <span class="sidebar-badge">229</span>
    </a>

    <div class="sidebar-section-title mt-3">Categories</div>
    <div class="sidebar-nav-item" id="btn_toggle_categories">
      <i class="bi bi-filter-left"></i> Filter by category
      <i class="bi bi-caret-right-fill ms-auto small transition" id="icon_category_caret"></i>
    </div>
    
    <div class="category-menu" id="menu_categories">
      <a href="#" class="category-item">Laptop</a>
      <a href="#" class="category-item">Monitor</a>
      <a href="#" class="category-item">Desktop</a>
      <a href="#" class="category-item">Headset</a>
    </div>

    <?php if (isAdmin()): ?>
      <div class="sidebar-section-title mt-3">Admin</div>
      <a href="/fsl-inventory/src/views/admin/audit_logs.php" class="sidebar-nav-item">
        <i class="bi bi-clock-history"></i> Audit History
        <span class="sidebar-badge">14</span>
      </a>
      <a href="/fsl-inventory/src/views/admin/users.php" class="sidebar-nav-item">
        <i class="bi bi-people"></i> User Management
      </a>
      <a href="/fsl-inventory/src/views/admin/categories.php" class="sidebar-nav-item">
        <i class="bi bi-tags"></i> Category Mgmt
      </a>
      <a href="#" class="sidebar-nav-item">
        <i class="bi bi-cloud-download"></i> Export / Import
      </a>
    <?php endif; ?>
  </div>

  <div class="p-3 border-top" style="border-color: rgba(255,255,255,0.08) !important;">
    <div class="d-flex align-items-center gap-2">
      <div class="brand-icon rounded-circle bg-orange text-white" style="width: 32px; height: 32px; font-size: 12px;">
        <?= strtoupper(substr($_SESSION['username'], 0, 2)) ?>
      </div>
      <div class="lh-sm">
        <div class="text-white fw-bold" style="font-size: 12px;"><?= htmlspecialchars($_SESSION['username']) ?></div>
        <div style="color: rgba(255,255,255,0.38); font-size: 10px;"><?= isAdmin() ? 'Administrator' : 'Standard User' ?></div>
      </div>
    </div>
  </div>
  
  <div class="mt-auto px-3 mb-2">
    <!-- Logout Button -->
    <a href="#" id="btn_logout" class="sidebar-nav-item text-danger" style="margin: 0; padding: 10px 12px;">
      <i class="bi bi-box-arrow-left"></i> Logout
    </a>
  </div>

  <div class="p-3 border-top" style="border-color: rgba(255,255,255,0.08) !important;">
    <div class="d-flex align-items-center gap-2">
      <div class="brand-icon rounded-circle bg-orange text-white" style="width: 32px; height: 32px; font-size: 12px;">
        <?= strtoupper(substr($_SESSION['username'], 0, 2)) ?>
      </div>
      <div class="lh-sm">
        <div class="text-white fw-bold" style="font-size: 12px;"><?= htmlspecialchars($_SESSION['username']) ?></div>
        <div style="color: rgba(255,255,255,0.38); font-size: 10px;"><?= isAdmin() ? 'Administrator' : 'Standard User' ?></div>
      </div>
    </div>
  </div>
</nav>
</nav>