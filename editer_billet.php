<?php

include 'config.php';

// BOUTON - VALIDER - TO DATABASE
if ($_SERVER['REQUEST_METHOD'] == "POST" AND isset($_POST["editer"])) {

    $Titre = $_POST["titre"];    

    // $Contenu = $_POST["contenu"];
    $Contenu = "test";

    // var_dump($Contenu);
    // PREPARE QUERY - use prepare pour les accents sur les lettres
    // EXAMPLE UPDATE QUERY "UPDATE MyGuests SET lastname='Doe' WHERE id=2"
    $id = $_POST['id'];    
    var_dump($id);

    
    //  sleep(10); Pause of 10 Seconds

    $bdd = new PDO('mysql:host=localhost;dbname='.$Database_Name, $Database_User, $Database_Password);
    $req = $bdd->prepare("UPDATE billets SET titre=" . $Titre . " and contenu=" . $Contenu . " WHERE id=$id");

    // $req = $bdd->prepare("UPDATE INTO billets(titre, contenu, date_creation) values (?, ?, NOW())");
    $req->execute(array($Titre, $Contenu));

    header('Location: index.php');
    exit();
}


//// BUILD HTML INDEX CODE

// GET Index.html into $view
$view = file_get_contents(("editer_billet.html"));

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

// GET CURRENT DATA INSIDE DATABASE
$bdd = new PDO('mysql:host=localhost;dbname='.$Database_Name, $Database_User, $Database_Password);
$req = $bdd->prepare('SELECT titre, contenu FROM billets WHERE id = :id');
$Id = $_GET['id'];
$req->bindParam(':id', $Id);

$req->execute();
$result = $req->fetchall();

foreach ($result as $current_result) {
    $view = str_replace("{INPUT_TITRE}", '<input type="text" name="titre" id="Titre" value="' . $current_result["titre"] . '" required>', $view);
    $view = str_replace("{INPUT_ID}",  '<input type="text" name="id" id="Id" value="' . $Id . '" required>' , $view);
    $view = str_replace("{INPUT_CONTENU}", '<div class="input_text"> <textarea id="textarea" name="contenu" required>' . $current_result["contenu"] . '</textarea></div>', $view);
    $view = str_replace("{ID}", $_GET['id'], $view);
}

// MONTRE TOUT LE CODE DE LA PAGE
echo $view;
