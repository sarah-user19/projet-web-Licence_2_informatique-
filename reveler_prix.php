<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["login"])) {
    echo json_encode(["status" => "non_connecte"]);
    exit;
}

$data_input  = json_decode(file_get_contents("php://input"), true);
$histoire_id = $data_input["histoire_id"] ?? null;
$vrai_prix   = floatval($data_input["vrai_prix"] ?? 0);

if (!$histoire_id || $vrai_prix <= 0) {
    echo json_encode(["status" => "erreur"]);
    exit;
}

$fichier = "jeu_prix.json";
$jeu     = file_exists($fichier)
    ? json_decode(file_get_contents($fichier), true)
    : [];
if (!$jeu) $jeu = [];

$propositions = $jeu[$histoire_id] ?? [];

// Calculer le gagnant
$gagnant  = null;
$diff_min = PHP_FLOAT_MAX;

foreach ($propositions as $user => $prix) {
    if (str_starts_with($user, "__")) continue;
    $diff = abs(floatval($prix) - $vrai_prix);
    if ($diff < $diff_min) {
        $diff_min = $diff;
        $gagnant  = $user;
    }
}

// Sauvegarder
$jeu[$histoire_id]["__vrai_prix__"] = $vrai_prix;
$jeu[$histoire_id]["__gagnant__"]   = $gagnant;
file_put_contents($fichier, json_encode($jeu, JSON_PRETTY_PRINT));

echo json_encode([
    "status"    => "ok",
    "vrai_prix" => $vrai_prix,
    "gagnant"   => $gagnant
]);
?>