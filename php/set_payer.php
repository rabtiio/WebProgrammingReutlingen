<?php
session_start();
//a appeler quand l'utilisateu à confirmer sa commande, avant le mail
$servname="localhost";
$key_cryptage='la securite avant tout';//clé de cryptage
$pass=openssl_decrypt(base64_decode("QUpZdVg3QVh2NU5Va29ZdnhEeDNPQT09"),"AES-128-ECB",$key_cryptage);
$user=openssl_decrypt("5UfEC4F+32Kr6EtKpwtz8A==","AES-128-ECB",$key_cryptage);

$bdd= new PDO("mysql:host=$servname;dbname=boulangerie",$user,$pass); //connexion base de données
$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
try{
    $recup_user = $bdd->prepare("UPDATE commande
    SET payer = :payer
    WHERE id_user = {$_SESSION['id_user']} and payer = 1");

    $payer=2;
    $recup_user->bindParam(':payer',$payer);

    $recup_user->execute();
    $statu = 'ok';
}
catch (Exception $e) {
    $statu = "ERREUR :" . $e->getMessage();
}
echo json_encode(['statu' => $statu]);
?>