<?php
session_start();
if (isset($_SESSION["login"])) {
   $nom  = $_SESSION["login"];
}


$data = json_decode(file_get_contents("data.json"), true);


?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>souvenirs élégants</title>
    <script src="https://kit.fontawesome.com/8428a8ab72.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="page2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<style>
    .ss1 > div {
         display: flex;
    flex-wrap: wrap;
    gap: 24px;
    padding: 40px 32px;
    justify-content: center;
    justify-content: space-around;


    }


</style>
<body>


<header>
    <img id="logo" src="./image/logo1.svg" alt="CROCHET ARTS">
    <ul class="menu">
        <?php if (! isset($_SESSION["login"])) : ?>
              <li><a href="index.html">Home</a></li>
               <li><a href="index.html#preview">Preview</a></li>
               <li><a href="index.html#reviews">Reviews</a></li>
        <?php else: ?>
            <li><a href="home_user_complet.php">Home</a></li>
         <?php endif; ?>   
        <li class="dropdown" id="pt">
                <a href="#">Categories  <img src="image/chevron.svg" id="flech1"></img></a>
            <ul class="dropdown-content">
                    <li><a href="page2.php">Souvenirs élégants</a></li>
                    <li><a href="page3.php">Souvenirs visuels</a></li>
                    <li><a href="page4.php">Vos trésors</a></li>
                
            </ul>
        </li>
    </ul>

 <div class="btn-header">
        <?php if (isset($_SESSION["login"])): ?>
            <!-- user connecté → on affiche son nom + déconnexion -->
            <div class="nav-avatar">
                <?php echo strtoupper(substr($nom, 0, 2)); ?>
            </div>
            <span class="nav-username"><?php echo htmlspecialchars($nom); ?></span> 
            <a href="logout.php" class="btn-logout">Déconnexion</a>
        <?php else: ?>
            <!-- user non connecté → bouton connexion normal -->
            <button class="btn">
                <a href="authentification.php">
                    <img src="image/user.svg" alt="">
                    <p><span>Connexion</span></p>
                </a>
            </button>
        <?php endif; ?>
    </div>




</header>




   <section class="hero">
     <h2 style="color: white;">Découvrez <br> <br> les <br> <span>Souvenirs élégants</span></h2>
     <img id="img1" src="image/9.jpg" alt="">
     <img id="img2" src="./image/10.jpg" alt="">
     <img id="img3" src="./image/11.jpg" alt="">
      
    </section>

<!-- Section objets -->

<section id="haut" class="ss1">


<div>
<?php

foreach ($data as $obj):
if ($obj['categorie']!='photo' && $obj['nom']=='admin'):
    $total = $obj['vrai'] + $obj['faux'];
    $pourcentage = $total > 0 ? round(($obj['vrai'] / $total) * 100) : 0;
?>
<div class="product">
    <img src="<?php echo htmlspecialchars($obj['img']); ?>" alt="<?php echo htmlspecialchars($obj['titre']); ?>">
    <strong><?php echo htmlspecialchars($obj['titre']); ?></strong>
    
    <?php if (!empty($obj['epoque']) || !empty($obj['lieu'])): ?>
        <p><?php echo htmlspecialchars($obj['lieu']); ?> — <?php echo htmlspecialchars($obj['epoque']); ?></p>
    <?php endif; ?>

    <p class="veracite">Véracité : <?php echo $pourcentage; ?>%</p>

    <a href="page6.php?id=<?php echo $obj['id']; ?>" class="bbtn">Voir l'histoire</a>
    <p>@<?php echo htmlspecialchars($obj['nom']); ?></p>
</div>
<?php endif; ?>
<?php endforeach; ?>
</div>
</section>


<footer class="footer-knot">
    <div class="footer-content">
        <div>
        <h1 class="footer-logo">Archive Of Truth</h1>
        <img style="width: 150px;" src="image/logo2.svg" alt="">
        </div>
        <p>
           Découvrez et partagez les histoires cachées derrière chaque objet ancien.
Une communauté passionnée qui donne vie aux souvenirs du passé.
        </p>
        <div class="social-icons">
            <p>Contact</p>
            <a href="#"><img src="image/instagram.svg" alt=""></a>
            <a href="#"><img src="image/wats.svg" alt=""></a>
            <a id="gmail" href="#">Archive.of.truth@gmail.com</a>
        </div>
      
    </div>

    <p class="footer-note">© 2026 Chaque objet a une histoire, partagez la vôtre. <img src="image/cr.svg" alt=""></p>
 </footer>