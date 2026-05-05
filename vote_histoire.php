<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["login"])) {
    echo json_encode(["status" => "non_connecte"]);
    exit;
}

$user       = $_SESSION["login"];
$data_input = json_decode(file_get_contents("php://input"), true);

$id   = $data_input["id"]   ?? null;
$type = $data_input["type"] ?? null;

if (!$id || !$type) {
    echo json_encode(["status" => "erreur"]);
    exit;
}

$fichier_votes = "votes_utilisateurs.json";
$votes_users   = file_exists($fichier_votes)
    ? json_decode(file_get_contents($fichier_votes), true)
    : [];
if (!$votes_users) $votes_users = [];

$cle         = "histoire_" . $id;
$ancien_vote = $votes_users[$user][$cle] ?? null;

if ($ancien_vote === $type) {
    echo json_encode(["status" => "deja_vote"]);
    exit;
}

$data = json_decode(file_get_contents("data.json"), true);
if (!$data) $data = [];

$vrai = 0; $faux = 0;
foreach ($data as &$obj) {
    if ($obj["id"] == $id) {
        if ($ancien_vote === "vrai") $obj["vrai"] = max(0, $obj["vrai"] - 1);
        if ($ancien_vote === "faux") $obj["faux"] = max(0, $obj["faux"] - 1);
        if ($type === "vrai") $obj["vrai"] += 1;
        if ($type === "faux") $obj["faux"] += 1;
        $vrai = $obj["vrai"];
        $faux = $obj["faux"];
        break;
    }
}

file_put_contents("data.json", json_encode($data, JSON_PRETTY_PRINT));

$votes_users[$user][$cle] = $type;
file_put_contents($fichier_votes, json_encode($votes_users, JSON_PRETTY_PRINT));

// Retourner les nouveaux compteurs
echo json_encode(["status" => "ok", "vrai" => $vrai, "faux" => $faux]);
?>