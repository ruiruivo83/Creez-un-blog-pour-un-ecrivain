<?php

// include_once 'controller/session_controller.php';

class pages_controller
{

    public function accueil()
    {

        $view = file_get_contents("view/frontend/_layout.html");
        $sessionController = new session_controller;
        $view = $sessionController->sessionTest($view);
        $view = str_replace("{CONTENT}", file_get_contents("view/frontend/accueil.html"), $view);
        echo $view;

    }

    public function biographie()
    {

        $view = file_get_contents("view/frontend/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/frontend/biographie.html"), $view);
        $sessionController = new session_controller;
        $view = $sessionController->sessionTest($view);
        echo $view;

    }

    public function blog()
    {

        $view = file_get_contents("view/frontend/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/frontend/blog.html"), $view);
        $sessionController = new session_controller;
        $view = $sessionController->sessionTest($view);

        //TODO
        $post = new post_controller;
        $view = str_replace("{POST_LIST}", $post->ReplacePostList($view), $view);

        echo $view;

    }

    public function login()
    {

        $view = file_get_contents("view/frontend/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/frontend/login.html"), $view);
        $sessionController = new session_controller;
        $view = $sessionController->sessionTest($view);
        echo $view;

    }

    public function register()
    {

        $view = file_get_contents("view/frontend/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/frontend/register.html"), $view);
        $sessionController = new session_controller;
        $view = $sessionController->sessionTest($view);
        echo $view;

    }

}
