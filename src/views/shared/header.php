<header id="main_header" class="header-wrapper d-flex align-items-center justify-content-between px-4">
  <div class="d-flex align-items-baseline gap-2">
    <h5 class="mb-0 fw-bold" style="color: var(--color-navy);"><?= isset($pageTitle) ? $pageTitle : 'Dashboard' ?></h5>
  </div>

  <div class="d-flex align-items-center gap-3">
    <div class="position-relative">
      <i class="bi bi-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%);"></i>
      <input type="text" id="input_global_search" class="header-search" placeholder="Search PO#, serial...">
    </div>
    
    <button class="btn btn-light rounded-circle border position-relative" style="width: 36px; height: 36px;">
      <i class="bi bi-bell text-muted"></i>
    </button>
    
    <!-- User Profile Dropdown -->
    <div class="dropdown">
      <button class="btn btn-light border dropdown-toggle d-flex align-items-center gap-2 rounded-pill px-3" type="button" id="dropdown_profile" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="brand-icon rounded-circle bg-orange text-white" style="width: 24px; height: 24px; font-size: 10px;">
          <?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>
        </div>
        <span class="small fw-bold text-navy"><?= htmlspecialchars($_SESSION['username']) ?></span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="dropdown_profile">
        <li><a class="dropdown-item small" href="/fsl-inventory/src/views/profile.php"><i class="bi bi-person me-2"></i>My Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item small text-danger" href="/fsl-inventory/src/api/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</header>