<div class="modal-shell" id="modal_add_asset">
  <div class="custom-modal modal-dark-theme p-4">
    
    <div class="d-flex justify-content-between align-items-start mb-3">
      <div>
        <h3 class="fw-bold text-white m-0" style="font-family: 'Syne', sans-serif;">Add New Asset</h3>
        <div class="text-muted mt-1 small">Fill in all fields across the 3 tabs before saving</div>
      </div>
      <button class="dark-close-btn" id="btn_close_add_asset"><i class="bi bi-x-lg"></i></button>
    </div>
    
    <div class="modal-body-custom px-0 py-2">
      
      <!-- Custom Tabs -->
      <div class="modal-tabs border-secondary mb-4">
        <div class="modal-tab active text-light" id="tab_add_basic">Basic Info</div>
        <div class="modal-tab text-muted" id="tab_add_location">Location & PO</div>
        <div class="modal-tab text-muted" id="tab_add_serials">Serial Numbers</div>
      </div>
      
      <!-- Tab 1: Basic Info -->
      <div id="content_add_basic">
        <div class="row g-3">
          <div class="col-12">
            <label class="info-label text-white">Description *</label>
            <input type="text" id="input_add_desc" class="form-control custom-dark-textarea" placeholder="e.g. HP Probook 440 G11 Laptop">
          </div>
          <div class="col-6">
            <label class="info-label text-white">Category *</label>
            <select id="select_add_category" class="form-control custom-dark-textarea">
              <option value="">-- Select Category --</option>
              <!-- Filled via JS -->
            </select>
          </div>
          <div class="col-6">
            <label class="info-label text-white">Vendor</label>
            <select id="select_add_vendor" class="form-control custom-dark-textarea">
              <option value="">-- Select Vendor --</option>
              <!-- Filled via JS -->
            </select>
          </div>
          <div class="col-12">
            <label class="info-label text-white">Remarks</label>
            <textarea id="input_add_remarks" class="form-control custom-dark-textarea" rows="2" placeholder="Optional notes..."></textarea>
          </div>
        </div>
      </div>

      <!-- Tab 2: Location & PO -->
      <div id="content_add_location" class="d-none">
        <div class="row g-3">
          <div class="col-12">
            <label class="info-label text-white">Link to Purchase Order</label>
            <select id="select_add_po" class="form-control custom-dark-textarea">
              <option value="">-- Select Existing PO (Optional) --</option>
              <!-- Filled via JS -->
            </select>
          </div>
          <div class="col-6">
            <label class="info-label text-white">Center / Location</label>
            <select id="select_add_location" class="form-control custom-dark-textarea">
              <option value="">-- Select Location --</option>
              <!-- Filled via JS -->
            </select>
          </div>
          <div class="col-6">
            <label class="info-label text-white">Process Owner / Team</label>
            <select id="select_add_owner" class="form-control custom-dark-textarea">
              <option value="">-- Select Owner --</option>
              <!-- Filled via JS -->
            </select>
          </div>
        </div>
      </div>

      <!-- Tab 3: Serials -->
      <div id="content_add_serials" class="d-none">
        <div class="p-3 mb-3 rounded" style="background-color: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
          <span class="small text-muted">Paste serial numbers below — one per line. The system will create a separate asset record for each serial number automatically.</span>
        </div>
        <label class="info-label text-white">Serial Numbers *</label>
        <textarea id="input_add_serials" class="form-control custom-dark-textarea font-monospace" rows="6" placeholder="5CD432D87V&#10;5CD432D888&#10;5CD432D87W"></textarea>
      </div>

    </div>
    
    <div class="d-flex justify-content-end gap-3 mt-3 pt-3 border-top border-secondary">
      <button class="btn btn-outline-light" id="btn_cancel_add_asset">Cancel</button>
      <button class="btn custom-btn-orange" id="btn_save_new_asset">Save Assets</button>
    </div>
    
  </div>
</div>