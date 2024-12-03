<?php
session_start();

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$cart = $_SESSION['cart'];

// Handle removing an item
if (isset($_GET['remove'])) {
    $removeId = $_GET['remove'];
    unset($_SESSION['cart'][$removeId]);
    header("Location: cart.php");
    exit();
}

// Handle updating quantities
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $id => $quantity) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = max(1, intval($quantity)); // Ensure at least 1
        }
    }
    header("Location: cart.php");
    exit();
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="cartstyles.css">
</head>
<body>
    <header class="header">
   
        <h1>Shopping Cart</h1>
        <a href="index.php" class="continue-shopping">Continue Shopping</a>
    </header>

    <main class="cart-main">
        <form method="POST" action="cart.php">
            <div class="cart-items">
                <?php if (!empty($cart)) { ?>
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart as $id => $item) { 
                                $itemTotal = $item['price'] * $item['quantity'];
                                $total += $itemTotal;
                            ?>
                                <tr class="cart-item-row">
                                    <td class="cart-item-name"><?= htmlspecialchars($item['name']) ?></td>
                                    <td class="cart-item-price">$<?= number_format($item['price'], 2) ?></td>
                                    <td>
                                        <input 
                                            type="number" 
                                            name="quantities[<?= $id ?>]" 
                                            value="<?= $item['quantity'] ?>" 
                                            class="quantity-input" 
                                            min="1"
                                        >
                                    </td>
                                    <td class="cart-item-total">$<?= number_format($itemTotal, 2) ?></td>
                                    <td>
                                        <a href="cart.php?remove=<?= $id ?>" class="remove-item">Remove</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p class="empty-cart">Your cart is empty.</p>
                <?php } ?>
            </div>

            <div class="checkout-section">
                <div class="checkout-summary">
                    <h3>Total: $<?= number_format($total, 2) ?></h3>
                </div>
                <div class="checkout-buttons">
                    <button type="submit" name="update_cart" class="update-cart-btn">Update Cart</button>
                    <button type="submit" formaction="checkout.php" class="checkout-btn" <?= empty($cart) ? 'disabled' : '' ?>>Proceed to Checkout</button>
                </div>
            </div>
        </form>
    </main>
</body>
</html>
