<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $image = file_get_contents($_FILES['image']['tmp_name']);

    $conn = new mysqli('localhost', 'root', '', 'ecommerce_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, category, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $category, $price, $image);
    $stmt->execute();

    echo "<script>alert('Product added successfully!');</script>";
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
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

        .form-section, .preview-section {
            flex: 1;
            padding: 10px;
        }

        .form-section form {
            display: flex;
            flex-direction: column;
        }

        .form-section label {
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .form-section input, .form-section select, .form-section button {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .form-section button {
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .form-section button:hover {
            background-color: #218838;
        }

        .preview-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .preview-section img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .preview-placeholder {
            font-size: 1.2rem;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Form Section -->
        <div class="form-section">
            <h2>Add Product</h2>
            <form action="add_product.php" method="POST" enctype="multipart/form-data" id="productForm">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>

                <label for="category">Category:</label>
                <select name="category" id="category" required>
                    <option value="household">Choose Option</option>
                    <option value="household">Household</option>
                    <option value="electronics">Electronics</option>
                    <option value="fashion">Fashion</option>
                    <option value="outdoor">Outdoor</option>
                </select>

                <label for="price">Price:</label>
                <input type="number" name="price" id="price" step="0.01" required>

                <label for="image">Image:</label>
                <input type="file" name="image" id="image" accept="image/*" required onchange="showPreview(event)">

                <button type="submit">Add Product</button>
            </form>
        </div>

        <!-- Preview Section -->
        <div class="preview-section">
            <h3>Image Preview</h3>
            <div id="previewContainer">
                <p class="preview-placeholder">No image selected</p>
            </div>
        </div>
    </div>

    <script>
        function showPreview(event) {
            const previewContainer = document.getElementById('previewContainer');
            previewContainer.innerHTML = ''; // Clear any existing content

            const file = event.target.files[0];
            if (file) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                previewContainer.appendChild(img);
            } else {
                previewContainer.innerHTML = '<p class="preview-placeholder">No image selected</p>';
            }
        }
    </script>
</body>
</html>
