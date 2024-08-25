<?php
    include 'database.php';
    $conn = getConnection();
        
    $q = $_REQUEST["q"];
    $type = $_REQUEST["type"];

    $hint = array();

    if ($type === "first") {
        $hint = searchByFirstName($conn, $q);
    } else {
        $hint = searchByLastName($conn, $q);
    }

    if ($hint->num_rows > 0) {
        echo '<table id=display-table>';
        echo '<tr><th>First Name</th><th>Last Name</th><th>Email</th></tr>';
        foreach ($hint as $row) {
            echo '<tr>';
            echo '<td>' . $row['first_name'] . '</td>';
            echo '<td>' . $row['last_name'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "No suggestions.";
    }
?>
