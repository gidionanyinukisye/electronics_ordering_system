document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("../api/register.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("msg").innerText = data.message;
        if (data.success) {
            this.reset();
        }
    })
    .catch(() => {
        document.getElementById("msg").innerText = "Server error";
    });
});