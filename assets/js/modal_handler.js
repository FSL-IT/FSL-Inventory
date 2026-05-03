// Add Asset Modal Elements
let modalAddAsset = document.getElementById("modal_add_asset");
let btnOpenAddAsset = document.getElementById("btn_add_asset"); // From your dashboard
let btnCloseAddAsset = document.getElementById("btn_close_add_asset");
let btnCancelAdd = document.getElementById("btn_cancel_add");

// Tab Elements
let tabBtnBasic = document.getElementById("tab_btn_basic");
let tabBtnLocation = document.getElementById("tab_btn_location");
let tabBtnSerials = document.getElementById("tab_btn_serials");

let tabContentBasic = document.getElementById("tab_content_basic");
let tabContentLocation = document.getElementById("tab_content_location");
let tabContentSerials = document.getElementById("tab_content_serials");

// Event Listeners for opening/closing
if (btnOpenAddAsset) {
  btnOpenAddAsset.addEventListener("click", openAddModal);
}

if (btnCloseAddAsset) {
  btnCloseAddAsset.addEventListener("click", closeAddModal);
}

if (btnCancelAdd) {
  btnCancelAdd.addEventListener("click", closeAddModal);
}

// Event Listeners for tabs
if (tabBtnBasic) {
  tabBtnBasic.addEventListener("click", () => switchTab("basic"));
}
if (tabBtnLocation) {
  tabBtnLocation.addEventListener("click", () => switchTab("location"));
}
if (tabBtnSerials) {
  tabBtnSerials.addEventListener("click", () => switchTab("serials"));
}

// Functions
function openAddModal() {
  modalAddAsset.classList.add("open");
}

function closeAddModal() {
  modalAddAsset.classList.remove("open");
  // Reset tabs to default when closing
  switchTab("basic"); 
}

function switchTab(tabName) {
  // Reset all tabs and contents
  tabBtnBasic.classList.remove("active");
  tabBtnLocation.classList.remove("active");
  tabBtnSerials.classList.remove("active");
  
  tabContentBasic.classList.add("d-none");
  tabContentLocation.classList.add("d-none");
  tabContentSerials.classList.add("d-none");

  // Activate selected tab
  if (tabName === "basic") {
    tabBtnBasic.classList.add("active");
    tabContentBasic.classList.remove("d-none");
  } else if (tabName === "location") {
    tabBtnLocation.classList.add("active");
    tabContentLocation.classList.remove("d-none");
  } else if (tabName === "serials") {
    tabBtnSerials.classList.add("active");
    tabContentSerials.classList.remove("d-none");
  }

// Add PO Modal Elements
let modalAddPO = document.getElementById("modal_add_po");
let btnOpenAddPO = document.getElementById("btn_open_add_po");
let btnCloseAddPO = document.getElementById("btn_close_add_po");
let btnCancelPO = document.getElementById("btn_cancel_po");

if (btnOpenAddPO) {
    btnOpenAddPO.addEventListener("click", () => {
        modalAddPO.classList.add("open");
    });
}

if (btnCloseAddPO) {
    btnCloseAddPO.addEventListener("click", () => {
        modalAddPO.classList.remove("open");
    });
}

if (btnCancelPO) {
    btnCancelPO.addEventListener("click", () => {
        modalAddPO.classList.remove("open");
    });
}
}