<?php




function fill_list_posts($view)
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
    $bloc_billet = "";
    foreach ($result as $current_result) {
        $current_billet = $post_default_code;
        // REPLACE {DATE_BILLET} - BY $current_result["date_creation"] - INSIDE $current_billet
        $current_billet = str_replace("{DATE_BILLET}", $current_result["date_creation"], $current_billet);
        $current_billet = str_replace("{TITRE_BILLET}", $current_result["titre"], $current_billet);
        $current_billet = str_replace("{CONTENU_BILLET}", $current_result["contenu"], $current_billet);
        $current_billet = str_replace("{ID}", $current_result["id"], $current_billet);
        $bloc_billet .= $current_billet;
    }

    return $bloc_billet;

}
