<?php
session_start();
include "findFreeLaptop.php"
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
    <form action="adminControl.php" method="post">
        <ol>
            <li>scan je studentenkaart</li>
            <li>kies één van de twee opties</li>
        </ol>
        <div class="d-flex">
            <button type="submit" class="mr-2" id="uitlenen" name="uitlenen" value="1">uitlenen</button>
            <button type="submit" class="ml-2" id="terugbrengen" name="terugbrengen" value="2">terugbrengen</button>
            <button type="submit" class="ml-2" id="alles" name="alles" value="3">Alles open</button>
        </div>
        <div class="alert alert-danger foutmeding mt-5" role="alert" id="uitleenError">Er zijn geen laptops meer vrij.</div>
        <!--            <div class="alert alert-danger foutmeding mt-5" role="alert" id="terugbrengError">Alle laptops zijn nog vrij.</div>-->
    </form>
</div>


<script>
    var buttonUitleen = document.getElementById("uitlenen");
    var uitleenError = document.getElementById("uitleenError");

    var buttonTerugbrengen = document.getElementById("terugbrengen");
    var terugbrengError = document.getElementById("terugbrengError");
    <?php
    if ($_SESSION['vrijeLaptop'] == 0){
    ?>
    buttonUitleen.setAttribute('disabled','');
    uitleenError.style.display = "block";
    <?php
    }else{
    ?>
    buttonUitleen.removeAttribute('disabled');
    uitleenError.style.display = "none";
    <?php
    }
    ?>


    <!---->
    <!--        --><?php
    //        if ($vrijeLaptop == 2){
    //        ?>
    //        buttonTerugbrengen.setAttribute('disabled','');
    //        terugbrengError.style.display = "block";
    //        <?php
    //        }else{
    //        ?>
    //        buttonTerugbrengen.removeAttribute('disabled');
    //        terugbrengError.style.display = "none";
    //        <?php
    //        }
    //        ?>
</script>
</body>
</html>