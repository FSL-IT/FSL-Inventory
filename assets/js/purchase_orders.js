document.addEventListener("DOMContentLoaded", initializePOTracker);

function initializePOTracker() {
    fetchPurchaseOrders();
    setupAddPOForm();
}

async function fetchPurchaseOrders() {
    let tableBody = document.getElementById("table_po_body");
    if (!tableBody) return;
    
    try {
        let response = await fetch('/fsl-inventory/src/api/purchase_orders.php');
        let result = await response.json();

        if (!result.success) {
            tableBody.innerHTML = `<tr><td colspan="5" class="text-danger text-center p-4">Error loading data.</td></tr>`;
            return;
        }

        renderPOTable(result.data, tableBody);

    } catch (error) {
        console.error("Error fetching POs:", error);
        tableBody.innerHTML = `<tr><td colspan="5" class="text-danger text-center p-4">Network connection failed.</td></tr>`;
    }
}

function renderPOTable(dataList, tableBody) {
    tableBody.innerHTML = "";

    if (!dataList.length) {
        tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-muted p-4">No purchase orders found.</td></tr>`;
        return;
    }

    dataList.forEach(po => {
        let tr = document.createElement("tr");

        let statusHtml = po.date_endorsed 
            ? `<span class="status-badge endorsed">Endorsed</span>` 
            : `<span class="status-badge pending">Pending</span>`;

        tr.innerHTML = `
            <td class="font-monospace fw-bold" style="color: var(--color-orange);">${po.po_number}</td>
            <td>${po.vendor_name || 'N/A'}</td>
            <td class="small text-muted">${po.date_received || 'N/A'}</td>
            <td>${statusHtml}</td>
            <td>
                <button class="btn btn-sm btn-light border text-muted"><i class="bi bi-eye"></i> View</button>
            </td>
        `;
        
        tableBody.appendChild(tr);
    });
}

function setupAddPOForm() {
    let formSaveBtn = document.getElementById("btn_save_po");
    if (!formSaveBtn) return;

    formSaveBtn.addEventListener("click", async function() {
        let poNumber = document.getElementById("add_po_number").value;
        let vendorId = document.getElementById("add_po_vendor").value;
        let dateReceived = document.getElementById("add_po_received").value;
        let dateEndorsed = document.getElementById("add_po_endorsed").value;

        if (!poNumber || !vendorId) {
            alert("PO Number and Vendor are required.");
            return;
        }

        try {
            let response = await fetch('/fsl-inventory/src/api/purchase_orders.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    po_number: poNumber,
                    vendor_id: vendorId,
                    date_received: dateReceived,
                    date_endorsed: dateEndorsed
                })
            });

            let result = await response.json();

            if (result.success) {
                // Refresh table and close modal
                fetchPurchaseOrders();
                document.getElementById("modal_add_po").classList.remove("open");
                
                // Clear form
                document.getElementById("add_po_number").value = "";
                document.getElementById("add_po_received").value = "";
                document.getElementById("add_po_endorsed").value = "";
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error("Error saving PO:", error);
        }
    });
}