<?php

    include_once("./../credentials.php");

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
        // set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    require __DIR__ . '/../vendor/autoload.php';

    $auth = new \Delight\Auth\Auth($pdo, dbTablePrefix: "hvb_admin_");

    $auth->logOut();

    if (!$auth->isLoggedIn()) {
        echo 'Logged out';
    }
