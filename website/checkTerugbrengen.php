<?php
session_start();
include "db.php";
include "getName.php";

$stmt = $conn->prepare("select kastNr from kastjes where uitgeleendDoor='".$_SESSION["rnummer"]."';");
$stmt->execute();
$result = $stmt->fetchAll();


if(empty($result)){//als er geen resultaten zijn was er niets uitgeleend en kan er dus ook niets teruggebracht worden
    header("Location: terugbrengenFout.php");
}else{
    header("Location: terugbrengen.php");
}