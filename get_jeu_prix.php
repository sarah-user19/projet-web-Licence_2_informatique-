<?php
session_start();
header('Content-Type: application/json');

$histoire_id = $_GET["histoire_id"] ?? null;
if (!$histoire_id) { echo json_encode([]); exit; }

$fichier = "jeu_prix.json";
$jeu     = file_exists($fichier)
    ? json_decode(file_get_contents($fichier), true)
    : [];

$data = $jeu[$histoire_id] ?? [];

$ma_proposition = null;
if (isset($_SESSION["login"])) {
    $user = $_SESSION["login"];
    $ma_proposition = isset($data[$user]) && !str_starts_with($user, "__") ? $data[$user] : null;
}

$vrai_prix = $data["__vrai_prix__"] ?? null;
$gagnant   = $data["__gagnant__"]   ?? null;

// Compter seulement les vrais participants
$total = count(array_filter(array_keys($data), fn($k) => !str_starts_with($k, "__")));

echo json_encode([
    "propositions"   => $data,
    "ma_proposition" => $ma_proposition,
    "vrai_prix"      => $vrai_prix,
    "gagnant"        => $gagnant,
    "total"          => $total
]);
?>