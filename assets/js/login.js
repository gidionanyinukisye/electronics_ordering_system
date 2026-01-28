document.getElementById("loginForm").addEventListener("submit", function(e){
    e.preventDefault();

    const formData = new FormData(this);

    fetch("../api/login.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            document.getElementById("msg").innerText = data.message;
            return;
        }

        if (data.role === "admin") {
            window.location.href = "../admin/dashboard.php";
        } else {
            window.location.href = "../customer/dashboard.php";
        }
    })
    .catch(() => {
        document.getElementById("msg").innerText = "Server error";
    });
});