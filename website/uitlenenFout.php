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
        <p>Je hebt al een laptop uitgeleend op <?= $_SESSION["datumUitgeleend"]?>.</p>
        <p>Breng deze terug voor je een nieuwe uitleend.</p>
        <p>Als dit niet klopt gelieve contact op te nemen met info@laptopkast.be.</p>
        <form action="afmelden.php" method="post">
            <button type="submit" value="5" name="afmelden" id="afmelden">afmelden</button>
        </form>
    </div>
</body>
</html>