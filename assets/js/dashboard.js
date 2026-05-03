document.addEventListener("DOMContentLoaded", loadDashboardData);

async function loadDashboardData() {
    try {
        let response = await fetch('/fsl-inventory/src/api/dashboard.php');
        let data = await response.json();

        if (!data.success) {
            console.error("Failed to load dashboard data");
            return;
        }

        updateKPIs(data.kpis);
        renderBarChart(data.charts.categories);
        renderRecentPOs(data.recent_pos);

    } catch (error) {
        console.error("Network error:", error);
    }
}

function updateKPIs(kpis) {
    let elTotal = document.getElementById("kpi_total_assets");
    let elActive = document.getElementById("kpi_endorsed");
    let elAttention = document.getElementById("kpi_pending");
    let elPOs = document.getElementById("kpi_po_records");

    if (elTotal) elTotal.textContent = kpis.total_assets;
    if (elActive) elActive.textContent = kpis.active_assets;
    if (elAttention) elAttention.textContent = kpis.attention_assets;
    if (elPOs) elPOs.textContent = kpis.total_pos;
}

function renderBarChart(categoryData) {
    let canvas = document.getElementById("chart_assets_bar");
    if (!canvas || !categoryData.length) return;

    // Extract labels and data arrays
    let chartLabels = categoryData.map(item => item.category || 'Uncategorized');
    let chartValues = categoryData.map(item => item.count);

    let ctx = canvas.getContext("2d");
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: chartLabels,
            datasets: [{
                label: "Asset Count",
                data: chartValues,
                backgroundColor: "#0B1F3A", 
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { display: false, grid: { display: false } },
                x: { grid: { display: false }, ticks: { color: "#8FA3BA", font: { size: 10 } } }
            }
        }
    });
}

function renderRecentPOs(poList) {
    let tableBody = document.getElementById("table_recent_pos_body");
    if (!tableBody) return;

    tableBody.innerHTML = ""; // Clear loader

    if (!poList.length) {
        tableBody.innerHTML = "<tr><td colspan='4' class='text-center text-muted'>No recent POs found.</td></tr>";
        return;
    }

    poList.forEach(po => {
        let tr = document.createElement("tr");
        
        // Status logic based on endorsement
        let statusHtml = po.date_endorsed 
            ? `<span class="status-badge endorsed">Endorsed</span>` 
            : `<span class="status-badge pending">Pending</span>`;

        tr.innerHTML = `
            <td class="fw-bold" style="color: var(--color-orange);">${po.po_number}</td>
            <td>${po.vendor_name || 'Unknown Vendor'}</td>
            <td class="text-muted">${po.date_received || 'N/A'}</td>
            <td>${statusHtml}</td>
        `;
        tableBody.appendChild(tr);
    });
}