<?php
session_start();
include "db.php";
include "findFreeLaptop.php";
include "getName.php";
//in de database zetten dat het kastje open mag
date_default_timezone_set("Europe/Brussels");
$tijd = date('Y-m-d H:i:s');
$sql = "INSERT INTO `openKastjes` (`tijd`, `kastNr`) VALUES ('2022-05-12 10:12:12', ".$_SESSION["vrijeLaptop"].")";
$conn->exec($sql);
$sql= "update kastjes set tijd='$tijd', uitgeleend=1, uitgeleendDoor='".$_SESSION['rnummer']."' , uitgeleendDatum='$tijd' where kastNr=".$_SESSION['vrijeLaptop'];
$conn->exec($sql)
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
        <p>U kan een laptop nemen uit kastje <?= $_SESSION['vrijeLaptop'] ?>.</p>
        <p>Sluit achteraf het deurtje en meld je af.</p>
        <button type="submit" value="3" name="afmelden" id="afmelden">afmelden</button>
    </form>
</div>
</body>
</html>
