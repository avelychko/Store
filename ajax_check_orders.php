<?php
    include 'database.php';
    $conn = getConnection();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pin_name = $_POST['pin_name'];
        $pin_id = getProductByName($conn, $pin_name)['id'];

        echo getOrdersByProductId($conn, $pin_id);
    }
?>