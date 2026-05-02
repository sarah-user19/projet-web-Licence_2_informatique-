<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["login"])) {
    echo json_encode(["status" => "non_connecte"]);
    exit;
}

$user = $_SESSION["login"];
$data = json_decode(file_get_contents("php://input"), true);

$histoire_id = $data["histoire_id"] ?? null;
$prix        = floatval($data["prix"] ?? 0);

if (!$histoire_id || $prix <= 0) {
    echo json_encode(["status" => "erreur"]);
    exit;
}

$fichier = "jeu_prix.json";
$jeu     = file_exists($fichier)
    ? json_decode(file_get_contents($fichier), true)
    : [];
if (!$jeu) $jeu = [];

// Un user = une seule proposition par objet
if (!isset($jeu[$histoire_id])) $jeu[$histoire_id] = [];
$jeu[$histoire_id][$user] = $prix;

file_put_contents($fichier, json_encode($jeu, JSON_PRETTY_PRINT));
echo json_encode(["status" => "ok", "prix" => $prix]);
?>
