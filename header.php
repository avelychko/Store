<header>
    <nav>
        <?php
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] == 1) { 
                    echo "<a href='index.php'>Home</a>";
                    echo "<a href='order_entry.php'>Order Entry</a>";
                    echo "<a href='admin.php'>Admin</a>";
                } else if ($_SESSION['role'] == 2) { 
                    echo "<a href='index.php'>Home</a>";
                    echo "<a href='store.php'>Store</a>";
                    echo "<a href='order_entry.php'>Order Entry</a>";
                    echo "<a href='adminProduct.php'>Products</a>";
                    echo "<a href='admin.php'>Admin</a>";
                } 
                echo "<a id='admin' href='logout.php'>Logout</a>";

                if ($_SESSION['role'] == 1 || $_SESSION['role'] == 2) {
                    echo "<div id='welcome'>Welcome, " . $_SESSION['first_name'] . "</div>";
                }
            } else {
                echo "<a href='index.php'>Home</a>";
                echo "<a href='store.php'>Store</a>";
            }
        ?>
    </nav>

    <h1>Pin Store</h1>
</header>
