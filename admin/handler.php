<?php

    include_once("./credentials.php");

    // Create connection
    $mysqli = new mysqli($servername, $username, $password, $db);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $json = json_decode(file_get_contents('php://input'), true);

    // Check if all trains are in train_types
    for($i = 0; $i < count($json["stop_data"]); $i++) {
        $train_name = $json["stop_data"][$i]["train"];
        if(!(array_key_exists($train_name, $json["train_types"]))) {
            die("Missing train_types");
        }
    }

    // Check if all trains have expected value "motor" or "damp"
    $trains_from_json = $json["train_types"];

    print_r($json["train_types"]);
    
    foreach($trains_from_json as &$train) {
        if(!($train == "motor" or $train == "damp")) {
            die("Unexpected train types");
        }
    }

    // Check if all values except train is valid
    $stop_data_from_json = $json["stop_data"];
    // Loop through departures
    foreach($stop_data_from_json as &$departure) {
        // Loop through stops
        foreach(array_keys($departure) as $stopkey) {
            // First key is for compartments, skip it!
            if($stopkey == "compartments" || $stopkey == "train") {
                continue;
            }

            $time = $departure[$stopkey];

            if(!preg_match('#^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $time)) {
                die("Unexpected times: " . $time);
            }
        }
    }

    // Check if date is valid
    $date = $json["date"];
    // Loop through departures
    if(!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $date)) {
        die("Unexpected date: " . $date);
    }

    // prepare and bind              "INSERT INTO `hvb_trains` (`train_id`, `train_seats`, `train_locomotive`, `train_compartments`, `events__event_id`) VALUES (NULL, '25', 'motor', '5', '1')"
    $stmtTrain = $mysqli -> prepare("INSERT INTO `hvb_trains` (`train_id`, `train_seats`, `train_locomotive`, `train_compartments`, `events__event_id`) VALUES (?, ?, ?, ?, ?)");
    // "VALUES (NULL, '25', 'motor', '5', '1')"
    //          i      i     s        i    i
    $stmtTrain -> bind_param("iisii", $train_id, $train_seats, $train_locomotive, $train_compartments, $events__event_id);

    // prepare and bind                 "INSERT INTO `hvb_departures` (`departure_id`, `trains__train_id`) VALUES (NULL, '')"
    $stmtDeparture = $mysqli -> prepare("INSERT INTO `hvb_departures` (`departure_id`, `trains__train_id`) VALUES (?, ?)");
    // VALUES (NULL, <train id>)
    //         i      i
    $stmtDeparture -> bind_param("ii", $departure_id, $trains__train_id);

    // prepare and bind            "INSERT INTO `hvb_stops` (`stop_id`, `stop_name`, `stop_departure_time`, `departures__departure_id`) VALUES (NULL, 'Hedehusgård', '2020-11-03 13:09:05', '1')"
    $stmtStop = $mysqli -> prepare("INSERT INTO `hvb_stops` (`stop_id`, `stop_name`, `stop_departure_time`, `departures__departure_id`) VALUES (?, ?, ?, ?)");
    // VALUES (NULL, 'Hedehusgård', '2020-11-03 13:09:05', '1')"
    //         i      s              s                      i
    $stmtStop -> bind_param("issi", $stop_id, $stop_name, $stop_departure_time, $departures__departure_id);



    foreach(array_keys($trains_from_json) as &$trainkey) {    
        // set parameters and execute
        $train_id = NULL; // Assign train_id by itself
        $train_seats = 0;
        $train_locomotive = $trains_from_json[$trainkey]; // "motor" or "damp"
        $train_compartments = 0;
        $events__event_id = 1; // Plantog is always the event with event_id = 1
        $stmtTrain -> execute(); // execute

        $assigned_train_id = $mysqli->insert_id;

        print("<TRAIN " . $trainkey . ">");
        
        foreach($stop_data_from_json as &$departure) {
            // Only do the departures that matches $train. $train corresponds to $assigned_train_id. Else skip
            if(!($departure["train"] == $trainkey)) {
                continue;
            }

            // Create departure
            $departure_id = NULL;
            $trains__train_id = $assigned_train_id;
            $stmtDeparture -> execute();

            $assigned_departure_id = $mysqli->insert_id;

            print("<DEPARTURE " . $trainkey . ">");

            // Create stops
            // Loop through stopkeys
            foreach(array_keys($departure) as $stopkey) {
                // First key is for compartments, skip it!
                if($stopkey == "compartments" || $stopkey == "train") {
                    continue;
                }
                $stop_id = NULL;
                $stop_name = $stopkey;
                $stop_departure_time = $date . " " . $departure[$stopkey];
                $departures__departure_id = $assigned_departure_id;
                
                if($stmtStop -> execute()) {
                    $ret = "success";
                } else {
                    $ret = "failure";
                }

                print("<STOP " . $trainkey . " " . $departures__departure_id . " " . $ret . " " . $stop_id . " " . $stop_name . " " . $stop_departure_time . " " . $departures__departure_id . ">\xA");
            }
        }
    }
    
    
    $stmtTrain -> close();
    $stmtDeparture -> close();
    $stmtStop -> close();
    $mysqli -> close();

    // $query = sprintf("INSERT INTO `hvb_trains` (`train_id`, `train_seats`, `train_locomotive`, `train_compartments`, `events__event_id`) VALUES (NULL, '25', 'motor', '5', '1')",
    //     mysql_real_escape_string($user),
    //     mysql_real_escape_string($password)
    // );

    print_r($json);

?>