<?php

require "model/User.php";

class userController
{
    // LOGIN VALIDATION FOR THE MAIN LOGIN
    public function loginValidation()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["email"])) {
            // GET LOGIN INFO FROM USER POST METHOD
            $login_email = $_POST["email"];
            $login_password = $_POST["password"];

            if ($this->testIfEmailExists($login_email)) {
                $user = User::findByEmail($login_email);
                if ($user != null && password_verify($login_password, $user->getPsw())) {   // IF PASSWORD IS OK
                    $_SESSION['user'] = $user; // IMPORTANT GAETAN
                    header('Location: ../index.php');
                } else {
                    $message = '<div class="alert alert-danger" role="alert">MOT DE PASSE EST INCORRECT</div>';
                    $view = file_get_contents('view/frontend/_layout.html');
                    $view = str_replace("{CONTENT}", file_get_contents('view/frontend/login.html'), $view);
                    $view = str_replace("<!--{MESSAGEALERT}-->", $message, $view);
                    $sessionController = new sessionController;
                    $view = $sessionController->replaceMenuIfSessionIsOpen($view);
                    echo $view;
                }
                exit();
            } else {
                $message = '<div class="alert alert-danger" role="alert">MAIL NON RECONNU</div>';
                $view = file_get_contents('view/frontend/_layout.html');
                $view = str_replace("{CONTENT}", file_get_contents('view/frontend/login.html'), $view);
                $view = str_replace("<!--{MESSAGEALERT}-->", $message, $view);
                $sessionController = new sessionController;
                $view = $sessionController->replaceMenuIfSessionIsOpen($view);
                echo $view;
            }
        }
    }


    // MAIN LOGOUT
    public function logout()
    {
        session_destroy();
        header('Location: ../index.php');
    }


    // REGISTER A NEW USER
    public function registerNewUser()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["prenom"])) {
            $prenom = $_POST["prenom"];
            $nom = $_POST["nom"];
            $email = $_POST["email"];
            $psw = password_hash($_POST["psw"], PASSWORD_DEFAULT);
            $user = new User(null, $psw, $email, $nom, $prenom);
            // Test if email exists in database
            if ($this->testIfEmailExists($email)) {
                $message = '<div class="alert alert-danger" role="alert">CE MAIL EST EXISTANT</div>';
                $view = file_get_contents('view/frontend/_layout.html');
                $view = str_replace("{CONTENT}", file_get_contents('view/frontend/register.html'), $view);
                $view = str_replace("<!--{MESSAGEALERT}-->", $message, $view);
                $sessionController = new sessionController;
                $view = $sessionController->replaceMenuIfSessionIsOpen($view);
                echo $view;
            } else {
                // Add User to Database
                $user->addUser();
                header('Location: ../index.php');
            }
        }
    }


    // TEST IF MAIL EXISTS IN THE DATABASE
    public function testIfEmailExists($email)
    {
        $user = new User(null, null, $email, null, null);
        $emailCount = $user->getEmailCount();
        if ($emailCount == 0) {
            $result = false;
            return $result;
        } else {
            $result = true;
            return $result;
        }
    }



}
