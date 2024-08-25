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
        $isDonated = $_POST['donation'];
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
        if ($isDonated == "Yes") {
            $donation = number_format(ceil($subtotal), 2) - $subtotal;
        }
        $message = "Welcome back!";

        //add customer if doesn't exist
        if (!$customerByEmail) {
            //add new customer
            addCustomer($conn, $first_name, $last_name, $email);
            $customerByEmail = getCustomerByEmail($conn, $email);
            $message = "Thank you for shopping with us.";
        } 

        //check if order was already created
        if (!getOrder($conn, $customerByEmail['id'], $productId, $timestamp)) {
            addOrder($conn, $productId, $customerByEmail['id'], $quantity, $price, $tax, $donation, $timestamp);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="process_order.css" />
        <link rel="stylesheet" type="text/css" href="common.css" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="script.js"></script>
        <title>Process Order</title>
    </head>
    <body>
        <?php
            include('header.php');
        ?>

        <main>
            <div id="receipt">
                <p><b>Hello <?=$first_name?> <?=$last_name?></b> - <i><?=$message?></i></p>
                <p>We hope you enjoy your <b><?=$pin_name?></b> pin!</p>
                <p>Order details:</p>

                <table>
                    <tr>
                        <td><?=$quantity?> @ $<?=$price?>:</td>
                        <td>$<?=$total?></td>
                    </tr>
                    <tr>
                        <td>Tax:</td>
                        <td>$<?=$tax?></td>
                    </tr>
                    <tr>
                        <td>Subtotal:</td>
                        <td>$<?=$subtotal?></td>
                    </tr>
                    <?php if ($isDonated == "Yes") : ?>
                        <tr>
                            <td>Total with donation:</td>
                            <td>$<?=number_format($donation + $subtotal, 2)?></td>
                        </tr>
                    <?php endif; ?>
                </table>
                <p>We will send special offers to <?=$email?>.</p>

                <?php if (isset($_COOKIE["userData"])) {       
                    $dataArray = json_decode($_COOKIE["userData"], true);

                    foreach ($dataArray as &$dataItem) { 
                        if ($dataItem['email'] === $email) {
                            $pinIndex = array_search($productId, $dataItem['pins']);
                            if ($pinIndex !== false) {
                                array_splice($dataItem['pins'], $pinIndex, 1);
                            }
                            $dataItem['pins'] = array_values($dataItem['pins']);

                            $pins = $dataItem['pins'];
                            if (!empty($pins)) {
                                echo "<p>Based on your viewing history, we would like to offer you 20% off on these items:</p>";
                                echo "<ul>";
                                foreach ($pins as $pin) {
                                    $name = getProductById($conn, $pin)['product_name'];
                                    echo "<li>$name</li>";
                                }
                                echo "</ul>";
                            }
                        }
                    }
                } ?>
            </div>
        </main>
        <?php
            include('footer.php');
        ?>
    </body>
    <script>
            document.addEventListener("DOMContentLoaded", function() {
                var email = "<?php echo $email; ?>";
                var pin = "<?php echo $productId; ?>";

                var arr = JSON.parse(getCookie("userData")) || [];

                var existingEmailIndex = arr.findIndex(item => item.email === email);
                if (existingEmailIndex !== -1) {
                    var pinIndex = arr[existingEmailIndex].pins.indexOf(pin);
                    if (pinIndex !== -1) {
                        arr[existingEmailIndex].pins.splice(pinIndex, 1);
                    }
                } 

                var json_str = JSON.stringify(arr);
                setCookie("userData", json_str, 30);
            });
        </script>
</html>