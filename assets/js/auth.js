// Handle Registration Form
const registerForm = document.getElementById("registerForm");
if(registerForm) {
    registerForm.addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch("../api/register.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            const msgElement = document.getElementById("msg");
            if(msgElement) {
                msgElement.innerText = data.message;
                if (data.success) {
                    msgElement.style.color = "green";
                    registerForm.reset();
                    setTimeout(() => {
                        window.location = 'login.html';
                    }, 2000);
                } else {
                    msgElement.style.color = "red";
                }
            }
        })
        .catch((err) => {
            console.error("Error:", err);
            const msgElement = document.getElementById("msg");
            if(msgElement) {
                msgElement.innerText = "Server error. Please try again.";
                msgElement.style.color = "red";
            }
        });
    });
}

// Handle Login Form
const loginForm = document.getElementById("loginForm");
if(loginForm) {
    loginForm.addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch("../api/login.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            const msgElement = document.getElementById("msg");
            if(msgElement) {
                msgElement.innerText = data.message;
                if (data.success) {
                    msgElement.style.color = "green";
                    loginForm.reset();
                    setTimeout(() => {
                        window.location = data.redirect;
                    }, 1500);
                } else {
                    msgElement.style.color = "red";
                }
            }
        })
        .catch((err) => {
            console.error("Error:", err);
            const msgElement = document.getElementById("msg");
            if(msgElement) {
                msgElement.innerText = "Server error. Please try again.";
                msgElement.style.color = "red";
            }
        });
    });
}

// Handle Logout via AJAX
document.addEventListener("DOMContentLoaded", function() {
    const logoutLinks = document.querySelectorAll('a[href*="logout"]');
    logoutLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            fetch("../api/logout.php", {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    window.location = data.redirect;
                } else {
                    alert("Logout failed: " + data.message);
                }
            })
            .catch(() => {
                // Fallback: direct redirect if fetch fails
                window.location = "../public/login.html";
            });
        });
    });
});
