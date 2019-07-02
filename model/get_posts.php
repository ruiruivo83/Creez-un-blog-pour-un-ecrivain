<?php

function get_posts()
{

    require "config.php";
    $post_default_code = file_get_contents("view/post_default_code.html");

    // GET BILLETS FROM DATABASE
    try {
        $bdd = new PDO('mysql:host=localhost; dbname=' . $Database_Name, $Database_User, $Database_Password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    // PREPARE QUERY - utilise prepare pour les accents sur les lettres
    $req = $bdd->prepare("SELECT * FROM billets ORDER BY date_creation DESC");
    $req->execute();

    // REPLACE CODE {LIST_BILLETS}
    // FETCH QUERY RESULT FROM DATABASE TO $result
    $result = $req->fetchall();

    return $result;

}
