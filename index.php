<?php
include 'config.php';


//// BUILD HTML INDEX CODE

// GET Index.html into $view
$view = file_get_contents(("index_vue.html"));

//// REMPLACE {HTML_DEFAULT_START} BY CODE
$html_default_start = file_get_contents("html_default_start.html");
$view = str_replace("{HTML_DEFAULT_START}", $html_default_start, $view);

//// REMPLACE {HTML_DEFAULT_END} BY CODE
$html_default_end = file_get_contents("html_default_end.html");
$view = str_replace("{HTML_DEFAULT_END}", $html_default_end, $view);



//// REMPLACE {LIST_BILLETS} BY CODE

// GET BILLET HTML CONTENT
$billets = file_get_contents("list_billets.html");

// GET BILLETS FROM DATABASE
try {
    $bdd = new PDO('mysql:host=localhost; dbname='.$Database_Name, $Database_User, $Database_Password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
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
    $current_billet = $billets;
    // REPLACE {DATE_BILLET} - BY $current_result["date_creation"] - INSIDE $current_billet
    $current_billet = str_replace("{DATE_BILLET}", $current_result["date_creation"], $current_billet);
    $current_billet = str_replace("{TITRE_BILLET}", $current_result["titre"], $current_billet);
    $current_billet = str_replace("{CONTENU_BILLET}", $current_result["contenu"], $current_billet);
    $current_billet = str_replace("{ID}", $current_result["id"], $current_billet);
    $bloc_billet .= $current_billet;
}

// REMPLACEMENT FINAL AVEC LE CODE FINALE
$view = str_replace("{LIST_BILLETS}", $bloc_billet, $view);

// MONTRE TOUT LE CODE DE LA PAGE
echo $view;

