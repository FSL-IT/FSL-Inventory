<!-- ADD ASSET MODAL -->
<div class="modal-shell" id="modal_add_asset">
  <div class="custom-modal">
    
    <div class="modal-header-custom">
      <div>
        <div class="modal-title-text">Add new asset</div>
        <div class="modal-sub-text">Fill in all fields across the 3 tabs before saving</div>
      </div>
      <button class="modal-close-btn" id="btn_close_add_asset">✕</button>
    </div>
    
    <div class="modal-body-custom">
      <div class="modal-tabs">
        <div class="modal-tab active" id="tab_btn_basic">Basic info</div>
        <div class="modal-tab" id="tab_btn_location">Location & PO</div>
        <div class="modal-tab" id="tab_btn_serials">Serial numbers</div>
      </div>
      
      <!-- Basic Info Tab -->
      <div id="tab_content_basic">
        <div class="form-grid">
          <div class="form-group-custom">
            <label for="add_category">Category</label>
            <select id="add_category">
              <option>-- Select --</option>
              <option>Laptop</option>
              <option>Monitor</option>
              <option>Headset</option>
            </select>
          </div>
          <div class="form-group-custom">
            <label for="add_qty">Quantity</label>
            <input type="number" id="add_qty" placeholder="e.g. 15">
          </div>
        </div>
        <div class="form-group-custom">
          <label for="add_desc">Description</label>
          <input type="text" id="add_desc" placeholder="e.g. HP Probook 440 G11 Laptop">
        </div>
        <div class="form-group-custom">
          <label for="add_vendor">Vendor</label>
          <input type="text" id="add_vendor" placeholder="e.g. Trends & Technologies, Inc.">
        </div>
      </div>

      <!-- Location & PO Tab (Hidden by default) -->
      <div id="tab_content_location" class="d-none">
        <div class="form-grid">
          <div class="form-group-custom">
            <label for="add_po">PO Number</label>
            <input type="text" id="add_po" placeholder="7100/NT/FY25/…">
          </div>
          <div class="form-group-custom">
            <label for="add_location">Center / Location</label>
            <select id="add_location">
              <option>Manila-Science Hub T1 2 Floor</option>
              <option>SH2 Ground Floor</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Serials Tab (Hidden by default) -->
      <div id="tab_content_serials" class="d-none">
        <div class="form-group-custom">
          <label for="add_serials">Serial numbers</label>
          <textarea id="add_serials" placeholder="Paste serials here..." style="min-height: 130px;"></textarea>
        </div>
      </div>
    </div>
    
    <div class="modal-footer-custom">
      <button class="btn btn-secondary custom-btn-outline" id="btn_cancel_add">Cancel</button>
      <button class="btn btn-primary custom-btn" id="btn_save_asset">Save Asset</button>
    </div>
    
  </div>
</div>