<?php

class comment_controller
{

    public function add_Comment()
    {

        // require "config.php";
        $config = new Config();

        //$this->console_log("INSIDE add_comment.php");

        // DEFINE DATABASE CONNECTION - PDO
        try {

            // $this->console_log("INSIDE TRY");

            $bdd = new PDO('mysql:host=localhost;dbname=' . $config->Database_Name, $config->Database_User, $config->Database_Password);
        } catch (\Throwable $e) {

            die('Erreur : ' . $e->getMessage());
        }

        // BOUTON - INSERT TO DATABASE
        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["UserName"])) {

            if ($_SESSION["db_admin"] == 1) {
                $Signale = 0;
                $Valide = 1;
            } else {
                $Signale = 1;
                $Valide = 0;
            }

            // $this->console_log("INSIDE IF");

            $UserName = $_POST["UserName"];

            $Comment = $_POST["Comment"];

            $Post_ID = $_POST["PostId"];

            // PREPARE QUERY - use prepare pour les accents sur les lettres

            $req = $bdd->prepare("INSERT INTO comments(valide, signale, username, contenu, post_id, date_creation) values (?, ?, ?, ?, ?, NOW()) ");

            $req->execute(array($Valide, $Signale, $UserName, $Comment, $Post_ID));

            header('Location: ../index.php?action=blog');

            exit();
        }
    }

    public function delete_comment($id)
    {

        // include 'config.php';
        $config = new Config();

        $bdd = new PDO('mysql:host=localhost;dbname=' . $config->Database_Name, $config->Database_User, $config->Database_Password);
        $req = $bdd->prepare('DELETE FROM comments WHERE id = :id');
        $req->bindParam(':id', $_GET['id']);

        $req->execute();

        header("Location: index.php?action=blog");

    }

    public function signal_comment($id)
    {
        // require "config.php";
        $config = new Config();

        $bdd = new PDO('mysql:host=localhost;dbname=' . $config->Database_Name, $config->Database_User, $config->Database_Password);
        $req = $bdd->prepare("UPDATE comments SET signale=1 WHERE id=?");

        // $req = $bdd->prepare("UPDATE INTO billets(titre, contenu, date_creation) values (?, ?, NOW())");
        $req->execute(array($id));

        // var_dump($bdd->errorInfo());
        // var_dump($req->errorInfo());

        header("Location: ../index.php?action=blog");

        exit();
    }

    public function validate_comment($id)
    {
        // require "config.php";
        $config = new Config();

        $bdd = new PDO('mysql:host=localhost;dbname=' . $config->Database_Name, $config->Database_User, $config->Database_Password);
        $req = $bdd->prepare("UPDATE comments SET valide=1, signale=0 WHERE id=?");

        // $req = $bdd->prepare("UPDATE INTO billets(titre, contenu, date_creation) values (?, ?, NOW())");
        $req->execute(array($id));

        // var_dump($bdd->errorInfo());
        // var_dump($req->errorInfo());

        header("Location: ../index.php?action=admin");

        exit();
    }

    public function GetCommentsforPost($post_id)
    {
        $config = new Config();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $comment_default_code = file_get_contents("view/backend/comment_default_code.html");
        // $Comments = new Comments();
        $result = $this->Get_Comments_From_Database($post_id);
        $List_Comments = "";
        foreach ($result as $current_result) {
            $current_comment = $comment_default_code;
            $current_comment = str_replace("{COMMENT_USER}", $current_result["username"], $current_comment);
            $current_comment = str_replace("{COMMENT_DATE_CREATION}", $current_result["date_creation"], $current_comment);
            $current_comment = str_replace("{COMMENT_CONTENT}", $current_result["contenu"], $current_comment);

            // REMOVE SIGNALE BUTTON FOR ADMIN COMMENTS
            // var_dump($current_result["admin"]);
            // die;

            if (!isset($_SESSION['db_email'])) {
                $current_comment = str_replace("{SIGNAL_COMMENT}", "", $current_comment);
            }

            if (isset($_SESSION['db_email']) && $current_result["username"] == $config->admin) {
                $current_comment = str_replace("{SIGNAL_COMMENT}", "", $current_comment);
            }

            // ADD DELETE BUTTON TO USER OWN COMMENTS
            if (isset($_SESSION['db_email']) && $current_result["username"] == $_SESSION['db_email']) {
                $id = $current_result["id"];
                $delete_comment_button = "<a href=\"index.php?action=delete_comment&id=" . $id . "\" class=\"btn btn-danger btn-sm\">Supprimer</a>";
                $current_comment = str_replace("{DELETE_COMMENT}", $delete_comment_button, $current_comment);
            }

            if (isset($_SESSION['db_email']) && $_SESSION['db_admin'] == 1) {
                $id = $current_result["id"];
                // DELETE BUTTON
                $delete_comment_button = "<a href=\"index.php?action=delete_comment&id=" . $id . "\" class=\"btn btn-danger btn-sm\">Supprimer</a>";
                $current_comment = str_replace("{DELETE_COMMENT}", $delete_comment_button, $current_comment);
                // SIGNAL BUTTON
                $signal_comment_button = "<a href=\"index.php?action=signal_comment&id=" . $id . "\" class=\"btn btn-warning btn-sm\">Signale</a>";
                $current_comment = str_replace("{SIGNAL_COMMENT}", $signal_comment_button, $current_comment);
            } else {
                $id = $current_result["id"];
                $current_comment = str_replace("{DELETE_COMMENT}", "", $current_comment);
                $signal_comment_button = "<a href=\"index.php?action=signal_comment&id=" . $id . "\" class=\"btn btn-warning btn-sm\">Signale</a>";
                $current_comment = str_replace("{SIGNAL_COMMENT}", $signal_comment_button, $current_comment);
            }

            $List_Comments .= $current_comment;
        }
        return $List_Comments;
    }

    public function Get_Comments_From_Database($post_id)
    {

        // require "config.php";
        $Config = new Config();

        // GET BILLETS FROM DATABASE
        try {
            $bdd = new PDO('mysql:host=localhost; dbname=' . $Config->Database_Name, $Config->Database_User, $Config->Database_Password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        // PREPARE QUERY - utilise prepare pour les accents sur les lettres
        $req = $bdd->prepare("SELECT * FROM comments WHERE post_id = '$post_id' AND valide = 1 AND signale = 0 ORDER BY date_creation DESC");
        $req->execute();

        // REPLACE CODE {LIST_BILLETS}
        // FETCH QUERY RESULT FROM DATABASE TO $result
        $result = $req->fetchall();

        return $result;

    }

    public function GetCommentsforThisUser($post_id)
    {
        $comment_default_code = file_get_contents("view/backend/non_validated_comment_default_code.html");
        // $Comments = new Comments();
        $List_Comments = "";
        if (isset($_SESSION['db_email'])) {
            $result = $this->Get_Non_Validated_Comments_For_This_User_From_The_DataBase($post_id);

            foreach ($result as $current_result) {
                $current_comment = $comment_default_code;
                $current_comment = str_replace("{COMMENT_USER}", $current_result["username"], $current_comment);
                $current_comment = str_replace("{COMMENT_DATE_CREATION}", $current_result["date_creation"], $current_comment);
                $current_comment = str_replace("{COMMENT_CONTENT}", $current_result["contenu"], $current_comment);

                $id = $current_result["id"];
                $link_to_apply_urgent = "<a href=\"index.php?action=apply_urgent&id=" . $id . "\" class=\"btn btn-danger btn-sm\">Demander a valider</a>(Commentaire non publié)";
                $current_comment = str_replace("{APPLY_URGENT}", $link_to_apply_urgent, $current_comment);

                /*
                if (isset($_SESSION['db_email']) && $_SESSION['db_admin'] == 1) {
                $id = $current_result["id"];
                $delete_comment_button = "<a href=\"index.php?action=delete_comment&id=" . $id . "\" class=\"btn btn-danger\">Supprimer</a>";
                $current_comment = str_replace("{DELETE_COMMENT}", $delete_comment_button, $current_comment);
                } else {
                $current_comment = str_replace("{DELETE_COMMENT}", "", $current_comment);
                }
                 */

                $List_Comments .= $current_comment;
            }
        }

        return $List_Comments;
    }

    public function Get_Non_Validated_Comments_For_This_User_From_The_DataBase($post_id)
    {

        // require "config.php";
        $Config = new Config();

        // GET BILLETS FROM DATABASE
        try {
            $bdd = new PDO('mysql:host=localhost; dbname=' . $Config->Database_Name, $Config->Database_User, $Config->Database_Password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        // PREPARE QUERY - utilise prepare pour les accents sur les lettres

        $username = $_SESSION['db_email'];
        $req = $bdd->prepare("SELECT * FROM comments WHERE post_id = '$post_id' AND valide = 0 AND username = '$username' ORDER BY date_creation DESC");
        $req->execute();

        // REPLACE CODE {LIST_BILLETS}
        // FETCH QUERY RESULT FROM DATABASE TO $result
        $result = $req->fetchall();

        return $result;

    }

    public function BuildNonValidatedCommentList_model()
    {
        $comment_default_code = file_get_contents("view/backend/comments_to_validate_default_code.html");
        $result = $this->Get_All_Non_Validated_Comments_From_Database();
        $List_Comments = "";
        foreach ($result as $current_result) {
            $current_comment = $comment_default_code;
            $current_comment = str_replace("{COMMENT_USER}", $current_result["username"], $current_comment);
            $current_comment = str_replace("{COMMENT_DATE_CREATION}", $current_result["date_creation"], $current_comment);
            $current_comment = str_replace("{COMMENT_CONTENT}", $current_result["contenu"], $current_comment);

            if (isset($_SESSION['db_email']) && $_SESSION['db_admin'] == 1) {
                $id = $current_result["id"];
                $delete_comment_button = "<a href=\"index.php?action=delete_comment&id=" . $id . "\" class=\"btn btn-danger btn-sm\">Supprimer</a>";
                $validate_comment_button = "<a href=\"index.php?action=validate_comment&id=" . $id . "\" class=\"btn btn-success btn-sm\">Valider</a>";
                $current_comment = str_replace("{DELETE_COMMENT}", $delete_comment_button, $current_comment);
                $current_comment = str_replace("{VALIDATE_COMMENT}", $validate_comment_button, $current_comment);
            } else {
                $current_comment = str_replace("{DELETE_COMMENT}", "", $current_comment);
            }

            $List_Comments .= $current_comment;
        }
        return $List_Comments;

    }

    public function Get_All_Non_Validated_Comments_From_Database()
    {

        // require "config.php";
        $config = new Config();

        // GET BILLETS FROM DATABASE
        try {
            $bdd = new PDO('mysql:host=localhost; dbname=' . $config->Database_Name, $config->Database_User, $config->Database_Password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        // PREPARE QUERY - utilise prepare pour les accents sur les lettres
        $req = $bdd->prepare("SELECT * FROM comments WHERE valide = 0 OR signale = 1 ORDER BY date_creation DESC");
        $req->execute();

        // REPLACE CODE {LIST_BILLETS}
        // FETCH QUERY RESULT FROM DATABASE TO $result
        $result = $req->fetchall();

        return $result;

    }

    public function apply_urgent($id)
    {

        /*
        $req = $bdd->prepare("UPDATE billets SET titre=? , contenu=? WHERE id=?");
        $req->execute(array($Titre, $Contenu, $id));
         */

        // TODO
        // require "config.php";

        $Config = new Config();

        $bdd = new PDO('mysql:host=localhost;dbname=' . $Config->Database_Name, $Config->Database_User, $Config->Database_Password);
        $req = $bdd->prepare("UPDATE comments SET date_creation = (NOW()) WHERE id = ?");

        // $req = $bdd->prepare("UPDATE INTO billets(titre, contenu, date_creation) values (?, ?, NOW())");
        $req->execute(array($id));

        // var_dump($bdd->errorInfo());
        // var_dump($req->errorInfo());

        header("Location: ../index.php");

        exit();
    }


}
