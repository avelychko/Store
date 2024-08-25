<!DOCTYPE html>
<?php
    date_default_timezone_set("America/Denver");
    include 'database.php';
    $conn = getConnection();

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    
    if (!isset($_SESSION['role'])) {
        header("Location: index.php?err=Must log in first");
        exit();
    }

    if (intval($_SESSION['role']) !== 2) {
        header("Location: index.php?err=You are not authorized for that page!");
        exit();
    }
?> 
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="adminProduct.css" />
        <link rel="stylesheet" type="text/css" href="common.css" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="script.js"></script>
        <title>Products</title>
    </head>
    <body>
        <?php
            include('header.php');
        ?>
        <main>
            <h3><i>Pins</i></h3>
            <div id="table">
                <table id="display-table">
                    <tr>
                        <th>Pin</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Inactive?</th>
                    </tr>
                    <?php
                        $result = getMyProducts($conn); ?>
                    <?php foreach($result as $row): ?>
                        <tr>
                            <td><?= $row['product_name'] ?></td>
                            <td><?= $row['image_name'] ?></td>
                            <td><?= $row['in_stock'] ?></td>
                            <td><?= $row['price'] ?></td>
                            <td><?= ($row['inactive']) ? 'Yes' : '' ?></td>
                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </main>
        <aside>
            <form method="post" id="productForm">
                <div>
                    <fieldset>
                        <legend>Puzzle Info</legend>
                        <label>Puzzle Name:<em>*</em></label>
                        <input type="text" name="pin_name" id="pin_name"><br>
                        <label>Puzzle Image:<em>*</em></label>
                        <input type="text" name="image_name" id="image_name"><br>
                        <label>Quantity:</label>
                        <input type="number" name="quantity" id="quantity" value=0 min=0 max=1000><br>
                        <label>Price:<em>*</em></label>
                        <input type="number" name="price" id="price" value=0.00 min=0.00 max=20.00 step=0.01><br>
                        <label>Inactive:</label>
                        <input type="checkbox" name="inactive" id="inactive">
                    </fieldset>
                </div>
                <div>
                    <input type="submit" id="add" name="add" value="Add Pin">
                    <input type="submit" id="update" name="update" value="Update">
                    <input type="submit" id="delete" name="delete" value="Delete">
                </div>
                <p id="validation"></p>
            </form>
        </aside>
        <?php
            include('footer.php');
        ?>

        <script>
            highlight_row();
            function highlight_row() {
                var table = document.getElementById('display-table');
                var cells = table.getElementsByTagName('td');

                for (var i = 0; i < cells.length; i++) {
                    var cell = cells[i];
                    cell.onclick = function () {
                        var rowId = this.parentNode.rowIndex;
                        var rows = table.getElementsByTagName('tr');
                        
                        for (var row = 0; row < rows.length; row++) {
                            rows[row].style.backgroundColor = "";
                            rows[row].classList.remove('selected');
                        }
                        
                        var rowSelected = table.getElementsByTagName('tr')[rowId];
                        rowSelected.style.backgroundColor = "yellow";
                        rowSelected.classList.add('selected');

                        console.log(rowId);
                        var pinName = rowSelected.cells[0].innerHTML;
                        var imageName = rowSelected.cells[1].innerHTML;
                        var quantity = rowSelected.cells[2].innerHTML;
                        var price = rowSelected.cells[3].innerHTML;
                        var inactive = rowSelected.cells[4].innerHTML;

                        document.getElementsByName('pin_name')[0].value = pinName;
                        document.getElementsByName('image_name')[0].value = imageName;
                        document.getElementsByName('quantity')[0].value = quantity;
                        document.getElementsByName('price')[0].value = price;
                        document.getElementsByName('inactive')[0].checked = (inactive === 'Yes');
                    }
                }
            }
        </script>
    </body>
</html>