<?php
session_start();
include "db.php";
include "getName.php";

$stmt = $conn->prepare("select kastNr, uitgeleendDatum from kastjes where uitgeleendDoor='".$_SESSION["rnummer"]."';");
$stmt->execute();
$result = $stmt->fetchAll();

$stmt = $conn->prepare("select aantalFouten from slechteHuurders where rnummerHuurder='".$_SESSION["rnummer"]."';");
$stmt->execute();
$result2 = $stmt->fetchAll();

if (!empty($result2) and $result2[0][0]==3){//als dit waar is dan heeft de persoon die wilt uitlenen al 3 keer niet goed de laptop teruggebracht.
    header("Location: uitlenenVerboden.php");
}else{
    if(!empty($result)){//als er al een laptop is uitgeleend moet die eerst worden teruggebracht
        $_SESSION["datumUitgeleend"] = $result[0][1];
        header("Location: uitlenenFout.php");
    }else{
        header("Location: uitlenen.php");
    }
}

