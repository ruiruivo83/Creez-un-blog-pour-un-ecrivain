<?php

// DEFINE DATABASE PDO
try {
    $bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
} catch (\Throwable $e) {
    die('Erreur : ' . $e->getMessage());
}

// INSERT TO DATABASE
if (isset($_POST["Titre"])) {
    $Titre = $_POST["Titre"];
    $Contenu = $_POST["Contenu"];

    // PREPARE QUERY - use prepare pour les accents sur les lettres
    $req = $bdd->prepare("INSERT INTO billets(titre, contenu, date_creation) values (?, ?, NOW())");
    $req->execute(array($Titre, $Contenu));
}

// GET INDEX HTML
$view = file_get_contents(("index_vue.html"));

// GET BILLET CONTENT
$billets = file_get_contents("billet_content.html");

// GET BILLETS FROM DATABASE
try {
    $bdd = new PDO('mysql:host=localhost; dbname=blog', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// PREPARE QUERY QUERY - use prepare pour les accents sur les lettres
$req = $bdd->prepare("SELECT * FROM billets ORDER BY date_creation DESC");
$req->execute();

// -- REPLACE LIST_BILLETS --

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