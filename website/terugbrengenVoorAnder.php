<?php
session_start();
include "db.php";
include "getName.php";
$laptopNr = $_POST["laptopNr"];
$reden = $_POST["reden"];
$stmt = $conn->prepare("select uitgeleendDoor from kastjes where kastNr=$laptopNr");
$stmt->execute();
$result = $stmt->fetchAll();
$uitgeleendDoor = $result[0][0];
$_SESSION["SlechteHuurder"] = $uitgeleendDoor;

date_default_timezone_set("Europe/Brussels");
$tijd = date('Y-m-d H:i:s');

$terugbrenger = $_SESSION["rnummer"];
$_SESSION["terugbrenger"] = $terugbrenger;

$_SESSION["gevondenLaptopNr"] = $laptopNr;


if(!empty($result) and $result[0][0]!="0"){
    if($reden=="gevonden"){//als een gevonden laptop wordt teruggebracht wordt de persoon die hem had uitgeleend geflaged
        echo "gevonden";
//        //checken of de persoon die fout uitleende al in de database zat.
        $stmt = $conn->prepare("select ID,aantalFouten from slechteHuurders where rnummerHuurder='$uitgeleendDoor'");
        $stmt->execute();
        $result2 = $stmt->fetchAll();
        if(!empty($result2)){//als de persoon al op de lijst staat updaten
            echo "al op de lijst";
            $aantalFouten = $result2[0][1];
            $aantalFouten +=1;
            $id = $result2[0][0];
            $sql = "update slechteHuurders set tijd='$tijd' ,rnummerTerugbrenger='$terugbrenger',aantalFouten=$aantalFouten where ID=$id ";
            $conn->exec($sql);
            header("Location: terugbrengenVoorAnderGechecked.php");
        }else{//staat nog niet op de lijst dus inserten
            $sql = "insert into slechteHuurders (tijd,rnummerHuurder,aantalFouten,rnummerTerugbrenger) values ('$tijd','$uitgeleendDoor',1,'$terugbrenger')";
            $conn->exec($sql);
            header("Location: terugbrengenVoorAnderGechecked.php");
        }
    }else{//laptop wordt gewoon teruggebracht door iemand anders
        header("Location: terugbrengenVoorAnderGechecked.php");
    }

}else{//de laptop die ze proberen terugbrengen is niet uitgeleend
    $_SESSION["foutmelding"] = "De laptop nummer die u ingaf is niet uitgeleend.";
    header("Location: terugbrengenFout.php");
}



//laptop moet op teruggebracht staan
//ergens moet komen wie de laptop terugbracht
//als de laptop gevonden was moet er opgeslagen worden wie hem kwijt was en wie hem terugbracht