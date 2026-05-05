<?php
require_once("users.inc.php");

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
        session_start();
        $_SESSION["login"]=$login;
        header("Location: home_user_complet.php");
        exit;
    }
    
    header("Location: authentification.php?msg=$msg");
        exit;
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

  .error-msg {
    background: rgba(220, 50, 50, 0.12);
    border: 1px solid rgba(220, 50, 50, 0.4);
    color: #c0392b;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 13px;
    margin-bottom: 12px;
    text-align: center;
  }
</style>
<body>


  <div class="container">
    <!-- Formulaire Connexion -->
    <div id="login-form">
      <h2>Connexion</h2>
      <?php if(isset($_GET["msg"]) && !isset($_POST["btn"])): ?>
        <div class="error-msg"><?php echo htmlspecialchars($_GET["msg"]); ?></div>
      <?php endif; ?>
      <form action="access.php" method="POST">
        <div class="form-group">
          <label for="login">Username</label>
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
      <?php if(isset($_GET["msg"])): ?>
        <div class="error-msg"><?php echo htmlspecialchars($_GET["msg"]); ?></div>
      <?php endif; ?>
      <form action="authentification.php" method="POST">
        <div class="form-group">
          <label for="name">Username</label>
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

    // Si erreur venant d'une inscription, afficher le bon formulaire
    <?php if(isset($_GET["msg"]) && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'authentification.php') !== false): ?>
    toggleForms(); // l'erreur vient du formulaire inscription → on l'affiche
    <?php endif; ?>
  </script>
</body>
</html>