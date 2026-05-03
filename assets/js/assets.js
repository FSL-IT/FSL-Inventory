// Global state to store data for client-side filtering
let allAssetsData = []; 

document.addEventListener("DOMContentLoaded", initializeAssetsPage);

function initializeAssetsPage() {
    fetchInventoryData();
    setupModalCloseListener();
    
    // Listen for category clicks coming from app.js
    document.addEventListener("categoryFiltered", function(e) {
        let selectedCategory = e.detail.category;
        filterAssetsByCategory(selectedCategory);
    });
}

async function fetchInventoryData() {
    let tableBody = document.getElementById("table_inventory_body");
    if (!tableBody) return;
    
    try {
        let response = await fetch('/fsl-inventory/src/api/assets.php');
        let result = await response.json();

        if (!result.success) {
            tableBody.innerHTML = `<tr><td colspan="6" class="text-danger text-center p-4">Error loading data.</td></tr>`;
            return;
        }

        // Store data globally and render the full table
        allAssetsData = result.data; 
        renderInventoryTable(allAssetsData, tableBody);

    } catch (error) {
        console.error("Error fetching inventory:", error);
        tableBody.innerHTML = `<tr><td colspan="6" class="text-danger text-center p-4">Network connection failed.</td></tr>`;
    }
}

function filterAssetsByCategory(categoryName) {
    let tableBody = document.getElementById("table_inventory_body");
    if (!tableBody) return;

    if (categoryName === "All") {
        renderInventoryTable(allAssetsData, tableBody);
        return;
    }

    // Filter the cached data array
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
        tr.style.cursor = "pointer"; // Make row clickable visually
        
        // Attach click event to open the detail modal
        tr.addEventListener("click", () => openAssetDetailsModal(asset.id));

        // UI badge logic
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

    // Show visual loading state
    document.getElementById("view_description").textContent = "Loading details...";
    modal.classList.add("open");

    try {
        let response = await fetch(`/fsl-inventory/src/api/asset_details.php?id=${assetId}`);
        let result = await response.json();

        if (result.success) {
            let data = result.data;
            
            // Populate Modal Fields safely
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
            document.getElementById("view_status").textContent = data.status.toUpperCase();
            
            document.getElementById("view_serial_container").innerHTML = `<div class="serial-chip">${data.serial_number}</div>`;
            
            document.getElementById("view_remarks").textContent = data.remarks || "No remarks.";
        } else {
            document.getElementById("view_description").textContent = "Error loading asset.";
        }
    } catch (error) {
        console.error("Failed to load asset details", error);
        document.getElementById("view_description").textContent = "Network error.";
    }
}

function setupModalCloseListener() {
    let btnClose = document.getElementById("btn_close_view_asset");
    let modal = document.getElementById("modal_view_asset");
    
    if (btnClose && modal) {
        btnClose.addEventListener("click", () => {
            modal.classList.remove("open");
        });
    }
}