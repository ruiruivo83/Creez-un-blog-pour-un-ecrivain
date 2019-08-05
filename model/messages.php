<?php

// require "config.php";
// require "controller/index_controller.php";

class Messages
{

    public function add_message()
    {
        $config = new Config();
        // DEFINE DATABASE CONNECTION - PDO
        try {
            $bdd = new PDO('mysql:host=localhost;dbname=' . $config->Database_Name, $config->Database_User, $config->Database_Password);
        } catch (\Throwable $e) {

            die('Erreur : ' . $e->getMessage());
        }

        // BOUTON - INSERT TO DATABASE
        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["Prenom"]) and isset($_POST["Nom"]) and isset($_POST["Message"])) {

            $Titre = $_POST["Titre"];

            $Contenu = $_POST["Contenu"];

            // PREPARE QUERY - use prepare pour les accents sur les lettres

            $req = $bdd->prepare("INSERT INTO billets(titre, contenu, date_creation) values (?, ?, NOW()) ");

            $req->execute(array($Titre, $Contenu));

            header('Location: ../index.php?action=blog');

            exit();
        }
    }

}
