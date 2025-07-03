<?php 
       
       $servname="localhost";      
        $key_cryptage='la securite avant tout';//clé de cryptage
        $pass=openssl_decrypt(base64_decode("QUpZdVg3QVh2NU5Va29ZdnhEeDNPQT09"),"AES-128-ECB",$key_cryptage);
        $user=openssl_decrypt("5UfEC4F+32Kr6EtKpwtz8A==","AES-128-ECB",$key_cryptage);
       try {
    // Connexion sans DB pour créer la base
    $bdd = new PDO("mysql:host=$servname;charset=utf8", $user, $pass);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bdd = new PDO("mysql:host=$servname;dbname=boulangerie;charset=utf8", $user, $pass);
    $bdd->exec("CREATE DATABASE IF NOT EXISTS boulangerie");
    
    // Connexion avec DB
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création table
    $sql = "CREATE TABLE IF NOT EXISTS categorie (
        cate TEXT NOT NULL,
        link TEXT NOT NULL,
        reference INT PRIMARY KEY,
        icone TEXT NOT NULL,
        citation TEXT NOT NULL
    )";
    $bdd->exec($sql);

           $new_table=" CREATE TABLE user(
               id INT AUTO_INCREMENT PRIMARY KEY,
               prenom VARCHAR(30) NOT NULL,
               nom VARCHAR(30) NOT NULL,
               mail VARCHAR(50) NOT NULL UNIQUE,
               mot_de_passe TEXT NOT NULL,
               sexe VARCHAR(20),
               date_naissance DATE NOT NULL,
               adresse TEXT NOT NULL,
               metier TEXT NOT NULL,
               resolution_ecran VARCHAR(20),
               derniere_connexion DATETIME
               );";
           $bdd->exec($new_table);

           $new_table = "CREATE TABLE produit (
            nom VARCHAR(50) NOT NULL PRIMARY KEY,
            code VARCHAR(2) NOT NULL UNIQUE,
            reference INT NOT NULL,
            presentation TEXT NOT NULL,
            img VARCHAR(100) NOT NULL,
            stock INT NOT NULL,
            prix DECIMAL(10,2) NOT NULL,
            ingredients TEXT NOT NULL,
            categorie_id INT,
            FOREIGN KEY (categorie_id) REFERENCES categorie(reference)
        )";
        $bdd->exec($new_table);
        /*$new_table = "ALTER TABLE produit ADD INDEX idx_code (code);";
        $bdd->exec($new_table);*/
        
        $new_table = "CREATE TABLE commande (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            quantite INT NOT NULL,
            payer INT NOT NULL,
            code_produit VARCHAR(2) NOT NULL,
            id_user INT NOT NULL,
            FOREIGN KEY (code_produit) REFERENCES produit(code),
            FOREIGN KEY (id_user) REFERENCES user(id)
        )";

        
        $bdd->exec($new_table);

            $link1="\'fa fa-wheat-awn\'";
            $link2="\'fa fa-cookie-bite\'";
            $link3="\'fa fa-bread-slice\'";
            $data_cate = "INSERT INTO categorie (reference,cate,link,citation,icone) VALUES 
            (1, 'Pasrties','categorie.php?produit=1','Baking and love are the same - a question of freshness <br> and that all the ingredients, even the most bitter, turn to delight','<i class={$link1}></i>'),
            (2, 'Viennese pastries','categorie.php?produit=2','From night to night, the crescent moon takes on brioche.','<i class={$link2}></i>'),
            (3, 'Sandwich','categorie.php?produit=3','Some free dinners are so boring that a paid sandwich is,<br> far preferable.','<i class={$link3}></i>');";
            $bdd->exec($data_cate);

            $data_pro = "INSERT INTO produit ( nom, reference, presentation, code, img, stock, prix, ingredients, categorie_id) VALUES 
            -- Patisserie
            ( 'Strawberry charlotte', 1, 'Cake composed of boudoir surrounding a soft cream composed of sweet mascarpone mixed with staberry jam', 'P1', 'img/charlotte400-min.png', 22, 27.90, 'Boudoir, Strawberries, Liquid cream, Mascarpone, Icing sugar, Strawberry jam', 1),
            ( 'Apple pie', 2, 'Traditional apple pie recipe, homemade with organic apples', 'P2', 'img/tartePommes400-min.jpg', 125, 18.80, 'Puff pastry, Apple, Eggs, Sugar, Single cream, Cinnamon, Vanilla sugar', 1),
            ( 'Profiteroles', 3, 'A recipe for homemade profiteroles, crunchy and soft puffs that will delight your taste buds.', 'P3', 'img/Profiteroles400-min.jpg', 119, 2.80, 'Milk, Water, Salt, Sugar, Eggs, Flour, Butter, Sugar, Chocolate', 1),
            ( 'Thousand leaves', 4, 'The mille-feuille, a classic pastry that always makes an impression with its vanilla cream and its beauty.', 'P4', 'img/mille400-min.jpg', 95, 11.90, 'Flour, Salt, Water, Butter, Eggs, Milk, Sugar', 1),
            ( 'Eclair', 5, 'A classic that we never tire of. This choux pastry, made by us, will delight you!', 'P5', 'img/Eclair400-min.jpg', 83, 2.00, 'Water, Milk, Salt, Sugar, Butter, Flour, Eggs, Cornstarch, Cocoa, Chocolate, Liquid cream', 1),
            -- Viennoiserie
            ( 'Chocolate bread', 6, 'Flaky chocolate bread with exceptional chocolate made by our finest artisans.', 'V1', 'img/painchoco400-min.png', 87, 1.10, 'Butter, Fresh yeast, Water, Flour, Sugar, Salt, Chocolate', 2),
            ( 'Raisin bread', 7, 'The combination of a magnificent and crunchy shortcrust pastry, cream and perfectly chosen grapes.', 'V2', 'img/raisin400-min.png', 11, 1.30, 'Butter, Yeast, Water, Sugar, Salt, Flour, Eggs, Milk', 2),
            ( 'Swiss bread', 8, 'This Swiss creation, taken up by our French workshops, will make you shiver.', 'V3', 'img/suisse400-min.jpg', 148, 1.70, 'Flour, Salt, Sugar, Yeast, Eggs, Butter, Pastry cream, Chocolate chips', 2),
            ( 'Croissant', 9, 'The classic French pastry made by our artisans.', 'V4', 'img/croissant400-min.jpg', 147, 1.05, 'Butter, Fresh yeast, Water, Flour, Sugar, Salt, Chocolate', 2),
            ( 'Apple turnover', 10, 'Crispy with apple compote, perfect for enjoying.', 'V5', 'img/chausson400-min.png', 98, 1.20, 'Apple, Butter, Eggs, Cinnamon, Sugar, Puff Pastry', 2),
            -- Sandwich
            ( 'Seafood sandwich', 11, 'Fresh tuna sandwich with raw vegetables and homemade mayonnaise.', 'S1', 'img/SandThon400-min.png', 85, 4.70, 'Bread, Salad, Tomato, Mayonnaise, Egg', 3),
            ( 'Parisian Sandwich', 12, 'The most popular sandwich in France, improved by our chef.', 'S2', 'img/SandJamb400-min.png', 90, 4.00, 'Bread, Ham, Butter, Cheese', 3),
            ( 'Bacon Sandwich', 13, 'Our unique, flavorful sandwich will win you over.', 'S3', 'img/sandwichBacon400-min.jpeg', 106, 4.50, 'Bread, Bacon, Cheese, Tomatoes, Salad', 3),
            ( 'Chicken sandwich', 14, 'Chicken sandwich with raw vegetables and homemade mayonnaise.', 'S4', 'img/SandPoulet400-min.jpg', 96, 5.20, 'Bread, Chicken, Mayonnaise, Salad, Tomato', 3),
            ( 'Vegetarian sandwich', 15, 'A vegetarian lunchtime alternative for plant-based lovers.', 'S5', 'img/BagelVege400-min.jpeg', 92, 4.90, 'Cheese, Mozzarella, Feta, Spinach, Tomatoes, Onion', 3);
            ";
            $bdd->exec($data_pro);
            $prenom="JOHN";
            $nom="DO";
            $mail="admin@95.fr";
            $mdp=MD5("motdepasse123");
            $sexe="Homme";
            $naissance="2000-04-04";
            $adresse="rue veaugirad";
            $metier="admin";
            $new = $bdd->prepare("INSERT INTO user (prenom, nom, mail, mot_de_passe, sexe, date_naissance, adresse, metier) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            // Liez les valeurs aux paramètres de la requête
            $new->bindParam(1, $prenom);
            $new->bindParam(2, $nom);
            $new->bindParam(3, $mail);
            $new->bindParam(4, $mdp);
            $new->bindParam(5, $sexe);
            $new->bindParam(6, $naissance);
            $new->bindParam(7, $adresse);
            $new->bindParam(8, $metier);
            
            // Exécutez la requête préparée
            $new->execute();
            header('Location: http://localhost:8080/index.php');
            exit();
        }
        catch(PDOException $e){
            echo "ERREUR : ". $e->getMessage();
        }
?>