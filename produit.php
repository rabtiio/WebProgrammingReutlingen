<?php
session_start();
//fait
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Breaking Bread</title>
    <link rel="stylesheet" href="css/header2.css">
    <link rel="stylesheet" href="css/produit.css">
    <script src="https://kit.fontawesome.com/82e270d318.js" crossorigin="anonymous"></script>
</head>

<body>
    <script>
        function affiche_stock(e) {
            const stock = document.getElementById("stock");

            if (stock.value == "mode1") {
                stock.innerHTML = "Il en reste " + e + " en stock";
                stock.value = "mode2";
            } else {
                stock.innerHTML = "Appuyer pour afficher le stock";
                stock.value = "mode1";
            }
        }
    </script>
    <?php
    include('php/header2.php');
    $recup_produit = $bdd->prepare("SELECT * from produit where categorie_id = {$_GET["produit"]} AND code = '{$_GET["code"]}';");
    $recup_produit->execute();
    $produit = $recup_produit->fetch(PDO::FETCH_ASSOC);

    ?>
        <section class="resume_produit">
            <div class="image-wrap">
                <div class="contour">
                    <div class="image" style="background-image:url('<?php echo $produit['img']?>')"></div>
                </div>
                <div class="description">
                    <div class="name"><?php echo $produit['nom']. ' :'; ?></div>
                    <hr>
                    <div class="tout_prix">
                        <div id="montant"><?php echo $produit['prix']; ?>€</div>
                        <div id="taxe">
                            <pre> TTC</pre>
                        </div>
                    </div><br>
                    <div class="icons" id="<?php echo "icons" .$produit['code']; ?>">             
                        <button class="cart-btn envoyer" id="submit"><span>Add to cart<i style="padding-left:6px;" class="fa-solid fa-basket-shopping"></i></span></button>
                        <button class="fa-solid fa-minus button_rose" id="minus"></button>
                        <button class="fa-solid fa-plus button_rose" id="plus" ></button>
                        <input type="text" readonly="readonly" class="number_product"  id="<?php echo $produit['code']; ?>" value="0"></input>                         
                    </div>
                    <hr>
                    <div class="longue">
                        <p class="titre_info"><b>DESCRIPTION</b></p>
                    </div>
                    <div class="description_txt">
                        <?php echo $produit['presentation'] ?>
                    </div>
                    <hr>
                    <div class="longue">
                        <p class="titre_info"><b>INGREDIENTS</b></p>
                    </div>
                    <div class="ingredients_txt">
                        <?php
                            $tab_ingredients = explode(",", $produit['ingredients']);
                            echo "<ul>";
                            $i = 0;
                            foreach ($tab_ingredients as $ingredient) {
                                echo '<li>' . $ingredient . '</li>';
                            }
                            echo "</ul>";
                        ?>
                    </div>
                    <hr>
                    <?php
                        if ($_SESSION['connecte'] == 2) {
                            echo "<br>";
                            echo  "<button class='display_stock' id='stock' onclick='affiche_stock(" . $produit['stock'] . ")' value='mode1'>Press to view stock</button>";
                           // echo '<button id="button_admin" value="'.$produit['stock'].'" class="display_stock">Appuyez pour afficher le stock</button>';
                            echo  "<br><hr>";
                        }
                    ?>
                    <div class="ref_produit">
                        <p>Product's reference : <?php echo $produit['code'] ?></p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php
    include('php/footer.php');
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/produit.js"></script>
</body>

</html>
