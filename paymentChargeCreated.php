<?php

$token = "6bLIJbqvEkjqBoUN6wWicuhPegcKR6YG"; // Have to be hidden before live
$client_token = $_SERVER['HTTP_AUTHORIZATION'];

if (!$token == $client_token) {
    die("Fatal error");
}
