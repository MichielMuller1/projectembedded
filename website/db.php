<?php
$sName = "10.1.0.57";
$uName = "ubuntu";
$pass = "Patje123";
$db_name = "laptops";
$port = 3306;

try {
$conn = new PDO("mysql:host=$sName;dbname=$db_name", $uName, $pass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo "Connection failed: ". $e->getMessage();
}