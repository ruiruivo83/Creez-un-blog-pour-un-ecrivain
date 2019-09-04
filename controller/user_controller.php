<?php

include_once "model/database.php";

class user_controller
{

    // OPENS DATABASE CONNECTION
    public function __construct()
    {
        $this->Bd = new database();
        $this->bdd = $this->Bd->connection();
    }


    public function Login_Validation()
    {

        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["email"])) {

            // GET LOGIN INFO FROM USER
            $login_email = $_POST["email"];
            $login_psw = $_POST["password"];

            // FIND USER WITH EMAIL
            try {

                $req = $this->bdd->prepare("SELECT * FROM users WHERE email = '$login_email'  ");
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

                // IF PASSWORD IS OK
                if (password_verify($login_psw, $db_psw)) {
          
                    $_SESSION['db_admin'] = $db_admin;
                    $_SESSION['db_nom'] = $db_nom;
                    $_SESSION['db_prenom'] = $db_prenom;
                    $_SESSION['db_email'] = $db_email;
                    $_SESSION['db_date_creation'] = $db_date_creation;

                    header('Location: ../index.php');
                } else {
                    var_dump("Password is incorrect");
                    header('Location: ../index.php?action=login');
                }
            } catch (\Throwable $th) {}

            exit();
        }
    }


    public function registerNewUser()
    {

        // BOUTON - INSERT TO DATABASE
        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["prenom"])) {

            $prenom = $_POST["prenom"];

            $nom = $_POST["nom"];

            $email = $_POST["email"];

            $psw = password_hash($_POST["psw"], PASSWORD_DEFAULT);

            // $psw = $_POST["psw-repeat"];

            // $date_creation = "NOW()";

            // PREPARE QUERY - use prepare pour les accents sur les lettres

            $req = $this->bdd->prepare("INSERT INTO users(prenom, nom, email, psw, date_creation ) values (?, ?, ?, ?, NOW()) ");

            $req->execute(array($prenom, $nom, $email, $psw));

            header('Location: ../index.php');

            exit();
        }
    }

    ////// ________________________________________________
    ////// ________________________________________________
    public function logout()
    {

        session_destroy();
        header('Location: ../index.php');
    }
}
