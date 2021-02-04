<?php

function refinePassengers(array $passengers)
{
    $refinedPassengers = array();

    foreach ($passengers as $passenger => $count) {
        $refinedPassenger = str_replace(" retur", "", str_replace(" enkelt", "", $passenger));
        $refinedPassengers[$refinedPassenger] = $count;
    }

    return $refinedPassengers;
}

function getPassengerCount(array $passengers)
{
    $totalCount = 0;
    foreach ($passengers as $passenger => $count) {
        $totalCount += $count;
    }

    return $totalCount;
}
