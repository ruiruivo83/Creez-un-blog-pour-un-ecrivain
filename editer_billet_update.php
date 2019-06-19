<?php

include 'config.php';

// BOUTON - VALIDER - TO DATABASE
if (isset($_POST["editer"])) {

    $Titre = $_POST["titre"];
    $Contenu = $_POST["contenu"];
    $Id = $_POST['id'];

    echo "<br>Titre: " . $Titre;
    echo "<br>Contenu: " . $Contenu;
    echo "<br>id: " . $Id;

    // PREPARE QUERY - use prepare pour les accents sur les lettres
    // EXAMPLE UPDATE QUERY "UPDATE MyGuests SET lastname='Doe' WHERE id=2"

    $bdd = new PDO('mysql:host=localhost;dbname='.$Database_Name, $Database_User, $Database_Password);
    $req = "UPDATE billets SET titre=' $Titre ', contenu=' $Contenu ' WHERE id=' $Id '";
    $req = $bdd->prepare($req);

// $req = $bdd->prepare("UPDATE INTO billets(titre, contenu, date_creation) values (?, ?, NOW())");
    if (!$req->execute(array($Titre, $Contenu, $Id))) {
        echo $req->errorInfo()[2];
    }

header('Location: index.php');
exit();


}
