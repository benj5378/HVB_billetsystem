<?php

require_once("getDepartureInfo.php");

function getFirstStops(mysqli $mysqli, string $date, string $departure_type = "")
{

    // The use of MAX/MIN is a hotfix! Special conditions for MAX in MySQL: https://stackoverflow.com/questions/17776693/why-doesnt-my-content-field-match-my-maxid-field-in-mysql

    // Get first stop

    // Overwrite sql if no departuretype is gived
    if ($departure_type == "") {
        $sql = "SELECT `stop_name`, CAST(`stop_departure_time` as TIME), `departures__departure_id` FROM `hvb_stops`\n"
            . "WHERE\n"
            . "    `stop_departure_time` IN (\n"
            . "        SELECT MIN(`stop_departure_time`) FROM `hvb_stops` GROUP BY `departures__departure_id`\n"
            . "    )\n"
            . "AND\n"
            . "`departures__departure_id` IN (\n"
            . "    SELECT `departure_id` FROM `hvb_departures` WHERE\n"
            . "    `trains__train_id` IN (\n"
            . "        SELECT `train_id` FROM `hvb_trains` WHERE `events__event_id`=1\n"
            . ")\n"
            . ")\n"
            . "AND\n"
            . "CAST(`stop_departure_time` AS DATE) = ?\n"
            . "ORDER BY `stop_departure_time` ASC";
        $stmtFirstStops = $mysqli->prepare($sql);
        $stmtFirstStops->bind_param("s", $date);
    } else {
        $sql = "SELECT `stop_name`, CAST(`stop_departure_time` as TIME), `departures__departure_id` FROM `hvb_stops`\n"
            . "WHERE\n"
            . "    `stop_departure_time` IN (\n"
            . "        SELECT MIN(`stop_departure_time`) FROM `hvb_stops` GROUP BY `departures__departure_id`\n"
            . "    )\n"
            . "AND\n"
            . "`departures__departure_id` IN (\n"
            . "    SELECT `departure_id` FROM `hvb_departures` WHERE\n"
            . "    `trains__train_id` IN (\n"
            . "        SELECT `train_id` FROM `hvb_trains` WHERE `events__event_id`=1\n"
            . ")\n"
            . "    AND\n"
            . "    `departure_type` = ?\n"
            . ")\n"
            . "AND\n"
            . "CAST(`stop_departure_time` AS DATE) = ?\n"
            . "ORDER BY `stop_departure_time` ASC";
        $stmtFirstStops = $mysqli->prepare($sql);
        $stmtFirstStops->bind_param("ss", $departure_type, $date);
    }


    $stmtFirstStops->execute(); // execute */
    $firstStopsResult = $stmtFirstStops->get_result();


    $firstStops = [];
    while ($row = mysqli_fetch_row($firstStopsResult)) {
        // [stop_name, stop_departure_time, departures__departure_id]
        array_push($firstStops, [$row[0], $row[1], $row[2]]);
    }

    mysqli_free_result($firstStopsResult);

    return $firstStops;
}


function getFirstStop(mysqli $mysqli, int $departureId)
{

    // Get first stop
    $sql = "SELECT `stop_name`, CAST(`stop_departure_time` as TIME), `departures__departure_id`, `stop_id` FROM `hvb_stops`\n"
        . "WHERE\n"
        . "`departures__departure_id`=?\n"
        . "ORDER BY `stop_departure_time` ASC\n"
        . "LIMIT 1";


    $stmtFirstStops = $mysqli->prepare($sql);
    $stmtFirstStops->bind_param("i", $departureId);
    $stmtFirstStops->execute(); // execute */
    $firstStopsResult = $stmtFirstStops->get_result();
    $row = mysqli_fetch_row($firstStopsResult);

    $firstStops = [$row[0], $row[1], $row[2], $row[3]]; // stop_name, stop_departure_time, departure_id, stop_id

    mysqli_free_result($firstStopsResult);

    return $firstStops;
}


function getLastStop(mysqli $mysqli, int $departureId)
{
    // Get last stop
    $sql = "SELECT `stop_name`, CAST(`stop_departure_time` as TIME), `departures__departure_id`, `stop_id` FROM `hvb_stops`\n"
        . "WHERE\n"
        . "`departures__departure_id`=?\n"
        . "ORDER BY `stop_departure_time` DESC\n"
        . "LIMIT 1";


    // prepare and bind
    $stmtLastStop = $mysqli->prepare($sql);
    $stmtLastStop->bind_param("i", $departureId);
    $stmtLastStop->execute(); // execute */
    $lastStopResult = $stmtLastStop->get_result();
    $row = mysqli_fetch_row($lastStopResult);

    $lastStop = [$row[0], $row[1], $row[2], $row[3]]; // stop_name, stop_departure_time, departure_id, stop_id

    mysqli_free_result($lastStopResult);

    return $lastStop;
}


function getLastStops(mysqli $mysqli, string $date, string $departure_type)
{
    // Get last stop
    $sql = "SELECT `stop_name`, CAST(`stop_departure_time` as TIME), `departures__departure_id` FROM `hvb_stops`\n"
        . "WHERE\n"
        . "    `stop_departure_time` IN (\n"
        . "        SELECT MAX(`stop_departure_time`) FROM `hvb_stops` GROUP BY `departures__departure_id`\n"
        . "    )\n"
        . "AND\n"
        . "`departures__departure_id` IN (\n"
        . "    SELECT `departure_id` FROM `hvb_departures` WHERE\n"
        . "    `trains__train_id` IN (\n"
        . "        SELECT `train_id` FROM `hvb_trains` WHERE `events__event_id`=1\n"
        . ")\n"
        . "    AND\n"
        . "    `departure_type` = ?\n"
        . ")\n"
        . "AND\n"
        . "CAST(`stop_departure_time` AS DATE) = ?\n"
        . "ORDER BY `stop_departure_time` ASC";


    // prepare and bind
    $stmtLastStops = $mysqli->prepare($sql);
    $stmtLastStops->bind_param("ss", $departure_type, $date);
    $stmtLastStops->execute(); // execute */
    $lastStopsResult = $stmtLastStops->get_result();

    $lastStops = [];
    while ($row = mysqli_fetch_row($lastStopsResult)) {
        // [stop_name, stop_departure_time]
        array_push($lastStops, [$row[0], $row[1], $row[2]]);
    }

    mysqli_free_result($lastStopsResult);

    return $lastStops;
}

function printDepartureCards(mysqli $mysqli, array $firstStops, array $lastStops, string $radioclass)
{
    // Check that results line up
    if (count($firstStops) != count($lastStops)) {
        die("Fatal error: Not matching queries!!");
    }

    $sql = "SELECT `train_locomotive` FROM `hvb_trains` WHERE `train_id` IN (SELECT `trains__train_id` FROM `hvb_departures` WHERE `departure_id`=?)";

    $stmtTraintype = $mysqli->prepare($sql);
    $stmtTraintype->bind_param("i", $departureId);


    for ($i = 0; $i < count($firstStops); $i++) {
        if ($firstStops[$i][2] != $lastStops[$i][2]) {
            die("Fatal error: Departures not matching!");
        }

        $startStop = $firstStops[$i][0];
        $startStopTime = mb_substr($firstStops[$i][1], 0, 5);
        $endStop = $lastStops[$i][0];
        $endStopTime = mb_substr($lastStops[$i][1], 0, 5);
        $departureId = $firstStops[$i][2];

        $stmtTraintype->execute();
        $trainTypeResult = $stmtTraintype->get_result();
        $trainTypeEnum = mysqli_fetch_row($trainTypeResult)[0];
        if ($trainTypeEnum == "motor") {
            $trainType = "Motor";
        } else if ($trainTypeEnum == "damp") {
            $trainType = "Damp";
        }

        if (getAvailableSeats($mysqli, $departureId) <= 0) {
            $disabled = " disabled";
        } else {
            $disabled = "";
        }

?>
        <div class="time<?php print($disabled) ?>" onclick="radioChoose(this);" data-radioclass="<?php print($radioclass) ?>" data-departureId="<?php print($departureId) ?>">
            <div><span>
                    <?php print($startStopTime) ?><span> fra
                        <?php print($startStop) ?>
                    </span>
                </span><span style="float: right"><span>til
                        <?php print($endStop) ?>
                    </span>
                    <?php print($endStopTime) ?>
                </span></div>
            <div class="type"><span><?php print($trainType) ?></span></div>
            <div><button class="timetable">Tidsplan️</button><button onclick="chooseStart()" class="buy proceed">Vælg <span class="arrow"></span></button></div>
        </div>
    <?php

    }
}



function printDepartureCards_special(mysqli $mysqli, array $firstStops, array $lastStops, string $datestr)
{
    // Check that results line up
    if (count($firstStops) != count($lastStops)) {
        die("Fatal error: Not matching queries!!");
    }

    $sql = "SELECT `train_locomotive` FROM `hvb_trains` WHERE `train_id` IN (SELECT `trains__train_id` FROM `hvb_departures` WHERE `departure_id`=?)";

    $stmtTraintype = $mysqli->prepare($sql);
    $stmtTraintype->bind_param("i", $departureId);


    if ($firstStops[2] != $lastStops[2]) {
        die("Fatal error: Departures not matching!");
    }

    $startStop = $firstStops[0];
    $startStopTime = mb_substr($firstStops[1], 0, 5);
    $endStop = $lastStops[0];
    $endStopTime = mb_substr($lastStops[1], 0, 5);
    $departureId = $firstStops[2];

    $stmtTraintype->execute();
    $trainTypeResult = $stmtTraintype->get_result();
    $trainTypeEnum = mysqli_fetch_row($trainTypeResult)[0];
    if ($trainTypeEnum == "motor") {
        $trainType = "Motor";
    } else if ($trainTypeEnum == "damp") {
        $trainType = "Damp";
    }

    $date = date("F j, Y", strtotime($datestr));


    ?>
    <div class="time" data-departureId="<?php print($departureId) ?>">
        <div><span>
                <?php print($startStopTime); ?><span> fra
                    <?php print($startStop); ?>
                </span>
            </span><span style="float: right"><span>til
                    <?php print($endStop); ?>
                </span>
                <?php print($endStopTime); ?>
            </span></div>
        <div class="type"><span><?php print($trainType); ?></span></div>
        <div><span><?php print($date); ?></span></div>
    </div>
<?php

}
