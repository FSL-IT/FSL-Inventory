let currentViewAssetId = null;
let searchTimeout;

document.addEventListener("DOMContentLoaded", () => {
  initializeAssetsPage();
  setupModalInteractions();
  setupAddAssetFeature();
  setupSearchFeature();
});

// Add these to the top of your file
const searchInput = document.getElementById("search_input");
const filterCategory = document.getElementById("filter_category");

document.addEventListener("DOMContentLoaded", () => {
  initializeAssetsPage();
  setupModalInteractions();
  setupAddAssetFeature();
  setupFilters(); // New combined filter setup
});

async function initializeAssetsPage() {
  // 1. Fetch categories for the filter dropdown
  await populateCategoryFilter();

  // 2. Check URL parameters for direct category links (from Sidebar or Dashboard)
  let urlParams = new URLSearchParams(window.location.search);
  let initialCategory = urlParams.get("category") || "";
  
  if (initialCategory && filterCategory) {
    // Attempt to set the dropdown to the URL parameter value
    filterCategory.value = initialCategory;
  }
  
  // 3. Fetch the initial inventory data
  fetchInventoryData(initialCategory, "");
}

async function populateCategoryFilter() {
  if (!filterCategory) return;

  let response;
  try {
    response = await fetch("../api/categories.php");
  } catch (error) {
    console.error("Failed to fetch categories for filter", error);
    return;
  }

  if (!response.ok) return;

  let data = await response.json();
  
  if (data.success) {
    let catHtml = '<option value="">All Categories</option>';
    data.data.forEach(c => {
      // Assuming the API allows filtering by Category Name or ID. 
      // Adjust value to c.id if your backend expects ID instead of Name.
      catHtml += `<option value="${c.name}">${c.name}</option>`; 
    });
    filterCategory.innerHTML = catHtml;
  }
}

function setupFilters() {
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      clearTimeout(searchTimeout);
      
      // Debounce API call by 300ms
      searchTimeout = setTimeout(() => {
        triggerDataFetch();
      }, 300);
    });
  }

  if (filterCategory) {
    filterCategory.addEventListener("change", function () {
      triggerDataFetch();
    });
  }
}

// Unified function to read both inputs and fetch data
function triggerDataFetch() {
  let currentSearch = searchInput ? searchInput.value.trim() : "";
  
  // Get the selected category value. If it's "All Categories", value is ""
  let currentCategory = filterCategory ? filterCategory.value : "";

  fetchInventoryData(currentCategory, currentSearch);
}

async function fetchInventoryData(category = "", searchQuery = "") {
  let tableBody = document.getElementById("table_inventory_body");
  if (!tableBody) return;

  // Show loading spinner
  tableBody.innerHTML = `
    <tr id="loading_spinner">
      <td colspan="6" class="text-center py-4">
        <div class="spinner-border text-warning" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </td>
    </tr>`;

  let response;
  try {
    let params = new URLSearchParams();
    if (category) params.append("category", category);
    if (searchQuery) params.append("search", searchQuery);

    response = await fetch(`../api/assets.php?${params.toString()}`);
  } catch (error) {
    console.error("Network Error:", error);
    tableBody.innerHTML = `<tr><td colspan="6" class="text-danger text-center p-4">Could not connect to API.</td></tr>`;
    return;
  }

  if (!response.ok) {
    tableBody.innerHTML = `<tr><td colspan="6" class="text-danger text-center p-4">HTTP Error: ${response.status}</td></tr>`;
    return;
  }

  let result;
  try {
    result = await response.json();
  } catch (error) {
    console.error("JSON Parse Error:", error);
    tableBody.innerHTML = `<tr><td colspan="6" class="text-danger text-center p-4">Server response error.</td></tr>`;
    return;
  }

  if (!result.success) {
    tableBody.innerHTML = `<tr><td colspan="6" class="text-danger text-center p-4">Backend Error: ${result.message}</td></tr>`;
    return;
  }

  renderInventoryTable(result.data, tableBody);
}

function renderInventoryTable(dataList, tableBody) {
  if (!dataList || !dataList.length) {
    tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted p-4">No assets found matching the criteria.</td></tr>`;
    return;
  }

  let htmlContent = "";

  dataList.forEach(asset => {
    let statusClass = "bg-secondary text-white";
    
    if (asset.status === "active" || asset.status === "deployed") {
        statusClass = "badge-endorsed"; 
    }
    if (asset.status === "defective" || asset.status === "in_repair") {
        statusClass = "badge-pending";
    }

    let poNumber = asset.po_number || "--";
    let location = asset.location_name || "Unassigned";
    let category = asset.category_name || "Uncategorized";

    // FIX: Removed 'text-white' from the first <td> to fix the invisible font bug
    htmlContent += `
      <tr class="table-row" onclick="openAssetDetailsModal(${asset.id})">
        <td class="font-monospace small fw-bold">${asset.serial_number}</td>
        <td><span class="badge bg-dark border border-secondary text-light">${category}</span></td>
        <td class="fw-medium">${asset.description}</td>
        <td class="po-text">${poNumber}</td>
        <td class="small text-muted">${location}</td>
        <td><span class="${statusClass}">${asset.status.toUpperCase()}</span></td>
      </tr>
    `;
  });

  tableBody.innerHTML = htmlContent;
}

async function openAssetDetailsModal(assetId) {
  let modal = document.getElementById("modal_view_asset");
  if (!modal) return;

  currentViewAssetId = assetId;
  resetRemarksEditState();

  document.getElementById("view_description").textContent = "Loading details...";
  modal.classList.add("open"); // Assuming your modal uses a custom .open class

  let response;
  try {
    response = await fetch(`../api/asset_details.php?id=${assetId}`);
  } catch (error) {
    console.error("Failed to load asset details", error);
    document.getElementById("view_description").textContent = "Network error.";
    return;
  }

  let result = await response.json();

  if (!result.success) {
    document.getElementById("view_description").textContent = "Error loading asset.";
    return;
  }

  let data = result.data;
  
  document.getElementById("view_category_badge").innerHTML = `<i class="bi bi-box me-1"></i> ${data.category_name || "Uncategorized"}`;
  document.getElementById("view_description").textContent = data.description;
  document.getElementById("view_vendor_sub").textContent = data.vendor_name || "No vendor recorded";
  
  document.getElementById("view_po_number").textContent = data.po_number || "--";
  document.getElementById("view_vendor").textContent = data.vendor_name || "--";
  document.getElementById("view_date_received").textContent = data.date_received || "--";
  document.getElementById("view_date_endorsed").textContent = data.date_endorsed || "Pending";
  
  document.getElementById("view_location").textContent = data.location_name || "--";
  document.getElementById("view_owner").textContent = data.owner_name || "--";
  document.getElementById("view_category").textContent = data.category_name || "--";
  document.getElementById("view_quantity").textContent = `${data.total_quantity || 1} units`;
  
  let serialContainer = document.getElementById("view_serial_container");
  let serialsHtml = "";
  
  if (data.sibling_serials && data.sibling_serials.length > 0) {
    data.sibling_serials.forEach(sib => {
      let isActive = (sib.id === assetId) ? "border-warning text-warning" : "border-secondary text-muted";
      serialsHtml += `<span class="badge border ${isActive} me-1 mb-1 p-2 font-monospace">${sib.serial_number}</span>`;
    });
  } else {
    serialsHtml = `<span class="badge border border-warning text-warning me-1 mb-1 p-2 font-monospace">${data.serial_number}</span>`;
  }
  
  serialContainer.innerHTML = serialsHtml;
  
  document.getElementById("view_remarks_display").textContent = data.remarks || "No remarks.";
  document.getElementById("input_edit_remarks").value = data.remarks || "";
}

function setupModalInteractions() {
  let btnClose = document.getElementById("btn_close_view_asset");
  let modal = document.getElementById("modal_view_asset");
  let btnEditRemarks = document.getElementById("btn_edit_remarks");
  let btnPrint = document.getElementById("btn_print_asset");
  
  if (btnClose && modal) {
    btnClose.addEventListener("click", () => modal.classList.remove("open"));
  }

  if (btnPrint) {
    btnPrint.addEventListener("click", () => window.print());
  }

  if (btnEditRemarks) {
    btnEditRemarks.addEventListener("click", handleRemarksEdit);
  }
}

async function handleRemarksEdit() {
  let btnEditRemarks = document.getElementById("btn_edit_remarks");
  let display = document.getElementById("view_remarks_display");
  let input = document.getElementById("input_edit_remarks");
  let btnText = btnEditRemarks.querySelector("span");
  let icon = btnEditRemarks.querySelector("i");

  if (input.classList.contains("d-none")) {
    display.classList.add("d-none");
    input.classList.remove("d-none");
    input.focus();
    btnText.textContent = "Save";
    btnEditRemarks.classList.replace("custom-btn", "btn-success");
    icon.classList.replace("bi-pencil", "bi-check-lg");
    return;
  } 

  let newRemarks = input.value.trim();
  
  try {
    let response = await fetch("../api/assets.php", {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: currentViewAssetId, remarks: newRemarks })
    });
    
    let result = await response.json();
    
    if (result.success) {
      display.textContent = newRemarks || "No remarks.";
      resetRemarksEditState();
      
      // Refresh the table to show updated data
      let searchInput = document.getElementById("search_input");
      let currentQuery = searchInput ? searchInput.value : "";
      fetchInventoryData("", currentQuery); 
    } else {
      console.error("Failed to save remarks:", result.message);
    }
  } catch (error) {
    console.error("Error saving remarks:", error);
  }
}

function resetRemarksEditState() {
  let display = document.getElementById("view_remarks_display");
  let input = document.getElementById("input_edit_remarks");
  let btnEditRemarks = document.getElementById("btn_edit_remarks");
  
  if(!display || !input || !btnEditRemarks) return;

  display.classList.remove("d-none");
  input.classList.add("d-none");
  
  let btnText = btnEditRemarks.querySelector("span");
  let icon = btnEditRemarks.querySelector("i");
  
  if (btnText) btnText.textContent = "Edit Remarks";
  btnEditRemarks.classList.replace("btn-success", "custom-btn");
  if (icon) icon.classList.replace("bi-check-lg", "bi-pencil");
}

function setupAddAssetFeature() {
  let btnOpenAdd = document.getElementById("btn_add_asset");
  let modalAdd = document.getElementById("modal_add_asset");
  let btnCloseAdd = document.getElementById("btn_close_add_asset");
  let btnCancelAdd = document.getElementById("btn_cancel_add_asset");
  let btnSaveAsset = document.getElementById("btn_save_new_asset");

  if (btnOpenAdd && modalAdd) {
    btnOpenAdd.addEventListener("click", () => {
      populateDropdowns();
      modalAdd.classList.add("open");
    });
  }

  let closeFunc = () => {
    if (modalAdd) modalAdd.classList.remove("open");
    clearAddAssetForm();
    
    let tabBasic = document.getElementById("tab_add_basic");
    if (tabBasic) tabBasic.click();
  };

  if (btnCloseAdd) btnCloseAdd.addEventListener("click", closeFunc);
  if (btnCancelAdd) btnCancelAdd.addEventListener("click", closeFunc);
  if (btnSaveAsset) btnSaveAsset.addEventListener("click", submitNewAsset);
}

async function populateDropdowns() {
  try {
    let catRes = await fetch("../api/categories.php");
    let catData = await catRes.json();
    let catSelect = document.getElementById("select_add_category");
    
    if (catData.success && catSelect) {
      let catHtml = '<option value="">-- Select Category --</option>';
      catData.data.forEach(c => {
        catHtml += `<option value="${c.id}">${c.name}</option>`;
      });
      catSelect.innerHTML = catHtml;
    }

    let poRes = await fetch("../api/purchase_orders.php");
    let poData = await poRes.json();
    let poSelect = document.getElementById("select_add_po");
    
    if (poData.success && poSelect) {
      let poHtml = '<option value="">-- Select Existing PO (Optional) --</option>';
      poData.data.forEach(po => {
        poHtml += `<option value="${po.id}">${po.po_number}</option>`;
      });
      poSelect.innerHTML = poHtml;
    }
  } catch (error) {
    console.error("Failed to populate dropdowns", error);
  }
}

async function submitNewAsset() {
  let desc = document.getElementById("input_add_desc").value;
  let category = document.getElementById("select_add_category").value;
  let vendor = document.getElementById("select_add_vendor").value;
  let remarks = document.getElementById("input_add_remarks").value;
  let po = document.getElementById("select_add_po").value;
  let location = document.getElementById("select_add_location").value;
  let owner = document.getElementById("select_add_owner").value;
  let serials = document.getElementById("input_add_serials").value;

  if (!desc || !category || !serials.trim()) {
    console.error("Validation failed: Missing required fields.");
    return;
  }

  let btnSave = document.getElementById("btn_save_new_asset");
  let originalText = btnSave.textContent;
  btnSave.textContent = "Saving...";
  btnSave.disabled = true;

  try {
    let response = await fetch("../api/assets.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        description: desc,
        category_id: category,
        vendor_id: vendor,
        remarks: remarks,
        po_id: po,
        location_id: location,
        owner_id: owner,
        serials: serials
      })
    });

    let result = await response.json();

    if (result.success) {
      document.getElementById("modal_add_asset").classList.remove("open");
      clearAddAssetForm();
      fetchInventoryData(); 
    } else {
      console.error("Server validation failed:", result.message);
    }
  } catch (error) {
    console.error("Error saving asset:", error);
  } finally {
    btnSave.textContent = originalText;
    btnSave.disabled = false;
  }
}

function clearAddAssetForm() {
  let fields = [
    "input_add_desc", "select_add_category", "select_add_vendor",
    "input_add_remarks", "select_add_po", "select_add_location",
    "select_add_owner", "input_add_serials"
  ];

  fields.forEach(id => {
    let el = document.getElementById(id);
    if (el) el.value = "";
  });
}