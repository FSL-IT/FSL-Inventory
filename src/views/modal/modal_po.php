<div class="modal-shell" id="modal_add_po">
  <div class="custom-modal modal-md">
    
    <div class="modal-header-custom">
      <div>
        <div class="modal-title-text">New Purchase Order</div>
        <div class="modal-sub-text">Create a new PO entry before assigning assets to it</div>
      </div>
      <button class="modal-close-btn" id="btn_close_add_po">✕</button>
    </div>
    
    <div class="modal-body-custom">
      <div class="form-grid">
        <div class="form-group-custom">
          <label for="add_po_number">PO Number *</label>
          <input type="text" id="add_po_number" placeholder="7100/NT/FY25/XXXXX">
        </div>
        <div class="form-group-custom">
          <label for="add_po_vendor">Vendor *</label>
          <select id="add_po_vendor">
            <!-- In production, fetch these dynamically. Hardcoded for DB testing -->
            <option value="1">Trends & Technologies</option>
            <option value="2">Phil-Data Business Systems</option>
            <option value="3">Visnet Technologies</option>
            <option value="4">Comlan Inc.</option>
          </select>
        </div>
      </div>

      <div class="form-grid mt-3">
        <div class="form-group-custom">
          <label for="add_po_received">Date Received</label>
          <input type="date" id="add_po_received">
        </div>
        <div class="form-group-custom">
          <label for="add_po_endorsed">Date Endorsed</label>
          <input type="date" id="add_po_endorsed">
        </div>
      </div>
    </div>
    
    <div class="modal-footer-custom">
      <button class="btn btn-secondary custom-btn-outline" id="btn_cancel_po">Cancel</button>
      <button class="btn btn-primary custom-btn" id="btn_save_po">Create PO</button>
    </div>
    
  </div>
</div>