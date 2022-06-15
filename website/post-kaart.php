<?php
$servername = "localhost";
$dbname = "laptops";
$username = "ubuntu";
$password = "Patje123";

$api_key_value = "tPmAT5Ab3j7F7";

$api_key= $num = $rnum = $vn = $an = $ad = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        $sqlget = "SELECT * FROM kaarten";
        if ($result = $conn->query($sql)) {
            $arr = [];
            $inc = 0;
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
            $arr[$inc] = $data;
            $inc++;
            }
            header('Content-type: application/json');
	        echo json_encode($arr);
    
            $result->free();
        }

        $num = test_input($_POST["num"]);

        for($x = 0; $x < count($arr); $x++){
            echo $arr;
        }




        
        
        // $t=time();
        // $date = "'" . date("Y-m-d H:i:s",$t) . "'";
        
        // $sql = "INSERT INTO `kaartlezer` (`ID`, `tijd`, `kaartnummer`, `rnummer`, `voornaam`, `achternaam`, `admin`) VALUES (NULL, $date, $num, $rnum, $vn, $an, $ad)";
        
        // if ($conn->query($sql) === TRUE) {
        //     echo "New record created successfully";
        // } 
        // else {
        //     echo "Error: " . $sql . "<br>" . $conn->error;
        // }
    
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
