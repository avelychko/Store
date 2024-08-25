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
?> 
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="order_entry.css" />
        <link rel="stylesheet" type="text/css" href="common.css" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="script.js"></script>
        <title>Order Entry</title>
    </head>
    <body>
        <?php
            include('header.php');
        ?>

        <main>
            <form method="post" id="orderForm">

                <div>
                    <fieldset>
                        <legend>Personal Info</legend>
                        <label>First Name:<em>*</em></label>
                        <input type="text" name="first_name" id="first_name" 
                        title="Please only use letters, spaces and an apostrophe." onkeyup="showHint(this.value, 'first')"><br>
                        <label>Last Name:<em>*</em></label>
                        <input type="text" name="last_name" id="last_name"
                        title="Please only use letters, spaces and an apostrophe." onkeyup="showHint(this.value, 'last')"><br>
                        <label>Email:<em>*</em></label>
                        <input type="email" name="email" id="email"><br>
                    </fieldset>
                </div>

                <div>
                    <fieldset>
                        <legend>Product Info</legend>
                        <select id="pins" name="pins" onchange="onSelectChange(this)">
                            <option value="" disabled selected hidden>-- select a pin --</option>
                            <?php $result = getMyProducts($conn); ?>
                            <?php foreach($result as $row): ?>
                                <option value="<?= $row['id'] ?>" data-quantity="<?= $row['in_stock'] ?>" data-src="<?= $row['image_name'] ?>">
                                    <?= $row['product_name'] ?> - $<?= $row['price'] ?>
                                </option>
                            <?php endforeach ?>
                        </select><br>
                        <label>Available: </label>
                        <input type="text" name="quantity" id="available" readonly><br>
                        <label>Quantity: </label>
                        <input type="number" name="quantity" id="quantity" value=1 min=1 max=100><br>
                    </fieldset>
                </div>
                
                <div>
                    <input type="hidden" id="timestamp" name="timestamp" value="<?php echo time(); ?>">
                    <input type="submit" id="submit" name="purchase" value="Purchase">
                    <input type="reset" name="clear" value="Clear Fields">
                </div>
            </form>
        </main>

        <aside>
        <p id="aside">
            Choose an existing customer: 
            <span id="txtHint"></span>
        </p>

        </aside>

        <?php
            include('footer.php');
        ?>
        <script>
            function onSelectChange(select) {
                var pinId = select.value;
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var quantity = this.responseText;
                        if (quantity === '0') {
                            quantity = 'Not found';
                        }
                        document.getElementById('available').value = quantity;
                    }
                };
                xmlhttp.open('GET', 'get_quantity.php?pinId=' + pinId, true);
                xmlhttp.send();
            }

            function showHint(str, searchType) {
                if (str.length === 0) {
                    document.getElementById("txtHint").innerHTML = "";
                    return;
                } else {
                    const xmlhttp = new XMLHttpRequest();
                    xmlhttp.onload = function() {
                        document.getElementById("txtHint").innerHTML = this.responseText;
                        highlight_row();
                    }
                    xmlhttp.open("GET", "get_customer_table.php?q=" + str + "&type=" + searchType);
                    xmlhttp.send();
                }
            }

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

                        var firstName = rowSelected.cells[0].innerHTML;
                        var lastName = rowSelected.cells[1].innerHTML;
                        var email = rowSelected.cells[2].innerHTML;

                        document.getElementsByName('first_name')[0].value = firstName;
                        document.getElementsByName('last_name')[0].value = lastName;
                        document.getElementsByName('email')[0].value = email;
                    }
                }
            }
        </script>
    </body>
</html>