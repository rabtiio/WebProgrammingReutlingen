<?php
session_start();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header2.css">
    <link rel="stylesheet" href="css/panier.css">
    <script src="https://kit.fontawesome.com/82e270d318.js" crossorigin="anonymous"></script>
    <title>Your shopping cart</title>
</head>

<body>
    <?php
    include('php/header2.php');
    if(isset($_SESSION['id_user'])){
        $recup_commande = $bdd->prepare("SELECT img,code,quantite,nom,prix,reference from produit inner join commande on produit.code=commande.code_produit where id_user='{$_SESSION['id_user']}' and payer = 1;");
        $recup_commande->execute();
        $commande = $recup_commande->fetchAll(PDO::FETCH_ASSOC);
        if($recup_commande->rowCount()>0){
    ?>
    <div class="resume_panier">
            <h2>Your shopping cart :</h2>
            <table>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Reference</th>
                    <th>Unit price</th>
                </tr>
                <?php
                

                        for($i=0;$i<$recup_commande->rowCount();$i++){
                            echo '<tr>';
                            echo '<td><img src="' . $commande[$i]['img'] . '" width="200" height="200"></img></td>';
                            echo '<td>' . $commande[$i]['nom'] . '</td>';
                            echo
                            '<td>
                                            <div class="change_quantity">
                                                <button class="button-minus" id="minus-' . $commande[$i]["code"] . '" role="button"><i class="fa-solid fa-minus"></i></button>
                                                <input type="text" size="5" id="text_' . $commande[$i]["code"] . '" readonly="readonly" class="input_btn" value="' . $commande[$i]["quantite"] . '"/>
                                                <button class="button-add" id="plus-' . $commande[$i]["code"] . '" role="button"><i class="fa-solid fa-plus"></i></button>
                                            </div>
                                        </td>';
                            echo '<td>' . $commande[$i]["code"] . '</td>';
                            echo '<td>' . $commande[$i]["prix"] . '</td>';
                            echo '<td class="bg_none"><button id="' . $commande[$i]["code"] . '"><i class="fa-solid fa-xmark"></i></button></td>';
                            echo '</tr>';
                        }
                        echo '</table>';
                    ?>


                    <input type="checkbox" id="option1" name="options" value="data protection"> I accept the data protection.
                    <div class="validation">
                        <button id="annuler">Cancel</button>
                        <button id="valider">Validate</button>
                    </div>
                    <div class="erreur" id="erreur"></div>
                <?php
                } else {
                echo '<h2>Your shoping cart is empty</h2>';
                }
            }
            else {
                echo '<h2>Your shopping cart is empty, you are not connected</h2>';
            }
            ?>
    </div>
    </div>

    <?php
    include('php/footer.php');
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/panier.js"></script>
</body>

</html>