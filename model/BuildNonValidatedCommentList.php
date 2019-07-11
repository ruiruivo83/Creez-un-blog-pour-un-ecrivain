<?php

function BuildNonValidatedCommentList_model()
{
    $comment_default_code = file_get_contents("view/comments_to_validate_default_code.html");
    $result = Get_All_Non_Validated_Comments_From_Database();
    $List_Comments = "";
    foreach ($result as $current_result) {
        $current_comment = $comment_default_code;
        $current_comment = str_replace("{COMMENT_USER}", $current_result["username"], $current_comment);
        $current_comment = str_replace("{COMMENT_DATE_CREATION}", $current_result["date_creation"], $current_comment);
        $current_comment = str_replace("{COMMENT_CONTENT}", $current_result["contenu"], $current_comment);

        if (isset($_SESSION['db_email']) && $_SESSION['db_admin'] == 1) {
            $id = $current_result["id"];
            $delete_comment_button = "<a href=\"index.php?action=delete_comment&id=" . $id . "\" class=\"btn btn-danger\">Supprimer</a>";
            $validate_comment_button = "<a href=\"index.php?action=validate_comment&id=" . $id . "\" class=\"btn btn-success\">Valider</a>";
            $current_comment = str_replace("{DELETE_COMMENT}", $delete_comment_button, $current_comment);
            $current_comment = str_replace("{VALIDATE_COMMENT}", $validate_comment_button, $current_comment);
        } else {
            $current_comment = str_replace("{DELETE_COMMENT}", "", $current_comment);
        }

        $List_Comments .= $current_comment;
    }
    return $List_Comments;

}










function Get_All_Non_Validated_Comments_From_Database()
{

    require "config.php";

    // GET BILLETS FROM DATABASE
    try {
        $bdd = new PDO('mysql:host=localhost; dbname=' . $Database_Name, $Database_User, $Database_Password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    // PREPARE QUERY - utilise prepare pour les accents sur les lettres
    $req = $bdd->prepare("SELECT * FROM comments WHERE valide = 0 ORDER BY date_creation DESC");
    $req->execute();

    // REPLACE CODE {LIST_BILLETS}
    // FETCH QUERY RESULT FROM DATABASE TO $result
    $result = $req->fetchall();

    return $result;

}
