document.addEventListener("DOMContentLoaded", fetchInventoryData);

async function fetchInventoryData() {
    let tableBody = document.getElementById("table_inventory_body");
    
    try {
        let response = await fetch('/fsl-inventory/src/api/assets.php');
        let result = await response.json();

        if (!result.success) {
            tableBody.innerHTML = `<tr><td colspan="6" class="text-danger text-center">Error loading data.</td></tr>`;
            return;
        }

        renderInventoryTable(result.data, tableBody);

    } catch (error) {
        console.error("Error fetching inventory:", error);
        tableBody.innerHTML = `<tr><td colspan="6" class="text-danger text-center">Network connection failed.</td></tr>`;
    }
}

function renderInventoryTable(dataList, tableBody) {
    tableBody.innerHTML = "";

    if (!dataList.length) {
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted p-4">No assets found in database.</td></tr>`;
        return;
    }

    dataList.forEach(asset => {
        let tr = document.createElement("tr");

        // Map database status to UI badges
        let statusClass = "bg-secondary text-white";
        if (asset.status === 'active' || asset.status === 'deployed') statusClass = "status-badge endorsed";
        if (asset.status === 'defective' || asset.status === 'in_repair') statusClass = "status-badge pending";

        tr.innerHTML = `
            <td class="font-monospace small">${asset.serial_number}</td>
            <td><span class="status-badge category">${asset.category_name || 'Uncategorized'}</span></td>
            <td>${asset.description}</td>
            <td style="color: var(--color-orange); font-size: 11px; font-weight: 600;">${asset.po_number || 'N/A'}</td>
            <td class="small text-muted">${asset.location_name || 'Unassigned'}</td>
            <td><span class="${statusClass}">${asset.status.toUpperCase()}</span></td>
        `;
        
        tableBody.appendChild(tr);
    });
}