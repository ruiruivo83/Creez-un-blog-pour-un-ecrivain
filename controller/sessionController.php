<?php

class sessionController {
    // REPLACE THE LOGIN AND REGISTER OPTION IF THE SESSION IS OPEN
    public function replaceMenuIfSessionIsOpen($view)
    {
        // REPLACE MENU OPTIONS - {LOGIN} {REGISTER} {ADMIN} {USER_INFO}
        $loginURL = '<a class="nav-link active" href="index.php?action=login">Se connecter</a>';
        $registerURL = '<a class="nav-link active" href="index.php?action=register">S\'enregistrer</a>';
        $adminURL = '<a class="nav-link active" href="index.php?action=admin">Admin</a>';
        // IF SESSION IS OPEN
        if (isset($_SESSION["user"])) {
            // $userInfoANDlogout_button = "<i class=\"fas fa-user\"></i> &nbsp;&nbsp; " . $_SESSION['user']->getEmail() ;
            $userInfoANDlogout_button = "<span class=\"nav-link active\" style=\" color: lightslategrey;\">" . $_SESSION['user']->getEmail() . "</span>" ;
            $logout_button =  file_get_contents("view/backend/button_logout.html");
            // IF USER IS ADMIN
            if ($_SESSION["user"]->isAdmin()) {
                $view = str_replace("{LOGIN}", "", $view);
                $view = str_replace("{REGISTER}", "", $view);
                $view = str_replace("{ADMIN}", $adminURL, $view);
                $view = str_replace("{USER_INFO}", $userInfoANDlogout_button, $view);
                $view = str_replace("{LOGOUT}", $logout_button, $view);
            } else {
                $view = str_replace("{LOGIN}", "", $view);
                $view = str_replace("{REGISTER}", "", $view);
                $view = str_replace("{ADMIN}", "", $view);
                $view = str_replace("{USER_INFO}", $userInfoANDlogout_button, $view);
                $view = str_replace("{LOGOUT}", $logout_button, $view);
            }
        } else {
            // IF SESSION IS NOT OPEN
            $view = str_replace("{LOGIN}", $loginURL, $view);
            $view = str_replace("{REGISTER}", $registerURL, $view);
            $view = str_replace("{ADMIN}", "", $view);
            $view = str_replace("{USER_INFO}", "", $view);
            $view = str_replace("{LOGOUT}", "", $view);
        }
        return $view;
    }

}