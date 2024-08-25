<?php
    include 'database.php';
    $conn = getConnection();

    $pinId = $_GET["pinId"];
    $quantity = getProductById($conn, $pinId);
    echo $quantity['in_stock'];
?>