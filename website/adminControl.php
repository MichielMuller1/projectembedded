<?php
session_start();
include 'db.php';

if(isset($_POST['uitlenen'])){
    header("Location: checkUitlenen.php");
}
if(isset($_POST['terugbrengen'])){
    header("Location: checkTerugbrengen.php");
}
if (isset($_POST['alles'])){
    header("Location: allesOpen.php");
}