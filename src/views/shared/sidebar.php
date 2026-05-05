<?php
$current_page = basename($_SERVER['PHP_SELF']);
$is_admin_route = strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false;
$base_path = $is_admin_route ? '../' : './';
?>

<nav id="sidebar_nav" class="sidebar-container d-flex flex-column flex-shrink-0 p-3 h-100">
  
  <a href="<?php echo $base_path; ?>dashboard.php" 
    class="d-flex align-items-center mb-4 text-white text-decoration-none">
    <div class="brand-icon me-3 d-flex align-items-center justify-content-center fw-bold">
      IT
    </div>
    <span class="fs-5 fw-bold" style="font-family: 'Syne', sans-serif;">
      AssetTrack
    </span>
  </a>
  
  <div class="sidebar-heading text-muted text-uppercase mb-2 px-2">Main</div>
  <ul class="nav flex-column mb-4 gap-1">
    <li class="nav-item">
      <a href="<?php echo $base_path; ?>dashboard.php" 
        class="nav-link sidebar-link <?php echo $current_page == 'dashboard.php' ? 'sidebar-link-active' : ''; ?>">
        <i class="bi bi-grid-1x2 me-2"></i> Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo $base_path; ?>assets.php" 
        class="nav-link sidebar-link <?php echo $current_page == 'assets.php' ? 'sidebar-link-active' : ''; ?>">
        <i class="bi bi-pc-display me-2"></i> Search Inventory
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo $base_path; ?>purchase_orders.php" 
        class="nav-link sidebar-link <?php echo $current_page == 'purchase_orders.php' ? 'sidebar-link-active' : ''; ?>">
        <i class="bi bi-receipt me-2"></i> PO Tracker
      </a>
    </li>
  </ul>

  <div class="sidebar-heading text-muted text-uppercase mb-2 px-2">
    Categories
  </div>
  <ul class="nav flex-column mb-4 gap-1">
    
    <li class="nav-item">
      <div id="btn_toggle_categories" class="nav-link sidebar-link" style="cursor: pointer;">
        <i class="bi bi-filter-left me-2"></i> 
        <span class="flex-grow-1">Filter by category</span>
        <i id="icon_category_caret" class="bi bi-caret-down-fill" style="font-size: 10px;"></i>
      </div>
      <div id="menu_categories" class="category-menu d-flex mt-1">
          </div>
    </li>

    <li class="nav-item mt-2">
      <a href="<?php echo $base_path; ?>categories.php" 
        class="nav-link sidebar-link <?php echo $current_page == 'categories.php' ? 'sidebar-link-active' : ''; ?>">
        <i class="bi bi-tags me-2"></i> Manage Categories
      </a>
    </li>
  </ul>

  <?php if (function_exists('isAdmin') && isAdmin()): ?>
    <div class="sidebar-heading text-muted text-uppercase mb-2 px-2 mt-auto">
      System
    </div>
    <ul class="nav flex-column gap-1">
      <li class="nav-item">
        <a href="<?php echo $base_path; ?>admin/users.php" 
          class="nav-link sidebar-link <?php echo $current_page == 'users.php' ? 'sidebar-link-active' : ''; ?>">
          <i class="bi bi-people me-2"></i> User Management
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo $base_path; ?>admin/audit_logs.php" 
          class="nav-link sidebar-link <?php echo $current_page == 'audit_logs.php' ? 'sidebar-link-active' : ''; ?>">
          <i class="bi bi-clock-history me-2"></i> Audit History
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo $base_path; ?>admin/backup.php" 
          class="nav-link sidebar-link <?php echo $current_page == 'backup.php' ? 'sidebar-link-active' : ''; ?>">
          <i class="bi bi-database-down me-2"></i> Backup & Restore
        </a>
      </li>
    </ul>
  <?php endif; ?>
</nav>