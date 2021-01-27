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

    if(!$auth->isLoggedIn()) {
        try {
            $auth->login($_POST['email'], $_POST['password']);
        
            echo 'User is logged in';
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            die('Wrong email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Wrong password');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            die('Email not verified');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }

?>


<html>

<head>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Lato&display=swap');
        @import url('https://fonts.googleapis.com/css?family=Raleway:400,700&display=swap');

        @font-face {
            font-family: "Gilroy-Bold";
            src: url(fonts/Gilroy-Bold.ttf);
        }


        body {
            font-size: 1.5em;
        }

        * {
            font-family: 'Gill Sans', 'Gill Sans MT', 'Lato', Calibri, 'Trebuchet MS', sans-serif;
        }

        .button {
            margin-bottom: 0.2em;
            font-size: 1em;
            padding: 0.25em;
            border: 0;
            display: block;
            text-decoration: none;
        }

        .black {
            background-color: black;
            color: white;
        }

        .blue {
            background-color: rgb(51, 153, 255);
            color: white;
        }

        .arrow {
            font-family: "Wingdings 3";
        }

        .page {
            margin: 0 auto;
            max-width: 1200px;
        }
    </style>
</head>

<body>
    <div class="page">
        <a class="button blue" target="_blank" href="./create.php">Opret nye afgange <span class="arrow"></span></a>
        <a class="button blue" target="_blank" href="./view.php">Se alle afgange <span class="arrow"></span></a>
        <br />
        <a class="button black" href="./logout.php">Log ud</a>
    </div>
</body>

</html>
