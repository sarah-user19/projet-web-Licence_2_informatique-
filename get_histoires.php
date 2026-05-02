<?php
header('Content-Type: application/json');

if (!file_exists("data.json")) {
    echo json_encode([]);
    exit;
}

$data = json_decode(file_get_contents("data.json"), true);
if (!$data) $data = [];

echo json_encode($data);
?>
