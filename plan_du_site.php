<?php
//fait
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/plan_du_site.css">
    <script src="https://kit.fontawesome.com/82e270d318.js" crossorigin="anonymous"></script>
    <title>Breaking Bread : Viennese pastries, Pastries</title>
</head>

<body>
    <?php
    include('php/header.php');
    ?>
    <div class="contenu">
        <h1><i>Site Map</i></h1>
        <h2>Pages</h2>
        <hr>
        <ul>
            <li><a href="index.php">Home Page</a></li>
            <li><a href="contact.php">Contact us</a></li>
            <li><a href="connexion.php">Connection/Inscription</a></li>
            <li><a href="mentionslegales.php">Legal Notices</a></li>
            <li><a href="panier.php">Shopping cart</a></li>
            <li><a href="plan_du_site.php">Site Map</a></li>
            <li>Products
                <ul>
                    <?php

                    for( $i=0;$i<$recup_categorie->rowCount();$i++){
                        if($categorie[$i]['link'] != '' && $categorie[$i]['cate']!= ''){
                            echo '<li><a href="' . $categorie[$i]['link'] . '">' . $categorie[$i]['cate'] . '</a></li>';
                        }
                        else {
                            echo 'problem with sql database, please try again';
                            header('Location: http://localhost:8080/index.php');
                            exit();
                        }
                    }
                    ?>
                </ul>
            </li>
        </ul>
    </div>
    </div>
    <?php
    include('php/footer.php');
    ?>
</body>

</html>