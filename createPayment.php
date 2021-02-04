<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require "functions/insertPayment.php";

include_once("./credentials.php");

// Create connection
$mysqli = new mysqli($servername, $username, $password, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$client_data = json_decode(file_get_contents('php://input'), true);

require "functions/refinePassengers.php";
$passengers = refinePassengers($client_data["tickets"]);
$wantsReturrejse = $client_data["returrejse_departureId"] != "none";

// Check if available seats exists
require "functions/getDepartureInfo.php";
$numPassengers = getPassengerCount($passengers);

//  Udrejse
if (!isNeededSeats($mysqli, $numPassengers, (int)$client_data["udrejse_departureId"])) {
    die("Not enough seats on udrejse");
}
//  Returrejse
if ($client_data["udrejse_departureId"] != "none" && !isNeededSeats($mysqli, $numPassengers, (int)$client_data["returrejse_departureId"])) {
    die("Not enough seats on returrejse");
}



// NEW REF AT EACH MODIFICATION. SAFE PREVIOUS REFS AND PRODUCTS!
$prices = array(
    "Barn 0-2 år retur" => array(
        "price" => 0,
        "taxRate" => 0,
        "ref" => "4"
    ),
    "Barn 3-11 år retur" => array(
        "price" => 35,
        "taxRate" => 0,
        "ref" => "5"
    ),
    "Voksen 12+ år retur" => array(
        "price" => 70,
        "taxRate" => 0,
        "ref" => "6"
    ),
    "Barn 0-2 år enkelt" => array(
        "price" => 0,
        "taxRate" => 0,
        "ref" => "1"
    ),
    "Barn 3-11 år enkelt" => array(
        "price" => 22,
        "taxRate" => 0,
        "ref" => "2"
    ),
    "Voksen 12+ år enkelt" => array(
        "price" => 44,
        "taxRate" => 0,
        "ref" => "3"
    )
);
// NEW REF AT EACH MODIFICATION. SAFE PREVIOUS REFS AND PRODUCTS!

$datastring = json_decode('{
        "order": {
            "items": [],
            "currency": "DKK"
        },
        "checkout": {
            "integrationType": "HostedPaymentPage",
            "returnUrl": "http://192.168.0.20/success.php",
            "termsUrl": "https://ibk.dk/biletter/terms.html",
            "merchantHandlesConsumerData": false,
            "consumerType": {
                "default": "B2C",
                "supportedTypes": ["B2C"]
            },
            "merchantHandlesShippingCost": false
        },
        "notifications": {
            "webhooks": [{
                    "eventName": "payment.reservation.created",
                    "url": "https://lego-ev3.com/development/test/paymentChargeCreated.php",
                    "authorization": "6bLIJbqvEkjqBoUN6wWicuhPegcKR6YG"
                }
            ]
        }
    }', true);

// Webhook for when payment has succesfully been reserved on the card



$total = 0;

foreach ($client_data["tickets"] as $key => $value) {
    if (!(array_key_exists($key, $prices) && $value >= 0 && gettype($value) == "integer")) {
        print_r($key);
        print_r(gettype($value));
        die("Fatal error: Not valid values");
    }

    array_push($datastring["order"]["items"], array(
        "reference" => $prices[$key]["ref"],
        "name" => $key,
        "quantity" => $value,
        "unit" => "pcs",
        "unitPrice" => $prices[$key]["price"] * 100, // Multiplication by 100, because 10000 is interpreted as 100.00,
        "taxRate" => $prices[$key]["taxRate"],
        "grossTotalAmount" => ($value * $prices[$key]["price"]) * 100, // Multiplication by 100, because 10000 is interpreted as 100.00
        "netTotalAmount" => ($value * $prices[$key]["price"] - $value * $prices[$key]["price"] * ($prices[$key]["taxRate"] / 100)) * 100 // Multiplication by 100, because 10000 is interpreted as 100.00
    ));
    $total += $value * $prices[$key]["price"];
}

if (!$total > 0) {
    die("Fatal error: invalid amount");
}

$datastring["order"]["amount"] = $total * 100;  // Multiplication by 100, because 10000 is interpreted as 100.00
//print_r(json_encode($datastring));
//print_r("<br /><br />");

$ch = curl_init('https://test.api.dibspayment.eu/v1/payments');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datastring));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    array(
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: test-secret-key-015454042c0e449984fbac913f5516d3'
    )
);
// ÆNDRES ÆNDRES ÆNDRES ÆNDRES
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only! // ÆNDRES ÆNDRES ÆNDRES ÆNDRES
// ÆNDRES ÆNDRES ÆNDRES ÆNDRES
$result = curl_exec($ch);

try {
    $responseJSON = json_decode($result, true);
} catch (Exception $e) {
    die("Fatal error: failed to create payment");
}

$paymentIdDibs = $responseJSON["paymentId"];

$paymentId = insertPayment($mysqli, $paymentIdDibs);

// Udrejse
require "functions/getStops.php";
$depatureId = (int)$client_data["udrejse_departureId"];
$startStopId = getFirstStop($mysqli, $depatureId)[3];
$lastStopId = getLastStop($mysqli, $depatureId)[3];

require "functions/insertTicket.php";
$ticketId = insertTicket($mysqli, $startStopId, $lastStopId, $paymentId);


require "functions/insertPassengers.php";
insertPassengers($mysqli, $ticketId, $passengers);


// Returrejse
if ($wantsReturrejse) {
    $depatureId = (int)$client_data["returrejse_departureId"];
    $startStopId = getFirstStop($mysqli, $depatureId)[3];
    $lastStopId = getLastStop($mysqli, $depatureId)[3];

    $ticketId = insertTicket($mysqli, $startStopId, $lastStopId, $paymentId);

    $passengers = refinePassengers($client_data["tickets"]);

    insertPassengers($mysqli, $ticketId, $passengers);
}


// TO DO: kun hostedpaymenturl skal returneres
print($result);
