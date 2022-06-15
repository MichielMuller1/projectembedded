<?php
session_start();
include 'db.php';
date_default_timezone_set("Europe/Brussels");
$tijd = date('Y-m-d H:i:s');

$stmt = $conn->prepare("select ID,kaartnummer from kaartlezer");
$stmt->execute();
$result1 = $stmt->fetchAll();
echo $result1[0][1];

if (empty($result1)){
    header("Location: indexFoutmelding.php");
}

$kaartnummer = $result1[0][1];

$stmt = $conn->prepare("select rnummer,voornaam,achternaam,admin from kaarten where UID = '$kaartnummer'");
$stmt->execute();
$result2 = $stmt->fetchAll();

if (empty($result2)){
    $sql = "DELETE FROM kaartlezer where ID=1";
    $conn->exec($sql);
    header("Location: indexFoutmelding.php");
}

$rnummer = $result2[0][0];
$voornaam = $result2[0][1];
$achternaam = $result2[0][2];
$admin = $result2[0][3];

$sql="delete from `kaartlezer` where ID=1";
$conn->exec($sql);

$sql="INSERT INTO `kaartlezer` (`ID`, `tijd`, `kaartnummer`, `rnummer`, `voornaam`, `achternaam`, `admin`) VALUES ('1', '$tijd', '$kaartnummer', '$rnummer', '$voornaam', '$achternaam', $admin)";
$conn->exec($sql);


$stmt = $conn->prepare("select * from kaartlezer");
$stmt->execute();
$result = $stmt->fetchAll();


//    print_r($result);
//    echo $result[0][1] . "   " . $result[0][2];


if (!empty($result)) {
    $_SESSION['rnummer'] = $result[0][3];
    $_SESSION['admin'] = $result[0][6];
    if ($_SESSION['rnummer']=="u0140140"){

        header("Location: Quinten.php");
    }else{
        if ($_SESSION['admin']==1){
            header("Location: adminIndex.php");

        }else{
            if(isset($_POST['uitlenen'])){
                header("Location: checkUitlenen.php");
            }
            if(isset($_POST['terugbrengen'])){
                header("Location: checkTerugbrengen.php");
            }
        }
    }




}else{
    $_SESSION['rnummer'] = 0;
    header("Location: indexFoutmelding.php");
}

