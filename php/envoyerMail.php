<?php
session_start();



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

$mail_a_envoyer=$_SESSION['utilisateur'];
$nom_client=$_SESSION['nom'];

    $servname="localhost";
    $key_cryptage='la securite avant tout';//clé de cryptage
    $pass=openssl_decrypt(base64_decode("QUpZdVg3QVh2NU5Va29ZdnhEeDNPQT09"),"AES-128-ECB",$key_cryptage);
    $user=openssl_decrypt("5UfEC4F+32Kr6EtKpwtz8A==","AES-128-ECB",$key_cryptage);

    $bdd= new PDO("mysql:host=$servname;dbname=boulangerie",$user,$pass); //connexion base de données
    $bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $sujet="";
    $sujet= $sujet .'<h2>Breaking Bread vous remercie de votre achat</h2><br><br>';
    $$sujet= $sujet .'Bonjour ' . $_SESSION['nom'] . ',<br>L\'équipe Breaking Bread est heureuse de vous envoyer votre récapitulatif de commande :<br><br>';
    $sujet= $sujet . '<h4>Commande :</h4>';

    $recup_commande = $bdd->prepare("SELECT img,code,quantite,nom,prix,reference from produit inner join commande on produit.code=commande.code_produit where id_user='{$_SESSION['id_user']}' and payer = 2;");
    $recup_commande->execute();
    $commande = $recup_commande->fetchAll(PDO::FETCH_ASSOC);
    if($recup_commande->rowCount()>0){
        for($i=0;$i<$recup_commande->rowCount();$i++){
            $sujet= $sujet . "Nom : " .  $commande[$i]['nom'] . "<br>";
            $sujet= $sujet ."Référence : " .  $commande[$i]['code'] . "<br>";
            $sujet= $sujet . "Quantité : " .  $commande[$i]['quantite'] . "<br>";
            $sujet= $sujet ."Prix unitaire : " .  $commande[$i]['prix'] . "<br><br>";
            $sujet= $sujet . $commande[$i]["prix"] * $commande[$i]['quantite'];
        }
        $sujet= $sujet . "Prix total : " . $prix_tot . " euros.";
        $sujet= $sujet . "<br>Nous vous remercions de votre confiance, à très vite";
    }
    $mail = new PHPMailer(true);


    try
    { $mail->IsSMTP();
        $mail->Debugoutput='HTML';
        $mail->Host = 'smtp.laposte.net';               //Adresse IP ou DNS du serveur SMTP
        $mail->Port = 587;                          //Port TCP du serveur SMTP
        $mail->SMTPAuth = true;                        //Utiliser l'identification


        $mail->SMTPSecure =  'TLS';               //Protocole de sécurisation des échanges avec le SMTPÖ
        $mail->Username   =  'boulangerie_CYTECH@laposte.net';   //Adresse email à utiliser
        $mail->Password   =  'password95CYTECH@';         //Mot de passe de l'adresse email à utiliser
        $mail->setFrom('boulangerie_CYTECH@laposte.net','Breaking Bread');

        $mail->CharSet = 'UTF-8';
        $mail->smtpConnect();

        $mail->Subject    =  'Récapitulatif de commande !';                      //Le sujet du mail
        $mail->WordWrap   = 100; 			                   //Nombre de caracteres pour le retour a la ligne automatique      
        $mail->IsHTML(false);                                  //Préciser qu'il faut utiliser le texte brut

        $mail->MsgHTML($sujet); 		                //Le contenu au format HTML
    


        $mail->AddAddress($mail_a_envoyer);  //définir le destinataire
        $mail->send();
        
        $recup_user = $bdd->prepare("UPDATE commande
        SET payer = :payer
        WHERE id_user = {$_SESSION['id_user']} and payer = 2");
    
        $payer=0;
        $recup_user->bindParam(':payer',$payer);
    
        $recup_user->execute();

        $succes='ok';
    }  
    catch (Exception $e) {
        $succes = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }



echo json_encode(['statu' => $succes]);

?>
