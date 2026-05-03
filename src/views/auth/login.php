<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FSL Inventory - Login</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Corrected Absolute Paths -->
  <link rel="stylesheet" href="/fsl-inventory/assets/css/variables.css">
  <link rel="stylesheet" href="/fsl-inventory/assets/css/components.css">
</head>
<body>

  <main id="login_page" class="login-page d-flex align-items-center justify-content-center">
    <div id="login_container" class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
          
          <div class="login-card p-4 shadow-lg">
            <div class="text-center mb-4">
              <!-- Recreating the mockup logo placeholder -->
              <div class="brand-icon mx-auto mb-2"></div>
              <h2 class="fw-bold text-white mb-1">FSL Inventory</h2>
              <p class="text-muted small">PO Received Asset Tracking System</p>
            </div>

            <form id="login_form">
              <div class="mb-3">
                <label for="login_user" class="form-label small text-uppercase fw-bold text-muted">
                  Username
                </label>
                <input 
                  type="text" 
                  id="login_user" 
                  class="form-control custom-input" 
                  placeholder="Enter username" 
                  required
                >
              </div>

              <div class="mb-4">
                <label for="login_pass" class="form-label small text-uppercase fw-bold text-muted">
                  Password
                </label>
                <div class="input-group">
                  <input 
                    type="password" 
                    id="login_pass" 
                    class="form-control custom-input border-end-0" 
                    placeholder="Enter password" 
                    required
                  >
                  <button type="button" id="toggle_password" class="input-group-text custom-input-group border-start-0">
                    👁️
                  </button>
                </div>
              </div>

              <button 
                type="submit" 
                id="submit_button" 
                class="btn w-100 custom-btn">
                Sign In
              </button>
            </form>

            <div id="error_message" class="error-toast text-center mt-3 d-none small">
              <!-- Error messages injected here -->
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Corrected Absolute Path -->
  <script src="/fsl-inventory/assets/js/auth.js"></script>
</body>
</html>