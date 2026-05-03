let loginForm = document.getElementById("login_form");
let togglePasswordBtn = document.getElementById("toggle_password");

if (loginForm) {
  loginForm.addEventListener("submit", processLogin);
}

if (togglePasswordBtn) {
  togglePasswordBtn.addEventListener("click", togglePasswordVisibility);
}

function togglePasswordVisibility() {
  let passInput = document.getElementById("login_pass");
  
  if (passInput.type === "password") {
    passInput.type = "text";
  } else {
    passInput.type = "password";
  }
}

async function processLogin(event) {
  event.preventDefault();

  let loginUser = document.getElementById("login_user").value;
  let loginPass = document.getElementById("login_pass").value;
  let submitBtn = document.getElementById("submit_button");

  if (!loginUser.length || !loginPass.length) {
    displayError("Please enter both username and password.");
    return;
  }

  // Set loading state
  submitBtn.textContent = "Signing in...";
  submitBtn.classList.add("btn-loading");
  hideError();

  try {
    let response = await fetch("/fsl-inventory/src/api/auth.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ 
        username: loginUser, 
        password: loginPass 
      })
    });

    let data = await response.json();

    if (!data.success) {
      displayError(data.message);
      resetButtonState(submitBtn);
      return;
    }

    // Redirect to absolute path on success
    window.location.href = "/fsl-inventory/src/views/dashboard.php";

  } catch (error) {
    console.error("Login Error:", error);
    displayError("A server error occurred. Please try again.");
    resetButtonState(submitBtn);
  }
}

function displayError(message) {
  let errorElement = document.getElementById("error_message");
  errorElement.textContent = message;
  errorElement.classList.remove("d-none");
}

function hideError() {
  let errorElement = document.getElementById("error_message");
  errorElement.classList.add("d-none");
}

function resetButtonState(buttonElement) {
  buttonElement.textContent = "Sign In";
  buttonElement.classList.remove("btn-loading");
}