<?php
header('Content-Type: text/html; charset=UTF-8');

$result = setlocale(LC_ALL, 'da_DK');

include_once("./credentials.php");

// Create connection
$mysqli = new mysqli($servername, $username, $password, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

require "functions/getStops.php"


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Lato&display=swap');
        @import url('https://fonts.googleapis.com/css?family=Raleway:400,700&display=swap');

        @font-face {
            font-family: "Gilroy-Bold";
            src: url(fonts/Gilroy-Bold.ttf);
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

        h2,
        h3 {
            font-family: "Raleway";
            font-weight: bold;
        }

        #logo {
            height: 140px;
            position: fixed;
            right: 50px;
            top: 0;
        }

        #dates>div {
            padding: 4px;
            background-color: lightgray;
            display: inline-block;
            font-size: 14pt;
        }

        #dates>div>span:first-child {
            transform: rotate(-90deg);
            display: inline-block
        }

        td,
        th {
            padding: 0 0.8em 0.2em 0;
            font-size: 1.5em;
        }

        td>a {
            background-color: rgb(69, 69, 69);
            padding: 0.4em;
            text-decoration: none;
            color: white;
        }

        td:first-child {
            font-weight: bold;
            background-color: white;
        }

        .time {
            display: flex;
            justify-content: space-between;
            background-color: rgb(230, 230, 230);
            padding: 0.5em;
            margin-bottom: 6px;
            cursor: pointer;
        }

        .time>div:first-child {
            flex: 1;
            max-width: 700px;
        }

        .time>div:not(:first-child) {
            justify-content: center;
            display: flex;
            margin-left: 0.6em;
        }

        .time>div>span>span {
            font-size: 0.7em;
            font-style: italic;
        }

        div>a {
            background-color: rgb(230, 230, 230);
            padding: 0.1em 0.3em;
            font-size: 0.7em;
            margin: 2px;
            cursor: pointer;
            font-family: "Gilroy-Bold"
        }

        /*
        .time>div>a {
            font-family: "Gilroy-Bold";
            align-items: center;
            justify-content: center;
            display: flex;
            text-decoration: none;
        }

        a.buy {
            background-color: rgb(51, 153, 255);
            color: white;
        }

        a.timetable {
            color: white;
        } */

        .arrow {
            font-family: "Wingdings 3";
        }

        .page {
            /* min-height: 100vh; */
            padding-top: 0.1em;
        }

        /* .page:first-child {
            min-height: calc(100vh - 140px);
        } */

        #turretur {
            display: flex;
        }

        #turretur>div {
            background-color: rgb(230, 230, 230);
            min-width: 120px;
            padding: 0.8em;
            cursor: pointer;
            font-family: "Gilroy-Bold";
        }

        #turretur>div>span {
            text-align: right;
            font-size: 0.6em;
            font-style: italic;
            font-family: 'Gill Sans', 'Gill Sans MT', 'Lato', 'Trebuchet MS', sans-serif;
        }

        #travellers {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        #travellers>span {
            background-color: rgb(230, 230, 230);
            padding: 0.3em 0.8em;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 250px;
            margin-bottom: 0.3em;
        }

        /*#travellers > span:not(:first-child):not(:last-child) {
            margin: 0 1em;
        }*/

        #travellers>span>input {
            font-size: 1em;
            padding: 0.08em;
            margin-left: 1em;
            width: 1.5em;
            text-align: center;
            font-family: inherit;
        }

        #travellers>span>span>.meta {
            display: inline-block;
            font-size: 0.6em;
            font-style: italic;
        }

        .section {
            margin-bottom: 0.8em;
        }

        #summary>div>div {
            display: flex;
            justify-content: space-between;
            max-width: 700px;
        }

        #summary>div:last-child>div>span {
            font-weight: bold;
        }

        #summary>div:not(:last-child) {
            border-bottom: 1px solid rgb(51, 153, 255);
        }

        .proceed {
            background-color: rgb(51, 153, 255);
        }

        .timetable {
            background-color: rgb(255, 169, 26);
        }

        button {
            padding: 0.1em 0.3em;
            border: none;
            font-size: 0.7em;
            color: white;
            font-family: "Gilroy-Bold";
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }

        button:not(:first-of-type) {
            margin-left: 12px
        }

        .italic {
            font-size: 0.75em;
            font-style: italic;
        }

        .active {
            background-color: rgb(69, 69, 69) !important;
            color: white;
        }

        .type>span {
            min-width: 3em;
        }

        .info {
            /* contact information */
            background-color: rgb(230, 230, 230);
            padding: 0.3em 0.8em;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 1em;
        }

        .info>div>input {
            font-size: 1em;
            font-family: "Gill Sans MT";
            padding: 0.1em 0.3em;
            margin-left: 1em;
            flex: 1;
        }

        .info>div {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 800px;
            align-items: center;
            flex-wrap: wrap;
        }

        .info>div>span {
            width: 9.375em;
        }

        .info>div:not(:last-child) {
            margin-bottom: 0.2em;
        }
    </style>
    <script src="https://test.checkout.dibspayment.eu/v1/checkout.js?v=1"></script>
    <script src="./scripts/view2.0.js"></script>
    <script>
        var prices = {
            "enkelt": {
                "Voksen 12+ år": 44,
                "Barn 3-11 år": 22,
                "Barn 0-2 år": 0
            },
            "retur": {
                "Voksen år": 70,
                "Barn 3-11 år": 35,
                "Barn 0-2 år": 0
            }
        };

        function chooseStart() {
            $('html, body').animate({
                scrollTop: $("#page2").offset().top
            }, 1000);
        }

        function update() {

            // DO PRICE SUMMARY
            summaryElement = document.getElementById("summary");

            // Get choosen ticket types
            var ticketElements = document.getElementsByClassName("ticketTypeNumber");
            var ticketOption = "enkelt"; //document.querySelector(".turreturButton.active").getAttribute("data-option");

            summary.innerHTML = "";

            var totalPrice = 0;

            for (var i = 0; i < ticketElements.length; i++) {
                var product = ticketElements[i].getAttribute("data-ticket-type") + " " + ticketOption;
                var count = ticketElements[i].value;
                var price = prices[ticketOption][ticketElements[i].getAttribute("data-ticket-type")] * count;
                var html = "<div class=\"summary-item\" data-product=\"" + product + "\" data-product-count=\"" + count + "\"><div><span>" + count + " " + product + "</span><span>" + price + " kr.</span></div></div>";
                totalPrice += price;
                summaryElement.innerHTML += html;
            }

            summaryElement.innerHTML += "<div id=\"total\"><div><span>I alt</span><span>" + totalPrice + " kr.</span></div></div>";

            // DO TIME SUMMARY
            // - DO TIME SUMMARY UDREJSE

            var choosenTicketIdUdrejse = document.querySelectorAll("[data-radioclass='udrejserejsetid']").getAttribute("data-trainnumber") //document.querySelector(".time.active").getAttribute("data-train-number");

            document.getElementById("summaryUdrejse").innerHTML = ""; // += "<span class=\"italic\">Udtur</span><br />" + ticketHTML;

            var request = new XMLHttpRequest();
            request.open('GET', '/getTime.php?id=' + choosenTicketIdUdrejse, true); // `false` makes the request synchronous
            request.send(null);
            if (request.status === 200) {
                // TO DO SKAL PARSES, idé: JSON FRA PHP
                var ticketHTML = request.responseText; //JSON.parse(request.responseText);
            }

            // - DO TIME SUMMARY RETURREJSE

            var choosenTicketIdReturrejse = document.querySelectorAll("[data-radioclass='returrejsetid']").getAttribute("data-trainnumber") //document.querySelector(".time.active").getAttribute("data-train-number");

            document.getElementById("summaryReturrejse").innerHTML = ""; // += "<span class=\"italic\">Udtur</span><br />" + ticketHTML;

            // Tjek om der skal være returrejse. Hvis ja, så hent togdata på samme måde, som for udrejse
            if (choosenTicketIdReturrejse == "ingenreturrejse") {
                document.getElementById("summaryReturrejse").innerHTML += "Ingen returrejse"; //"<span class=\"italic\">Udtur</span><br /><div class=\"time\">Returbilletten gælder til et valgfrit tog mod Hedehusgårds</div>"
            } else {
                var request = new XMLHttpRequest();
                request.open('GET', '/getTime.php?id=' + choosenTicketIdReturrejse, true); // `false` makes the request synchronous
                request.send(null);
                if (request.status === 200) {
                    // TO DO SKAL PARSES, idé: JSON FRA PHP
                    var ticketHTML = request.responseText; //JSON.parse(request.responseText);
                }
            }
        }

        function startUp() {
            var ticketElements = document.getElementsByClassName("ticketTypeNumber");
            for (var i = 0; i < ticketElements.length; i++) {
                ticketElements[i].addEventListener("input", update);
            }

            var turreturElements = document.getElementsByClassName("turreturButton");
            for (var i = 0; i < turreturElements.length; i++) {
                turreturElements[i].setAttribute("onclick", "radioChoose(this)");
            }

            // Click first date button
            document.getElementById("dateContainer").getElementsByTagName("a")[0].click()

            update();
        }

        function radioChoose(origin, doUpdate = true) {
            // Find all elements with same radioclass
            var elements = document.querySelectorAll("[data-radioclass='" + origin.getAttribute('data-radioclass') + "']"); //document.getElementsByClassName(origin.className);
            for (var i = 0; i < elements.length; i++) {
                elements[i].classList.remove("active");
            }
            origin.classList.add("active");
            if (doUpdate) {
                //update();
                "hej";
            }
        }

        function dateChoose(origin) {
            //if (document.getElementByClass("page").id
            var data = origin.getAttribute('data-date');
            var elements = document.querySelectorAll("[data-viewclass]");
            for (var i = 0; i < elements.length; i++) {
                elements[i].getAttribute('data-viewclass') == data ? elements[i].style.display = "block" : elements[i].style.display = "none";
            }
        }

        function complete() {
            var request = new XMLHttpRequest();
            var summaryitems = document.getElementsByClassName("summary-item");

            var postdata = new Object();
            postdata["tickets"] = {};
            for (var i = 0; i < summaryitems.length; i++) {
                postdata["tickets"][summaryitems[i].getAttribute("data-product")] = parseFloat(summaryitems[i].getAttribute("data-product-count"));
            }

            console.log(postdata);

            request = new XMLHttpRequest;
            request.open('POST', './createPayment.php', true); // `false` makes the request synchronous
            request.setRequestHeader("Content-type", "application/json;charset=UTF-8");
            request.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Typical action to be performed when the document is ready:
                    console.log(request.responseText);
                    var response = JSON.parse(request.responseText);

                    window.location = response["hostedPaymentPageUrl"];
                }
            };
            request.send(JSON.stringify(postdata));
        }
    </script>
    <script>
        function msieversion() {
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer, return version number
            {
                alert("Øv! Vi understøtter desværre ikke Internet Explorer...");
            }

            return false;
        }
    </script>
</head>

<body onload="msieversion(); startUp()">
    <img id="logo" src="hvb_logo_margin_negativ.svg" />
    <div class="wrapper">
        <h1 style="height: 140px; margin: 0">Billetter</h1>


        <div class="page" id="page2">
            <h2>Vælg billetter</h2>
            <!-- <div id="turretur" class="section">
                <div class="turreturButton" data-option="enkelt">Enkelt<br /><span>for 35 kroner</span></div>
                <div class="turreturButton active" data-option="retur" style="">Retur<br /><span>for 70 kroner</span></div>
            </div> -->

            <div id="travellers" class="section">
                <span>
                    <span>
                        <span>Voksen 12+ år</span><br />
                        <span class="meta">Kun udrejse: 44 kr.<br />Med returrejse: 70 kr.</span>
                    </span>
                    <input class="ticketTypeNumber" data-ticket-type="Voksen 12+ år" type="number" value="0" onchange="" />
                </span>
                <span>
                    <span>
                        <span>Barn 3-11 år</span><br />
                        <span class="meta">Kun udrejse: 44 kr.<br />Med returrejse: 70 kr.</span>
                    </span>
                    <input class="ticketTypeNumber" data-ticket-type="Barn 3-11 år" type="number" value="0" onchange="" />
                </span>
                <span>
                    <span>
                        <span>Barn 0-2 år</span><br />
                        <span class="meta">Kun udrejse: 35 kr.<br />Med returrejse: 22 kr.</span>
                    </span>
                    <input class="ticketTypeNumber" data-ticket-type="Barn 0-2 år" type="number" value="0" onchange="" />
                </span>
            </div>
        </div>

        <div class="page" id="page1">
            <h2>Vælg dato og tid</h2>
            <div id="dateContainer" style="display: flex; flex-wrap: wrap">

                <?php

                $sql = "SELECT DISTINCT CAST(`stop_departure_time` AS DATE)\n"
                    . "FROM `hvb_stops` WHERE `departures__departure_id` IN (\n"
                    . "    SELECT `departure_id` FROM `hvb_departures` WHERE `trains__train_id` IN (\n"
                    . "        SELECT `train_id` FROM `hvb_trains` WHERE `events__event_id`=1\n"
                    . "	)\n"
                    . ")"
                    . "ORDER BY `stop_departure_time` ASC";

                if ($result = $mysqli->query($sql)) {
                    $allDates = [];

                    // Fetch one and one row
                    while ($row = mysqli_fetch_row($result)) {
                        $date = $row[0];

                        // $allDates to be used later
                        array_push($allDates, $date);

                        //Convert the date string into a unix timestamp.
                        $unixTimestamp = strtotime($date);
                        //Get the day of the week using PHP's date function.
                        $strdate = ucfirst(utf8_encode(strftime("%A <br />d. %d. %B", $unixTimestamp)));

                        " d. %d. %B"

                ?>
                        <a onclick="radioChoose(this); dateChoose(this)" data-radioclass="dag" data-date="<?php print($row[0]) ?>">
                            <?php print($strdate); ?>
                        </a>
                        <!-- <a>Tirsdag<br>d. 15. oktober</a> -->
                <?php
                    }
                    mysqli_free_result($result);
                }

                ?>
            </div>
            <h3>Udrejse</h3>
            <div class="timeContainer">
                <?php
                foreach ($allDates as &$date) {
                ?>
                    <div data-viewclass="<?php print($date); ?>">
                        <?php

                        $firstStops = getFirstStops($mysqli, $date, "outbond");

                        $lastStops = getLastStops($mysqli, $date, "outbond");

                        printDepartureCards($mysqli, $firstStops, $lastStops, "udrejsetid")

                        ?>
                    </div>
                <?php
                }
                ?>
            </div>

            <h3>Returrejse</h3>
            <div class="timeContainer">
                <div>
                    <div class="time" onclick="radioChoose(this)" data-radioclass="hjemrejsetid">
                        <div><span>Ingen - jeg ønsker ingen returbillet</span></div>
                    </div>
                </div>
                <?php
                foreach ($allDates as &$date) {
                ?>
                    <div data-viewclass="<?php print($date); ?>">
                        <?php

                        $firstStops = getFirstStops($mysqli, $date, "homebond");

                        $lastStops = getLastStops($mysqli, $date, "homebond");

                        printDepartureCards($mysqli, $firstStops, $lastStops, "hjemrejsetid")

                        ?>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>




        <div class="page">
            <h2>Opsumering</h2>
            <p>
                Du modtager dine billetter i en PDF-fil via den e-mailadresse du oplyser under betalingen på
                DIBS-betalingsside. Alle priser er i danske kroner (DKK).
            </p>
            <span class="italic">Udrejse</span>
            <div id="summaryUdrejse"></div>
            <span class="italic">Returrejse</span>
            <div id="summaryReturrejse"></div>

            <span class="italic">Valgte billetter</span>
            <div id="summary">

            </div>
            <div style="display: flex; justify-content: space-between; align-items: bottom; margin-top: 1em;">
                <div>
                    <span class="italic">Betaling med DIBS</span><br />
                    <img src="https://cdn.dibspayment.com/logo/checkout/combo/horiz/DIBS_checkout_kombo_horizontal_02.png" alt="DIBS - Payments made easy" width="450" />
                </div>
                <button onclick="complete()" class="proceed" style="font-size: 1em;">Betaling <span class="arrow"></span></button>
            </div>
        </div>
    </div>
</body>

</html>