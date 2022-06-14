<?php
session_start();
include 'db.php';


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

