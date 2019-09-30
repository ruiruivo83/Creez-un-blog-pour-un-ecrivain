<?php

require 'controller/sessionController.php';

class pagesController
{

    // PAGE ACCUEIL
    public function accueil()
    {
        $view = file_get_contents("view/frontend/_layout.html");
        $sessionController = new sessionController;
        $view = $sessionController->replaceMenuIfSessionIsOpen($view);
        $view = str_replace("{CONTENT}", file_get_contents("view/frontend/accueil.html"), $view);
        echo $view;
    }

    // PAGE BIOGRAPHIE
    public function biographie()
    {
        $view = file_get_contents("view/frontend/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/frontend/biographie.html"), $view);
        $sessionController = new sessionController;
        $view = $sessionController->replaceMenuIfSessionIsOpen($view);
        echo $view;
    }

    // PAGE LOGIN
    public function login()
    {
        $view = file_get_contents("view/frontend/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/frontend/login.html"), $view);
        $sessionController = new sessionController;
        $view = $sessionController->replaceMenuIfSessionIsOpen($view);
        echo $view;
    }

    // PAGE BLOG
    public function blog()
    {

        $view = file_get_contents("view/frontend/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/frontend/blog.html"), $view);

        $sessionController = new sessionController;
        $view = $sessionController->replaceMenuIfSessionIsOpen($view);

        // TODO
        $post = new postController;
        $view = str_replace("{POST_LIST}", $post->ReplacePostList($view), $view);
        echo $view;

    }

    public function register()
    {
        $view = file_get_contents("view/frontend/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/frontend/register.html"), $view);
        $sessionController = new sessionController;
        $view = $sessionController->replaceMenuIfSessionIsOpen($view);
        echo $view;
    }

}