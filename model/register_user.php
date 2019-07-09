<?php

require "../config.php";

// DEFINE DATABASE CONNECTION - PDO
try {
 
    $bdd = new PDO('mysql:host=localhost;dbname=' . $Database_Name, $Database_User, $Database_Password);

} catch (\Throwable $e) {

    die('Erreur : ' . $e->getMessage());

}

// BOUTON - INSERT TO DATABASE
if ($_SERVER['REQUEST_METHOD'] == "POST" AND isset($_POST["prenom"])) {
    
    $prenom = $_POST["prenom"];

    $nom = $_POST["nom"];

    $email = $_POST["email"];

    $psw = password_hash($_POST["psw"], PASSWORD_DEFAULT);
    
    // $psw = $_POST["psw-repeat"];

    $date_creation = "NOW()";


    // PREPARE QUERY - use prepare pour les accents sur les lettres
    
    $req = $bdd->prepare("INSERT INTO users(prenom, nom, email, psw, date_creation ) values (?, ?, ?, ?, NOW()) ");

    $req->execute(array($prenom, $nom, $email, $psw ));

    header('Location: ../index.php');

    exit();

}