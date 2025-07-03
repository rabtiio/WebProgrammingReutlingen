<?php
session_start();

$servname="localhost";
$key_cryptage='la securite avant tout';//clé de cryptage
$pass=openssl_decrypt(base64_decode("QUpZdVg3QVh2NU5Va29ZdnhEeDNPQT09"),"AES-128-ECB",$key_cryptage);
$user=openssl_decrypt("5UfEC4F+32Kr6EtKpwtz8A==","AES-128-ECB",$key_cryptage);

$bdd= new PDO("mysql:host=$servname;dbname=boulangerie",$user,$pass); //connexion base de données
$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
try{
    $recup_commande = $bdd->prepare("SELECT code_produit,quantite from commande WHERE id_user={$_SESSION['id_user']} AND payer = 1;");
    $recup_commande->execute();
    $commande=$recup_commande->fetchAll(PDO::FETCH_ASSOC);

    //supprime toutes les commandes de l'utilisateur non payée
    for( $i=0;$i<$recup_commande->rowCount();$i++){

    //mettre à jour le stock
        $recup_commande = $bdd->prepare("UPDATE produit SET stock = stock + {$commande[$i]['quantite']} WHERE code LIKE '{$commande[$i]['code_produit']}';");
        $recup_commande->execute();

        //delete la commande
        $del_commande = $bdd->prepare("DELETE FROM commande WHERE id_user={$_SESSION['id_user']} AND code_produit LIKE '{$commande[$i]['code_produit']}';");
        $del_commande->execute();
        $statue='ok';
    }
}
catch(PDOException $e){
    $statue="ERREUR : ". $e->getMessage();
}
echo json_encode(['statue' => $statue]);
?>