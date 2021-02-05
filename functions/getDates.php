<?php

function getDates(mysqli $mysqli)
{
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
            array_push($allDates, $date);
        }
        mysqli_free_result($result);
    }

    return $allDates;
}

function printDates(mysqli $mysqli)
{
    $allDates = getDates($mysqli);

    foreach ($allDates as $date) {
        //Convert the date string into a unix timestamp.
        $unixTimestamp = strtotime($date);
        //Get the day of the week using PHP's date function.
        $strdate = ucfirst(utf8_encode(strftime("%A <br />d. %d. %B", $unixTimestamp)));
?>
        <a onclick="radioChoose(this); dateChoose(this)" data-radioclass="dag" data-date="<?php print($date) ?>">
            <?php print($strdate); ?>
        </a>
        <!-- <a>Tirsdag<br>d. 15. oktober</a> -->
<?php
    }
}
