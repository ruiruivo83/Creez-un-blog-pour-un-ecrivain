<?php

include_once 'model/database.php';

class post_controller
{

    // USE CONSTRUCTOR TO VERIFY IF ADMIN SESSION IS OPEN
    public function __construct()
    {

        if (!isset($_SESSION["admin"])) {
            // PAGE ERREUR
        } else {
            $dbConnection = new db();
            $this->bdd = $dbConnection->connection();
        }

    }

    public function GetPostListHTMLTable()
    {
        $HTMLPostListTable = "";

        $bloc_post_list = file_get_contents("view/backend/post_list_table_default_code_main_bloc.html");

        $post_default_code = file_get_contents("view/backend/post_list_table_default_code.html");

        $result = $this->get_posts(); // FROM MODEL
        $bloc_billet = "";
        foreach ($result as $current_result) {
            $current_billet = $post_default_code;
            $current_billet = str_replace("{POST_TITLE}", $current_result["titre"], $current_billet);
            $current_billet = str_replace("{BUTTON_DELETE}", "<a href=\"index.php?action=delete_post&id=" . $current_result["id"] . "\" class=\"btn btn-danger btn-sm\">Supprimer</a>", $current_billet);
            $current_billet = str_replace("{BUTTON_EDIT}", "<a href=\"index.php?action=edit_post&id=" . $current_result["id"] . "\" class=\"btn btn-secondary btn-sm\">Editer</a>", $current_billet);

            $HTMLPostListTable .= $current_billet;
        }

        $bloc_post_list = str_replace("{ADMIN_LIST_BLOC}", $HTMLPostListTable, $bloc_post_list);

        return $bloc_post_list;
    }

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


        $view = file_get_contents("view/frontend/_layout.html");
        $view = str_replace("{CONTENT}", $this->edit_post_model($id), $view);
        $IndexController = new session_controller();
        $view = $IndexController->sessionTest($view);
        echo $view;
    }

    public function edit_post_model($id)
    {

        // require "config.php";
        $config = new Config();

        $edit_post_default_code = file_get_contents("view/backend/edit_post_default_code.html");

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

    public function delete_post($id)
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

    public function update_post()
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

    // REPLACE POST LIST
    public function ReplacePostList()
    {
        $comments = new comment_controller;

        $post_default_code = file_get_contents("view/backend/post_default_code.html");
        // $post = new Posts();
        $result = $this->get_posts(); // FROM MODEL
        $bloc_billet = "";
        foreach ($result as $current_result) {
            $current_billet = $post_default_code;
            // ADD COMMENT FORM - IF USER IS REGISTERED
            if (isset($_SESSION['db_email'])) {
                $current_billet = str_replace("{ADD_COMMENTS}", file_get_contents("view/backend/add_comment.html"), $current_billet);
                // REPLACE {POST_ID} - FOR COMMENT POST_ID
                $current_billet = str_replace("{POST_ID}", $current_result["id"], $current_billet);
                // REPLACE USERNAME for identificatin
                $current_billet = str_replace("{USERNAME}", $_SESSION['db_email'], $current_billet);

                if ($_SESSION['db_admin'] == 1) {
                    // DECLARE edit and delete post buttons code
                    $button_edit_post = "<a href=\"index.php?action=edit_post&id={ID}\" class=\"btn btn-secondary btn-sm\">Editer</a>";
                    $button_delete_post = "<a href=\"index.php?action=delete_post&id={ID}\" class=\"btn btn-danger btn-sm\">Supprimer</a>";
                    // REPLACE {BOUTON_EDITER_BILLET}
                    $current_billet = str_replace("{BUTTON_EDIT_POST}", $button_edit_post, $current_billet);
                    // REPLACE {BOUTON_SUPPRIMER_BILLET}
                    $current_billet = str_replace("{BUTTON_DELETE_POST}", $button_delete_post, $current_billet);
                } else {
                    $current_billet = str_replace("{BUTTON_EDIT_POST}", "", $current_billet);
                    $current_billet = str_replace("{BUTTON_DELETE_POST}", "", $current_billet);
                }
            } else {
                $current_billet = str_replace("{ADD_COMMENTS}", "", $current_billet);
                $current_billet = str_replace("{BUTTON_EDIT_POST}", "", $current_billet);
                $current_billet = str_replace("{BUTTON_DELETE_POST}", "", $current_billet);
            }

            $current_billet = str_replace("{POST_DATE}", $current_result["date_creation"], $current_billet);
            $current_billet = str_replace("{POST_TITLE}", $current_result["titre"], $current_billet);
            $current_billet = str_replace("{POST_CONTENT}", $current_result["contenu"], $current_billet);

            // {ID} only exists after first {Bouton...} replacement
            $current_billet = str_replace("{ID}", $current_result["id"], $current_billet);
            $current_billet = str_replace("{POST_ID}", $current_result["id"], $current_billet);

            // GET COMMENTS FO SPECIFIC POST ID

            $current_billet = str_replace("{COMMENTS_BODY}", $comments->GetCommentsforPost($current_result["id"]), $current_billet);

            // GET COMMENTS WAITING FOR THIS USER
            $current_billet = str_replace("{COMMENTS_WAITING}", $comments->GetCommentsforThisUser($current_result["id"]), $current_billet);

            // AJOUTER ICI LA FONCTION POUR CALCULER LE TOTAL DES COMMENTAIRES DANS LA BD pour chaque billet
            $bloc_billet .= $current_billet;
        }
        $bloc_billet = str_replace("{POST_LIST}", $bloc_billet, $bloc_billet);
        return $bloc_billet;
    }

}
