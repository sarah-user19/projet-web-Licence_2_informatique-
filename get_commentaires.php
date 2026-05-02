<?php
header('Content-Type: application/json');

$histoire_id = $_GET["histoire_id"] ?? null;

if (!$histoire_id || !file_exists("commentaires.json")) {
    echo json_encode([]);
    exit;
}

$tous = json_decode(file_get_contents("commentaires.json"), true);
if (!$tous) $tous = [];

$filtres = array_values(array_filter($tous, function($c) use ($histoire_id) {
    return $c["histoire_id"] == $histoire_id;
}));

echo json_encode($filtres);
?>
