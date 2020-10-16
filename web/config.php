<?php
$servername = "localhost";
$username = "root";
$dbname = "automatizacao_aerador";
$password = "123";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error)
	die("Connection failed: " . $conn->connect_error);

mysqli_autocommit($conn,FALSE);
$language = "SET lc_messages = 'pt_BR';";

$language = $conn->query($language);
mysqli_commit($conn);