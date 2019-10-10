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
        $postController = new postController;
        $view = str_replace("{POST_LIST}", $postController->ReplacePostList($view), $view);
        $view = str_replace("<!--{REGISTERMESSAGE}-->", "", $view);
        echo $view;
    }

    // PAGE REGISTER
    public function register()
    {
        $view = file_get_contents("view/frontend/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/frontend/register.html"), $view);
        $sessionController = new sessionController;
        $view = $sessionController->replaceMenuIfSessionIsOpen($view);
        echo $view;
    }

    // PAGE POLITIQUE
    public function politique()
    {
        $view = file_get_contents("view/frontend/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/frontend/politique.html"), $view);
        $sessionController = new sessionController;
        $view = $sessionController->replaceMenuIfSessionIsOpen($view);
        echo $view;
    }

}