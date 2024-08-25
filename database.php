<?php
    //connection
    function getConnection() {
        include "database_credentials.php";
        $conn = new mysqli($servername, $username, $password, $database);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    //customers
    function getMyCustomers($conn) {
        $sql = "SELECT * FROM customer";
        $result = $conn->query($sql);
        return $result;
    }

    function getCustomerById($conn, $customerId) {
        $sql = "SELECT * FROM customer WHERE id = $customerId";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    function getCustomerByEmail($conn, $email) {
        $sql = "SELECT * FROM customer WHERE email = '$email'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    function addCustomer($conn, $firstName, $lastName, $email) {
        $sql = "INSERT INTO customer (first_name, last_name, email) VALUES ('$firstName', '$lastName', '$email')";
        if ($conn->query($sql) === TRUE) {
            $newCustomerId = $conn->insert_id;
            return getCustomerById($conn, $newCustomerId);
        } else {
            return false;
        }
    }

    function searchByFirstName($conn, $firstName) {
        $sql = "SELECT * FROM CUSTOMER WHERE first_name LIKE '%$firstName%'";
        $result = $conn->query($sql);
        return $result;
    }

    function searchByLastName($conn, $lastName) {
        $sql = "SELECT * FROM CUSTOMER WHERE last_name LIKE '%$lastName%'";
        $result = $conn->query($sql);
        return $result;
    }

    //orders
    function getMyOrders($conn) {
        $sql = "SELECT * FROM orders";
        $result = $conn->query($sql);
        return $result;
    }

    function addOrder($conn, $productId, $customerId, $quantity, $price, $tax, $donation, $timestamp) {
        $sql = "INSERT INTO orders (product_id, customer_id, quantity, price, tax, donation, timestamp)
                VALUES ('$productId', '$customerId', '$quantity', '$price', '$tax', '$donation', '$timestamp')";
        return $conn->query($sql);
    }

    function getOrder($conn, $customerId, $productId, $timestamp) {
        $sql = "SELECT * FROM orders WHERE customer_id = $customerId AND product_id = $productId AND timestamp = $timestamp";
        $result = $conn->query($sql);
    
        if ($result->num_rows === 0) {
            return null;  
        } else {
            return $result->fetch_assoc();  
        }
    }

    function getOrdersByProductId($conn, $productId) {
        $sql = "SELECT * FROM orders WHERE product_id = $productId";
        $result = $conn->query($sql);
        return $result->num_rows;  
    }

    //product
    function getMyProducts($conn) {
        $sql = "SELECT * FROM product";
        $result = $conn->query($sql);
        return $result;
    }
    
    function getProductById($conn, $productId) {
        $sql = "SELECT * FROM product WHERE id = $productId";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    function getProductByName($conn, $productName) {
        $sql = "SELECT * FROM product WHERE product_name = '$productName'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    function updateProductQuantity($conn, $amount, $productId) {
        $sql = "UPDATE PRODUCT SET in_stock = $amount WHERE id = $productId";
        $result = $conn->query($sql);
        return $result;
    }

    function addProduct($conn, $productName, $imageName, $price, $inStock, $inactive) {
        $sql = "INSERT INTO product (product_name, image_name, price, in_stock, inactive) 
                VALUES ('$productName', '$imageName', $price, $inStock, $inactive)";
        $result = $conn->query($sql);
        return $result;
    }
    
    function deleteProduct($conn, $id) {
        $sql = "DELETE FROM product WHERE id = $id";
        $result = $conn->query($sql);
        return $result;
    }

    function updateProduct($conn, $productId, $productName, $imageName, $price, $inStock, $inactive) {
        $sql = "UPDATE product 
                SET product_name = '$productName', image_name = '$imageName', 
                    price = $price, in_stock = $inStock, inactive = $inactive
                WHERE id = $productId";
        $result = $conn->query($sql);
        return $result;
    }

    //user
    function getUser($conn, $email, $password) {
        $sql = "SELECT * FROM USERS WHERE email = '$email' AND password = '$password'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }
?> 