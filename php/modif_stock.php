<?php
session_start();
//peut etre mettre un echo vous ne pouvez pas commander
if(!isset($_SESSION['id_user'])){
    header("Location: index.php");
    exit();
}
$servname="localhost";
$key_cryptage='la securite avant tout';//clé de cryptage
$pass=openssl_decrypt(base64_decode("QUpZdVg3QVh2NU5Va29ZdnhEeDNPQT09"),"AES-128-ECB",$key_cryptage);
$user=openssl_decrypt("5UfEC4F+32Kr6EtKpwtz8A==","AES-128-ECB",$key_cryptage);

$bdd= new PDO("mysql:host=$servname;dbname=boulangerie",$user,$pass); //connexion base de données
$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

//récupère les données 
$recup_stock = $bdd->prepare("SELECT stock from produit where code LIKE '{$_GET['id']}';");
$recup_stock->execute();
$stocks = $recup_stock->fetch(PDO::FETCH_ASSOC);
if($recup_stock->rowCount() >0 && $stocks['stock'] >= $_GET['quantity']){
    //insérer la commande
    $new = $bdd->prepare("INSERT INTO commande (code_produit,quantite,id_user,payer) VALUES (?, ?, ?, ?)");
    $payer=1;
    // Liez les valeurs aux paramètres de la requête
    $new->bindParam(1, $_GET['id']);
    $new->bindParam(2, $_GET['quantity']);
    $new->bindParam(3, $_SESSION['id_user']);
    $new->bindParam(4, $payer);
    $new->execute();

    $stock=$stocks['stock']-$_GET['quantity'];
    //mettre à jour la bdd
    $recup_user = $bdd->prepare("UPDATE produit
    SET stock = {$stock} 
    WHERE code LIKE '{$_GET['id']}' ;");
    $recup_user->execute();
    $status='ok'; 
    $stock=$stocks['stock']-$_GET['quantity'];
}
else{
    $status='Il n\'y a plus assez de stock pour votre demande. '; 
    $stock=$stocks['stock'];
}


echo json_encode(['status' => $status, 'stock' => $stock]);
?>
