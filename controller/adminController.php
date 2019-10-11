<?php

// require 'controller/sessionController.php';

class adminController
{

    // SHOW ADMIN PAGE IF USER LOGGED IS ADMIN
    public function admin()
    {
        $view = file_get_contents("view/frontend/_layout.html");
        if (isset($_SESSION['user']) && $_SESSION["user"]->isAdmin()) {
            $view = str_replace("{CONTENT}", file_get_contents("view/frontend/admin.html"), $view);
            $view = str_replace("{ADD_POST}", file_get_contents("view/backend/add_post.html"), $view);
            $sessionController = new sessionController;
            $view = $sessionController->replaceMenuIfSessionIsOpen($view);
            // GET NON VALIDATED COMMENTS BY DESC ORDER BY DATE
            $ValidationBuild = new commentController;
            $list_non_validated_comments = $ValidationBuild->BuildNonValidatedCommentList();
            // GET COMMENTS LIST TO VALIDATE
            $view = str_replace("{COMMENTS_TO_VALIDATE}", $list_non_validated_comments, $view);
            // GET POST LIST IN TABLE
            $GetPostListHTMLTable = new postController;
            $view = str_replace("{POST_LIST_TABLE}", $GetPostListHTMLTable->GetPostListHTMLTable(), $view);
        } else {
            header('Location: ../index.php');
        }
        // Build button to validate comments
        echo $view;
    }


}