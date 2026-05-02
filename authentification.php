<?php
require_once("users.inc.php");
if(isset($_GET["msg"])){
    echo "<p style='color:red'>" . $_GET["msg"] . "</p>";
}

if(isset($_POST["btn"])){
    
    $login = $_POST["login"];
    $mdp = $_POST["mdp"];
    $mdp2 = $_POST["mdp2"];
    $email = $_POST["email"];
     // champs vides
    if($login == "" || $mdp == "" || $email == ""){
        $msg = "Champs vides";
    }
    elseif($mdp != $mdp2){
        $msg = "Mots de passe différents";
    }
    elseif(exist($login)){
        $msg = "username déjà existe";
    }
     else{
        addUser($login,$mdp,$email);
        header("Location: home_user_complet.php?msg=Inscription réussie");
        return;
    }
    
    header("Location: authentification.php?msg=$msg");
        return;





}

?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Connexion / Inscription </title>
  <link rel="stylesheet" href="authentification.css">

</head>
<style>
  body{
     background-image: url('./image/1.jpg');
  }
</style>
<body>


  <div class="container">
    <!-- Formulaire Connexion -->
    <div id="login-form">
      <h2>Connexion</h2>
      <form action="access.php" method="POST">
        <div class="form-group">
          <label for="login">Nom</label>
          <input type="text" id="login-email" name="login" required />
        </div>
        <div class="form-group">
          <label for="login-password">Mot de passe</label>
          <input type="password" id="login-password" name="mdp" required />
        </div>
        <button type="submit" name="cnx" class="btn">Se connecter</button>
      </form>
      <div class="toggle-link">
        Pas encore de compte ? <span onclick="toggleForms()">Créer un compte</span>
      </div>
    </div>

    <!-- Formulaire Inscription -->
    <div id="register-form" class="hidden">
      <h2>Créer un compte</h2>
      <form action="authentification.php" method="POST">
        <div class="form-group">
          <label for="name">Nom complet</label>
          <input type="text" id="name" name="login" required />
        </div>
        <div class="form-group">
          <label for="email">Adresse e-mail</label>
          <input type="email" id="email" name="email" required />
        </div>
        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="mdp" required />
        </div>
        <div class="form-group">
          <label for="confirm-password">Confirmer le mot de passe</label>
          <input type="password" id="confirm-password" name="mdp2" required />
        </div>
        <button type="submit" name="btn" class="btn">Créer un compte</button>
      </form>
      <div class="toggle-link">
        Déjà inscrit ? <span onclick="toggleForms()">Se connecter</span>
      </div>
    </div>
  </div>

  <script>
    function toggleForms() {
      document.getElementById("login-form").classList.toggle("hidden");
      document.getElementById("register-form").classList.toggle("hidden");
    }
  </script>
</body>
</html>
