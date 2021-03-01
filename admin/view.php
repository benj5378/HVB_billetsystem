<?php

require_once("authenticate.php");

chdir("./../");
require_once("./connect.php");
require_once "./functions/getDates.php";
require_once "./functions/getStops.php";
require_once "./functions/getPassengers.php";

?>


<html>

<head>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="./../styles.css">
    <style>
        .border {
            box-shadow: 
                2px 0 0 0 black, 
                0 2px 0 0 black, 
                2px 2px 0 0 black,   /* Just to fix the corner */
                2px 0 0 0 black inset, 
                0 2px 0 0 black inset;
            padding: 0.1em;
            /* border: 2px solid black; */
        }

        @media print {
            div {
                font-size: 0.9em;
            }
        }
    </style>
</head>

<body>
    <h2>Se alle afgange</h2>
    <?php
        $dates = getDates($mysqli);
        
        ?> <div> <?php
        foreach ($dates as $date) {
            ?>
            <div style="display: flex"> 
                <div class="border" style="width: 170px">
                    <?php print($date); ?>
                </div>
                <div class="border">
                <?php

                $firstStops = getFirstStops($mysqli, $date);
                foreach ($firstStops as $firstStop) {
                    ?> <div class="border" style="display: flex"> <?php
                        ?>
                        <div style="width: 350px"> <?php print($firstStop[1] . " fra " . $firstStop[0]); ?> </div>
                        <div style="width: 280px; padding-left: 1em;">
                            <?php
                            $departureId = $firstStop[2];
                            $passengerCount = getPassengerCountSorted($mysqli, $departureId);
                            foreach ($passengerCount as $ticketType => $ticketCount) {
                                print("$ticketType: $ticketCount<br />");
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }

                ?>
                </div>
            </div>
            <br />
            <?php
        }
    ?>

</body>

</html>