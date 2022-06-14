<?php
$servername = "localhost";
$dbname = "laptops";
$username = "ubuntu";
$password = "Patje123";

$api_key_value = "tPmAT5Ab3j7F7";

$api_key= $num = $rnum = $vn = $an = $ad = "0";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    // if($api_key == $api_key_value) {
        $num = test_input($_POST["num"]);
        $rnum = test_input($_POST["rnum"]);
        $vn = test_input($_POST["vn"]);
        $an = test_input($_POST["an"]);
        $ad = test_input($_POST["ad"]);

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        $t=time();
        $date = '"'. date("Y-m-d H:i:s",$t) .'"';
        
        $sql = "INSERT INTO kaartlezer (tijd, kaartnummer, rnummer, voornaam, achternaam, admin)
        VALUES ($date, $num, $rnum, $vn, $an, $ad)";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();
    // }
    // else {
    //     echo "Wrong API Key provided.";
    // }

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
