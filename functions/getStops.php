<?php

function getFirstStops($mysqli, $date, $departure_type)
{

    // The use of MAX/MIN is a hotfix! Special conditions for MAX in MySQL: https://stackoverflow.com/questions/17776693/why-doesnt-my-content-field-match-my-maxid-field-in-mysql

    // Get first stop
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


function getLastStops($mysqli, $date, $departure_type)
{
    global $mysqli;

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

function printDepartureCards($firstStops, $lastStops, $radioclass)
{

    // Check that results line up
    if (count($firstStops) != count($lastStops)) {
        die("Fatal error: Not matching queries!!");
    }

    for ($i = 0; $i < count($firstStops); $i++) {
        if ($firstStops[$i][2] != $lastStops[$i][2]) {
            die("Fatal error: Departures not matching!");
        }

        $startStop = $firstStops[$i][0];
        $startStopTime = mb_substr($firstStops[$i][1], 0, 5);
        $endStop = $lastStops[$i][0];
        $endStopTime = mb_substr($lastStops[$i][1], 0, 5);
        $departureId = $firstStops[$i][2];

?>
        <div class="time" onclick="radioChoose(this)" data-radioclass="<?php print($radioclass) ?>" data-departureId="<?php print($departureId) ?>">
            <div><span>
                    <?php print($startStopTime) ?><span> fra
                        <?php print($startStop) ?>
                    </span>
                </span><span style="float: right"><span>til
                        <?php print($endStop) ?>
                    </span>
                    <?php print($endStopTime) ?>
                </span></div>
            <div class="type"><span>Motor</span></div>
            <div><button class="timetable">Tidsplan️</button><button onclick="chooseStart()" class="buy proceed">Vælg <span class="arrow"></span></button></div>
        </div>
<?php

    }
}
