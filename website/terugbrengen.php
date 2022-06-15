<?php
session_start();
include "db.php";
include "getName.php";

$stmt = $conn->prepare("select kastNr from kastjes where uitgeleendDoor='".$_SESSION["rnummer"]."';");
$stmt->execute();
$result = $stmt->fetchAll();

if (!empty($result)){
    $uitgeleendKastje = $result[0][0];
}else{
    $uitgeleendKastje = 0;
}


date_default_timezone_set("Europe/Brussels");
$tijd = date('Y-m-d H:i:s');
$datum = date('Y-m-d');
$sql= "update kastjes set tijd='$tijd', uitgeleend=0, uitgeleendDoor='0' where kastNr=$uitgeleendKastje";
$conn->exec($sql);

$sql="INSERT INTO `terugbrengLog` (`tijd`, `datum`, `rnummer`) VALUES ('$tijd','$datum','".$_SESSION['rnummer']."')";
$conn->exec($sql);

//in de database zetten dat het kastje open mag
date_default_timezone_set("Europe/Brussels");
$tijd = date('Y-m-d H:i:s');
$sql = "update openKastjes set tijd='$tijd', kastNr = '$uitgeleendKastje' where ID=1";
$conn->exec($sql);

?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>laptop uitleen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body class="container">
<div class="center">
    <form action="afmelden.php" method="post">
        <p>Hallo <?= $_SESSION['naam'] ?>,</p>
        <p>Plaats de laptop in kastje <?= $uitgeleendKastje?>.</p>
        <p>Steek de oplader in, sluit het deurtje en meld je af.</p>
        <button type="submit" value="4" name="afmelden" id="afmelden">afmelden</button>
    </form>
</div>
</body>
</html>
