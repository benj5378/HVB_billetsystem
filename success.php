<?php
    // se om pengene er reserveret, hvis ja, træk dem, bagefter afsend e-mail
    // $_GET["paymentid"]
?>
<html>
<head>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Lato&display=swap');
        @font-face {
            font-family: "Gilroy-Bold";
            src: url(Gilroy-Bold.ttf);
        }

        body {
            margin: 0px 30px;
            font-family: 'Gill Sans', 'Gill Sans MT', 'Lato', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 2em;
        }

        h1 {
            font-family: "Gilroy-Bold";
            font-size: 65pt;
            font-weight: normal;
            align-items: center;
            display: flex;
        }

        #check {
            font-size: 32vh;
        }
        .flow {
            justify-content: space-between;
            display: flex;
            align-items: center;
            flex-flow: column;
        }
    </style>
</head>
<body>
<h1>Succes</h1>
<div class="flow">
<span id="check">✅</span>
<p>Modtager du ikke billetten på e-mail inden 3 dage før afgang, så kontakt <a href="mailto:benja@ibk.dk">benja@ibk.dk</a></p>
</div>
</body>
</html>