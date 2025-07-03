<?php
session_start();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';



$today = date('Y-m-d');
$date_min = date('1905-01-01');

if (empty($_POST)) {
    $_POST["prenom"] = "";
    $_POST["nom"] = "";
    $_POST["metier"] = "";
    $_POST["sujet"] = "";
    $_POST["email"] = "";
    $_POST["genre"] = "";
    $_POST["date_contact"] = "";
    $_POST["date_naissance"] = "";
    $_POST["Contenu"] = "";
    $_POST["envoyer"] = "";
}
if (!isset($_POST["genre"])){
    $_POST["genre"] = ""; 
}
$prenom = htmlentities($_POST['prenom']);
$nom = htmlentities($_POST['nom']);
$metier = htmlentities($_POST['metier']);
$sujet = htmlentities($_POST['sujet']);
$email = htmlentities($_POST['email']);
$genre = htmlentities($_POST['genre']);
$date_contact = htmlentities($_POST['date_contact']);
$date_naissance = htmlentities($_POST['date_naissance']);
$contenu = htmlentities($_POST['Contenu']);
$envoyer = $_POST["envoyer"];

$erreurNom = "";
$erreurPrenom = "";
$erreurNaissance = "";
$erreurMail = "";
$erreurMetier = "";
$erreurPrenom = "";
$erreurContact = "";
$erreurSujet = "";
$erreurContenu = "";
$erreurGenre = "";
$nbr_erreur = 0;

if ($_POST['envoyer']=="Envoyer") {
    if (empty($genre)) {
        $erreurGenre = '<pre class="erreurGenre" style="color:red;">Select a gender</pre>';
        $nbr_erreur++;
    }
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurMail = '<pre class="erreurMail" style="color: red;">Enter a correct e-mail address</pre>';
        $nbr_erreur++;
    }
    if ((empty($prenom)) || (!preg_match("/^[a-zA-ZÀ-ú\s]*$/", $prenom))) {
        $erreurPrenom = '<pre class="erreurPrenom" style="color: red;">Enter a correct first name</pre>';
        $nbr_erreur++;
    }
    if (empty($nom) || (!preg_match("/^[a-zA-ZÀ-ú\s]*$/", $nom))) {
        $erreurNom = '<pre class="erreurNom" style="color: red;">Enter a correct name</pre>';
        $nbr_erreur++;
    }
    if (empty($metier) || (!preg_match('/^[a-zA-ZÀ-ú\s]*$/', $metier))) {
        $erreurMetier = '<pre class="erreurMetier" style="color: red;">Enter only letters</pre>';
        $nbr_erreur++;
    }
    if (empty($sujet) || (!preg_match('/./', $sujet))) {
        $erreurSujet = '<pre class="erreurSujet" style="color: red;">Do not put special character</pre>';
        $nbr_erreur++;
    }
    if (empty($date_naissance) || $today <= $date_naissance || $date_naissance <= $date_min) {
        $erreurNaissance = '<pre class="erreurNaissance" style="color: red;">Select a correct date</pre>';
        $nbr_erreur++;
    }
    if (empty($date_contact) ||  $date_contact != $today) {
        $erreurContact = '<pre class="erreurContact" style="color: red;">Select today\'date</pre>';
        $nbr_erreur++;
    }

    if (empty($contenu) || (!preg_match('/./', $contenu))) {
        $erreurContenu = '<pre class="erreurContenu" style="color: red;">Enter a message</pre>';
        $nbr_erreur++;
    }
    if ($nbr_erreur == 0) {

        try {

            $mail = new PHPMailer(true);




            $mail->IsSMTP();
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
        
                              //Le sujet du mail
            $mail->WordWrap   = 100; 			                   //Nombre de caracteres pour le retour a la ligne automatique      
            $mail->IsHTML(false);                                  //Préciser qu'il faut utiliser le texte brut
        
            $mail->MsgHTML('<h2>Hello Webmaster,</h2><br><br> A new customer send you a message :<br>Nom du client : ' . $nom . ' ' . $prenom . '<br>Contenu : ' . $contenu . '<br><br>Passez une agréable journée jeune maitre.'); 		                //Le contenu au format HTML
           
        
        
            $mail->AddAddress('boulangerie_CYTECH@laposte.net', 'Webmaster');  //définir le destinataire
            $mail->send();
            $succes = 'Your request is send';
        } catch (Exception $e) {
            $succes = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $succes = "";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/contact.css">
    <script src="https://kit.fontawesome.com/82e270d318.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/header.css">
    <title>Contact Breaking Bread</title>
</head>

<body>
    <?php
    include('php/header.php');
    ?>
    <div class="formulaire">
        <div class="formulaire_titre">Contact request</div>

        <form action="#" method="post" id="form_contact">
            <div class=boite>
                <div class="gauche">
                    <div class="div_nom">
                        <input type="text" name="nom" placeholder="Enter your name" size="10" id="Nom" class="Nom" value="<?php if (isset($nom)) echo $nom; ?>"><?php echo $erreurNom; ?>
                    </div>

                    <div class="div_mail">
                        <input type="mail" name="email" placeholder="Enter your E-mail address" size="15" id="mail" class="mail" value="<?php if (isset($email)) echo $email; ?>"><?php echo $erreurMail; ?>
                    </div>

                    <div class="div_naissance">
                        <label for="Date_de_Naissance">Birthdate :</label>
                        <input type="date" name="date_naissance" id="Naissance" class="Naissance" min="1905-01-01" max="2030-01-01" value="<?php if (isset($date_naissance)) echo $date_naissance; ?>"><?php echo $erreurNaissance; ?>
                    </div>
                    <div class="div_metier">
                        <input type="text" name="metier" placeholder="Job" size="15" id="Metier" class="Metier" value="<?php if (isset($metier)) echo $metier; ?>"> <?php echo $erreurMetier; ?>
                    </div>
                </div>

                <div class="droite">
                    <div class="div_prenom">
                        <input type="text" name="prenom" placeholder="Enter your first name" size="10" id="Prenom" class="Prenom" value="<?php if (isset($prenom)) echo $prenom; ?>"><?php echo $erreurPrenom; ?>
                    </div>

                    <div class="GenreRadio">
                        <label for="genre" class="genre">Gender :</label>
                        <input type="radio" name="genre" value="Homme" <?php if ($genre == 'Homme') echo 'checked = "checked"'; ?>><label for="homme">Man</label>
                        <input type="radio" name="genre" value="Femme" <?php if ($genre == 'Femme') echo 'checked = "checked"'; ?>><label for="femme">Woman</label><?php echo $erreurGenre; ?>
                    </div>

                    <div class="div_contact">
                        <label for="Date_de_Contact" id="Date2contact">Contact Date :</label>
                        <input type="date" name="date_contact" id="Contact" class="Contact" value="<?php if (isset($date_contact)) echo $date_contact; ?>"><?php echo $erreurContact; ?>
                    </div>
                    <div class="div_sujet">
                        <input type="text" name="sujet" placeholder="Enter the subject of your mail" size="16" id="sujet" class="sujet" value="<?php if (isset($sujet)) echo $sujet; ?>"><?php echo $erreurSujet; ?>
                    </div>
                </div>
            </div>

            <div class="contenu_sujet">
                <textarea name="Contenu" cols="70" rows="8" placeholder="Write your message here" id="contenu" class="contenu"><?php if (isset($contenu)) echo $contenu; ?></textarea><?php echo $erreurContenu; ?>
            </div>

            <input type="submit" value="Send" name="envoyer" id="button-sbt">
            <div><?php if (isset($succes)){
                    echo $succes;
             } ?></div>
        </form>
    </div>

    <div class="imageContact">
        <img src="img/contactez-min.jpg" alt="">
    </div>
    </div>
    </div><br><br><br>
    <footer class="foot"><br>
        <center>
            <div class="up">SAS © <i>Breaking Bread 2022</i></div>
        </center>
        <center>
            <div class="down"><a href="mentionslegales.php">Legal Notice</a> <a href="plan_du_site.php">Site Map</a></div>
        </center>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/contact.js"></script>
</body>

</html>
