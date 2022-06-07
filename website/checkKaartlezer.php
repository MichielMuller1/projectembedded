<?php
session_start();
include 'db.php';

$stmt = $conn->prepare("select * from kaartlezer");
$stmt->execute();
$result = $stmt->fetchAll();
//    print_r($result);
//    echo $result[0][1] . "   " . $result[0][2];


if (!empty($result)){
$_SESSION['rnummer'] = $result[0][3];

if(isset($_POST['uitlenen'])){
    header("Location: checkUitlenen.php");
}
if(isset($_POST['terugbrengen'])){
    header("Location: checkTerugbrengen.php");
}

}else{
    $_SESSION['rnummer'] = 0;
    header("Location: indexFoutmelding.php");
}

