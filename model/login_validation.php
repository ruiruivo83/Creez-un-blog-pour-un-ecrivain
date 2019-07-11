<?php

require "../config.php";

// DEFINE DATABASE CONNECTION - PDO
try {

    $bdd = new PDO('mysql:host=localhost;dbname=' . $Database_Name, $Database_User, $Database_Password);

} catch (\Throwable $e) {

    die('Erreur : ' . $e->getMessage());

}

if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["email"])) {

    // GET LOGIN INFO FROM USER
    $login_email = $_POST["email"];
    $login_psw = $_POST["password"];

    // FIND USER WITH EMAIL
    try {

        $req = $bdd->prepare("SELECT * FROM users WHERE email = '$login_email'  ");
        $req->execute();
        $result = $req->fetchall();

        foreach ($result as $current_result) {
            $db_prenom = $current_result["prenom"];
            $db_nom = $current_result["nom"];
            $db_email = $current_result["email"];
            $db_date_creation = $current_result["date_creation"];
            $db_psw = $current_result["psw"];
            $db_admin = $current_result["admin"];
        }

        if (password_verify($login_psw, $db_psw)) {
            // VALIDATION OK
            // ACTIVATE SESSION HERE

            // SESSION
            // IMPLEMENT SESSION VERIFICATION
            // On démarre la session AVANT d'écrire du code HTML
            session_start();

            $_SESSION['db_prenom'] = $db_prenom;
            $_SESSION['db_nom'] = $db_nom;
            $_SESSION['db_email'] = $db_email;
            $_SESSION['db_date_creation'] = $db_date_creation;
            $_SESSION['db_admin'] = $db_admin;           

            header('Location: ../index.php');

        } else {

            header('Location: ../index.php?action=login');
            
        }

    } catch (\Throwable $th) {

    }

    exit();

}
