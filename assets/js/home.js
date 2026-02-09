// Load Categories
fetch("api/get_categories.php")
.then(res => res.json())
.then(data => {
    let html = "";
    data.forEach(cat => {
        html += `
        <div class="card">
            <img src="uploads/${cat.image}">
            <h3>${cat.category_name}</h3>
        </div>`;
    });
    document.getElementById("categories").innerHTML = html;
});

// Load Products
fetch("api/get_products.php")
.then(res => res.json())
.then(data => {
    let html = "";
    data.forEach(p => {
        html += `
        <div class="card product">
            <img src="uploads/${p.image}">
            <h4>${p.product_name}</h4>
            <small>${p.category_name}</small>
        </div>`;
    });
    document.getElementById("products").innerHTML = html;
});