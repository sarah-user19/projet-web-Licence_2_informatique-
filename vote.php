<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["login"])) {
    echo json_encode(["status" => "non_connecte"]);
    exit;
}

$user = $_SESSION["login"];
$data = json_decode(file_get_contents("php://input"), true);

$id   = $data["id"]   ?? null;
$type = $data["type"] ?? null;

if (!$id || !$type) {
    echo json_encode(["status" => "erreur"]);
    exit;
}

$fichier_votes = "votes_utilisateurs.json";
$votes_users   = file_exists($fichier_votes)
    ? json_decode(file_get_contents($fichier_votes), true)
    : [];
if (!$votes_users) $votes_users = [];

$cle         = "commentaire_" . $id;
$ancien_vote = $votes_users[$user][$cle] ?? null;

if ($ancien_vote === $type) {
    echo json_encode(["status" => "deja_vote"]);
    exit;
}

$commentaires = json_decode(file_get_contents("commentaires.json"), true);
if (!$commentaires) $commentaires = [];

$vrai = 0; $faux = 0;
foreach ($commentaires as &$c) {
    if ($c["id"] == $id) {
        if ($ancien_vote === "vrai") $c["vrai"] = max(0, $c["vrai"] - 1);
        if ($ancien_vote === "faux") $c["faux"] = max(0, $c["faux"] - 1);
        if ($type === "vrai") $c["vrai"] += 1;
        if ($type === "faux") $c["faux"] += 1;
        $vrai = $c["vrai"];
        $faux = $c["faux"];
        break;
    }
}

file_put_contents("commentaires.json", json_encode($commentaires, JSON_PRETTY_PRINT));

$votes_users[$user][$cle] = $type;
file_put_contents($fichier_votes, json_encode($votes_users, JSON_PRETTY_PRINT));

//  Retourner les nouveaux compteurs
echo json_encode(["status" => "ok", "vrai" => $vrai, "faux" => $faux]);
?>