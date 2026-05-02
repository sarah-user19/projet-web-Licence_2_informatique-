<?php
session_start();

// ✅ Récupère le nom de l'utilisateur connecté
$auteur = isset($_SESSION["login"]) ? $_SESSION["login"] : "Anonyme";

$data = json_decode(file_get_contents("php://input"), true);

$texte       = $data["texte"]       ?? "";
$histoire_id = $data["histoire_id"] ?? null;

if (trim($texte) == "" || !$histoire_id) {
    echo "vide";
    exit;
}

$commentaires = file_exists("commentaires.json")
    ? json_decode(file_get_contents("commentaires.json"), true)
    : [];

if (!$commentaires) $commentaires = [];

$commentaires[] = [
    "id"          => uniqid(),
    "histoire_id" => $histoire_id,
    "auteur"      => $auteur,       // ✅ nom stocké
    "texte"       => $texte,
    "vrai"        => 0,
    "faux"        => 0
];

file_put_contents("commentaires.json", json_encode($commentaires, JSON_PRETTY_PRINT));
echo "ok";
?>