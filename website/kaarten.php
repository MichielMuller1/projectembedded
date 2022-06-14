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

$sql = "SELECT * FROM kaarten";

if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $row_uid = $row["UID"];
        $row_rnummer = $row["rnummer"];
		$row_voornaam = $row["voornaam"];
        $row_achternaam = $row["achternaam"];
	
		
		
		
		
		$data = array(
		'uid' => $row_uid,
		'rnummer' => $row_rnummer, 
		'voornaam' => $row_voornaam, 
		'achternaam' => $row_achternaam
			     );
		header('Content-type: application/json');
		echo json_encode($data);
    }
    $result->free();
}


$conn->close();
?>
