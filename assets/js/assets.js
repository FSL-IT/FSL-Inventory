// Global variable to hold the currently viewed asset ID
let currentViewAssetId = null;

async function openAssetDetailsModal(assetId) {
    let modal = document.getElementById("modal_view_asset");
    if (!modal) return;

    currentViewAssetId = assetId;

    // Reset Edit Remarks State
    resetRemarksEditState();

    document.getElementById("view_description").textContent = "Loading details...";
    modal.classList.add("open");

    try {
        let response = await fetch(`/fsl-inventory/src/api/asset_details.php?id=${assetId}`);
        let result = await response.json();

        if (result.success) {
            let data = result.data;
            
            // Populate Modal Fields
            document.getElementById("view_category_badge").textContent = data.category_name || "Uncategorized";
            document.getElementById("view_description").textContent = data.description;
            document.getElementById("view_vendor_sub").textContent = data.vendor_name || "No vendor recorded";
            
            document.getElementById("view_po_number").textContent = data.po_number || "--";
            document.getElementById("view_vendor").textContent = data.vendor_name || "--";
            document.getElementById("view_date_received").textContent = data.date_received || "--";
            document.getElementById("view_date_endorsed").textContent = data.date_endorsed || "--";
            
            document.getElementById("view_location").textContent = data.location_name || "--";
            document.getElementById("view_owner").textContent = data.owner_name || "--";
            document.getElementById("view_category").textContent = data.category_name || "--";
            document.getElementById("view_quantity").textContent = `${data.total_quantity || 1} units`;
            
            // Populate Sibling Serial Chips
            let serialContainer = document.getElementById("view_serial_container");
            serialContainer.innerHTML = "";
            
            if (data.sibling_serials && data.sibling_serials.length > 0) {
                data.sibling_serials.forEach(sib => {
                    let isActive = (sib.id === assetId) ? "active-chip" : "";
                    serialContainer.innerHTML += `<div class="serial-chip ${isActive}">${sib.serial_number}</div>`;
                });
            } else {
                serialContainer.innerHTML = `<div class="serial-chip active-chip">${data.serial_number}</div>`;
            }
            
            // Remarks
            let remarksDisplay = document.getElementById("view_remarks_display");
            let remarksInput = document.getElementById("input_edit_remarks");
            
            let remarkText = data.remarks || "No remarks.";
            remarksDisplay.textContent = remarkText;
            remarksInput.value = data.remarks || "";

        } else {
            document.getElementById("view_description").textContent = "Error loading asset.";
        }
    } catch (error) {
        console.error("Failed to load asset details", error);
        document.getElementById("view_description").textContent = "Network error.";
    }
}

function setupModalInteractions() {
    let btnClose = document.getElementById("btn_close_view_asset");
    let modal = document.getElementById("modal_view_asset");
    let btnEditRemarks = document.getElementById("btn_edit_remarks");
    let btnPrint = document.getElementById("btn_print_asset");
    
    // Close Modal
    if (btnClose && modal) {
        btnClose.addEventListener("click", () => {
            modal.classList.remove("open");
        });
    }

    // Print functionality
    if (btnPrint) {
        btnPrint.addEventListener("click", () => {
            window.print();
        });
    }

    // Edit Remarks Toggle & Save Logic
    if (btnEditRemarks) {
        btnEditRemarks.addEventListener("click", async function() {
            let display = document.getElementById("view_remarks_display");
            let input = document.getElementById("input_edit_remarks");
            let btnText = btnEditRemarks.querySelector("span");

            if (input.classList.contains("d-none")) {
                // Switch to Edit Mode
                display.classList.add("d-none");
                input.classList.remove("d-none");
                input.focus();
                btnText.textContent = "Save Remarks";
                btnEditRemarks.classList.replace("custom-btn-orange", "btn-success");
                btnEditRemarks.querySelector("i").classList.replace("bi-pencil", "bi-check-lg");
            } else {
                // Switch to Save Mode & Execute API Call
                let newRemarks = input.value.trim();
                
                try {
                    let response = await fetch('/fsl-inventory/src/api/assets.php', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: currentViewAssetId, remarks: newRemarks })
                    });
                    
                    let result = await response.json();
                    
                    if (result.success) {
                        display.textContent = newRemarks || "No remarks.";
                        // Reset UI back to view mode
                        resetRemarksEditState();
                        // Refresh the underlying table
                        fetchInventoryData();
                    } else {
                        alert("Failed to save remarks.");
                    }
                } catch (error) {
                    console.error("Error saving remarks:", error);
                }
            }
        });
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
    btnText.textContent = "Edit Remarks";
    btnEditRemarks.classList.replace("btn-success", "custom-btn-orange");
    btnEditRemarks.querySelector("i").classList.replace("bi-check-lg", "bi-pencil");
}

// --- Add Asset Form Logic ---
document.addEventListener("DOMContentLoaded", setupAddAssetFeature);

function setupAddAssetFeature() {
    let btnOpenAdd = document.getElementById("btn_add_asset"); // Header button
    let modalAdd = document.getElementById("modal_add_asset");
    let btnCloseAdd = document.getElementById("btn_close_add_asset");
    let btnCancelAdd = document.getElementById("btn_cancel_add_asset");
    let btnSaveAsset = document.getElementById("btn_save_new_asset");

    if (btnOpenAdd && modalAdd) {
        btnOpenAdd.addEventListener("click", () => {
            populateDropdowns(); // Fetch categories, POs, etc.
            modalAdd.classList.add("open");
        });
    }

    let closeFunc = () => {
        if(modalAdd) modalAdd.classList.remove("open");
        clearAddAssetForm();
        if(typeof switchAddAssetTab === "function") switchAddAssetTab("basic"); // reset to tab 1
    };

    if (btnCloseAdd) btnCloseAdd.addEventListener("click", closeFunc);
    if (btnCancelAdd) btnCancelAdd.addEventListener("click", closeFunc);

    if (btnSaveAsset) {
        btnSaveAsset.addEventListener("click", submitNewAsset);
    }
}

async function populateDropdowns() {
    // In a full production app, you would have distinct API endpoints for locations/vendors.
    // For now, we will fetch Categories and POs to demonstrate the dynamic population.
    try {
        let catRes = await fetch('/fsl-inventory/src/api/categories.php');
        let catData = await catRes.json();
        let catSelect = document.getElementById("select_add_category");
        
        if (catData.success && catSelect) {
            catSelect.innerHTML = `<option value="">-- Select Category --</option>`;
            catData.data.forEach(c => {
                catSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
            });
        }

        let poRes = await fetch('/fsl-inventory/src/api/purchase_orders.php');
        let poData = await poRes.json();
        let poSelect = document.getElementById("select_add_po");
        
        if (poData.success && poSelect) {
            poSelect.innerHTML = `<option value="">-- Select Existing PO (Optional) --</option>`;
            poData.data.forEach(po => {
                poSelect.innerHTML += `<option value="${po.id}">${po.po_number}</option>`;
            });
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
        alert("Description, Category, and at least one Serial Number are required.");
        if(typeof switchAddAssetTab === "function") switchAddAssetTab("basic");
        return;
    }

    let btnSave = document.getElementById("btn_save_new_asset");
    let originalText = btnSave.textContent;
    btnSave.textContent = "Saving...";
    btnSave.disabled = true;

    try {
        let response = await fetch('/fsl-inventory/src/api/assets.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
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
            alert(result.message); // Show success message
            document.getElementById("modal_add_asset").classList.remove("open");
            clearAddAssetForm();
            fetchInventoryData(); // Refresh the table automatically!
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Error saving asset:", error);
        alert("A network error occurred.");
    } finally {
        btnSave.textContent = originalText;
        btnSave.disabled = false;
    }
}

function clearAddAssetForm() {
    document.getElementById("input_add_desc").value = "";
    document.getElementById("select_add_category").value = "";
    document.getElementById("select_add_vendor").value = "";
    document.getElementById("input_add_remarks").value = "";
    document.getElementById("select_add_po").value = "";
    document.getElementById("select_add_location").value = "";
    document.getElementById("select_add_owner").value = "";
    document.getElementById("input_add_serials").value = "";
}

// Ensure this is called when DOM loads
document.addEventListener("DOMContentLoaded", setupModalInteractions);