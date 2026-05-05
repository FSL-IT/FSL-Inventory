document.addEventListener("DOMContentLoaded", initializeApp);

function initializeApp() {
    setupSidebarAccordion();
    highlightActiveNav();
    loadSidebarCategories();
}

function setupSidebarAccordion() {
    let btnToggle = document.getElementById("btn_toggle_categories");
    let menu = document.getElementById("menu_categories");

    if (!btnToggle || !menu) return;

    // GUIDELINE APPLIED: Pass function directly
    btnToggle.addEventListener("click", handleCategoryToggle);
}

function handleCategoryToggle() {
    let menu = document.getElementById("menu_categories");
    let caret = document.getElementById("icon_category_caret");

    if (!menu || !caret) return;

    menu.classList.toggle("d-none");
    menu.classList.toggle("d-flex");
    
    if (menu.classList.contains("d-flex")) {
        caret.classList.replace("bi-caret-right-fill", "bi-caret-down-fill");
    } else {
        caret.classList.replace("bi-caret-down-fill", "bi-caret-right-fill");
    }
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

    let urlParams = new URLSearchParams(window.location.search);
    let activeCategory = urlParams.get('category') || 'All';

    let response;
    
    // GUIDELINE APPLIED: Isolate Try-Catch for fetch
    try {
        response = await fetch('/fsl-inventory/src/api/categories.php');
    } catch (error) {
        console.error("Failed to load categories", error);
        menu.innerHTML = `<div class="text-danger small ps-3 py-2">Connection failed</div>`;
        return;
    }

    if (!response.ok) return;

    let result;
    
    // GUIDELINE APPLIED: Isolate Try-Catch for JSON parsing
    try {
        result = await response.json();
    } catch (error) {
        console.error("JSON parse error", error);
        return;
    }

    if (!result.success) {
        menu.innerHTML = `<div class="text-danger small ps-3 py-2">Error loading</div>`;
        return;
    }

    let isActiveAll = (activeCategory === 'All') ? 'active' : '';
    let html = `
        <div class="category-item ${isActiveAll}" data-category="All">
            <div class="category-dot"></div> All
        </div>
    `;

    result.data.forEach(cat => {
        let isActive = (activeCategory === cat.name) ? 'active' : '';
        html += `
            <div class="category-item ${isActive}" data-category="${cat.name}">
                <div class="category-dot"></div> ${cat.name}
            </div>
        `;
    });

    menu.innerHTML = html;

    let categoryItems = document.querySelectorAll(".category-item");
    
    categoryItems.forEach(item => {
        // GUIDELINE APPLIED: Pass function directly
        item.addEventListener("click", handleCategoryItemClick);
    });
}

function handleCategoryItemClick(event) {
    let item = event.currentTarget;
    let selectedCategory = item.getAttribute("data-category");
    let currentPath = window.location.pathname;

    if (currentPath.includes("assets.php")) {
        let categoryItems = document.querySelectorAll(".category-item");
        categoryItems.forEach(i => i.classList.remove("active"));
        item.classList.add("active");
        
        // Dispatch event for assets.js to listen to
        let filterEvent = new CustomEvent("categoryFiltered", { 
            detail: { category: selectedCategory } 
        });
        document.dispatchEvent(filterEvent);

        let newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?category=' + encodeURIComponent(selectedCategory);
        window.history.pushState({path: newUrl}, '', newUrl);

    } else {
        window.location.href = `/fsl-inventory/src/views/assets.php?category=${encodeURIComponent(selectedCategory)}`;
    }
}