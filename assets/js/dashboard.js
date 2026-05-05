const recentPoTable = document.getElementById("recent_po_table");
const loadingSpinner = document.getElementById("loading_spinner");
const btnViewAll = document.getElementById("btn_view_all");

// KPI Elements
const kpiTotalAssets = document.getElementById("kpi_total_assets");
const kpiPoRecords = document.getElementById("kpi_po_records");
const kpiEndorsed = document.getElementById("kpi_endorsed");
const kpiPending = document.getElementById("kpi_pending");

// Chart instances
let barChartInstance = null;
let donutChartInstance = null;

if (btnViewAll) {
  btnViewAll.addEventListener("click", navigateToPoTracker);
}

function navigateToPoTracker() {
  window.location.href = "purchase_orders.php";
}

function statusBadge(endDate) {
  let isPending = !endDate || endDate === "0000-00-00" || endDate === "null";

  if (isPending) {
    return '<span class="badge-pending">Pending</span>';
  }
  return '<span class="badge-endorsed">Endorsed</span>';
}

function updateTableHeaders() {
  let thead = document.querySelector("#po_table_main thead tr");
  
  if (!thead) return;
  
  thead.innerHTML = `
    <th>PO Number</th>
    <th>Vendor</th>
    <th>Date Received</th>
    <th>Status</th>
  `;
}

function renderDashboardTable(data) {
  if (loadingSpinner) {
    loadingSpinner.classList.add("d-none");
  }

  if (!data || !data.length) {
    recentPoTable.innerHTML = 
      '<tr><td colspan="4" class="text-center text-muted">' +
      'No recent POs found.</td></tr>';
    return;
  }

  updateTableHeaders();
  let htmlContent = "";

  data.forEach((row) => {
    let poNumber = row.po_number || "-";
    let vendor = row.vendor_name || "-";
    let dateRecv = row.date_received || "-";

    htmlContent += `
      <tr class="table-row">
        <td class="po-text fw-medium text-white">${poNumber}</td>
        <td class="small text-muted">${vendor}</td>
        <td class="small">${dateRecv}</td>
        <td>${statusBadge(row.date_endorsed)}</td>
      </tr>
    `;
  });

  recentPoTable.innerHTML = htmlContent;
}

function updateKpis(kpiData) {
  if (!kpiData) return;

  if (kpiTotalAssets) {
    kpiTotalAssets.textContent = kpiData.total_assets || "0";
  }
  
  if (kpiPoRecords) {
    kpiPoRecords.textContent = kpiData.total_pos || "0";
  }
  
  if (kpiEndorsed) {
    kpiEndorsed.textContent = kpiData.active_assets || "0";
  }
  
  if (kpiPending) {
    kpiPending.textContent = kpiData.attention_assets || "0";
  }
}

function renderCharts(chartData, kpiData) {
  let ctxBar = document.getElementById("chart_assets_bar");
  let ctxDonut = document.getElementById("chart_endorse_donut");

  if (ctxBar && chartData && chartData.categories) {
    let labels = chartData.categories.map(item => item.category);
    let values = chartData.categories.map(item => item.count);

    if (barChartInstance) {
      barChartInstance.destroy();
    }

    barChartInstance = new Chart(ctxBar, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [{
          label: "Total Assets",
          data: values,
          backgroundColor: "#f97316",
          borderRadius: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { 
            beginAtZero: true, 
            grid: { color: "rgba(255,255,255,0.05)" } 
          },
          x: { 
            grid: { display: false }, 
            ticks: { color: "#cbd5e1" } 
          }
        }
      }
    });
  }

  if (ctxDonut && kpiData) {
    if (donutChartInstance) {
      donutChartInstance.destroy();
    }

    donutChartInstance = new Chart(ctxDonut, {
      type: "doughnut",
      data: {
        labels: ["Endorsed", "Pending"],
        datasets: [{
          data: [kpiData.active_assets || 0, kpiData.attention_assets || 0],
          backgroundColor: ["#4ade80", "#f97316"],
          borderWidth: 0,
          hoverOffset: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "75%",
        plugins: {
          legend: { position: "bottom", labels: { color: "#cbd5e1" } }
        }
      }
    });
  }
}

async function fetchDashboardData() {
  let response;

  try {
    response = await fetch("../api/dashboard.php");
  } catch (error) {
    console.error("Dashboard Data Network Error:", error);
    if (loadingSpinner) {
      loadingSpinner.classList.add("d-none");
    }
    recentPoTable.innerHTML = 
      '<tr><td colspan="4" class="text-center text-danger">' +
      'Failed to connect to the server.</td></tr>';
    return;
  }

  if (!response.ok) {
    console.error("Dashboard HTTP Error:", response.status);
    return;
  }

  let data;

  try {
    data = await response.json();
  } catch (error) {
    console.error("Dashboard JSON Parsing Error:", error);
    return;
  }

  if (data.success) {
    updateKpis(data.kpis);
    renderCharts(data.charts, data.kpis);
    renderDashboardTable(data.recent_pos);
  }
}

// Initialization
fetchDashboardData();