<?php

require_once "authenticate.php";

?>


<html>

<head>
    <link rel="stylesheet" href="./../styles.css">
    <link rel="stylesheet" href="styles.css">
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
        <h1>Admin</h1>

        <a class="button blue" target="_blank" href="./create.php">Opret nye afgange <span class="arrow"></span></a>
        <a class="button blue" target="_blank" href="./view.php">Se alle afgange <span class="arrow"></span></a>
        <br />

        <!--         <a class="button blue" target="_blank" href="./create.php">Opret nye afgange <span class="arrow"></span></a>
        <a class="button blue" target="_blank" href="./view.php">Se alle afgange <span class="arrow"></span></a>
        <br /> -->

        <a class="button black" href="./logout.php">Log ud</a>
    </div>
</body>

</html>