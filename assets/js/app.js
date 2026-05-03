// Global App Utilities

document.addEventListener("DOMContentLoaded", initializeApp);

function initializeApp() {
    setupLogout();
}

function setupLogout() {
    let btnLogout = document.getElementById("btn_logout");
    
    if (!btnLogout) return;

    btnLogout.addEventListener("click", async (event) => {
        event.preventDefault();
        
        try {
            let response = await fetch("/fsl-inventory/src/api/logout.php", {
                method: "POST"
            });
            
            let data = await response.json();
            
            if (data.success) {
                // Redirect back to the login entry point
                window.location.href = "/fsl-inventory/public/";
            }
        } catch (error) {
            console.error("Logout failed", error);
            alert("Error logging out. Please check your connection.");
        }
    });
}

// Reusable Toast Notification System (Call this from any file)
function showToast(message, type = "success") {
    // Note: In a full implementation, you would dynamically create a Bootstrap Toast 
    // element here and append it to the body. For now, we log it to verify logic.
    console.log(`[TOAST ${type.toUpperCase()}]: ${message}`);
}