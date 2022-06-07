<?php
include 'db.php';

$stmt = $conn->prepare("select voornaam, achternaam, rnummer  from kaartlezer");
$stmt->execute();
$result = $stmt->fetchAll();

if (!empty($result)){
    $_SESSION["naam"] = $result[0][0]. " ".$result[0][1];
    $_SESSION["rnummer"] = $result[0][2];
}else{
    $_SESSION["naam"] = "no name";
    $_SESSION["rnummer"] = 0;
}