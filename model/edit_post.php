<?php

function edit_post_model($id)
{

    require "config.php";

    $edit_post_default_code = file_get_contents("view/edit_post_default_code.html");

// DEFINE DATABASE CONNECTION - PDO
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=' . $Database_Name, $Database_User, $Database_Password);
    } catch (\Throwable $e) {
        die('Erreur : ' . $e->getMessage());
    }

// GET CURRENT DATA INSIDE DATABASE
    $bdd = new PDO('mysql:host=localhost;dbname=' . $Database_Name, $Database_User, $Database_Password);
    $req = $bdd->prepare('SELECT titre, contenu FROM billets WHERE id = :id');

    $req->bindParam(':id', $id);

    $req->execute();
    $result = $req->fetchall();

    foreach ($result as $current_result) {
        $edit_post_default_code = str_replace("{INPUT_TITRE}", '<input type="text" name="titre" id="Titre" value="' . $current_result["titre"] . '" required>', $edit_post_default_code);
        $edit_post_default_code = str_replace("{INPUT_ID}", '<input type="text" name="id" id="Id" value="' . $id . '" required>', $edit_post_default_code);
        $edit_post_default_code = str_replace("{INPUT_CONTENU}", '<div class="input_text"> <textarea id="textarea" name="contenu" required>' . $current_result["contenu"] . '</textarea></div>', $edit_post_default_code);
        $edit_post_default_code = str_replace("{ID}", $id, $edit_post_default_code);
    }

// MONTRE TOUT LE CODE DE LA PAGE
    return $edit_post_default_code;
}
