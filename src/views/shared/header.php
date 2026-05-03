<header id="main_header" class="header-wrapper d-flex align-items-center justify-content-between">
  <!-- Page Title & Breadcrumb -->
  <div class="d-flex align-items-baseline gap-2">
    <h5 class="mb-0 fw-bold" style="color: var(--color-navy);"><?= isset($pageTitle) ? $pageTitle : 'Dashboard' ?></h5>
  </div>

  <!-- Actions & Profile -->
  <div class="d-flex align-items-center gap-3">
    
    <!-- Search -->
    <div class="position-relative">
      <i class="bi bi-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%);"></i>
      <input type="text" id="input_global_search" class="header-search" placeholder="Search PO#, serial, description...">
    </div>
    
    <!-- Notification -->
    <button class="btn btn-light rounded-circle border position-relative" style="width: 40px; height: 40px;">
      <i class="bi bi-bell text-muted"></i>
      <span class="position-absolute top-0 start-100 translate-middle p-1 bg-orange border border-light rounded-circle" style="margin-top: 8px; margin-left: -8px;">
        <span class="visually-hidden">New alerts</span>
      </span>
    </button>
    
    <!-- Add Asset Button -->
    <button id="btn_add_asset" class="btn-primary-custom">
      + Add Asset
    </button>

    <!-- Profile Dropdown (Moved from Sidebar) -->
    <div class="dropdown ms-2">
      <div class="d-flex align-items-center gap-2 cursor-pointer" id="dropdown_profile" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
        <div class="brand-icon rounded-circle bg-orange text-white" style="width: 40px; height: 40px; font-size: 14px;">
          <?= strtoupper(substr($_SESSION['username'], 0, 2)) ?>
        </div>
        <div class="lh-1 d-none d-md-block">
          <div class="fw-bold text-navy" style="font-size: 13px;"><?= htmlspecialchars($_SESSION['username']) ?></div>
          <div class="text-muted" style="font-size: 11px;"><?= isAdmin() ? 'Administrator' : 'User' ?></div>
        </div>
      </div>
      
      <!-- Dropdown Menu -->
      <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="dropdown_profile">
        <li><a class="dropdown-item small py-2" href="/fsl-inventory/src/views/profile.php"><i class="bi bi-person me-2"></i>My Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item small py-2 text-danger" href="/fsl-inventory/src/api/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
      </ul>
    </div>

  </div>
</header>