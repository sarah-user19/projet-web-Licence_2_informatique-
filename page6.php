<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>



<?php

$data = json_decode(file_get_contents("data.json"), true);

$id = $_GET['id'];

foreach ($data as $current) {
    if ($current["id"] == $id) {
        $obj = $current;
    }
}


if (!$obj) {
    echo "Objet introuvable";
    exit;
}
?>



<title><?php echo $obj["titre"]; ?></title>

<img src="<?php echo $obj["img"];?>" style="width:300px;">

<p>Histoire de l’objet...</p>

<ul>
    <li>Couleur : violet</li>
    <li>Style : bohème</li>
</ul>

<div>
    <button>👍 J’aime</button>
    <button>💬 Commenter</button>
</div>

<h3>Commentaires</h3>
<textarea></textarea>
<button>Envoyer</button>
<div id="comments"></div>





</body>
</html>