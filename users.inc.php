<?php

function  exist($login){
    $contenu = file("users.csv");

    foreach($contenu as $ligne){
        $tmp = explode(",", trim($ligne)); // séparer login,mdp

        if($tmp[0] == $login){
            return true;
        }
    }

    return false;//YA PAS CE LOGINE [username]

}



function loginOk($login,$mdp){ 

    $contenu = file("users.csv");

    foreach($contenu as $ligne){
        $tmp = explode(",", trim($ligne));

        if($tmp[0] == $login && $tmp[1] == $mdp){
            return true;
        }
    }

    return false;
}


function addUser($login, $mdp,$email){ // ajouter [username]
    $ligne = "$login,$mdp,$email\n";
    file_put_contents("users.csv", $ligne, FILE_APPEND);
}

?>