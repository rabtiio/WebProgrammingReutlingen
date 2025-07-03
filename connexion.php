<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<!-- fait -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Breaking Bread</title>
    <link rel="stylesheet" href="css/connexion.css">
    <link rel="stylesheet" href="css/header2.css">
    <script src="https://kit.fontawesome.com/82e270d318.js" crossorigin="anonymous"></script>
</head>

<body>
    <div id="content">
        <?php
        include('php/header2.php');
        ?>

        <?php
        if ($_SESSION['connecte'] != 0) {
            /*$servname="localhost";
            $key_cryptage='la securite avant tout';//clé de cryptage
            $pass=openssl_decrypt(base64_decode("QUpZdVg3QVh2NU5Va29ZdnhEeDNPQT09"),"AES-128-ECB",$key_cryptage);
            $user=openssl_decrypt("5UfEC4F+32Kr6EtKpwtz8A==","AES-128-ECB",$key_cryptage);

            $bdd= new PDO("mysql:host=$servname;dbname=boulangerie",$user,$pass); //connexion base de données
            $bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);*/

        ?>
            <div class="info_client">
                <h2> Your personal data :</h2>
                <div class="info_json">
                    <table>
                        <tr>
                            <th>Name</th>
                            <th>First name</th>
                            <th>Birth date</th>
                            <th>E-mail</th>
                        </tr>
                        <tr>
                            <?php
                                $recup_user = $bdd->prepare("SELECT * from user where mail LIKE '{$_SESSION['utilisateur']}' ;");
                                $recup_user->execute();
                                $users = $recup_user->fetch(PDO::FETCH_ASSOC);
                                if ($recup_user->rowCount() > 0){
                                    echo '<td>' . $users["nom"] . '</td>';
                                    echo '<td>' . $users["prenom"] . '</td>';
                                    echo '<td>' . $users["date_naissance"] . '</td>';
                                    echo '<td>' . $users["mail"] . '</td>';
                                }
                            ?>
                        </tr>
                    </table>
                    <?php
                        if ($_SESSION['connecte'] == 1) {
                    ?>
                        <div class="histo_change">
                            <div class="historique_commande">
                                <h2>My past commands :</h2>
                                <div class="precedente_commande">
                                    <?php
                                        $recup_commande = $bdd->prepare("SELECT nom,quantite from commande inner join produit on commande.code_produit=produit.code where id_user = {$users['id']} and payer=0 ;");
                                        $recup_commande->execute();
                                        $commande = $recup_commande->fetchAll(PDO::FETCH_ASSOC);

                                        for($i=0;$i < $recup_commande->rowCount(); $i++){
                                            echo $commande[$i]['nom']." : ".$commande[$i]['quantite'];
                                            echo '<br>';
                                        }
                                    ?>
                                </div>
                            </div>
                            <button id="affiche_coord">Change my contact details</button>
                        </div>
                        <div class="right_part">
                            <div class="change_coord notActive">
                                <form action="php/modif_coord.php" method="POST" id="form_change">
                                    <div id="terme" class="nom_user">
                                        <label id="label" for="nom">Name :</label><input type="text" name="nom_change" value="<?php echo $users["nom"] ?> " required>
                                    </div>
                                    <div id="terme" class="prenom_user">
                                        <label id="label" for="nom">First Name :</label><input type="text" name="prenom_change" value="<?php echo $users["prenom"] ?>"required>
                                    </div>
                                    <div id="terme" class="mail_user">
                                        <label id="label" for="nom">E-mail :</label><input type="text" name="mail_change" value="<?php echo $users["mail"] ?>" required>
                                    </div>
                                    <div id="terme" class="password_user">
                                        <label id="label" for="nom">Password :</label><input type="password" name="password_change" hidden>
                                    </div>
                                    <div id="terme" class="sexe_user">
                                        <label for="sexe" id="label">Sex :</label>
                                        <select id="coord_sexe" name="sexe_change">
                                            <option value="Homme" <?php if ($users['sexe'] == "Homme") echo 'selected="selected"'; ?>>Homme </option>
                                            <option value="Femme" <?php if ($users['sexe'] == "Femme") echo 'selected="selected"'; ?>>Femme</option>
                                        </select>
                                    </div>
                                    <div id="terme" class="naissance_user">
                                        <label id="label" for="nom">Birthdate :</label><input type="date" name="naissance_change" id="naissance_change" value="<?php echo $users["date_naissance"] ?>" required>
                                    </div>
                                    <div id="terme" class="adresse_user">
                                        <label id="label" for="nom">Adress :</label><input type="text" name="adresse_change" value="<?php echo $users["adresse"] ?>"required>
                                    </div>
                                    <div id="terme" class="metier_user">
                                        <label id="label" for="nom">Job :</label><input type="text" name="metier_change" value="<?php echo $users["metier"] ?>"required>
                                    </div>
                                    <div id="terme" class="code_user">
                                        <label id="label" for="nom">Customer Code</label><input type="text" readonly="readonly" value="<?php echo $users["id"] ?>">
                                    </div>
                                    <div class="choice">
                                        <input type="submit" id="valider"></input>
                                    </div>
                                    <input type="reset" id="annuler"></input>
                                    <div class="resultat_change"></div>
                                </form>    
                            </div>
                        </div>
                    <?php
                        } elseif ($_SESSION['connecte'] == 2) {
                    ?>
                        <div class="salutation">Administrator Right</div>
                        <div class="envoyer_mail">
                            <div class="description_mail">Send advertising mail to every customer : </div>
                            <button id="mail_all">Send</button>
                            <div class="accuse"></div>
                        </div>
                        <h3 class="jsp">Back Office</h3>
                        <div class="back_office">
                            <div class="flex_mettre">
                                <div class="titre_BO">Select the category to delete</div>
                                <form action="php/supp_BO.php" method="post" id="form_supp_bo">
                                    <select name="categorie_supp" id="categorie_supprimer">
                                        <option value="">Category</option>
                                        <?php
                                            for( $i=0;$i<$recup_categorie->rowCount();$i++){
                                                echo '<option value="' . $categorie[$i]["reference"] . '">' . $categorie[$i]["cate"] . '</option>';
                                            }
                                        ?>
                                    </select>
                                    <input type="submit" id="submit_bo" class="notActive" value="Valider">
                                </form>
                                <div class="erreur_categorie"></div>
                            </div>
                            <div class="flex_mettre2">
                                <div class="titre_produit">Select the product to delete</div>
                                <!-- include  -->
                                <form action="php/supp_BO_produit.php" method="post" id="form_supp_produit">
                                    <select name="produit_supp" id="produit_supprimer">
                                        <option value="">Product</option>
                                        <?php
                                            $recup_produit2 = $bdd->prepare("SELECT reference,nom from produit ;");
                                            $recup_produit2->execute();
                                            $produit2 = $recup_produit2->fetchAll(PDO::FETCH_ASSOC);
                                            for($j=0;$j<$recup_produit2->rowCount();$j++){
                                                echo '<option value="' . $produit2[$j]["reference"] . '">' . $produit2[$j]["nom"] . '</option>';
                                            }
                                        ?>
                                    </select>
                                    <input type="submit" id="submit_produit" class="notActive" value="Valider">
                                </form>
                                <div class="erreur_prod"></div>
                            </div>
                        </div>
                    <?php
                        }
                    ?>
                </div>
            </div>


        <?php
            }else{;
        ?>
        <div class="encadre">
            <div class="toggleInscr_Connex" id="toggleInscr_Connex">
                <div class="form_connexion" id="form_connexion">
                    <div class="logo">
                        <i class="fas fa-user"></i>
                        <div class="titre_connexion">Connection</div>
                    </div>
                    <div class="tab-body">
                       
                        <!-- include  -->
                        <form id="form1" action="php/verif_connexion.php" method="post">
                            <div class="row">
                                <i class="far fa-user"></i>
                                <input type="text" name="email" class="input" placeholder="Adresse Mail" id="mail1">
                            </div>
                            <div class="row">
                                <i class="fas fa-lock"></i>
                                <input type="password" placeholder="Mot de Passe" name="mdp" class="input" id="mdp">
                            </div>
                            <input class="btn" type="submit" value="Connection"></input>
                        </form>
                    </div>
                </div>
                <!-- include  -->
                <form action="php/verif_inscription.php" method="post" id="form_inscription" class="notActive">
                    <input type="hidden" id="resolution_ecran" name="resolution_ecran" />
                    <div class="logo2">
                        <i class="fas fa-user"></i>
                        <div class="titre_inscription">Registration</div>
                    </div>
                    <div class="inscription">
                        <div class="annonce">Fill all the fields to create your new account !</div>
                        <div class="info_perso">Personal data :</div>
                        <div class="nom_co">
                            <input type="text" name="nom" size="10" placeholder="Name" id="nom">
                            <input type="text" name="prenom" size="10" placeholder="First Name" id="prenom">
                        </div>
                        <div class="mail">
                            <label for="mail">E-mail address :</label>
                            <input type="text" name="mail" placeholder="E-mail address" size="12" id="mail">
                        </div>
                        <div class="mdp">
                            <label for="mdp">Password :</label>
                            <input type="password" name="mot_de_passe" placeholder="Password" size="11" id="password">
                        </div>
                        <div class="sexe">
                            <label for="sexe">Sex :</label>
                            <input type="radio" name="sexe" value="Homme" id="homme"><label for="homme">Man</label>
                            <input type="radio" name="sexe" value="Femme" id="femme"><label for="femme">Woman</label>
                        </div>
                        <div class="date_naissance">
                            <label for="naissance">Birthdate :</label>
                            <input type="date" name="naissance" size="7" id="naissance">
                        </div>
                        <div class="adresse">
                            <label for="adresse">Adress :</label>
                            <input type="text" name="adresse" id="adresse" placeholder="Adress" size="16">
                        </div>
                        <div class="metier">
                            <label for="metier">Job :</label>
                            <input type="text" name="metier" placeholder="Job" size="16" id="metier">
                        </div>
                    </div>
                    <input type="submit" value="Create my account" class="btn-submit">
                </form>
            </div>
            <div class="erreur" id="erreur"></div>
            <div class="erreur2" id="erreur2"></div>
            <div class="tab-link" id="div_inscription">Are you not register yet ?<a href="#" id="inscription"> Click here</a></div>
            <div class="tab-link2 notActive" id="div_connexion">Are you already register ?<a href="#" id="connexion"> Click here</a></div>
        </div>
    <?php
    }
    ?>

    </div>
    <?php
    include('php/footer.php');
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/connexion.js"></script>
</body>

</html>
