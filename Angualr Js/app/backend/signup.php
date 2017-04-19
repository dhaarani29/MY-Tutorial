<?php

$customer = json_decode(file_get_contents("php://input"), true);

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'Impelsys1';
$dbname = 'userdatabase';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die('Could not connect: ' . mysql_error());
} else {
    $insertQuery = "INSERT INTO user_profile (user_name,email_address,password,sex,nationality,state,user_image) VALUES ("
            . "'" . $customer['userName'] . "',"
            . "'" . $customer['emailAddress'] . "',"
            . "'" . $customer['passWord'] . "',"
            . "'" . $customer['sex'] . "',"
            . "'" . isset($customer['nationality'])? $customer['nationality']:'0'. "',"
            . "'" . $customer['state'] . "',"
            . "'" . $customer['userImage'] . "')";

    $insert = mysqli_query($conn, $insertQuery);

    if (!$insert) {
        http_response_code(400);
        $responseArray = array('msg' => 'Error to create the user');
    } else {
        http_response_code(201);
        $responseArray = array('msg' => 'success fully created');
    }

    echo json_encode($responseArray, true);
}



