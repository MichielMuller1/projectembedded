<?php
session_start();
include "db.php";
include "getName.php";

$sql = "update openKastjes set tijd='2022-05-12 10:12:12', kastNr = 'A' where ID=1";
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
        <p>Alle kastjes zijn nu open.</p>
        <button type="submit" value="3" name="afmelden" id="afmelden">afmelden</button>
    </form>
</div>
</body>
</html>