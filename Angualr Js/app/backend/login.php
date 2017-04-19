<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$userCredentials = json_decode(file_get_contents("php://input"), true);

$userName = $userCredentials['userName'];
$password = $userCredentials['passWord'];

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'Impelsys1';
$dbname = 'userdatabase';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

$loginQuery = "SELECT id FROM user_profile WHERE user_name='" . $userName . "' AND password='" . $password . "'";

$loginResult = mysqli_query($conn, $loginQuery);

if (mysqli_num_rows($loginResult) <= 0) {

    $responseArray = array('msg' => 'Invalid credientials', 'statusCode' => 400);
} else {

    $responseArray = array('msg' => 'user Authenticated successfully', 'statusCode' => 200);
}
echo json_encode($responseArray, true);
