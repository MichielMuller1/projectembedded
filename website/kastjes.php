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

$sql = "SELECT * FROM kastjes";

if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
	$row_kastNr = $row["kastNr"];
    $row_oplaadStatus = $row["oplaadStatus"];
    $row_solenoidStatus = $row["solenoidStatus"];
    $row_magneetStatus = $row["magneetStatus"]; 
	$row_uitgeleend = $row["uitgeleend"]; 
	$row_uitgeleendDoor = $row["uitgeleendDoor"]; 
	$row_uitgeleendDatum = $row["uitgeleeendDatum"];
	
		
		
		$data = array(
		'kastNr' => $row_kastNr, 
		'oplaadStatus' => $row_oplaadStatus,
		'solenoidStatus' => $row_solenoidStatus,
		'magneetStatus' => $row_magneetStatus,
		'uitgeleend' => $row_uitgeleend,
		'uitgeleendDoor' => $row_uitgeleendDoor,
		'uitgeleendDatum' => $row_uitgeleendDatum,
			     );
		header('Content-type: text/javascript');
		echo json_encode($data);
    }
    $result->free();
}


$conn->close();
?>
