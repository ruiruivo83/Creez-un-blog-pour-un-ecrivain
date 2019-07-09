<!-- add_comment.php -->
<?php

require "../config.php";

console_log("INSIDE add_comment.php");


// DEFINE DATABASE CONNECTION - PDO
try {

    
    console_log("INSIDE TRY");
   

    $bdd = new PDO('mysql:host=localhost;dbname=' . $Database_Name, $Database_User, $Database_Password);

} catch (\Throwable $e) {

    die('Erreur : ' . $e->getMessage());

}

// BOUTON - INSERT TO DATABASE
if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["UserName"])) {


    console_log("INSIDE IF");

    $UserName = $_POST["UserName"];

    var_dump($UserName);
    die;
    
    

    $Comment = $_POST["Comment"];

    $Valide = "TRUE";

    $Post_ID = $_POST["PostId"];

    // PREPARE QUERY - use prepare pour les accents sur les lettres

    $req = $bdd->prepare("INSERT INTO comments(username, contenu, valide, post_id, date_creation) values (?, ?, ?, ?, NOW()) ");

    $req->execute(array($UserName, $Comment, $Valide, $Post_ID));

    // header('Location: ../index.php?action=acces_admin');

    exit();

}




function console_log($Data) {
    echo '<script>';
    echo 'console.log('. json_encode( $Data ) .')';
    echo '</script>';
}