<?php

//Initialize the session
$curlSession = curl_init();

$url = "http://www.facebook.com";

//Set options
curl_setopt($curlSession, CURLOPT_URL, $url);

curl_setopt($curlSession, CULROPT_RETURNTRANSFER, 1);

curl_setopt($curlSession, CURLOPT_HEADER, FALSE);

//Execute the curl
$result = curl_exec($curlSession);

if ($result === FALSE) {

    echo "cURL Error: " . curl_error($curlSession);
}

curl_close($curlSession);
$responce = adb_rest("POST", "/_api/collection", "", '{"name" : "test"}');
print_r($result);
die;
