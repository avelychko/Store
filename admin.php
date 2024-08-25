<!DOCTYPE html>
<?php
    include 'database.php';
    $conn = getConnection();

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    
    if (!isset($_SESSION['role'])) {
        header("Location: index.php?err=Must log in first");
        exit();
    }
?> 
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="admin.css" />
        <link rel="stylesheet" type="text/css" href="common.css" />
        <title>Admin</title>
    </head>
    <body>
        <?php
            include('header.php');
        ?>

        <div>
            
            <?php
                $result = getMyCustomers($conn);
            ?>

            <!-- Customers: Table -->
            <h3><i>Customers</i></h3>
            <table>
                <tr>
                    <th>Customer #</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Email</th>
                </tr>
                <?php if ($result): ?>
                    <?php foreach($result as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['last_name'] ?></td>
                        <td><?= $row['first_name'] ?></td>
                        <td><?= $row['email'] ?></td>            
                    </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </table>
        </div>

        <div>
            <?php
                function displayOrders($conn) {
                    $result = getMyOrders($conn);

                    if ($result->num_rows > 0): ?>
                        <table>
                            <tr>
                                <th>Customer</th>
                                <th>Pin</th>
                                <th>Date</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Tax</th>
                                <th>Donation</th>
                                <th>Total</th>
                            </tr>
                            <?php foreach($result as $row): 
                                $customer = getCustomerById($conn, $row['customer_id']);
                                $customerName = $customer['first_name'] . " " . $customer['last_name'];
                                $productName = getProductById($conn, $row['product_id'])['product_name'];
                                $total = number_format($row['quantity'] * $row['price'] + $row['tax'] + $row['donation'], 2);
                            ?>
                                <tr>
                                    <td><?= $customerName ?></td>
                                    <td><?= $productName ?></td>
                                    <td><?= date("m/d/y h:i A", $row['timestamp']) ?></td>
                                    <td><?= $row['quantity'] ?></td>
                                    <td><?= $row['price'] ?></td>
                                    <td><?= $row['tax'] ?></td>
                                    <td><?= $row['donation'] ?></td>
                                    <td><?= $total ?></td>
                                </tr>
                            <?php endforeach ?>
                        </table>
                    <?php else: 
                        echo "No orders yet.";
                    endif;
                }
            ?>

            <!-- Orders -->
            <h3><i>Orders</i></h3>
            <?php 
                displayOrders($conn); 
            ?>
        </div>

        <div>
            <!-- Products -->
            <h3><i>Products</i></h3>
            <table>
                <tr>
                    <th>Pin #</th>
                    <th>Pin</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
                <?php
                    $result = getMyProducts($conn); ?>
                <?php foreach($result as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['product_name'] ?></td>
                        <td><?= $row['in_stock'] ?></td>
                        <td><?= $row['price'] ?></td>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
        <?php
            include('footer.php');
        ?>
    </body>
</html>