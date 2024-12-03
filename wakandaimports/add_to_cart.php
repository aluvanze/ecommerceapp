<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $exists = false;

    foreach ($cart as &$item) {
        if ($item['id'] == $id) {
            $item['quantity'] += $quantity;
            $exists = true;
            break;
        }
    }

    if (!$exists) {
        $cart[] = ['id' => $id, 'name' => $name, 'price' => $price, 'quantity' => $quantity];
    }

    $_SESSION['cart'] = $cart;

    header('Location: cart.php');
    exit;
}
?>
