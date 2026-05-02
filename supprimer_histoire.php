<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: authentification.php");
    exit;
}

$id  = $_GET['id'] ?? null;
$nom = $_SESSION["login"];

if (!$id) { echo "ID manquant"; exit; }

$data = json_decode(file_get_contents("data.json"), true);
if (!$data) { echo "Erreur"; exit; }

// Trouver l'objet
$obj = null;
foreach ($data as $o) {
    if ($o["id"] == $id) { $obj = $o; break; }
}

if (!$obj) { echo "Objet introuvable"; exit; }

// ✅ Seulement le propriétaire ou admin peut supprimer
if ($obj["nom"] !== $nom && $nom !== "admin") {
    echo "Accès refusé";
    exit;
}

// Supprimer l'objet
$data = array_values(array_filter($data, fn($o) => $o["id"] != $id));
file_put_contents("data.json", json_encode($data, JSON_PRETTY_PRINT));

// Supprimer aussi les commentaires liés
if (file_exists("commentaires.json")) {
    $commentaires = json_decode(file_get_contents("commentaires.json"), true) ?? [];
    $commentaires = array_values(array_filter($commentaires, fn($c) => $c["histoire_id"] != $id));
    file_put_contents("commentaires.json", json_encode($commentaires, JSON_PRETTY_PRINT));
}

header("Location: page4.php");
exit;
?>