<?php
include 'db.php';

$stmt = $conn->prepare("select kastNr,uitgeleend from kastjes");
$stmt->execute();
$result = $stmt->fetchAll();

//telt hoeveel rijen er zijn
$sql = "SELECT COUNT(*) FROM kastjes";
$res = $conn->query($sql);
$count = $res->fetchColumn();

$_SESSION["vrijeLaptop"] = 0;


if (!empty($result)){

    for ($x = 0; $x < $count; $x++) {

        if($result[$x][1]==0){
            $_SESSION["vrijeLaptop"] = $x+1;
            break; //1 vrije laptop vinden is genoeg
        }
    }
}
