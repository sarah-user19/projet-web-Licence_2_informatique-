<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <title>Ajouter un objet</title>
</head>
<style>


    /* ── AJOUT PAGE ── */
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Outfit:wght@300;400;500;600&display=swap');

body {
    background: #f5f0e8;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
}

.form-container {
    background: #fff;
    border-radius: 24px;
    padding: 48px 52px;
    max-width: 580px;
    width: 100%;
    box-shadow: 0 8px 48px rgba(44,26,10,0.1);
    border: 1px solid #ede8df;
}

.form-container h2 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2rem;
    color: #1e1208;
    margin-bottom: 8px;
}

.form-subtitle {
    font-family: 'Outfit', sans-serif;
    font-size: 0.85rem;
    color: #9e8c7a;
    margin-bottom: 36px;
    letter-spacing: 0.05em;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 20px;
}

.form-group label {
    font-family: 'Outfit', sans-serif;
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: #7a6e5f;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #e8e2d9;
    border-radius: 12px;
    background: #faf8f4;
    font-family: 'Outfit', sans-serif;
    font-size: 0.92rem;
    color: #1e1208;
    outline: none;
    transition: border-color 0.2s, background 0.2s;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #BA7517;
    background: #fff;
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: #bfb8ae;
}

.form-group textarea {
    height: 160px;
    resize: vertical;
    line-height: 1.7;
}

/* Upload image */
.file-upload {
    position: relative;
}

.file-upload input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
}

.file-upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 2px dashed #e8e2d9;
    border-radius: 12px;
    padding: 28px;
    background: #faf8f4;
    cursor: pointer;
    transition: border-color 0.2s, background 0.2s;
    text-align: center;
}

.file-upload:hover .file-upload-label {
    border-color: #BA7517;
    background: #fff8ee;
}

.file-upload-icon {
    font-size: 2rem;
}

.file-upload-text {
    font-family: 'Outfit', sans-serif;
    font-size: 0.85rem;
    color: #9e8c7a;
}

/* Grille 2 colonnes pour époque + lieu */
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

/* Bouton submit */
.btn-submit-ajout {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #BA7517, #e8a030);
    color: #fff;
    border: none;
    border-radius: 14px;
    font-family: 'Outfit', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    letter-spacing: 0.05em;
    transition: opacity 0.2s, transform 0.2s;
    margin-top: 8px;
}

.btn-submit-ajout:hover {
    opacity: 0.88;
    transform: translateY(-2px);
}
</style>
<body>

<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: authentification.php?msg='erreur de connexion'");
    return;
}

$nom = $_SESSION["login"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Si pas connecté, on bloque aussi côté serveur
    if (!$nom) {
        header("Location: authentification.php");
        exit;
    }
    
    $titre = $_POST['titre'];
    $story = $_POST['story'];
    $epoque= intval($_POST['epoque'] ?? 0);    
    $lieu = htmlspecialchars(trim($_POST['lieu'] ?? ''));
    $categorie = htmlspecialchars(trim($_POST['categorie'] ?? ''));

    // Gestion de l'upload de fichier
    if (!isset($_FILES['img']) || $_FILES['img']['error'] !== UPLOAD_ERR_OK) {
        echo "<p style='color:red'>Erreur lors de l'upload de l'image.</p>";
        exit;
    }

    $fichier    = $_FILES['img'];
    $extension  = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
    $extensions_autorisees = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($extension, $extensions_autorisees)) {
        echo "<p style='color:red'>Format non autorisé. Utilisez jpg, png, gif ou webp.</p>";
        exit;
    }

    $dossier = "image/";
    if (!is_dir($dossier)) mkdir($dossier, 0777, true);

    $nom_fichier = uniqid("img_", true) . "." . $extension;
    $chemin      = $dossier . $nom_fichier;

    if (!move_uploaded_file($fichier['tmp_name'], $chemin)) {
        echo "<p style='color:red'>Impossible de sauvegarder l'image.</p>";
        exit;
    }

    $data = json_decode(file_get_contents("data.json"), true);
    if (empty($data)) $data = [];

    $ids    = array_column($data, 'id');
    $new_id = empty($ids) ? 1 : max($ids) + 1;

  $prix_reel = floatval($_POST['prix_reel'] ?? 0);

$data[] = [
    "id"        => $new_id,
    "nom"       => $nom,
    "titre"     => $titre,
    "epoque"    => $epoque,
    "lieu"      => $lieu,
    "categorie" => $categorie,
    "img"       => $chemin,
    "story"     => $story,
    "prix_reel" => $prix_reel,  // ← ajouté
    "vrai"      => 0,
    "faux"      => 0
];
    file_put_contents("data.json", json_encode($data, JSON_PRETTY_PRINT));
    
    header("Location: home_user_complet.php?msg='<p style='color:green'>Objet ajouté avec succès !</p>'");
}
?>

<div class="form-container">
    <h2>Ajouter un objet</h2>
    <p class="form-subtitle">Partagez l'histoire d'un trésor ancien</p>

    <form action="ajout.php" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Titre de l'objet</label>
            <input type="text" name="titre" placeholder="Ex: Le pistolet du dernier duel" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Année</label>
                <input type="number" name="epoque" placeholder="Ex: 1712" min="-3000" max="2025">
            </div>
            <div class="form-group">
                <label>Lieu d'origine</label>
                <input type="text" name="lieu" placeholder="Ex: Paris, France">
            </div>
        </div>

        <div class="form-group">
            <label>Catégorie</label>
            <input type="text" name="categorie" placeholder="Ex: arme, bijou, meuble...">
        </div>

        <div class="form-group">
            <label>Image</label>
            <div class="file-upload">
                <input type="file" name="img" accept="image/*" required>
                <div class="file-upload-label">
                    <span class="file-upload-icon"><img src="image/search.png" alt=""></span>
                    <span class="file-upload-text">Cliquez pour choisir une image<br><small>jpg, png, gif, webp</small></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>L'histoire</label>
            <textarea name="story" placeholder="Racontez l'histoire de cet objet..." required></textarea>
        </div>
        
        <div class="form-group" id="groupe-prix" style="display:none;">
        <label>Prix réel de l'objet</label>
        <input type="number" name="prix_reel" placeholder="Prix réel en €" min="0" step="0.01">
        </div>
        <button type="submit" class="btn-submit-ajout">Publier l'objet</button>

    </form>
</div>
<script>
    document.querySelector('input[name="categorie"]').addEventListener('input', function() {
        const groupe = document.getElementById('groupe-prix');
        groupe.style.display = this.value.trim().toLowerCase() === 'photo' ? 'none' : 'flex';
    });
</script>
</body>
</html>