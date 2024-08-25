<?php
include 'database.php';
$conn = getConnection();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$email = $_POST['email'];
$password = $_POST['password'];

if (!empty($email) && !empty($password)) {
    $user = getUser($conn, $email, $password);

    if ($user !== null) {
        $_SESSION['role'] = $user['role'];
        $_SESSION['first_name'] = $user['first_name'];

        if ($user['role'] == 1) {
            header("Location: order_entry.php");
            exit();
        } elseif ($user['role'] == 2) {
            header("Location: adminProduct.php");
            exit();
        }
    } else {
        header("Location: index.php?err=Invalid User");
        exit();
    }
} else {
    header("Location: index.php?err=Email and password are required");
    exit();
}
?>
