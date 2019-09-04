<?php

require 'controller/session_controller.php';
// require 'controller/post_controller.php';
require 'model/validationbuild.php';

class admin_controller
{

    public function __construct()
    {
        if (!isset($_SESSION["admin"])) {
            // PAGE ERREUR
        } else {
         
            $dbConnection = new db();
            $this->bdd = $dbConnection->connection();
        }
    }

    public function admin()
    {

        if (isset($_SESSION['db_email']) && $_SESSION['db_admin'] == 1) {
            $view = file_get_contents("view/frontend/_layout.html");
            $view = str_replace("{CONTENT}", file_get_contents("view/frontend/admin.html"), $view);

            $view = str_replace("{ADD_POST}", file_get_contents("view/backend/add_post.html"), $view);

            $sessionController = new session_controller;
            $view = $sessionController->sessionTest($view);

            // TODO
            // GET NON VALIDATED COMMENTS ORDER DESC by date
            $ValidationBuild = new ValidationBuild();
            $list_non_validated_comments = $ValidationBuild->BuildNonValidatedCommentList_model();

            // GET COMMENTS LIST TO VALIDATE
            $view = str_replace("{COMMENTS_TO_VALIDATE}", $list_non_validated_comments, $view);

            // GET POST LIST IN TABLE
            $GetPostListHTMLTable = new post_controller;
            $view = str_replace("{POST_LIST_TABLE}", $GetPostListHTMLTable->GetPostListHTMLTable(), $view);

        } else {
            header('Location: ../index.php');
        }

        // Build button to validate comments
        echo $view;
    }

}
