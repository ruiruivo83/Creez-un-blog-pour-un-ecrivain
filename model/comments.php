<?php

class Comments
{

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

    public function add_Comment()
    {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

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

    function delete_comment($id)
{

    // include 'config.php';
    $config = new Config();

    $bdd = new PDO('mysql:host=localhost;dbname=' . $config->Database_Name, $config->Database_User, $config->Database_Password);
    $req = $bdd->prepare('DELETE FROM comments WHERE id = :id');
    $req->bindParam(':id', $_GET['id']);

    $req->execute();

    header("Location: index.php?action=blog");

}

    public function ValidateComment($id)
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

    public function SignalComment($id)
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

}
