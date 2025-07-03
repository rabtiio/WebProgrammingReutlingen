<?php
session_start();

$servname="localhost";
$key_cryptage='la securite avant tout';//clé de cryptage
$pass=openssl_decrypt(base64_decode("QUpZdVg3QVh2NU5Va29ZdnhEeDNPQT09"),"AES-128-ECB",$key_cryptage);
$user=openssl_decrypt("5UfEC4F+32Kr6EtKpwtz8A==","AES-128-ECB",$key_cryptage);

$bdd= new PDO("mysql:host=$servname;dbname=boulangerie",$user,$pass); //connexion base de données
$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

//récupère les données 
$recup_stock = $bdd->prepare("SELECT stock from produit where code = '{$_GET["id"]}';");
$recup_stock->execute();

if($recup_stock->rowCount() >0){
    $git=$_GET["id"];
}
else{
    $git='ERREUR';
}

echo json_encode(['git' => $git]);
?>