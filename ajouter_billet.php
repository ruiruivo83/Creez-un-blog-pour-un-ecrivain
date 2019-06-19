<?php

include 'config.php';

//// BUILD HTML INDEX CODE

// GET Index.html into $view
$view = file_get_contents(("ajouter_billet.html"));

//// REMPLACE {HTML_DEFAULT_START} BY CODE
$html_default_start = file_get_contents("html_default_start.html");
$view = str_replace("{HTML_DEFAULT_START}", $html_default_start, $view);

//// REMPLACE {HTML_DEFAULT_END} BY CODE
$html_default_end = file_get_contents("html_default_end.html");
$view = str_replace("{HTML_DEFAULT_END}", $html_default_end, $view);

// DEFINE DATABASE CONNECTION - PDO
try {
    $bdd = new PDO('mysql:host=localhost;dbname='.$Database_Name, $Database_User, $Database_Password);
} catch (\Throwable $e) {
    die('Erreur : ' . $e->getMessage());
}

// BOUTON - INSERT TO DATABASE
if (isset($_POST["Titre"])) {
    $Titre = $_POST["Titre"];
    $Contenu = $_POST["Contenu"];
    // PREPARE QUERY - use prepare pour les accents sur les lettres
    $req = $bdd->prepare("INSERT INTO billets(titre, contenu, date_creation) values (?, ?, NOW())");
    $req->execute(array($Titre, $Contenu));

    header('Location: index.php');
    exit();
}

// MONTRE TOUT LE CODE DE LA PAGE
echo $view;
