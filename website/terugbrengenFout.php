<?php
session_start();
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
    <p>Je hebt nog geen laptop uitgeleend en kunt dus ook niets terugbrengen.</p>
    <form action="afmelden.php" method="post">
        <button type="submit" value="7" name="afmelden" id="afmelden">afmelden</button>
    </form>

<!--dit uitvoeren als de laptopnummer die je kan ingeven hieronder ingegeven was maar niet uitgeleend was.-->
    <?php
        if (isset($_SESSION["foutmelding"])){
            ?>
            <div class="alert alert-danger foutmeding mt-5" role="alert" id="uitleenError"><?=$_SESSION["foutmelding"]?></div>
            <?php
        }
    ?>
    <form action="terugbrengenVoorAnder.php" method="post">
        <p>Laptop gevonden of terugbrengen voor iemand anders?</p>
        <div><label for="laptopNr">laptopnummer</label>
            <input type="number" name="laptopNr" id="laptopNr" required></div>
        <div><label for="reden">reden</label>
            <select name="reden" id="reden" required>
                <option disabled selected value>Kies een optie</option>
                <option value="gevonden">laptop gevonden</option>
                <option value="gevraagd">terugbrengen voor iemand</option>
            </select></div>
        <button type="submit" value="6">terugbrengen</button>
    </form>


</div>
</body>
</html>