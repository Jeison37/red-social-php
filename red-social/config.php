<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "rsocial";


$conn = new mysqli( $hostname,
 $username,
 $password,
 $database);

if ($conn->connect_error) echo "Error: $conn->connect_error";
