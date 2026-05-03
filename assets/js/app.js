document.addEventListener("DOMContentLoaded", initializeApp);

function initializeApp() {
    setupSidebarAccordion();
    highlightActiveNav();
    loadSidebarCategories();
}

function setupSidebarAccordion() {
    let btnToggle = document.getElementById("btn_toggle_categories");
    let menu = document.getElementById("menu_categories");
    let caret = document.getElementById("icon_category_caret");

    if (!btnToggle || !menu) return;

    btnToggle.addEventListener("click", () => {
        menu.classList.toggle("d-none");
        menu.classList.toggle("d-flex");
        
        if (menu.classList.contains("d-flex")) {
            caret.classList.replace("bi-caret-right-fill", "bi-caret-down-fill");
        } else {
            caret.classList.replace("bi-caret-down-fill", "bi-caret-right-fill");
        }
    });
}

function highlightActiveNav() {
    let currentPath = window.location.pathname;
    let navLinks = document.querySelectorAll(".sidebar-nav-item");

    navLinks.forEach(link => {
        link.classList.remove("active");
        if (link.getAttribute("href") === currentPath) {
            link.classList.add("active");
        }
    });
}

async function loadSidebarCategories() {
    let menu = document.getElementById("menu_categories");
    if (!menu) return;

    try {
        let response = await fetch('/fsl-inventory/src/api/categories.php');
        let result = await response.json();

        if (!result.success) {
            menu.innerHTML = `<div class="text-danger small ps-3 py-2">Error loading</div>`;
            return;
        }

        let html = "";
        
        // Loop through fetched categories (NO "All" option included)
        result.data.forEach(cat => {
            html += `
                <div class="category-item" data-category="${cat.name}">
                    <div class="category-dot"></div> ${cat.name}
                </div>
            `;
        });

        menu.innerHTML = html;

        let categoryItems = document.querySelectorAll(".category-item");
        categoryItems.forEach(item => {
            item.addEventListener("click", function() {
                categoryItems.forEach(i => i.classList.remove("active"));
                this.classList.add("active");
                
                let selectedCategory = this.getAttribute("data-category");
                let filterEvent = new CustomEvent("categoryFiltered", { 
                    detail: { category: selectedCategory } 
                });
                document.dispatchEvent(filterEvent);
            });
        });

    } catch (error) {
        console.error("Failed to load categories", error);
        menu.innerHTML = `<div class="text-danger small ps-3 py-2">Connection failed</div>`;
    }
}