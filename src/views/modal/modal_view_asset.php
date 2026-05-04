<div class="modal-shell" id="modal_view_asset">
  <div class="custom-modal modal-dark-theme p-4 print-area">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
      <div>
        <div class="d-inline-flex align-items-center gap-2 px-2 py-1 mb-2 rounded modal-category-badge">
          <i class="bi bi-box-seam"></i> <span id="view_category_badge">Category</span>
        </div>
        <h3 class="fw-bold text-white m-0" id="view_description">Loading...</h3>
        <div class="text-muted mt-1 small" id="view_vendor_sub">Vendor info</div>
      </div>
      <button class="dark-close-btn no-print" id="btn_close_view_asset"><i class="bi bi-x-lg"></i></button>
    </div>

    <div class="modal-body-custom px-0 py-2">
      
      <!-- PO INFO -->
      <div class="modal-section-header"><i class="bi bi-receipt"></i> Purchase Order Info</div>
      <div class="row g-3">
        <div class="col-6">
          <div class="info-card-dark">
            <div class="info-label">PO Number</div>
            <div class="info-value info-value-orange" id="view_po_number">--</div>
          </div>
        </div>
        <div class="col-6">
          <div class="info-card-dark">
            <div class="info-label">Vendor</div>
            <div class="info-value" id="view_vendor">--</div>
          </div>
        </div>
        <div class="col-6">
          <div class="info-card-dark">
            <div class="info-label">Date Received</div>
            <div class="info-value" id="view_date_received">--</div>
          </div>
        </div>
        <div class="col-6">
          <div class="info-card-dark">
            <div class="info-label">Date Endorsed By Admin</div>
            <div class="info-value" id="view_date_endorsed">--</div>
          </div>
        </div>
      </div>

      <!-- ASSIGNMENT INFO -->
      <div class="modal-section-header mt-4"><i class="bi bi-geo-alt"></i> Assignment Info</div>
      <div class="row g-3">
        <div class="col-6">
          <div class="info-card-dark">
            <div class="info-label">Center / Location</div>
            <div class="info-value" id="view_location">--</div>
          </div>
        </div>
        <div class="col-6">
          <div class="info-card-dark">
            <div class="info-label">Process Owner / Team</div>
            <div class="info-value" id="view_owner">--</div>
          </div>
        </div>
        <div class="col-6">
          <div class="info-card-dark">
            <div class="info-label">Category</div>
            <div class="info-value" id="view_category">--</div>
          </div>
        </div>
        <div class="col-6">
          <div class="info-card-dark">
            <div class="info-label">Quantity (PO)</div>
            <div class="info-value" id="view_quantity">--</div>
          </div>
        </div>
      </div>

      <!-- SERIAL NUMBERS -->
      <div class="modal-section-header mt-4"><i class="bi bi-upc-scan"></i> Serial Numbers</div>
      <div class="info-card-dark p-3">
        <div class="serial-chip-container" id="view_serial_container">
          <div class="serial-chip">Loading...</div>
        </div>
      </div>

      <!-- REMARKS -->
      <div class="modal-section-header mt-4"><i class="bi bi-chat-left-text"></i> Remarks</div>
      <div class="info-card-dark position-relative">
        <div id="view_remarks_display" class="info-value text-muted" style="min-height: 40px; font-weight: normal;">No remarks.</div>
        <textarea id="input_edit_remarks" class="form-control d-none custom-dark-textarea" rows="3"></textarea>
      </div>

    </div>

    <!-- Footer Actions -->
    <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top border-secondary no-print">
      <button class="btn btn-outline-light d-flex align-items-center gap-2" id="btn_print_asset"><i class="bi bi-printer"></i> Print</button>
      <button class="btn btn-outline-light d-flex align-items-center gap-2"><i class="bi bi-download"></i> Export</button>
      <button class="btn custom-btn-orange d-flex align-items-center gap-2" id="btn_edit_remarks"><i class="bi bi-pencil"></i> <span>Edit Remarks</span></button>
    </div>

  </div>
</div>