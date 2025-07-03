<?php
session_start();
$_SESSION['page_produit'] = $_GET['produit'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/mentionslegales.css">
    <script src="https://kit.fontawesome.com/82e270d318.js" crossorigin="anonymous"></script>
    <title>Breaking Bread : Viennese pastries, Pastries</title>
</head>

<body>

    <?php
    include('php/header.php');
    ?>

    <div class="legales">
        <p>All the tabs on BreakingBread are published by SAS BREAKING BREAD.</p>
        <p>Head office : Avenue du Parc, 95000, Cergy.</p>
        <p>Company email address : boulangerie_CYTECH@laposte.net </p>
        <p>Director of Publication : Mariem Mahdi.</p>
        <p>Tel : 07 75 79 74 01</p><br>
        <p>For any product inquiries, suggestions or information, please fill out <a href="contact.php">this form</a></p>
    </div>
    </div>

    <?php
    include('php/footer.php');
    ?>
    <script src="js/index.js"></script>
</body>

</html>