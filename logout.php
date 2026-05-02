<?php
session_start();

$_SESSION = []; // vider les données -----les variables

session_destroy();// supprimer la session

header("Location: index.html?msg=Déconnexion");
return;
?>