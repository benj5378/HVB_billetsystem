<?php

function refinePassengers($passengers)
{
    $refinedPassengers = array();

    foreach ($passengers as $passenger => $amount) {
        $refinedPassenger = str_replace(" retur", "", str_replace(" enkelt", "", $passenger));
        $refinedPassengers[$refinedPassenger] = $amount;
    }

    return $refinedPassengers;
}
