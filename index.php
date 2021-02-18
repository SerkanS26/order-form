<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

//we are going to use session variables so we need to enable sessions
session_start();

function whatIsHappening()
{
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}

//your products with their price.
if ((isset($_GET["food"])) && ($_GET["food"] == 0)) {

    $products = [
        ['name' => 'Cola', 'price' => 2],
        ['name' => 'Fanta', 'price' => 2],
        ['name' => 'Sprite', 'price' => 2],
        ['name' => 'Ice-tea', 'price' => 3],
    ];

} else {
    $products = [
        ['name' => 'Club Ham', 'price' => 3.20],
        ['name' => 'Club Cheese', 'price' => 3],
        ['name' => 'Club Cheese & Ham', 'price' => 4],
        ['name' => 'Club Chicken', 'price' => 4],
        ['name' => 'Club Salmon', 'price' => 5]
    ];

}


$totalValue = 0;
$delivery_time = date("H:i:s", strtotime("+2 Hours"));
$deliveryMsg = "The delivery time is: ";

if (isset($_POST["express_delivery"])) {
    $totalValue += 5;
//    $deliveryMsg = "Delivery time is ";
    $delivery_time = date("H:i:s", strtotime("+45 Minutes"));

}

if (isset($_POST["products"])) {
    foreach ($_POST["products"] as $i => $price) {
        $totalValue += $products[$i]["price"];
    }
}

//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);


//whatIsHappening();

$error = "";
$email = "";
$street = "";
$streetNumber = "";
$city = "";
$zipCode = "";


$submit = $_POST["submit"];
$result = true;

if (isset($submit)) {
    if (empty($_POST["email"])) {
        $error = "email is empty" . "<br>";
        $result = false;
    } elseif (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
        $result = false;
    } else {
        setcookie("emailCookie", $_POST["email"]);
        $email = $_POST["email"];
    }

    if (isset($_POST["street"])) {
        $street = $_POST["street"];
        setcookie("streetCookie", $street);
        if (empty($street)) {
            $error .= "Street, Street number, City and Zipcode are required!" . "<br>";
            $result = false;
        }
    }
    if (isset($_POST["streetnumber"])) {
        $streetNumber = $_POST["streetnumber"];
        setcookie("streetNumCookie", $streetNumber);
        if (!is_numeric($streetNumber)) {
            $error .= "Street number is not numeric or empty" . "<br>";
            $result = false;
        }
    }
    if (isset($_POST["city"])) {
        $city = $_POST["city"];
        setcookie("cityCookie", $city);
        if (empty($city)) {
            $error .= "Street, Street number, City and Zipcode are required!" . "<br>";
            $result = false;
        }
    }
    if (isset($_POST["zipcode"])) {
        $zipCode = $_POST["zipcode"];
        setcookie("zipCookie", $zipCode);
        if (!is_numeric($zipCode)) {
            $error .= "Zipcode is not numeric or empty" . "<br>";
            $result = false;
        }
    }
    if (($result == true) && (!empty($totalValue))) {
        $error = "Thank you, your order has been successfully sent. " . $deliveryMsg . $delivery_time;
    }

} else {
    if (!empty($_COOKIE["emailCookie"])) {
        $email = $_COOKIE["emailCookie"];
    }
    if (!empty($_COOKIE["streetCookie"])) {
        $street = $_COOKIE["streetCookie"];
    }
    if (!empty($_COOKIE["streetNumCookie"])) {
        $streetNumber = $_COOKIE["streetNumCookie"];
    }
    if (!empty($_COOKIE["cityCookie"])) {
        $city = $_COOKIE["cityCookie"];
    }

    if (!empty($_COOKIE["zipCookie"])) {
        $zipCode = $_COOKIE["zipCookie"];
    }

}


require 'form-view.php';
