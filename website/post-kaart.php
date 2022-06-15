<?php
$servername = "localhost";
$dbname = "laptops";
$username = "ubuntu";
$password = "Patje123";

$api_key_value = "tPmAT5Ab3j7F7";

$api_key= $num = $rnum = $vn = $an = $ad = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $num = test_input($_POST["num"]);
               
        $t=time();
        $date = "'" . date("Y-m-d H:i:s",$t) . "'";
        $rnum = $vn = $an = $ad = "0";
        
        $sql = "INSERT INTO `kaartlezer` (`ID`, `tijd`, `kaartnummer`, , `rnummer`, `voornaam`, `achternaam`, `admin`) VALUES (1, $date, $num, $rnum, $vn, $an, $ad)";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
         $conn->close();
    
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
