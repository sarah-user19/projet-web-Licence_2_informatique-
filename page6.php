<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>



<?php

<<<<<<< HEAD

$data = json_decode(file_get_contents("data.json"), true);
/*on decode l'objet JSON en un array dans PHP pour pouvoir le lire */

=======
$data = json_decode(file_get_contents("data.json"), true);
>>>>>>> f916fe35a210d7051030a2c784230e77322d7530

$id = $_GET['id'];

foreach ($data as $current) {
    if ($current["id"] == $id) {
        $obj = $current;
    }
}


if (!$obj) {
    echo "Objet introuvable";
    exit;
}
?>

<<<<<<< HEAD
<div class="story-container">
    <header>
        <h1><?php echo $obj["titre"]; ?></h1>
        <!-- Indicateur temps réel : qui regarde en même temps ? -->
        <div id="live-users" class="badge-live">
            <span class="pulse"></span> <span id="user-count">1</span> explorateur(s) sur ce récit
        </div>
    </header>

    <div class="content-split">
        <img src="<?php echo $obj["img"];?>" class="relic-image">
        
        <div class="narrative-text">
            <h3>Origine de l'objet</h3>
            <p><?php echo $obj["story"];?></p>
        
        </div>
    </div>

    <!-- LE JEU ENTRE UTILISATEURS : Le "Rituel de Mémoire" -->
    <div class="interaction-zone">
        <h3>Le Rituel Collectif</h3>
        <p>Cliquez ensemble pour illuminer la relique !</p>
        <button id="like-btn" class="action-btn">✨ Apporter une lueur</button>
        <div id="relic-glow-level">Énergie actuelle : 0%</div>
    </div>

    <!-- Section Commentaires (AJAX obligatoire) -->
    <section class="comments-section">
        <h3>Fragments de mémoire (Commentaires)</h3>
        <div id="comments-feed">
            <!-- Les messages des autres apparaissent ici sans recharger (AJAX) -->
      
      
        </div>
        <textarea id="user-comment" placeholder="Ajoutez un fragment à l'histoire..."></textarea>
        <button onclick="sendComment(<?php echo $id; ?>)">Envoyer</button>
    </section>
</div>

=======


<title><?php echo $obj["titre"]; ?></title>

<img src="<?php echo $obj["img"];?>" style="width:300px;">

<p>Histoire de l’objet...</p>

<ul>
    <li>Couleur : violet</li>
    <li>Style : bohème</li>
</ul>

<div>
    <button>👍 J’aime</button>
    <button>💬 Commenter</button>
</div>

<h3>Commentaires</h3>
<textarea></textarea>
<button>Envoyer</button>
<div id="comments"></div>





>>>>>>> f916fe35a210d7051030a2c784230e77322d7530
</body>
</html>