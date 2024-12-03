<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Handle product deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_products.php"); // Refresh the page after deletion
    exit();
}

// Fetch product details when a product is clicked
$product = null;
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $product_sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($product_sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            display: flex;
            justify-content: space-between;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: auto;
        }

        .product-list, .product-details {
            flex: 1;
            padding: 10px;
        }

        .product-list ul {
            list-style: none;
            padding: 0;
        }

        .product-list li {
            margin-bottom: 10px;
            cursor: pointer;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }

        .product-list li:hover {
            background-color: #ddd;
        }

        .product-details {
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
            display: none;
        }

        .product-details.active {
            display: block;
        }

        .product-details img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 10px;
        }

        .delete-button {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .product-details h3 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Product List Section (Left) -->
        <div class="product-list">
            <h2>Product List</h2>
            <ul>
                <?php
                if ($result->num_rows > 0) {
                    $counter = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<li><a href='?product_id={$row['id']}'>{$counter}. {$row['name']}</a></li>";
                        $counter++;
                    }
                } else {
                    echo "<li>No products found</li>";
                }
                ?>
            </ul>
        </div>

        <!-- Product Details Section (Right) -->
        <div class="product-details <?php echo isset($product) ? 'active' : ''; ?>">
            <?php if ($product): ?>
                <h3>Product Details</h3>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="Product Image">
                <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
                <p><strong>Price:</strong> Ksh<?php echo htmlspecialchars($product['price']); ?></p>
                <form action="manage_products.php" method="GET">
                    <input type="hidden" name="delete_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="delete-button">Delete Product</button>
                </form>
            <?php else: ?>
                <p>Select a product to view details</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
