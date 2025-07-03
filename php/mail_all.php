<?php
session_start();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

function envoyer_mail($mail_a_envoyer, $nom_client){
    $mail = new PHPMailer(true);




    $mail->IsSMTP();
    $mail->Debugoutput='HTML';
    $mail->Host = 'smtp.laposte.net';               //Adresse IP ou DNS du serveur SMTP
    $mail->Port = 587;                          //Port TCP du serveur SMTP
    $mail->SMTPAuth = true;                        //Utiliser l'identification


    $mail->SMTPSecure =  'TLS';               //Protocole de sécurisation des échanges avec le SMTPÖ
    $mail->Username   =  'rs2shanks@gmail.com';   //Adresse email à utiliser
    $mail->Password   =  'Leseddiki2)';         //Mot de passe de l'adresse email à utiliser
    $mail->setFrom('boulangerie_CYTECH@laposte.net','Breaking Bread');

    $mail->CharSet = 'UTF-8';
    $mail->smtpConnect();

    //Attachments         //Add attachments
    $mail->addAttachment('../img/charlotte400-min.png', 'charlotte_aux_fraises.jpg');    //Optional name
    $mail->addAttachment('../img/croissant400-min.jpg', 'Croissant.jpg');    
    $mail->addAttachment('../img/tartePommes400-min.jpg', 'tarte_Pommes.jpg');  


    $mail->Subject    =  'Promotion Breaking Bread !';                      //Le sujet du mail
    $mail->WordWrap   = 100; 			                   //Nombre de caracteres pour le retour a la ligne automatique      
    $mail->IsHTML(false);                                  //Préciser qu'il faut utiliser le texte brut

    $mail->MsgHTML( "<h2>VENTE FLASH BREAKING BREAD</h2><br><br>Bonjour ".$nom_client.",<br><b>Nos produits phares sont actuellement en promotions !!
    </b><br>La fameuse <i>charlotte aux fraises</i>, spécialité de nos grands chefs est actuellement en promotion à -30% !<br>Mais ce n'est pas tout ! 
    la <i>tarte aux pommes</i> et le <i>croissant</i> sont eux aussi en promotion pendant une durée limitée alors venez en profiter !!"); 		                //Le contenu au format HTML
    $mail->IsHTML(true);


    $mail->AddAddress($mail_a_envoyer);  //définir le destinataire


    if (!$mail->send()) {
    $succes= "Message non envoyé"; //si il ya des erreurs
    } else{
    $succes='Message bien envoyé';
    }
}


$servname="localhost";
$key_cryptage='la securite avant tout';//clé de cryptage
$pass=openssl_decrypt(base64_decode("QUpZdVg3QVh2NU5Va29ZdnhEeDNPQT09"),"AES-128-ECB",$key_cryptage);
$user=openssl_decrypt("5UfEC4F+32Kr6EtKpwtz8A==","AES-128-ECB",$key_cryptage);

$bdd= new PDO("mysql:host=$servname;dbname=boulangerie",$user,$pass); //connexion base de données
$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$recup_user = $bdd->prepare("SELECT nom,prenom,mail from user;");
$recup_user->execute();
$users = $recup_user->fetchAll(PDO::FETCH_ASSOC);
 for($i=0;$i<$recup_user->rowCount();$i++){
    envoyer_mail($users['mail'], $users["prenom"]." ".$users["nom"]);
 }

?>