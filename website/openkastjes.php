<?php

$servername = "localhost";

// REPLACE with your Database name
$dbname = "laptops";
// REPLACE with Database user
$username = "ubuntu";
// REPLACE with Database user password
$password = "Patje123";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM openKastjes";

if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
		$arr = [];
    	$inc = 0;
		$row_kastNr = $row["kastNr"];
	
		
		
		$data = array(
		'kastNr' => $row_kastNr
			     );
	$arr[$inc] = $data;
    $inc++;
	}

	header('Content-type: application/json');
	echo json_encode($arr);

    $result->free();
}


$conn->close();
?>