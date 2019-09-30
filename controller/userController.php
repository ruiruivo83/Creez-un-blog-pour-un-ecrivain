<?php

require "model/User.php";

class userController
{

    public function loginValidation()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["email"])) {
            // GET LOGIN INFO FROM USER POST METHOD
            $login_email = $_POST["email"];
            $login_psw = $_POST["password"];
            $user = User::findByEmail($login_email);
            if ($user != null && password_verify($login_psw, $user->getPsw())) {   // IF PASSWORD IS OK
                $_SESSION['user'] = $user; // IMPORTANT GAETAN
                header('Location: ../index.php');
            } else {
                var_dump("Password is incorrect");
                sleep(5);
                header('Location: ../index.php?action=login');
            }
            exit();
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: ../index.php');
    }



    public function registerNewUser()
    {


        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["prenom"])) {

            $prenom = $_POST["prenom"];
            $nom = $_POST["nom"];
            $email = $_POST["email"];
            $psw = password_hash($_POST["psw"], PASSWORD_DEFAULT);

            $user = new User(null, $psw, $email, $nom, $prenom);
            $user->addUser();

            header('Location: ../index.php');

        }

    }


}
