<?php

function getPassengerCountSorted(mysqli $mysqli, int $departureId) {
    $sql = "SELECT\n"
        . "    `ticket_type`,\n"
        . "    count(`ticket_type`)\n"
        . "FROM\n"
        . "    `hvb_passengers`\n"
        . "WHERE\n"
        . "    `tickets__ticket_id` IN (\n"
        . "        SELECT\n"
        . "            `ticket_id`\n"
        . "        FROM\n"
        . "            `hvb_ticket`\n"
        . "        WHERE\n"
        . "            `ticket_start__stops__stop_id` IN (\n"
        . "                SELECT\n"
        . "                    `stop_id`\n"
        . "                FROM\n"
        . "                    `hvb_stops`\n"
        . "                WHERE\n"
        . "                    `departures__departure_id` = ?\n"
        . "            )\n"
        . "    )\n"
        . "GROUP by\n"
        . "    `ticket_type`\n"
        . "ORDER BY\n`"
        . "ticket_type` DESC";


    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $departureId);
    $stmt->execute();
    $result = $stmt->get_result();

    $counts = [];

    // Fetch one and one row
    while ($row = mysqli_fetch_row($result)) {
        $ticketType = $row[0];
        $ticketCount = $row[1];
        $counts[$ticketType] = $ticketCount;
    }
    mysqli_free_result($result);

    return $counts;
}
