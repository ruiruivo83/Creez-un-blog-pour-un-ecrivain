<?php

require "../config.php";

echo "INSIDE<br>";

// DEFINE DATABASE CONNECTION - PDO
try {
    $bdd = new PDO('mysql:host=localhost;dbname=' . $Database_Name, $Database_User, $Database_Password);

    echo "CON OK<br>";

} catch (\Throwable $e) {
    die('Erreur : ' . $e->getMessage());
}

echo "AFTER CON<br>";

// BOUTON - INSERT TO DATABASE
if ($_SERVER['REQUEST_METHOD'] == "POST" AND isset($_POST["Titre"])) {
    
    echo "INSIDE IF<br>";

    $Titre = $_POST["Titre"];

    $Contenu = $_POST["Contenu"];

    // PREPARE QUERY - use prepare pour les accents sur les lettres
    
    $req = $bdd->prepare("INSERT INTO billets(titre, contenu, date_creation) values (?, ?, NOW()) ");

    $req->execute(array($Titre, $Contenu));

    header('Location: ../index.php');

    exit();

}
