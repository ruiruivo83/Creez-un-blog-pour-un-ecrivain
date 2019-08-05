<?php

// require "config.php";
// require "controller/index_controller.php";

class Posts
{

    public function get_posts()
    {

        // GET BILLETS FROM DATABASE
        try {
            $Config = new Config();
            $bdd = new PDO('mysql:host=localhost; dbname=' . $Config->Database_Name, $Config->Database_User, $Config->Database_Password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        // PREPARE QUERY - utilise prepare pour les accents sur les lettres
        $req = $bdd->prepare("SELECT * FROM billets ORDER BY date_creation DESC");
        $req->execute();

        // REPLACE CODE {LIST_BILLETS}
        // FETCH QUERY RESULT FROM DATABASE TO $result
        $result = $req->fetchall();

        return $result;

    }

    public function add_post()
    {
        $config = new Config();
        // DEFINE DATABASE CONNECTION - PDO
        try {
            $bdd = new PDO('mysql:host=localhost;dbname=' . $config->Database_Name, $config->Database_User, $config->Database_Password);
        } catch (\Throwable $e) {

            die('Erreur : ' . $e->getMessage());
        }

        // BOUTON - INSERT TO DATABASE
        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["Titre"])) {

            $Titre = $_POST["Titre"];

            $Contenu = $_POST["Contenu"];

            // PREPARE QUERY - use prepare pour les accents sur les lettres

            $req = $bdd->prepare("INSERT INTO billets(titre, contenu, date_creation) values (?, ?, NOW()) ");

            $req->execute(array($Titre, $Contenu));

            header('Location: ../index.php?action=blog');

            exit();
        }

    }

    public function edit_post($id)
    {

        // REQUIRED FOR SESSION

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $view = file_get_contents("view/_layout.html");
        $view = str_replace("{CONTENT}", $this->edit_post_model($id), $view);
        $IndexController = new index_controller();
        $view = $IndexController->ApplySession($view);
        echo $view;

    }

    public function edit_post_model($id)
    {

        // require "config.php";
        $config = new Config();

        $edit_post_default_code = file_get_contents("view/edit_post_default_code.html");

// DEFINE DATABASE CONNECTION - PDO
        try {
            $bdd = new PDO('mysql:host=localhost;dbname=' . $config->Database_Name, $config->Database_User, $config->Database_Password);
        } catch (\Throwable $e) {
            die('Erreur : ' . $e->getMessage());
        }

// GET CURRENT DATA INSIDE DATABASE
        $bdd = new PDO('mysql:host=localhost;dbname=' . $config->Database_Name, $config->Database_User, $config->Database_Password);
        $req = $bdd->prepare('SELECT titre, contenu FROM billets WHERE id = :id');

        $req->bindParam(':id', $id);

        $req->execute();
        $result = $req->fetchall();

        foreach ($result as $current_result) {
            $edit_post_default_code = str_replace("{INPUT_TITRE}", '<input type="text" name="titre" id="Titre" value="' . $current_result["titre"] . '" required>', $edit_post_default_code);
            $edit_post_default_code = str_replace("{INPUT_ID}", '<input type="text" name="id" id="Id" value="' . $id . '" required hidden>', $edit_post_default_code);
            $edit_post_default_code = str_replace("{INPUT_CONTENU}", '<div class="input_text"> <textarea id="textarea" name="contenu" required>' . $current_result["contenu"] . '</textarea></div>', $edit_post_default_code);
            $edit_post_default_code = str_replace("{ID}", $id, $edit_post_default_code);
        }

// MONTRE TOUT LE CODE DE LA PAGE
        return $edit_post_default_code;
    }

    public function UpdatePost()
    {
        // require "../config.php";
        $config = new Config();

        // BOUTON - VALIDER - TO DATABASE

        // var_dump($_SERVER['REQUEST_METHOD']);

        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["update_post"])) {

            // echo "INSIDE";

            $Titre = $_POST["titre"];

            $Contenu = $_POST["contenu"];
            // $Contenu = "test";

            $id = $_POST['id'];

            $bdd = new PDO('mysql:host=localhost;dbname=' . $config->Database_Name, $config->Database_User, $config->Database_Password);
            $req = $bdd->prepare("UPDATE billets SET titre=? , contenu=? WHERE id=?");

            // $req = $bdd->prepare("UPDATE INTO billets(titre, contenu, date_creation) values (?, ?, NOW())");
            $req->execute(array($Titre, $Contenu, $id));

            // var_dump($bdd->errorInfo());
            // var_dump($req->errorInfo());

        }

        header("Location: ../index.php?action=blog");

        exit();
    }

    public function DeletePost($id)
    {
        // include 'config.php';
        $config = new Config();

        $bdd = new PDO('mysql:host=localhost;dbname=' . $config->Database_Name, $config->Database_User, $config->Database_Password);
        // DELETE POST ID
        $req = $bdd->prepare('DELETE FROM billets WHERE id = :id');
        $req->bindParam(':id', $_GET['id']);
        $req->execute();

        // DELETE COMMENTS FOR POST ID
        $req = $bdd->prepare('DELETE FROM comments WHERE post_id = :id');
        $req->bindParam(':id', $_GET['id']);
        $req->execute();

        header("Location: index.php?action=blog");
    }

    

}
