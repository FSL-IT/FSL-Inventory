document.addEventListener("DOMContentLoaded", initializeUserManagement);

function initializeUserManagement() {
    fetchSystemUsers();
    setupAddUserForm();
}

async function fetchSystemUsers() {
    let tableBody = document.getElementById("table_users_body");
    if (!tableBody) return;
    
    try {
        let response = await fetch('/fsl-inventory/src/api/users.php');
        let result = await response.json();

        if (!result.success) {
            tableBody.innerHTML = `<tr><td colspan="6" class="text-danger text-center p-4">Error loading data.</td></tr>`;
            return;
        }

        renderUsersTable(result.data, tableBody);

    } catch (error) {
        console.error("Error fetching users:", error);
        tableBody.innerHTML = `<tr><td colspan="6" class="text-danger text-center p-4">Network connection failed.</td></tr>`;
    }
}

function renderUsersTable(dataList, tableBody) {
    tableBody.innerHTML = "";

    if (!dataList.length) {
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-muted p-4">No users found.</td></tr>`;
        return;
    }

    dataList.forEach(user => {
        let tr = document.createElement("tr");

        let roleBadge = user.role === 'admin' 
            ? `<span class="status-badge" style="background-color: var(--color-orange-dim); color: var(--color-orange);">Admin</span>` 
            : `<span class="status-badge" style="background-color: var(--color-bg-main); color: var(--color-navy); border: 1px solid var(--color-border-dark);">User</span>`;

        let initial = user.username.charAt(0).toUpperCase();

        tr.innerHTML = `
            <td>
                <div class="brand-icon rounded-circle bg-orange text-white" style="width: 28px; height: 28px; font-size: 11px;">
                    ${initial}
                </div>
            </td>
            <td class="fw-bold" style="color: var(--color-navy);">${user.username}</td>
            <td>${roleBadge}</td>
            <td class="small text-muted">${user.created_at || 'Unknown'}</td>
            <td><div style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--color-success);"></div></td>
            <td>
                <button class="btn btn-sm btn-light border text-danger" onclick="deactivateUser(${user.id}, '${user.username}')" title="Deactivate">
                    <i class="bi bi-person-x"></i>
                </button>
            </td>
        `;
        
        tableBody.appendChild(tr);
    });
}

function setupAddUserForm() {
    let formSaveBtn = document.getElementById("btn_save_user");
    if (!formSaveBtn) return;

    formSaveBtn.addEventListener("click", async function() {
        let username = document.getElementById("add_user_name").value;
        let role = document.getElementById("add_user_role").value;
        let password = document.getElementById("add_user_password").value;

        if (!username || !password) {
            alert("Username and password are required.");
            return;
        }

        if (password.length < 8) {
            alert("Password must be at least 8 characters.");
            return;
        }

        // Visual loading state
        let originalText = formSaveBtn.textContent;
        formSaveBtn.textContent = "Saving...";
        formSaveBtn.disabled = true;

        try {
            let response = await fetch('/fsl-inventory/src/api/users.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    username: username,
                    role: role,
                    password: password
                })
            });

            let result = await response.json();

            if (result.success) {
                fetchSystemUsers();
                document.getElementById("modal_add_user").classList.remove("open");
                
                // Clear form
                document.getElementById("add_user_name").value = "";
                document.getElementById("add_user_password").value = "";
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error("Error saving user:", error);
        } finally {
            formSaveBtn.textContent = originalText;
            formSaveBtn.disabled = false;
        }
    });
}

async function deactivateUser(userId, username) {
    if (!confirm(`Are you sure you want to deactivate ${username}? They will no longer be able to log in.`)) {
        return;
    }

    try {
        let response = await fetch(`/fsl-inventory/src/api/users.php?id=${userId}`, {
            method: 'DELETE'
        });

        let result = await response.json();

        if (result.success) {
            fetchSystemUsers();
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("Error deactivating user:", error);
    }
}