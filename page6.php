<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: authentification.php?msg='erreur de connexion'");
    exit;
}
$nom  = $_SESSION["login"];
$data = json_decode(file_get_contents("data.json"), true);
$id   = $_GET['id'] ?? null;
$obj  = null;
if (!$id) { echo "ID manquant"; exit; }
foreach ($data as $current) {
    if ($current["id"] == $id) { $obj = $current; break; }
}
if (!$obj) { echo "Objet introuvable"; exit; }
$total       = $obj['vrai'] + $obj['faux'];
$pourcentage = $total > 0 ? round(($obj['vrai'] / $total) * 100) : 0;
$prix_reel   = isset($obj['prix_reel']) && $obj['prix_reel'] > 0 ? floatval($obj['prix_reel']) : null;
$categorie   = strtolower(trim($obj['categorie'] ?? ''));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail objet</title>
    <link rel="stylesheet" href="page2.css">
    <style>
        .objet-detail {
            grid-template-columns: 300px 1fr;
            gap: 36px;
            max-width: 1200px;
        }

        .detail-right {
            height: 800px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .story-box {
            background: #fff;
            border: 1px solid #e8e2d9;
            border-radius: 12px;
            padding: 20px 24px;
            height: 100%;
            width: 100%;
            min-width: 0;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 0 2px 12px rgba(44,26,10,0.07);
            scrollbar-width: thin;
            scrollbar-color: #D3C5B0 transparent;
        }

        .story-box::-webkit-scrollbar { width: 5px; }
        .story-box::-webkit-scrollbar-thumb { background: #D3C5B0; border-radius: 10px; }

        .detail-story {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 1rem;
            line-height: 1.9;
            color: #3a2e22;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .story-header {
            display: flex;
            gap: 20px;
            font-size: 0.85rem;
            color: #8a6a45;
            font-style: italic;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        /* Commentaires */
        .commentaires-section {
            max-width: 1200px;
            margin: 40px auto 20px;
            padding: 0 24px;
        }

        .comments-box {
            width: 100%;
            border: 1px solid #e8e2d9;
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 4px 16px rgba(44,26,10,0.08);
            display: flex;
            flex-direction: column;
        }

        .comments-scroll {
            height: 300px !important;
            overflow-y: scroll !important;
            padding: 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .comments-scroll .barre-container { width: 100px !important; height: 6px !important; margin: 3px 0 !important; align-self: flex-start; }
        .comments-scroll .commentaire { padding: 6px 10px !important; }
        .comments-scroll .commentaire p,
        .comments-scroll .commentaire pre { font-size: 0.78rem !important; line-height: 1.3 !important; margin: 2px 0 !important; font-family: inherit !important; white-space: pre-wrap !important; }
        .comments-scroll .commentaire-nom { font-size: 0.78rem !important; }
        .comments-scroll .commentaire-avatar { width: 24px !important; height: 24px !important; font-size: 0.6rem !important; }
        .comments-scroll .veracite-com { font-size: 0.68rem !important; }

        .comments-input {
            display: flex;
            flex-direction: row;
            gap: 10px;
            align-items: flex-end;
            padding: 14px 20px;
            border-top: 1px solid #f0ebe3;
            background: #FAF6F0;
        }

        .comments-input textarea {
            flex: 1; width: auto !important; height: 60px !important;
            padding: 10px 14px; border-radius: 10px;
            border: 1.5px solid #e8e2d9; resize: none; margin: 0 !important;
        }

        .comments-input button {
            height: 40px; padding: 0 20px !important; width: auto !important;
            background: #BA7517 !important; color: #fff !important;
            border: none !important; border-radius: 10px !important;
            cursor: pointer !important; white-space: nowrap; flex-shrink: 0;
        }

        /* Jeu prix */
        .jeu-prix-section {
            max-width: 600px;
            margin: 30px auto 60px;
            padding: 32px;
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e8e2d9;
            box-shadow: 0 4px 20px rgba(44,26,10,0.08);
            text-align: center;
        }

        .jeu-prix-section h3 { font-family: 'Playfair Display', serif; font-size: 1.4rem; color: #2C1A0A; margin-bottom: 8px; }
        .jeu-sous-titre { font-size: 0.88rem; color: #9e8c7a; margin-bottom: 24px; }

        .jeu-input-row { display: flex; gap: 10px; justify-content: center; align-items: center; margin-bottom: 16px; }

        .jeu-input-row input {
            width: 180px; padding: 12px 16px; border-radius: 12px;
            border: 1.5px solid #e8e2d9; font-size: 1rem; text-align: center; outline: none;
        }
        .jeu-input-row input:focus { border-color: #BA7517; }

        .jeu-input-row button {
            padding: 12px 24px !important;
            background: linear-gradient(135deg, #BA7517, #e8a030) !important;
            color: #fff !important; border: none !important; border-radius: 12px !important;
            font-weight: 600 !important; cursor: pointer !important; width: auto !important;
        }

        .jeu-total { font-size: 0.82rem; color: #9e8c7a; margin-bottom: 16px; }

        .jeu-ma-proposition {
            background: #fff8ee; border: 1px solid #EF9F27;
            border-radius: 10px; padding: 10px 16px;
            font-size: 0.9rem; color: #BA7517; font-weight: 600; margin-bottom: 16px;
        }

        .btn-voir-resultat {
            padding: 10px 24px !important;
            background: #2C1A0A !important; color: #EF9F27 !important;
            border: none !important; border-radius: 10px !important;
            font-weight: 600 !important; cursor: pointer !important; width: auto !important;
        }

        .jeu-resultat {
            background: linear-gradient(135deg, #fff8ee, #fff);
            border: 2px solid #EF9F27; border-radius: 16px; padding: 24px; margin-top: 16px;
        }

        .jeu-resultat .vrai-prix { font-size: 2rem; font-weight: 700; color: #BA7517; margin-bottom: 8px; }
        .jeu-resultat .gagnant { font-size: 1.1rem; color: #2C1A0A; font-weight: 600; }
        .jeu-resultat .gagnant span { color: #BA7517; }

        .jeu-propositions { margin-top: 16px; display: flex; flex-direction: column; gap: 6px; text-align: left; }

        .jeu-prop-item {
            display: flex; justify-content: space-between;
            font-size: 0.82rem; padding: 6px 12px;
            border-radius: 8px; background: #faf6f0;
        }

        .jeu-prop-item.gagnant-item { background: #fff8ee; border: 1px solid #EF9F27; font-weight: 600; }

        .supprimer-lien {
            display: inline-block; margin-top: 10px;
            font-size: 0.78rem; color: #ef4444;
            text-decoration: underline; cursor: pointer;
        }
    </style>
</head>
<body>

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
    <div class="btn-header">
        <?php if (isset($_SESSION["login"])): ?>
            <div class="nav-avatar"><?php echo strtoupper(substr($nom, 0, 2)); ?></div>
            <span class="nav-username"><?php echo htmlspecialchars($nom); ?></span>
            <a href="logout.php" class="btn-logout">Déconnexion</a>
        <?php endif; ?>
    </div>
</header>

<div class="objet-detail">

    <!-- GAUCHE -->
    <div class="detail-left">
        <div class="detail-img-box">
          <a href="<?php echo htmlspecialchars($obj['img']); ?>" target="_blank">
    <img src="<?php echo htmlspecialchars($obj['img']); ?>" alt="<?php echo htmlspecialchars($obj['titre']); ?>">
</a>
        </div>
        <h1 class="detail-titre"><?php echo htmlspecialchars($obj['titre']); ?></h1>
        <div class="auteur-card">
            <div class="auteur-avatar"><?php echo strtoupper(substr($obj['nom'], 0, 2)); ?></div>
            <div class="auteur-info">
                <span class="auteur-label">Publié par</span>
                <span class="auteur-nom"><?php echo htmlspecialchars($obj['nom']); ?></span>
            </div>
        </div>
        <div class="barre-container">
            <div class="barre" style="width: <?php echo $pourcentage; ?>%"></div>
        </div>
        <p class="veracite">Véracité : <?php echo $pourcentage; ?>%</p>
        <div class="vote-btns">
            <button onclick="voterHistoire(<?php echo $id; ?>, 'vrai')"><img style="height: 20px;" src="image/like.png" alt=""> <?php echo $obj['vrai']; ?></button>
            <button onclick="voterHistoire(<?php echo $id; ?>, 'faux')"><img style="height: 20px;" src="image/dislike.png" alt=""> <?php echo $obj['faux']; ?></button>
        </div>
        <?php if ($nom === $obj["nom"]): ?>
        <a class="supprimer-lien"
           href="supprimer_histoire.php?id=<?php echo $id; ?>"
           onclick="return confirm('Supprimer cet objet définitivement ?')">
            🗑 Supprimer cet objet
        </a>
        <?php endif; ?>
    </div>

    <!-- DROITE -->
    <div class="detail-right">
        <h3 class="story-label">L'histoire</h3>
        <div class="story-box">
            <?php if (!empty($obj['epoque']) || !empty($obj['categorie']) || !empty($obj['lieu'])): ?>
            <div class="story-header">
                <?php if (!empty($obj['epoque'])): ?>
                    <span><img style="height: 20px;" src="image/date.png" alt=""> <?php echo htmlspecialchars($obj['epoque']); ?></span>
                <?php endif; ?>
                <?php if (!empty($obj['lieu'])): ?>
                    <span><img style="height: 20px;" src="image/place.png" alt=""> <?php echo htmlspecialchars($obj['lieu']); ?></span>
                <?php endif; ?>
                <?php if (!empty($obj['categorie'])): ?>
                    <span><img style="height: 20px;" src="image/cat.png" alt=""> <?php echo htmlspecialchars($obj['categorie']); ?></span>
                <?php endif; ?>
            </div>
            <hr style="border:none; border-top:1px solid #e8e2d9; margin-bottom:16px;">
            <?php endif; ?>
            <pre class="detail-story"><?php echo htmlspecialchars($obj['story']); ?></pre>
        </div>
    </div>

</div>

<!-- COMMENTAIRES -->
<div class="commentaires-section">
    <div class="comments-box">
        <div class="comments-scroll" id="commentaires-<?php echo $id; ?>"></div>
        <div class="comments-input">
            <textarea id="input-<?php echo $id; ?>" placeholder="Ajouter un commentaire..."></textarea>
            <button onclick="envoyerCommentaire(<?php echo $id; ?>)">Envoyer</button>
        </div>
    </div>
</div>

<!-- JEU (sauf photo) -->
<?php if ($categorie !== 'photo'): ?>
<div class="jeu-prix-section">
    <h3><img src="image/win.png" alt=""> Qui devine le prix ?</h3>
    <p class="jeu-sous-titre">Estimez la valeur de cet objet. Le plus proche gagne !( 1 = sans prix)</p>
    <div id="jeu-contenu"></div>
</div>
<?php endif; ?>

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

<script src="script.js"></script>
<script>
const jeuHistoireId   = <?php echo $id; ?>;
const jeuProprietaire = "<?php echo addslashes($obj['nom']); ?>";
const jeuUserCourant  = "<?php echo addslashes($nom); ?>";
const prixReel        = <?php echo $prix_reel ? $prix_reel : 'null'; ?>;

function chargerJeu() {
    if (!document.getElementById("jeu-contenu")) return;

    if (!prixReel) {
        document.getElementById("jeu-contenu").innerHTML =
            '<p style="color:#9e8c7a;font-size:0.85rem;">Prix non encore défini pour cet objet.</p>';
        return;
    }

    fetch("get_jeu_prix.php?histoire_id=" + jeuHistoireId)
        .then(r => r.json())
        .then(data => {
            const zone = document.getElementById("jeu-contenu");
            let html   = '';

            if (data.vrai_prix) {
                let propsHtml = '';
                Object.entries(data.propositions).forEach(([user, prix]) => {
                    if (user.startsWith("__")) return;
                    const isGagnant = user === data.gagnant;
                    propsHtml += `
                        <div class="jeu-prop-item ${isGagnant ? 'gagnant-item' : ''}">
                            <span>${isGagnant ? '<img src="image/win.png" alt=""> ' : ''}${user}</span>
                            <span>${parseFloat(prix).toFixed(2)} €</span>
                        </div>`;
                });
                zone.innerHTML = `
                    <div class="jeu-resultat">
                        <div class="vrai-prix"> <img src="image/money.png" alt=""> ${parseFloat(data.vrai_prix).toFixed(2)} €</div>
                        <p style="color:#9e8c7a;font-size:0.82rem;margin-bottom:8px;">Prix réel de l'objet</p>
                        <div class="gagnant"><img src="image/win.png" alt=""> Gagnant : <span>${data.gagnant ?? "Personne"}</span></div>
                    </div>
                    <div class="jeu-propositions">${propsHtml}</div>`;
                return;
            }

            if (data.ma_proposition !== null) {
                html += `<div class="jeu-ma-proposition">Votre proposition : ${parseFloat(data.ma_proposition).toFixed(2)} €</div>`;
                html += `<button class="btn-voir-resultat" onclick="voirResultat()">Voir le résultat 🏆</button>`;
            } else if (jeuUserCourant !== jeuProprietaire) {
                html += `
                    <div class="jeu-input-row">
                        <input type="number" id="prix-input" placeholder="Ex: 250 €" min="0" step="0.01">
                        <button onclick="soumettreP()">Proposer</button>
                    </div>`;
            } else {
                html += `<p style="color:#9e8c7a;font-size:0.85rem;">Vous êtes le propriétaire de cet objet.</p>`;
            }

            html += `<p class="jeu-total">${data.total} participant(s)</p>`;
            zone.innerHTML = html;
        });
}

function soumettreP() {
    const prix = parseFloat(document.getElementById("prix-input").value);
    if (!prix || prix <= 0) { alert("Entre un prix valide !"); return; }

    fetch("soumettre_prix.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ histoire_id: jeuHistoireId, prix: prix })
    })
    .then(r => r.json())
    .then(res => { if (res.status === "ok") chargerJeu(); });
}

function voirResultat() {
    fetch("reveler_prix.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ histoire_id: jeuHistoireId, vrai_prix: prixReel })
    })
    .then(r => r.json())
    .then(() => chargerJeu());
}

chargerJeu();

fetch("get_mes_votes.php")
    .then(r => r.json())
    .then(votes => {
        mesVotes = votes;
        chargerCommentaires(<?php echo $id; ?>);
        let bVrai = document.querySelector(`button[onclick="voterHistoire(<?php echo $id; ?>, 'vrai')"]`);
        let bFaux = document.querySelector(`button[onclick="voterHistoire(<?php echo $id; ?>, 'faux')"]`);
        appliquerEtatBoutons(bVrai, bFaux, mesVotes["histoire_<?php echo $id; ?>"]);
    });
</script>

</body>
</html>