<?php

function Get_Comments_From_Database($post_id)
{

    require "config.php";

    // GET BILLETS FROM DATABASE
    try {
        $bdd = new PDO('mysql:host=localhost; dbname=' . $Database_Name, $Database_User, $Database_Password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    // PREPARE QUERY - utilise prepare pour les accents sur les lettres
    $req = $bdd->prepare("SELECT * FROM comments WHERE post_id = '$post_id' AND valide = 1 ORDER BY date_creation DESC");
    $req->execute();

    // REPLACE CODE {LIST_BILLETS}
    // FETCH QUERY RESULT FROM DATABASE TO $result
    $result = $req->fetchall();



    return $result;
    

}
