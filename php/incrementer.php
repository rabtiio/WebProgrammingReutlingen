<?php
session_start();
$tab=explode("-",$_GET["id"]);
$signe=$tab[0];
$id_recup=$tab[1];

$servname="localhost";
$key_cryptage='la securite avant tout';//clé de cryptage
$pass=openssl_decrypt(base64_decode("QUpZdVg3QVh2NU5Va29ZdnhEeDNPQT09"),"AES-128-ECB",$key_cryptage);
$user=openssl_decrypt("5UfEC4F+32Kr6EtKpwtz8A==","AES-128-ECB",$key_cryptage);

$bdd= new PDO("mysql:host=$servname;dbname=boulangerie",$user,$pass); //connexion base de données
$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


//récupère les données 
$recup_produit = $bdd->prepare("SELECT stock from produit WHERE code LIKE '{$id_recup}';");
$recup_produit->execute();
$produit = $recup_produit->fetch(PDO::FETCH_ASSOC);
$stat4=$produit['stock'];

if ((string) $signe == 'plus' && $stat4-1 >= 0){
    //met à jour le stock
    $maj = $bdd->prepare("UPDATE produit
    SET stock = :stock
    WHERE code LIKE '{$id_recup}'");

    $new_stock=$stat4-1;
    $maj->bindParam(':stock', $new_stock);
 
    $maj->execute();

    //met à jour la commande
    $maj = $bdd->prepare("UPDATE commande
    SET quantite = quantite + 1
    WHERE code_produit LIKE '{$id_recup}' and id_user = {$_SESSION['id_user']} and payer = 1 ");

    $maj->execute();
    $stat='ok';
}
else if((string) $signe == 'minus'){
        //met à jour le stock
        $maj = $bdd->prepare("UPDATE produit
        SET stock = :stock
        WHERE code LIKE '{$id_recup}'");
    
        $new_stock=$stat4+1;
        $maj->bindParam(':stock', $new_stock);
     
        $maj->execute();
    
        //met à jour la commande
        $maj = $bdd->prepare("UPDATE commande
        SET quantite = quantite - 1
        WHERE code_produit LIKE '{$id_recup}' and id_user = {$_SESSION['id_user']} and payer = 1 ");
    
        $maj->execute();
        $stat='ok';
}
else {
    $stat='pas assez de stock';
}


echo json_encode(['stat' => $stat, 'stat2' => $signe,'stat4' => $stat4]);
?>