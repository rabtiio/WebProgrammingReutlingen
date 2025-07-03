<?php
session_start();

$servname="localhost";
$key_cryptage='la securite avant tout';//clé de cryptage
$pass=openssl_decrypt(base64_decode("QUpZdVg3QVh2NU5Va29ZdnhEeDNPQT09"),"AES-128-ECB",$key_cryptage);
$user=openssl_decrypt("5UfEC4F+32Kr6EtKpwtz8A==","AES-128-ECB",$key_cryptage);

$bdd= new PDO("mysql:host=$servname;dbname=boulangerie",$user,$pass); //connexion base de données
$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
try{
//mettre à jour le stock
    $recup_commande = $bdd->prepare("UPDATE produit SET stock = stock + (SELECT sum(quantite) FROM commande WHERE id_user={$_SESSION['id_user']} AND code_produit LIKE '{$_GET['id']}') WHERE code LIKE '{$_GET['id']}';");
    $recup_commande->execute();

    //delete la commande
    $del_commande = $bdd->prepare("DELETE FROM commande WHERE id_user={$_SESSION['id_user']} AND code_produit LIKE '{$_GET['id']}';");
    $del_commande->execute();
    $status='ok';
}
catch(PDOException $e){
    $status="ERREUR : ". $e->getMessage();
}
echo json_encode(['status' => $status]);
?>