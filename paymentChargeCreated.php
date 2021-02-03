<?php

include_once("./credentials.php");

// Create connection
$mysqli = new mysqli($servername, $username, $password, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = "6bLIJbqvEkjqBoUN6wWicuhPegcKR6YG"; // Have to be hidden before live
$client_token = $_SERVER['HTTP_AUTHORIZATION'];

if (!($token === $client_token)) {
    die("Fatal error");
}

$dataJSON = json_decode(file_get_contents('php://input'), true);

$payment_id_dibs = $dataJSON["data"]["paymentId"];
$email = $dataJSON["data"]["consumer"]["email"];
$timestamp = $dataJSON["timestamp"];
$consumerName = $dataJSON["data"]["consumer"]["firstName"] . " " . $dataJSON["data"]["consumer"]["lastName"];

$sql = "UPDATE `hvb_payments` SET `payment.reservation.created_datetime` = ?, `payment_consumer_name` = ?, `payment_email_address` = ?\n"
    . "WHERE `hvb_payments`.`payment_id_dibs` = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ssss", $timestamp, $consumerName, $email, $payment_id_dibs);
$success = $stmt->execute();

if(!$success) {
    die($stmt->error);
}
