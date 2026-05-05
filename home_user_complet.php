<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon espace</title>
    <link rel="stylesheet" href="page2.css">
</head>
<body>
<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: authentification.php?msg='erreur de connexion'");
    return;
}

$nom = $_SESSION["login"];

// Lire le fichier JSON
$data = json_decode(file_get_contents("data.json"), true);
if (empty($data)) $data = [];

// Objets de l'utilisateur connecté
$mes_objets = array_filter($data, function($obj) use ($nom) {
    return $obj['nom'] === $nom;
});
$mes_objets = array_values($mes_objets);

// Objets des autres utilisateurs (découvrir)
$autres_objets = array_filter($data, function($obj) use ($nom) {
    return $obj['nom'] !== $nom;
});
$autres_objets = array_values($autres_objets);

// Stats
$nb_objets = count($mes_objets);
?>
     
 <header id="Home">
    <img id="logo" src="./image/logo1.svg" alt="logo knot.co">
    <ul class="menu">
        <li><a href="home_user_complet.php">Home</a></li>

        <li class="dropdown" id="pt">
            <a href="#">Categories <img src="image/chevron.svg" id="flech1"></a>
            <ul class="dropdown-content">
                <li><a href="page2.php">Souvenirs élégants</a></li>
                <li><a href="page3.php">Souvenirs visuels</a></li>
                <li><a href="page4.php">Vos trésors</a></li>
            </ul>
        </li>
    </ul>

    <!--  Remplace le bouton Connexion par le nom de l'utilisateur connecté -->
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




      

<!-- HERO -->
<div class="hero" id="hhero">
    <div class="hero-greeting">Bonjour <img style="height: 20px;" src="image/hi.png" alt=""></div>
    <h1 class="hero-name"><?php echo htmlspecialchars($nom); ?></h1>
    <p class="hero-sub">Bienvenue sur votre espace Reliques</p>
    <div class="hero-stats">
        <div>
            <div class="hero-stat-num"><?php echo $nb_objets; ?></div>
            <div class="hero-stat-label">Mes objets</div>
        </div>
        <div>
            <div class="hero-stat-num"><?php echo count($data); ?></div>
            <div class="hero-stat-label">Total galerie</div>
        </div>
    </div>
</div>

<!-- ACTIONS RAPIDES -->
<div class="section">
    <div class="section-header">
        <span class="section-title">Actions rapides</span>
    </div>
</div>
<div class="quick-actions">
    <a href="ajout.php" class="qa">
        <div class="qa-icon amber"><img src="image/box.png" alt=""></div>
        <span class="qa-label">Publier un objet</span>
    </a>
    <a href="page4.php" class="qa">
        <div class="qa-icon green"><img src="image/search.png" alt=""></div>
        <span class="qa-label">Explorer</span>
    </a>
</div>

<!-- BANNIÈRE LIVE -->
<div class="live-banner">
    <div class="live-dot"></div>
    <span class="live-text">
        <?php echo count($autres_objets); ?> objets disponibles dans la galerie
    </span>
</div>

<!-- DÉCOUVRIR -->
<div class="section">
    <div class="section-header">
        <span class="section-title">Découvrir</span>
        <a href="page2.php" class="section-link">Voir tout →</a>
    </div>
</div>
<div class="h-scroll">
    <?php if (empty($autres_objets)): ?>
        <p style="padding:0 20px;color:#888780;font-size:13px;">Aucun objet à découvrir pour l'instant.</p>
    <?php endif; ?>

    <?php foreach (array_slice($autres_objets, 0, 6) as $obj): ?>
    <div class="hcard" onclick="window.location='page6.php?id=<?php echo $obj['id']; ?>'">
        <div class="hcard-img">
            <img src="<?php echo htmlspecialchars($obj['img']); ?>" alt="<?php echo htmlspecialchars($obj['titre']); ?>">
        </div>
        <div class="hcard-body">
            <div class="hcard-title"><?php echo htmlspecialchars($obj['titre']); ?></div>
            <div class="hcard-epoch"><?php echo htmlspecialchars(substr($obj['story'], 0, 40)); ?>...</div>
            <div class="hcard-foot">
                <span class="hcard-user">@<?php echo htmlspecialchars($obj['nom']); ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- MES OBJETS -->
<div class="section">
    <div class="section-header">
        <span class="section-title">Mes objets</span>

    </div>
</div>
<div class="my-grid">
    <?php if (empty($mes_objets)): ?>
        <p style="padding:0 0 16px;color:#888780;font-size:13px;">Vous n'avez pas encore publié d'objet.</p>
    <?php endif; ?>

    <?php foreach (array_slice($mes_objets, 0, 5) as $obj): ?>
    <div class="mgcard" onclick="window.location='page6.php?id=<?php echo $obj['id']; ?>'">
        <div class="mgcard-img">
            <img src="<?php echo htmlspecialchars($obj['img']); ?>" alt="<?php echo htmlspecialchars($obj['titre']); ?>">
        </div>
        <div class="mgcard-overlay">
            <div class="mgcard-overlay-title"><?php echo htmlspecialchars($obj['titre']); ?></div>
        </div>
        <div class="mgcard-info">
            <div class="mgcard-title"><?php echo htmlspecialchars($obj['titre']); ?></div>
            <div class="mgcard-epoch">@<?php echo htmlspecialchars($obj['nom']); ?></div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Bouton ajouter -->
    <a href="ajout.php" class="add-card">
        <div class="add-plus">+</div>
        <div class="add-label">Ajouter</div>
    </a>
</div>

<!-- DERNIERS AJOUTS -->
<div class="section">
    <div class="section-header">
        <span class="section-title">Derniers ajouts</span>
        
    </div>
</div>
<div class="activity-list">
    <?php foreach (array_slice(array_reverse($data), 0, 4) as $obj): ?>
    <div class="act-item" onclick="window.location='page6.php?id=<?php echo $obj['id']; ?>' " style="cursor:pointer">
        <div class="act-icon"><img src="image/box.png" alt=""></div>
        <div class="act-content">
            <div class="act-text">
                <strong><?php echo htmlspecialchars($obj['nom']); ?></strong>
                a publié <strong><?php echo htmlspecialchars($obj['titre']); ?></strong>
            </div>
            <div class="act-time">Voir l'histoire →</div>
        </div>
        <div class="act-thumb">
            <img src="<?php echo htmlspecialchars($obj['img']); ?>" alt="">
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- FOOTER -->
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

</body>
</html>