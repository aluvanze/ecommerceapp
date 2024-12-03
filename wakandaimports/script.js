// Example product data (could be fetched from a backend in a real project)
const products = [
    { id: 1, name: "Dishwasher", category: "household", price: 150, image: "dishwasher.jpg" },
    { id: 2, name: "Laptop", category: "electronics", price: 1200, image: "laptop.jpg" },
    { id: 3, name: "Shirt", category: "fashion", price: 25, image: "shirt.jpg" },
];

const categorySelect = document.getElementById("category");
const productList = document.querySelector(".product-list");

// Function to load products based on selected category
function loadProducts(category) {
    productList.innerHTML = '';
    const filteredProducts = category === "all" ? products : products.filter(product => product.category === category);

    filteredProducts.forEach(product => {
        const productElement = document.createElement('div');
        productElement.classList.add('product');
        productElement.innerHTML = `
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p>$${product.price}</p>
            <button onclick="addToCart(${product.id})">Add to Cart</button>
        `;
        productList.appendChild(productElement);
    });
}

// Add product to cart
function addToCart(productId) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    const product = products.find(p => p.id === productId);
    const existingProductIndex = cart.findIndex(item => item.id === productId);

    if (existingProductIndex > -1) {
        cart[existingProductIndex].quantity += 1;
    } else {
        cart.push({ ...product, quantity: 1 });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
}

// Load products when the page is ready
window.onload = () => {
    loadProducts("all");

    categorySelect.addEventListener("change", (e) => {
        loadProducts(e.target.value);
    });
};
