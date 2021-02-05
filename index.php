<?php
header('Content-Type: text/html; charset=UTF-8');

setlocale(LC_ALL, 'dan'); // Windows
// setlocale(LC_ALL, 'da_DK'); // Linux

include_once("./credentials.php");

// Create connection
$mysqli = new mysqli($servername, $username, $password, $db);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

require_once "functions/getStops.php"


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="styles.css">

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

            var choosenTicketUdrejse_departureId = document.querySelectorAll("[data-radioclass='udrejsetid'].active")[0].getAttribute("data-departureid");

            if (choosenTicketUdrejse_departureId != null) {
                console.log("ved ud");

                var request_a = new XMLHttpRequest();
                request_a.onreadystatechange = function() {
                    if (request_a.status === 200) {
                        console.log("started ud");

                        // TO DO SKAL PARSES, idé: JSON FRA PHP
                        var ticketHTML = request_a.responseText; //JSON.parse(request_a.responseText);


                        document.getElementById("summaryUdrejse").innerHTML = ticketHTML;
                        console.log("finished ud");
                    }
                }
                request_a.open('GET', '/getTime.php?departureid=' + choosenTicketUdrejse_departureId, true);
                request_a.send(null);
            }

            // - DO TIME SUMMARY RETURREJSE



            if (document.getElementById("ingenReturrejse").classList.contains("active")) {
                console.log("Ingen returrejse");

                var choosenTicketReturrejse_departureId = document.querySelectorAll("[data-radioclass='returrejsetid'].active")[0].getAttribute("data-departureid");

                var request_b = new XMLHttpRequest();
                request_b.onreadystatechange = function() {
                    if (request_b.status === 200) {
                        // TO DO SKAL PARSES, idé: JSON FRA PHP
                        var ticketHTML = request_b.responseText; //JSON.parse(request_b.responseText);

                        document.getElementById("summaryReturrejse").innerHTML = ticketHTML;
                        console.log("finished retur");
                    }
                }
                request_b.open('GET', '/ingenReturrejse.php', true);
                request_b.send(null);
            } else {
                var choosenTicketReturrejse_departureId = document.querySelectorAll("[data-radioclass='returrejsetid'].active")[0].getAttribute("data-departureid");

                var request_b = new XMLHttpRequest();
                request_b.onreadystatechange = function() {
                    if (request_b.status === 200) {
                        // TO DO SKAL PARSES, idé: JSON FRA PHP
                        var ticketHTML = request_b.responseText; //JSON.parse(request_b.responseText);

                        document.getElementById("summaryReturrejse").innerHTML = ticketHTML;
                        console.log("finished retur");
                    }
                }
                request_b.open('GET', '/getTime.php?departureid=' + choosenTicketReturrejse_departureId, true);
                request_b.send(null);
            }
        }

        function startUp() {
            document.getElementById("timeContainerUdrejse").getElementsByTagName("div")[0].getElementsByTagName("div")[0].click();
            document.getElementById("timeContainerReturrejse").getElementsByTagName("div")[0].getElementsByTagName("div")[0].click();



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
                update();
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

            var departureIdUdrejse = document.getElementById("summaryUdrejse").getElementsByTagName("div")[0].getAttribute("data-departureid");
            postdata["udrejse_departureId"] = departureIdUdrejse;

            var departureIdReturrejse = document.getElementById("summaryReturrejse").getElementsByTagName("div")[0].getAttribute("data-departureid");
            postdata["returrejse_departureId"] = departureIdReturrejse;


            console.log(JSON.stringify(postdata));

            request = new XMLHttpRequest;
            request.open('POST', './createPayment.php', true); // `false` makes the request synchronous
            request.setRequestHeader("Content-type", "application/json;charset=UTF-8");
            request.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Typical action to be performed when the document is ready:

                    try {
                        var response = JSON.parse(request.responseText);
                    } catch {
                        alert(request.responseText);
                        console.log(request.responseText);
                    }

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
    <div class="wrapper" style="padding-bottom: 1em">
        <h1 style="height: 140px; margin: 0">Billetter</h1>


        <div class="page" id="page2">
            <h2>Vælg billetter</h2>

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
            <div id="timeContainerUdrejse" class="timeContainer">
                <?php
                foreach ($allDates as &$date) {
                ?>
                    <div data-viewclass="<?php print($date); ?>">
                        <?php

                        $firstStops = getFirstStops($mysqli, $date, "outbond");

                        $lastStops = getLastStops($mysqli, $date, "outbond");

                        printDepartureCards($mysqli, $firstStops, $lastStops, "udrejsetid");

                        ?>
                    </div>
                <?php
                }
                ?>
            </div>

            <h3>Returrejse</h3>
            <div id="timeContainerReturrejse" class="timeContainer">
                <div>
                    <?php require "ingenReturrejse.php" ?>
                </div>
                <?php
                foreach ($allDates as &$date) {
                ?>
                    <div data-viewclass="<?php print($date); ?>">
                        <?php

                        $firstStops = getFirstStops($mysqli, $date, "homebond");

                        $lastStops = getLastStops($mysqli, $date, "homebond");

                        printDepartureCards($mysqli, $firstStops, $lastStops, "returrejsetid")

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
            <div id="summaryUdrejse">
            </div>
            <span class="italic">Returrejse</span>
            <div id="summaryReturrejse">
            </div>

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