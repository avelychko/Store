$(document).ready(function(){
    $("#submit").click(function(e){
        e.preventDefault();

        var first_name = $("#first_name").val();
        var last_name = $("#last_name").val();
        var email = $("#email").val();
        var pins = $("#pins").val();
        var timestamp = $("#timestamp").val();
        var quantity = parseInt($("#quantity").val());
        var available = parseInt($("#available").val());

        //successful data submission message
        var dataString = 'first_name='+ first_name + '&last_name='+ last_name + '&email='+ email 
        + '&pins='+ pins + '&timestamp='+ timestamp + '&quantity='+ quantity + '&available='+ available;

        if(first_name==''||last_name==''||email==''){
            alert("Please fill in all fields.");
        } else if (pins === null || pins === '') {
            alert("Please select a pin.");
        } else if (quantity > available) {
            alert("Quantity entered (" + quantity + ") is greater than available (" + available + ")!");
        } else {
            $.ajax({
                type: "POST",
                url: "Unit5_ajaxsubmit.php",
                data: dataString,
                cache: false,
                success: function(result){
                    $("#aside").html(result);
                    $("#orderForm")[0].reset();
                    $("#txtHint")[0].reset();
                }
            });
        }
        return false;
    });

    $("#add").click(function(e){
        e.preventDefault();

        var pin_name = $("#pin_name").val();
        var image_name = $("#image_name").val();
        var quantity = $("#quantity").val();
        var price = $("#price").val();
        var inactive = $("#inactive").prop("checked") ? 1 : 0;

        var dataString = 'pin_name='+ pin_name + '&image_name='+ image_name + '&quantity='+ quantity 
        + '&price='+ price + '&inactive='+ inactive;

        if (pin_name === ''){
            $("#validation").html("Pin name can't be blank.");
        } else if (image_name === '') {
            $("#validation").html("Image can't be blank.");
        } else if (parseFloat(price) === 0.00) {
            $("#validation").html("Price can't be $0.00.");
        } else {
            $.ajax({
                type: "POST",
                url: "Unit5_ajaxadd.php",
                data: dataString,
                cache: false,
                success: function(){
                    location.reload();
                }
            });
        }
        return false;
    });

    $("#update").click(function(e){
        e.preventDefault();

        var pin_name = $("#pin_name").val();
        var image_name = $("#image_name").val();
        var quantity = $("#quantity").val();
        var price = $("#price").val();
        var inactive = $("#inactive").prop("checked") ? 1 : 0;

        var dataString = 'pin_name='+ pin_name + '&image_name='+ image_name + '&quantity='+ quantity 
        + '&price='+ price + '&inactive='+ inactive;

        if (pin_name === ''){
            $("#validation").html("Pin name can't be blank.");
        } else if (image_name === '') {
            $("#validation").html("Image can't be blank.");
        } else if (parseFloat(price) === 0.00) {
            $("#validation").html("Price can't be $0.00.");
        } else {
            $.ajax({
                type: "POST",
                url: "Unit5_ajaxupdate.php",
                data: dataString,
                cache: false,
                success: function(){
                    location.reload();
                }
            });
        }
        return false;
    });

    $("#delete").click(function(e){
        e.preventDefault();

        var pin_name = $("#pin_name").val();
        var image_name = $("#image_name").val();
        var quantity = $("#quantity").val();
        var price = $("#price").val();
        var inactive = $("#inactive").prop("checked") ? 1 : 0;

        var dataString = 'pin_name='+ pin_name + '&image_name='+ image_name + '&quantity='+ quantity 
        + '&price='+ price + '&inactive='+ inactive;

        if (pin_name === ''){
            $("#validation").html("Pin name can't be blank.");
        } else if (image_name === '') {
            $("#validation").html("Image can't be blank.");
        } else if (parseFloat(price) === 0.00) {
            $("#validation").html("Price can't be $0.00.");
        } else {
            $.ajax({
                type: "POST",
                url: "Unit5_ajax_check_orders.php",
                data: dataString,
                cache: false,
                success: function(result){
                    if (result != 0) {
                        alert("Cannot delete the product. There are existing orders.");
                    } else {
                        if (window.confirm("Are you sure you want to delete this product?")) {
                            $.ajax({
                                type: "POST",
                                url: "Unit5_ajaxdelete.php",
                                data: dataString,
                                cache: false,
                                success: function() {
                                    location.reload();
                                }
                            });
                        }
                    }
                }
            });
        }
        return false;
    });
});

function setCookie(name, value, daysToLive) {
    var cookie = name + "=" + encodeURIComponent(value);
    
    if(typeof daysToLive === "number") {
        cookie += "; max-age=" + (daysToLive*24*60*60);
        document.cookie = cookie;
    }
}

function getCookie(name) {
    var cookieArr = document.cookie.split(";");
    
    for(var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");

        if(name == cookiePair[0].trim()) {
            return decodeURIComponent(cookiePair[1]);
        }
    }

    return null;
}
    