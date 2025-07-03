<?php
//fait
session_start();
//changement de mail attention peut être véerifier en envoyant un mail et mdp
$nom=htmlentities($_POST['nom_change']);
$prenom=htmlentities($_POST['prenom_change']);
$mail=htmlentities($_POST['mail_change']);
$sexe=htmlentities($_POST['sexe_change']);
$naissance=htmlentities($_POST['naissance_change']);
$adresse=htmlentities($_POST['adresse_change']);
$metier=htmlentities($_POST['metier_change']);





$servname="localhost";
$key_cryptage='la securite avant tout';//clé de cryptage
$pass=openssl_decrypt(base64_decode("QUpZdVg3QVh2NU5Va29ZdnhEeDNPQT09"),"AES-128-ECB",$key_cryptage);
$user=openssl_decrypt("5UfEC4F+32Kr6EtKpwtz8A==","AES-128-ECB",$key_cryptage);

$bdd= new PDO("mysql:host=$servname;dbname=boulangerie",$user,$pass); //connexion base de données
$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
try{
    $recup_user = $bdd->prepare("UPDATE user
    SET prenom = :prenom,
        nom = :nom,
        mail = :mail,
        sexe = :sexe,
        date_naissance = :naissance,
        adresse = :adresse,
        metier = :metier
    WHERE id = {$_SESSION['id_user']}");

    $recup_user->bindParam(':prenom', $prenom);
    $recup_user->bindParam(':nom', $nom);
    $recup_user->bindParam(':mail', $mail);
    $recup_user->bindParam(':sexe', $sexe);
    $recup_user->bindParam(':naissance', $naissance);
    $recup_user->bindParam(':adresse', $adresse);
    $recup_user->bindParam(':metier', $metier);
    $recup_user->execute();
    $_SESSION['nom']= $nom.' '.$prenom;
    $_SESSION['utilisateur']=$mail;
    $info= "ok";
}
catch(PDOException $e){
    $info="ERREUR : Votre demande n'a pas été update";
}

echo json_encode(['info' => $info,'mail' => $mail]);
?>
