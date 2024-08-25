<!DOCTYPE html>
<?php
    date_default_timezone_set("America/Denver");
    include 'database.php';
    $conn = getConnection();
?> 
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="store.css" />
        <link rel="stylesheet" type="text/css" href="common.css" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="script.js"></script>
        <title>Product Store</title>
    </head>
    <body>
        <?php
            include('header.php');
        ?>

        <main>
            <form action="process_order.php" method="post">
                <div>
                    <fieldset>
                        <legend>Personal Info</legend>
                        <label>First Name:<em>*</em></label>
                        <input type="text" name="first_name" required pattern="[A-Za-z\s']*" 
                        title="Please only use letters, spaces and an apostrophe."><br>
                        <label>Last Name:<em>*</em></label>
                        <input type="text" name="last_name" required pattern="[A-Za-z\s']*"
                        title="Please only use letters, spaces and an apostrophe."><br>
                        <label>Email:<em>*</em></label>
                        <input type="email" id="email" name="email" required><br>
                    </fieldset>
                </div>

                <div>
                    <fieldset>
                        <legend>Product Info</legend>
                        <select id="pins" name="pins" required onchange="onSelectChange(this)">
                            <option value="" disabled selected hidden>-- select a pin --</option>
                            <?php $result = getMyProducts($conn); ?>
                            <?php foreach($result as $row): ?>
                                <?php if (!$row['inactive']) : ?>
                                    <option value="<?= $row['id'] ?>" data-name="<?= $row['product_name'] ?>" data-quantity="<?= $row['in_stock'] ?>" data-src="<?= $row['image_name'] ?>">
                                        <?= $row['product_name'] ?> - $<?= $row['price'] ?>
                                    </option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select><br>
                        <label>Quantity: </label>
                        <input type="number" name="quantity" value=1 min=1 max=100><br>
                    </fieldset>
                </div>

                <div>
                    <label>Round up to the nearest dollar for a donation?</label><br>
                    <input type="radio" name="donation" value="Yes">
                    <label>Yes</label><br>
                    <input type="radio" name="donation" value="No" checked>
                    <label>No</label><br>
                </div>
                
                <div>
                <input type="hidden" name="timestamp" value="<?php echo time(); ?>">
                <input type="submit" name="purchase" value="Purchase"></div>
            </form>

            <div id="picture">
                <p>Select a pin to see the image</p>
                <img id="image" src="">
                <p id="low_quantity"></p>
            </div>
        </main>

        <?php
            include('footer.php');
        ?>

        <script>
            function onSelectChange(select) {
                var select = document.getElementById('pins');
                var option = select.options[select.selectedIndex];
                var img = option.getAttribute('data-src');
                var quantity = option.getAttribute('data-quantity');
                
                document.getElementById('image').src = img;
                if (quantity == 0) {
                    document.getElementById('low_quantity').innerText = "SOLD OUT";
                } else if (quantity <= 5) {
                    document.getElementById('low_quantity').innerText = "Only " + quantity + " left";
                } else {
                    document.getElementById('low_quantity').innerText = "";
                }
            }

            //cookies
            document.addEventListener('change', function() {
                var select = document.getElementById('pins');
                var option = select.options[select.selectedIndex];
                var pin = option.value;
                var email = document.getElementById('email').value;
                if (email !== '' && pin !== '') {
                    var arr = JSON.parse(getCookie("userData")) || [];

                    var existingEmailIndex = arr.findIndex(item => item.email === email);
                    if (existingEmailIndex !== -1) {
                        if (!arr[existingEmailIndex].pins.includes(pin)) {
                            arr[existingEmailIndex].pins.push(pin);
                        }
                    } else {
                        arr.push({ email: email, pins: [pin] });
                    }
                    console.log(arr);

                    var json_str = JSON.stringify(arr);
                    setCookie("userData", json_str, 30);
                }
            });
        </script>
    </body>
</html>