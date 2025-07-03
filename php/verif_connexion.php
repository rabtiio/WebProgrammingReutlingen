<?php
session_start();
//fait mais voir si il se trompe ce que ca fait 
$mail=htmlentities($_POST['email']);
$servname="localhost";
$password=htmlentities($_POST['mdp']);
$mdp=MD5($password);
$key_cryptage='la securite avant tout';//clé de cryptage
$pass=openssl_decrypt(base64_decode("QUpZdVg3QVh2NU5Va29ZdnhEeDNPQT09"),"AES-128-ECB",$key_cryptage);
$user=openssl_decrypt("5UfEC4F+32Kr6EtKpwtz8A==","AES-128-ECB",$key_cryptage);

$bdd= new PDO("mysql:host=$servname;dbname=boulangerie",$user,$pass); //connexion base de données
$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
if($mail == "admin@95.fr"){
    $recup_user = $bdd->prepare("SELECT id,nom,prenom from  user WHERE mail = '{$mail}' AND mot_de_passe = '{$mdp}' ;");
    $recup_user->execute();
    if( $recup_user->rowCount() > 0){
        $users = $recup_user->fetch(PDO::FETCH_ASSOC);
        $_SESSION['connecte']=2;
        $_SESSION['nom']= $users['nom'].' '.$users['prenom'];
        $_SESSION['utilisateur']=$mail;
        $_SESSION['id_user']=$users['id'];    
        $response='yes';
    }
    else{
        $response="L'adresse mail ou le mot de passe est incorecte";
    }
}
else{
    try{

        $recup_user = $bdd->prepare("SELECT id,nom,prenom,derniere_connexion from  user WHERE mail = '{$mail}' AND mot_de_passe = '{$mdp}' ;");
        $recup_user->execute();
        if( $recup_user->rowCount() > 0){
            $users = $recup_user->fetch(PDO::FETCH_ASSOC);
            $_SESSION['connecte']=1;
            $_SESSION['nom']= $users['nom'].' '.$users['prenom'];
            $_SESSION['utilisateur']=$mail;
            $_SESSION['derniere_connexion'] = $users['derniere_connexion'];



            $_SESSION['id_user']=$users['id'];

            $now = date("Y-m-d H:i:s");
            $update = $bdd->prepare("UPDATE user SET derniere_connexion = ? WHERE id = ?");
            $update->execute([$now, $users['id']]);
        }
        else{
            $response="L'adresse mail ou le mot de passe est incorecte";
        }
    }
    catch(PDOException $e){
        $response="ERREUR : Votre profil n'a pas été créé";
    }
}
echo json_encode(['response' => $response ]);
?>