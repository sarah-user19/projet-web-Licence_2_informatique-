<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    
    include_once("users.inc.php"); // pour loginOk()----le fichier ou ya les fonctions
        //vérifie si formulaire envoyé
       
    if (isset($_POST["cnx"])){    //“est-ce que le formulaire a été envoyé ?”
    // récupérer les valeurs
        $login = $_POST["login"];
        $mdp = $_POST["mdp"];
        $email=$_POST["email"];

        session_start(); //“j’ouvre la mémoire du site”

        if(loginOk($login, $mdp)){


            //[user]entrain de se connecter donc pas besoin de if(!isset($_SESSION["login"]))
             
    
            $_SESSION["login"] = $login;//On enregistre l’utilisateur

            /*
            “je retiens que cette personne est connectée”

            💡 PHP va s’en souvenir sur toutes les pages

            “l’utilisateur est maintenant connecté” 
            */

            
            header("Location: home_user_complet.php");
            return;
        }

        else{
            header("Location: authentification.php?msg=Erreur");
            // envoyer un mssg d'erreur dans le URL 
            return;
        }





}
    
    
    
    
    
    
    
    
    ?>
</body>
</html>