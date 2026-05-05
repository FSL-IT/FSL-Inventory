<?php
$current_page = basename($_SERVER['PHP_SELF']);

$base_url = '/src/views/';
?>

<nav 
  id="sidebar_nav" 
  class="d-flex flex-column flex-shrink-0 p-3 h-100" 
  style="width: 260px; background-color: var(--color-navy-dark);">
  
  <a href="<?php echo $base_url; ?>dashboard.php" 
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
      <a href="<?php echo $base_url; ?>dashboard.php" 
        class="nav-link sidebar-link <?php echo $current_page == 'dashboard.php' ? 'sidebar-link-active' : ''; ?>">
        <i class="bi bi-grid-1x2 me-2"></i> Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo $base_url; ?>assets.php" 
        class="nav-link sidebar-link <?php echo $current_page == 'assets.php' ? 'sidebar-link-active' : ''; ?>">
        <i class="bi bi-pc-display me-2"></i> Search Inventory
      </a>
    </li>
    <li class="nav-item">
      <a href="<?php echo $base_url; ?>purchase_orders.php" 
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
      <a href="<?php echo $base_url; ?>categories.php" 
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
        <a href="<?php echo $base_url; ?>admin/users.php" 
          class="nav-link sidebar-link <?php echo $current_page == 'users.php' ? 'sidebar-link-active' : ''; ?>">
          <i class="bi bi-people me-2"></i> User Management
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo $base_url; ?>admin/audit_logs.php" 
          class="nav-link sidebar-link <?php echo $current_page == 'audit_logs.php' ? 'sidebar-link-active' : ''; ?>">
          <i class="bi bi-clock-history me-2"></i> Audit History
        </a>
      </li>
      <li class="nav-item">
        <a href="<?php echo $base_url; ?>admin/backup.php" 
          class="nav-link sidebar-link <?php echo $current_page == 'backup.php' ? 'sidebar-link-active' : ''; ?>">
          <i class="bi bi-database-down me-2"></i> Backup & Restore
        </a>
      </li>
    </ul>
  <?php endif; ?>
</nav>