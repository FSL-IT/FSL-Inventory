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

    // Check URL to see if a category is already selected
    let urlParams = new URLSearchParams(window.location.search);
    let activeCategory = urlParams.get('category') || 'All';

    try {
        let response = await fetch('/fsl-inventory/src/api/categories.php');
        let result = await response.json();

        if (!result.success) {
            menu.innerHTML = `<div class="text-danger small ps-3 py-2">Error loading</div>`;
            return;
        }

        // Generate "All" option
        let isActiveAll = (activeCategory === 'All') ? 'active' : '';
        let html = `
            <div class="category-item ${isActiveAll}" data-category="All">
                <div class="category-dot"></div> All
            </div>
        `;

        // Append fetched categories
        result.data.forEach(cat => {
            let isActive = (activeCategory === cat.name) ? 'active' : '';
            html += `
                <div class="category-item ${isActive}" data-category="${cat.name}">
                    <div class="category-dot"></div> ${cat.name}
                </div>
            `;
        });

        menu.innerHTML = html;

        // Attach click listeners
        let categoryItems = document.querySelectorAll(".category-item");
        
        categoryItems.forEach(item => {
            item.addEventListener("click", function() {
                let selectedCategory = this.getAttribute("data-category");
                let currentPath = window.location.pathname;

                if (currentPath.includes("assets.php")) {
                    // We are already on the Inventory page -> Filter instantly
                    categoryItems.forEach(i => i.classList.remove("active"));
                    this.classList.add("active");
                    
                    let filterEvent = new CustomEvent("categoryFiltered", { 
                        detail: { category: selectedCategory } 
                    });
                    document.dispatchEvent(filterEvent);

                    // Update the URL without reloading the page
                    let newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?category=' + encodeURIComponent(selectedCategory);
                    window.history.pushState({path: newUrl}, '', newUrl);

                } else {
                    // We are on Dashboard or another page -> Redirect to Inventory
                    window.location.href = `/fsl-inventory/src/views/assets.php?category=${encodeURIComponent(selectedCategory)}`;
                }
            });
        });

    } catch (error) {
        console.error("Failed to load categories", error);
        menu.innerHTML = `<div class="text-danger small ps-3 py-2">Connection failed</div>`;
    }
}