<?php
    include 'database.php';
    $conn = getConnection();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $timestamp = $_POST['timestamp'];
        $productId = $_POST['pins'];
        $quantity = $_POST['quantity'];
        $price =  getProductById($conn, $productId)['price'];
        $pin_name = getProductById($conn, $productId)['product_name'];
        $in_stock = getProductById($conn, $productId)['in_stock'];
        $customerByEmail = getCustomerByEmail($conn, $email);

        //check quantity
        if ($in_stock < $quantity) {
            $quantity = $in_stock;
            updateProductQuantity($conn, 0, $productId);
        }
        else {
            $newQuantity = $in_stock - $quantity;
            updateProductQuantity($conn, $newQuantity, $productId);
        }

        $total = number_format($quantity * $price, 2);
        $tax_rate = 0.075;
        $tax = number_format($total * $tax_rate, 2);
        $subtotal = number_format($total + $tax, 2);
        $donation = 0.0;

        //add customer if doesn't exist
        if (!$customerByEmail) {
            //add new customer
            addCustomer($conn, $first_name, $last_name, $email);
            $customerByEmail = getCustomerByEmail($conn, $email);
        } 

        //check if order was already created
        if (!getOrder($conn, $customerByEmail['id'], $productId, $timestamp)) {
            addOrder($conn, $productId, $customerByEmail['id'], $quantity, $price, $tax, $donation, $timestamp);
        }
    }

    echo "<br><b>Order Submitted Successfully</b><br>";
    echo "Name: " . $first_name . " " . $last_name . "<br>";
    echo "Quantity: " . $quantity . "<br>";
    echo "Pin: " . $pin_name . "<br>";
    echo "Total: $" . $subtotal;
?>

