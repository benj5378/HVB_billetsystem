<?php

include_once("./../credentials.php");

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

require __DIR__ . '/../vendor/autoload.php';

$auth = new \Delight\Auth\Auth($pdo, dbTablePrefix: "hvb_admin_");

if (!$auth->isLoggedIn()) {
    try {
        $auth->login($_POST['email'], $_POST['password']);

        echo 'User is logged in';
    } catch (\Delight\Auth\InvalidEmailException $e) {
        die('Wrong email address');
    } catch (\Delight\Auth\InvalidPasswordException $e) {
        die('Wrong password');
    } catch (\Delight\Auth\EmailNotVerifiedException $e) {
        die('Email not verified');
    } catch (\Delight\Auth\TooManyRequestsException $e) {
        die('Too many requests');
    }
}
