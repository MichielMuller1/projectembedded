<?php
include 'db.php';


if(isset($_POST['afmelden'])){
    //kaartlezer leeg maken
    $sql = "DELETE FROM kaartlezer where ID=1";
    $conn->exec($sql);
    echo $_POST["afmelden"];

    //openkastje moet nog op dicht gezet worden => wat als mensen op afmelden drukken voor ze laptop weg leggen? => na 5minuten automatisch sluiten?
    session_start();
    session_unset();
    session_destroy();

    header("Location: index.php");
}
