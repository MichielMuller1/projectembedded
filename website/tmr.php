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

$sql = "SELECT * FROM kaarten, kastjes, openKastjes, slechteHuurders";

if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $row_uid = $row["uid"];
        $row_rnummer = $row["rnummer"];
		$row_voornaam = $row["voornaam"];
        $row_achternaam = $row["achternaam"];
	$row_kastNr = $row["kastNr"];
    $row_oplaadStatus = $row["oplaadStatus"];
    $row_solenoidStatus = $row["solenoidStatus"];
    $row_magneetStatus = $row["magneetStatus"]; 
	$row_uitgeleend = $row["uitgeleend"]; 
	$row_uitgeleendDoor = $row["uitgeleendDoor"]; 
	$row_uitgeleendDatum = $row["uitgeleeendDatum"];
	$row_kastNrOpen = $row["kastNrOpen"];
	$row_rnummerHuurder = $row["rnummerHuurder"];
	$row_aantalFouten = $row["aantalFouten"];
	$row_rnummerTerugbrenger = $row["rnummerTerugbrenger"];
	
		
		
		
		
		$data = array(
		'uid' => $row_uid,
		'rnummer' => $row_rnummer, 
		'voornaam' => $row_voornaam, 
		'achternaam' => $row_achternaam, 
		'kastNr' => $row_kastNr, 
		'oplaadStatus' => $row_oplaadStatus,
		'solenoidStatus' => $row_solenoidStatus,
		'magneetStatus' => $row_magneetStatus,
		'uitgeleend' => $row_uitgeleend,
		'uitgeleendDoor' => $row_uitgeleendDoor,
		'uitgeleendDatum' => $row_uitgeleendDatum,
		'kastNrOpen' => $row_kastNrOpen,
		'rnummmerHuurder' => $row_rnummerHuurder,
		'aantalFouten' => $row_aantalFouten,
		'rnummerTerugbrenger' => $row_rnummerTerugbrenger
			     );
		header('Content-type: text/javascript');
		echo json_encode($data);
    }
    $result->free();
}


$conn->close();
?>
