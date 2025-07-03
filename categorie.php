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
    <link rel="stylesheet" href="css/header2.css">
    <link rel="stylesheet" href="css/categorie.css">
    <script src="https://kit.fontawesome.com/82e270d318.js" crossorigin="anonymous"></script>
    <title>Breaking Bread : Viennese pastries, Pastries</title>
</head>

<body>

    <?php
    include('php/header2.php');
    ?>
    <div class="fond_accueil"></div>
    <div class="container">
        <div class="titre_section">
            <?php
            $recup_categorie = $bdd->prepare("SELECT link,cate,reference,icone from categorie where reference = {$_SESSION['page_produit']};");
            $recup_categorie->execute();
            $categorie = $recup_categorie->fetch(PDO::FETCH_ASSOC);
            if ($recup_categorie->rowCount() > 0){
                echo $categorie['cate'];
            }
            else{
                echo 'Cette catégorie n\'existe pas ';
            }
            ?>
        </div>
        <div class="lien_ancre">
            <a href="#produit">Command now !</a>
        </div>
    </div>
    </div>

    <section class="products" id="produit">
        <h1 class="titre_produit">OUR LAST PRODUCTS :</h1>
        <div class="box-container">
            <?php
            
            $recup_produit = $bdd->prepare("SELECT * from produit where categorie_id = {$_SESSION['page_produit']} AND stock > 0;");
            $recup_produit->execute();
            $produit = $recup_produit->fetchAll(PDO::FETCH_ASSOC);
            if ($recup_produit->rowCount() <= 0){
                echo "There is no product in this category";
            }
            else{
                for($j=0;$j< $recup_produit->rowCount();$j++){

                
            ?>
                            <div class="box">
                                <span class="discount"><?php echo '-' . rand(5, 30) . '%';  ?></span>
                                <div class="image">
                                    <img src="<?php echo $produit[$j]['img']; ?>" alt="<?php echo $produit[$j]['nom']; ?>">
                                    <div class="icons" id="<?php echo "icons" . $produit[$j]['code']; ?>">
                                        <button class="fa-solid fa-minus button_rose" id="minus"></button>
                                        <button class="cart-btn envoyer" id="submit"><span>Ajouter au panier</span></button>
                                        <button class="fa-solid fa-plus button_rose" id="plus"></button>
                                        <input type="text" readonly="readonly" class="number_product" id="<?php echo $produit[$j]['code']; ?>" value="0"></input>
                                    </div>
                                    <div class="content">
                                        <h3><?php echo $produit[$j]['nom']; ?></h3>
                                        <div class="price"><?php
                                                            echo $produit[$j]['prix'];
                                                            if ($_SESSION['connecte'] == 2) {
                                                                echo '  | Stock : ' .$produit[$j]['stock'];
                                                            }
                                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
            <?php                       
                }
            }
            ?>
        </div>
    </section>


    <?php
    include('php/footer.php');
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/categorie.js"></script>
</body>

</html>
