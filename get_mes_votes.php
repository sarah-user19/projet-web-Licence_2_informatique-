<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["login"])) {
    echo json_encode([]);
    exit;
}

$user          = $_SESSION["login"];
$fichier_votes = "votes_utilisateurs.json";

if (!file_exists($fichier_votes)) {
    echo json_encode([]);
    exit;
}

$votes_users = json_decode(file_get_contents($fichier_votes), true);
echo json_encode($votes_users[$user] ?? []);
?>
