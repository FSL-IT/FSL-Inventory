let allAssetsData = [];
let currentViewAssetId = null;

document.addEventListener("DOMContentLoaded", () => {
    initializeAssetsPage();
    setupModalInteractions();
    setupAddAssetFeature();
});

function initializeAssetsPage() {
    fetchInventoryData();
    
    document.addEventListener("categoryFiltered", function(e) {
        let selectedCategory = e.detail.category;
        filterAssetsByCategory(selectedCategory);
    });
}

async function fetchInventoryData() {
    let tableBody = document.getElementById("table_inventory_body");
    if (!tableBody) return;
    
    try {
        // Use relative path to avoid folder name issues
        let response = await fetch('../api/assets.php');
        let rawText = await response.text(); 
        
        try {
            let result = JSON.parse(rawText);
            if (!result.success) {
                tableBody.innerHTML = `<tr><td colspan="6" class="text-danger text-center p-4">Backend Error: ${result.message}</td></tr>`;
                return;
            }

            allAssetsData = result.data; 
            
            // Check URL for filters
            let urlParams = new URLSearchParams(window.location.search);
            let initialCategory = urlParams.get('category') || 'All';
            filterAssetsByCategory(initialCategory);

        } catch (parseError) {
            console.error("Server sent HTML instead of JSON:", rawText);
            tableBody.innerHTML = `<tr><td colspan="6" class="text-danger text-center p-4">Session expired or server error. Check F12 console.</td></tr>`;
        }
    } catch (error) {
        console.error("Network Error:", error);
        tableBody.innerHTML = `<tr><td colspan="6" class="text-danger text-center p-4">Could not connect to API.</td></tr>`;
    }
}

function filterAssetsByCategory(categoryName) {
    let tableBody = document.getElementById("table_inventory_body");
    if (!tableBody) return;

    if (categoryName === "All") {
        renderInventoryTable(allAssetsData, tableBody);
        return;
    }

    let filteredData = allAssetsData.filter(asset => asset.category_name === categoryName);
    renderInventoryTable(filteredData, tableBody);
}

function renderInventoryTable(dataList, tableBody) {
    tableBody.innerHTML = "";

    if (!dataList.length) {
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted p-4">No assets found for this category.</td></tr>`;
        return;
    }

    dataList.forEach(asset => {
        let tr = document.createElement("tr");
        tr.style.cursor = "pointer";
        
        tr.addEventListener("click", () => openAssetDetailsModal(asset.id));

        let statusClass = "bg-secondary text-white";
        if (asset.status === "active" || asset.status === "deployed") statusClass = "status-badge endorsed";
        if (asset.status === "defective" || asset.status === "in_repair") statusClass = "status-badge pending";

        tr.innerHTML = `
            <td class="font-monospace small">${asset.serial_number}</td>
            <td><span class="status-badge category">${asset.category_name || "Uncategorized"}</span></td>
            <td>${asset.description}</td>
            <td style="color: var(--color-orange); font-size: 11px; font-weight: 600;">${asset.po_number || "--"}</td>
            <td class="small text-muted">${asset.location_name || "Unassigned"}</td>
            <td><span class="${statusClass}">${asset.status.toUpperCase()}</span></td>
        `;
        tableBody.appendChild(tr);
    });
}
async function openAssetDetailsModal(assetId) {
    let modal = document.getElementById("modal_view_asset");
    if (!modal) return;

    currentViewAssetId = assetId;
    resetRemarksEditState();

    document.getElementById("view_description").textContent = "Loading details...";
    modal.classList.add("open");

    try {
        let response = await fetch(`../api/asset_details.php?id=${assetId}`);
        let result = await response.json();

        if (result.success) {
            let data = result.data;
            
            document.getElementById("view_category_badge").innerHTML = `📦 ${data.category_name || "Uncategorized"}`;
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
            
            // Render Sibling Chips
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
            
            remarksDisplay.textContent = data.remarks || "No remarks.";
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
    
    if (btnClose && modal) {
        btnClose.addEventListener("click", () => modal.classList.remove("open"));
    }

    if (btnPrint) {
        btnPrint.addEventListener("click", () => window.print());
    }

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
                // Save Mode
                let newRemarks = input.value.trim();
                try {
                    let response = await fetch('../api/assets.php', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: currentViewAssetId, remarks: newRemarks })
                    });
                    let result = await response.json();
                    
                    if (result.success) {
                        display.textContent = newRemarks || "No remarks.";
                        resetRemarksEditState();
                        fetchInventoryData(); // Update table row instantly
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
        if(modalAdd) modalAdd.classList.remove("open");
        clearAddAssetForm();
        // Reset to first tab via DOM
        let tabBasic = document.getElementById("tab_add_basic");
        if (tabBasic) tabBasic.click();
    };

    if (btnCloseAdd) btnCloseAdd.addEventListener("click", closeFunc);
    if (btnCancelAdd) btnCancelAdd.addEventListener("click", closeFunc);

    if (btnSaveAsset) {
        btnSaveAsset.addEventListener("click", submitNewAsset);
    }
}

async function populateDropdowns() {
    try {
        let catRes = await fetch('../api/categories.php');
        let catData = await catRes.json();
        let catSelect = document.getElementById("select_add_category");
        
        if (catData.success && catSelect) {
            catSelect.innerHTML = `<option value="">-- Select Category --</option>`;
            catData.data.forEach(c => {
                catSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`;
            });
        }

        let poRes = await fetch('../api/purchase_orders.php');
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
        return;
    }

    let btnSave = document.getElementById("btn_save_new_asset");
    let originalText = btnSave.textContent;
    btnSave.textContent = "Saving...";
    btnSave.disabled = true;

    try {
        let response = await fetch('../api/assets.php', {
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
            alert(result.message);
            document.getElementById("modal_add_asset").classList.remove("open");
            clearAddAssetForm();
            fetchInventoryData(); // Reload table
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
    let elDesc = document.getElementById("input_add_desc");
    if(elDesc) elDesc.value = "";
    let elCat = document.getElementById("select_add_category");
    if(elCat) elCat.value = "";
    let elVen = document.getElementById("select_add_vendor");
    if(elVen) elVen.value = "";
    let elRem = document.getElementById("input_add_remarks");
    if(elRem) elRem.value = "";
    let elPo = document.getElementById("select_add_po");
    if(elPo) elPo.value = "";
    let elLoc = document.getElementById("select_add_location");
    if(elLoc) elLoc.value = "";
    let elOwn = document.getElementById("select_add_owner");
    if(elOwn) elOwn.value = "";
    let elSer = document.getElementById("input_add_serials");
    if(elSer) elSer.value = "";
}