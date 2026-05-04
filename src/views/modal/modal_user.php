<div class="modal-shell" id="modal_add_user">
  <div class="custom-modal modal-sm">
    
    <div class="modal-header-custom">
      <div>
        <div class="modal-title-text" id="user_modal_title">Add user account</div>
        <div class="modal-sub-text">Create a new system user</div>
      </div>
      <button class="modal-close-btn" id="btn_close_add_user">✕</button>
    </div>
    
    <div class="modal-body-custom">
      <div class="form-group-custom">
        <label for="add_user_name">Username *</label>
        <input type="text" id="add_user_name" placeholder="e.g. j.delacruz">
      </div>
      
      <div class="form-group-custom mt-3">
        <label for="add_user_role">Role *</label>
        <select id="add_user_role">
          <option value="user">Standard User</option>
          <option value="admin">Administrator</option>
        </select>
      </div>

      <div class="form-group-custom mt-3">
        <label for="add_user_password">Temporary Password *</label>
        <input type="password" id="add_user_password" placeholder="Min. 8 characters">
      </div>
    </div>
    
    <div class="modal-footer-custom">
      <button class="btn btn-secondary custom-btn-outline" id="btn_cancel_user">Cancel</button>
      <button class="btn btn-primary custom-btn" id="btn_save_user">Save user</button>
    </div>
    
  </div>
</div>