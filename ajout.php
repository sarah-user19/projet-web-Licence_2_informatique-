<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titre = $_POST['titre'];
    $img = $_POST['img'];
    $story = $_POST['story'];

    $data = json_decode(file_get_contents("data.json"), true);

    if (empty($data)) {
        $new_id = 1;
    } else {
        $ids = array_column($data, 'id');
        $new_id = max($ids) + 1;
    }

    $new_obj = [
        "id" => $new_id,
        "titre" => $titre,
        "img" => $img,
        "story" => $story
    ];

    $data[] = $new_obj;

    file_put_contents("data.json", json_encode($data, JSON_PRETTY_PRINT));

    echo "Objet ajouté avec succès !";
}



?>








?>

<h2>Ajouter un objet</h2>

<form action="ajouter.php" method="POST">
    <input type="text" name="titre" placeholder="Titre" required><br><br>

    <input type="text" name="img" placeholder="Chemin de l'image (ex: image/vtm18.png)" required><br><br>

    <textarea name="story" placeholder="Racontez l’histoire..." required></textarea><br><br>

    <button type="submit">Ajouter</button>
</form>



</body>
</html>