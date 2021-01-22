<?php

    $token = "6bLIJbqvEkjqBoUN6wWicuhPegcKR6YG";

    $headers = getallheaders(); // $_SERVER['HTTP_AUTHORIZATION'];


    if($token = $headers["Authorization"]) {
        $txt = file_get_contents("php://input");
    } else {
        $txt = "Uauthorized " . print_r($headers, true);
    }

    print_r($txt);

    file_put_contents('logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);



?>