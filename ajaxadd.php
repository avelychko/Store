<?php
    include 'database.php';
    $conn = getConnection();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pin_name = $_POST['pin_name'];
        $image_name = $_POST['image_name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $inactive = $_POST['inactive'];

        addProduct($conn, $pin_name, $image_name, $price, $quantity, $inactive);
    }
?>