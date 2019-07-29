<?php

class User
{

    public function Info()
    {

        $view = file_get_contents("view/_layout.html");
        $User_Info = file_get_contents("view/user_info.html");
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $User_Info = str_replace("{USER_DB_EMAIL}", $_SESSION['db_email'], $User_Info);

        $view = str_replace("{CONTENT}", $User_Info, $view);
        $IndexController = new index_controller();
        $view = $IndexController->ApplySession($view);
        echo $view;

    }
}
