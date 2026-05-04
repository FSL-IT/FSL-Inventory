document.addEventListener("DOMContentLoaded", initializeCategoryMgmt);

function initializeCategoryMgmt() {
    fetchManageCategories();
    
    let form = document.getElementById("form_add_category");
    if (form) {
        form.addEventListener("submit", handleAddCategory);
    }
}

async function fetchManageCategories() {
    let tableBody = document.getElementById("table_categories_body");
    let countBadge = document.getElementById("category_count");
    if (!tableBody) return;

    try {
        let response = await fetch('../../../src/api/categories.php');
        let result = await response.json();

        if (!result.success) {
            tableBody.innerHTML = `<tr><td colspan="4" class="text-danger text-center p-4">Error loading data.</td></tr>`;
            return;
        }

        renderCategoryTable(result.data, tableBody);
        if (countBadge) countBadge.textContent = `${result.data.length} Total`;

    } catch (error) {
        console.error("Error fetching categories:", error);
        tableBody.innerHTML = `<tr><td colspan="4" class="text-danger text-center p-4">Network connection failed.</td></tr>`;
    }
}

function renderCategoryTable(dataList, tableBody) {
    tableBody.innerHTML = "";

    if (!dataList.length) {
        tableBody.innerHTML = `<tr><td colspan="4" class="text-center text-muted p-4">No categories found.</td></tr>`;
        return;
    }

    dataList.forEach(cat => {
        let tr = document.createElement("tr");
        tr.innerHTML = `
            <td class="font-monospace text-muted small">${cat.id}</td>
            <td class="fw-bold" style="color: var(--color-navy);">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--color-orange);"></div>
                    ${cat.name}
                </div>
            </td>
            <td class="small text-muted">System Default</td>
            <td>
                <button class="btn btn-sm btn-light border text-muted disabled"><i class="bi bi-pencil"></i></button>
            </td>
        `;
        tableBody.appendChild(tr);
    });
}

async function handleAddCategory(event) {
    event.preventDefault();
    
    let inputName = document.getElementById("input_new_category");
    let categoryName = inputName.value.trim();
    let btnSave = document.getElementById("btn_save_category");

    if (!categoryName) return;

    let originalText = btnSave.textContent;
    btnSave.textContent = "Saving...";
    btnSave.disabled = true;

    try {
        let response = await fetch('../../../src/api/categories.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name: categoryName })
        });

        let result = await response.json();

        if (result.success) {
            inputName.value = ""; // Clear input
            fetchManageCategories(); // Refresh table
            
            if (typeof loadSidebarCategories === "function") {
                loadSidebarCategories();
            }
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Error saving category:", error);
        alert("A network error occurred.");
    } finally {
        btnSave.textContent = originalText;
        btnSave.disabled = false;
    }
}