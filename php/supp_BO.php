<?php
//fait

$servname="localhost";
$key_cryptage='la securite avant tout';//clé de cryptage
$pass=openssl_decrypt(base64_decode("QUpZdVg3QVh2NU5Va29ZdnhEeDNPQT09"),"AES-128-ECB",$key_cryptage);
$user=openssl_decrypt("5UfEC4F+32Kr6EtKpwtz8A==","AES-128-ECB",$key_cryptage);

$bdd= new PDO("mysql:host=$servname;dbname=boulangerie",$user,$pass); //connexion base de données
$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$val="Erreur de supression";
if($_POST["categorie_supp"] != ""){
    $sup = $bdd->prepare("DELETE from commande where code_produit in (SELECT code from produit where categorie_id = {$_POST["categorie_supp"]});");
    $sup->execute();
    $sup = $bdd->prepare("DELETE from produit where categorie_id = {$_POST["categorie_supp"]};");
    $sup->execute();
    $sup = $bdd->prepare("DELETE from categorie where reference = {$_POST["categorie_supp"]};");
    $sup->execute();


    $val="ok";
}

echo json_encode(['val' => $val]);
?>