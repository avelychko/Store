<?php
    include 'database.php';
    $conn = getConnection();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pin_name = $_POST['pin_name'];
        $image_name = $_POST['image_name'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $inactive = $_POST['inactive'];

        $pin_id = getProductByName($conn, $pin_name)['id'];
        deleteProduct($conn, $pin_id, $pin_name, $image_name, $price, $quantity, $inactive);
    }
?>