<?php
session_start();
//fait
$nom=htmlentities($_POST['nom']);
$prenom=htmlentities($_POST['prenom']);
$mail=htmlentities($_POST['mail']);
$password=htmlentities($_POST['mot_de_passe']);
$sexe=htmlentities($_POST['sexe']);
$naissance=htmlentities($_POST['naissance']);
$adresse=htmlentities($_POST['adresse']);
$metier=htmlentities($_POST['metier']);
$resolution = isset($_POST['resolution_ecran']) ? $_POST['resolution_ecran'] : null;
$mdp=MD5($password);


$servname="localhost";
$key_cryptage='la securite avant tout';//clé de cryptage
$pass=openssl_decrypt(base64_decode("QUpZdVg3QVh2NU5Va29ZdnhEeDNPQT09"),"AES-128-ECB",$key_cryptage);
$user=openssl_decrypt("5UfEC4F+32Kr6EtKpwtz8A==","AES-128-ECB",$key_cryptage);

$bdd= new PDO("mysql:host=$servname;dbname=boulangerie",$user,$pass); //connexion base de données
$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
try {
    // Vérification de l'email déjà utilisé
    $recup_user = $bdd->prepare("SELECT * FROM user WHERE mail = :mail");
    $recup_user->execute(['mail' => $mail]);

    if ($recup_user->rowCount() > 0) {
        $response = 'Cette adresse mail est déjà utilisée';
    }
    // Vérification prénom et nom
    else if (empty($prenom) || !preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ ]+$/', $prenom) || !preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ ]+$/', $nom)) {
        $response = 'Le prénom, le nom et l\'adresse ne doivent contenir que des lettres sans caractères spéciaux.';
    }
    // Vérification du mot de passe brut
    else if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{9,}$/', $password)) {
        $response = 'Le mot de passe doit contenir au moins 9 caractères, une lettre majuscule, une lettre minuscule et un chiffre.';
    }
    else {

        // Insertion en base
        $new = $bdd->prepare("INSERT INTO user (prenom, nom, mail, mot_de_passe, sexe, date_naissance, adresse, metier,resolution_ecran)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $new->execute([$prenom, $nom, $mail, $mdp, $sexe, $naissance, $adresse, $metier, $resolution]);

        // Démarrer la session
        $_SESSION['connecte'] = 1;
        $_SESSION['nom'] = $nom . ' ' . $prenom;
        $_SESSION['utilisateur'] = $mail;

        // Récupération de l'ID nouvel utilisateur
        $recup_user = $bdd->query("SELECT max(id) as id FROM user");
        $users = $recup_user->fetch(PDO::FETCH_ASSOC);
        $_SESSION['id_user'] = $users['id'];

        $response = "ok";
    }
}
catch (PDOException $e) {
    $response = "ERREUR : " . $e->getMessage();
}

echo json_encode(['response' => $response]);
