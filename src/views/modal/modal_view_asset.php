<div class="modal-shell" id="modal_view_asset">
  <div class="custom-modal modal-dark-theme p-4">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start mb-2">
      <div>
        <div class="d-inline-flex align-items-center gap-2 px-2 py-1 mb-2 rounded" style="background: rgba(249,115,22,0.15); border: 1px solid rgba(249,115,22,0.3);">
          <span style="color: var(--color-orange); font-size: 11px; font-weight: 700;" id="view_category_badge">📦 Category</span>
        </div>
        <h3 class="fw-bold text-white m-0" id="view_description" style="font-family: 'Syne', sans-serif;">Loading...</h3>
        <div class="text-muted mt-1 small" id="view_vendor_sub">Vendor info</div>
      </div>
      <button class="dark-close-btn" id="btn_close_view_asset"><i class="bi bi-x-lg"></i></button>
    </div>

    <div class="modal-body-custom px-0" style="overflow-x: hidden;">
      
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
      <div class="modal-section-header"><i class="bi bi-geo-alt"></i> Assignment Info</div>
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
            <div class="info-label">Status</div>
            <div class="info-value" id="view_status">--</div>
          </div>
        </div>
      </div>

      <!-- SERIAL NUMBERS -->
      <div class="modal-section-header"><i class="bi bi-upc-scan"></i> Serial Number</div>
      <div class="info-card-dark p-3">
        <div class="serial-chip-container" id="view_serial_container">
          <div class="serial-chip">Loading...</div>
        </div>
      </div>

      <!-- REMARKS -->
      <div class="modal-section-header"><i class="bi bi-chat-left-text"></i> Remarks</div>
      <div class="info-card-dark" style="min-height: 80px;" id="view_remarks">
        No remarks.
      </div>

    </div>

    <!-- Footer Actions -->
    <div class="d-flex justify-content-end gap-3 mt-3 pt-3 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
      <button class="btn btn-outline-light d-flex align-items-center gap-2"><i class="bi bi-printer"></i> Print</button>
      <button class="btn btn-outline-light d-flex align-items-center gap-2"><i class="bi bi-download"></i> Export</button>
      <button class="btn btn-warning d-flex align-items-center gap-2 fw-bold" style="background-color: var(--color-orange); border: none; color: white;"><i class="bi bi-pencil"></i> Edit Remarks</button>
    </div>

  </div>
</div>