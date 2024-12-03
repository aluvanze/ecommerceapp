<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$sql = $category === 'all' ? "SELECT * FROM products" : "SELECT * FROM products WHERE category='$category'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My E-Commerce Website</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
    <div class="logo">
        <img src="images/wakandalogo.jpg" alt="Wakada Imports Logo" class="logo-image">
    </div>
        <div class="filters">
            <form method="GET" action="index.php">
                <label for="category">Category:</label>
                <select name="category" id="category" onchange="this.form.submit()">
                    <option value="all" <?= $category === 'all' ? 'selected' : '' ?>>All</option>
                    <option value="household" <?= $category === 'household' ? 'selected' : '' ?>>Household</option>
                    <option value="electronics" <?= $category === 'electronics' ? 'selected' : '' ?>>Electronics</option>
                    <option value="fashion" <?= $category === 'fashion' ? 'selected' : '' ?>>Fashion</option>
                    <option value="outdoor" <?= $category === 'outdoor' ? 'selected' : '' ?>>Outdoor</option>
                </select>
            </form>
        </div>
        <div class="cart">
            <a href="cart.php">View Cart</a>
        </div>
    </header>

    <main>
        <div class="product-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $image = base64_encode($row['image']);
                    echo "
                    <div class='product'>
                        <img src='data:image/jpeg;base64,$image' alt='{$row['name']}'>
                        <h3>{$row['name']}</h3>
                        <p>\${$row['price']}</p>
                        <form method='POST' action='add_to_cart.php'>
                            <input type='hidden' name='id' value='{$row['id']}'>
                            <input type='hidden' name='name' value='{$row['name']}'>
                            <input type='hidden' name='price' value='{$row['price']}'>
                            <input type='number' name='quantity' value='1' min='1'>
                            <button type='submit'>Add to Cart</button>
                        </form>
                    </div>";
                }
            } else {
                echo "<p>No products found in this category.</p>";
            }
            ?>
        </div>
    </main>
</body>
</html>
