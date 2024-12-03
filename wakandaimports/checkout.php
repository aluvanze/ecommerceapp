<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Clear the cart (simulate order processing)
    unset($_SESSION['cart']);
    echo "<h1>Thank you for your purchase!</h1>";
    echo "<a href='index.php'>Continue Shopping</a>";
}
?>
