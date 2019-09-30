<?php

// require 'model/Comments.php';

class commentController
{

    public function add_Comment()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["UserName"])) {

            if ($_SESSION["user"]->isAdmin()) {
                $Signale = 0;
                $Valide = 1;
            } else {
                $Signale = 1;
                $Valide = 0;
            }
            $UserName = $_POST["UserName"];
            $Comment = htmlspecialchars($_POST["Comment"]);
            $Post_ID = $_POST["PostId"];
            $comments = new Comments;
            $comments->add_comment($Valide, $Signale, $UserName, $Comment, $Post_ID);
            header('Location: ../index.php?action=blog');
            exit();
        }
    }

    public function GetCommentsforPost($post_id)
    {
        $config = new Config();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $comment_default_code = file_get_contents("view/backend/comment_default_code.html");
        $result = $this->Get_Comments_From_Database($post_id);
        $List_Comments = "";
        foreach ($result as $current_result) {
            $current_comment = $comment_default_code;
            $current_comment = str_replace("{COMMENT_USER}", $current_result["username"], $current_comment);
            $current_comment = str_replace("{COMMENT_DATE_CREATION}", $current_result["date_creation"], $current_comment);
            $current_comment = str_replace("{COMMENT_CONTENT}", $current_result["contenu"], $current_comment);

            if (!isset($_SESSION['user'])) {
                $current_comment = str_replace("{SIGNAL_COMMENT}", "", $current_comment);
            }

            if (isset($_SESSION['user']->getEmail) && $current_result["username"] == $config->admin) {
                $current_comment = str_replace("{SIGNAL_COMMENT}", "", $current_comment);
            }



            // ADD DELETE BUTTON TO USER OWN COMMENTS
            if (isset($_SESSION['user']) && $current_result["username"] == $_SESSION['user']->getEmail()) {
                $id = $current_result["id"];
                $delete_comment_button = "<a href=\"index.php?action=delete_comment&id=" . $id . "\" class=\"btn btn-danger btn-sm\">Supprimer</a>";
                $current_comment = str_replace("{DELETE_COMMENT}", $delete_comment_button, $current_comment);
                // DELETE SIGNALE BUTTON FOR USER OWN COMMENTS
                $current_comment = str_replace("{SIGNAL_COMMENT}", "", $current_comment);
            }

            if (isset($_SESSION['user']) && $_SESSION['user']->isadmin()) {
                $id = $current_result["id"];
                // DELETE BUTTON
                $delete_comment_button = "<a href=\"index.php?action=delete_comment&id=" . $id . "\" class=\"btn btn-danger btn-sm\">Supprimer</a>";
                $current_comment = str_replace("{DELETE_COMMENT}", $delete_comment_button, $current_comment);
                // SIGNAL BUTTON
                $signal_comment_button = "<a href=\"index.php?action=signal_comment&id=" . $id . "\" class=\"btn btn-warning btn-sm\">Signale</a>";
                $current_comment = str_replace("{SIGNAL_COMMENT}", $signal_comment_button, $current_comment);
            } else {
                $id = $current_result["id"];
                $current_comment = str_replace("{DELETE_COMMENT}", "", $current_comment);
                $signal_comment_button = "<a href=\"index.php?action=signal_comment&id=" . $id . "\" class=\"btn btn-warning btn-sm\">Signale</a>";
                $current_comment = str_replace("{SIGNAL_COMMENT}", $signal_comment_button, $current_comment);
            }

            $List_Comments .= $current_comment;
        }
        return $List_Comments;
    }

    public function Get_Comments_From_Database($post_id)
    {
        $comments = new Comments();
        $result = $comments->GetComments($post_id); // FROM MODEL
        return $result;
    }

    public function GetCommentsforThisUser($post_id)
    {
        $comment_default_code = file_get_contents("view/backend/non_validated_comment_default_code.html");
        $List_Comments = "";
        if (isset($_SESSION['user'])) {
            $result = $this->Get_Non_Validated_Comments_For_This_User_From_The_DataBase($post_id);

            foreach ($result as $current_result) {
                $current_comment = $comment_default_code;
                $current_comment = str_replace("{COMMENT_USER}", $current_result["username"], $current_comment);
                $current_comment = str_replace("{COMMENT_DATE_CREATION}", $current_result["date_creation"], $current_comment);
                $current_comment = str_replace("{COMMENT_CONTENT}", $current_result["contenu"], $current_comment);

                $id = $current_result["id"];
                $link_to_apply_urgent = "<a href=\"index.php?action=apply_urgent&id=" . $id . "\" class=\"btn btn-danger btn-sm\">Demander a valider</a>(Commentaire non publié)";
                $current_comment = str_replace("{APPLY_URGENT}", $link_to_apply_urgent, $current_comment);

                $List_Comments .= $current_comment;
            }
        }
        return $List_Comments;
    }

    public function BuildNonValidatedCommentList()
    {
        $comment_default_code = file_get_contents("view/backend/comments_to_validate_default_code.html");
        $result = $this->Get_All_Non_Validated_Comments_From_Database();
        $List_Comments = "";
        foreach ($result as $current_result) {
            $current_comment = $comment_default_code;
            $current_comment = str_replace("{COMMENT_USER}", $current_result["username"], $current_comment);
            $current_comment = str_replace("{COMMENT_DATE_CREATION}", $current_result["date_creation"], $current_comment);
            $current_comment = str_replace("{COMMENT_CONTENT}", $current_result["contenu"], $current_comment);

            if (isset($_SESSION['user']) && $_SESSION["user"]->isAdmin()) {
                $id = $current_result["id"];
                $delete_comment_button = "<a href=\"index.php?action=delete_comment&id=" . $id . "\" class=\"btn btn-danger\">Supprimer</a>";
                $validate_comment_button = "<a href=\"index.php?action=validate_comment&id=" . $id . "\" class=\"btn btn-success\">Valider</a>";
                $current_comment = str_replace("{DELETE_COMMENT}", $delete_comment_button, $current_comment);
                $current_comment = str_replace("{VALIDATE_COMMENT}", $validate_comment_button, $current_comment);
            } else {
                $current_comment = str_replace("{DELETE_COMMENT}", "", $current_comment);
            }
            $List_Comments .= $current_comment;
        }
        return $List_Comments;
    }

    public function Get_All_Non_Validated_Comments_From_Database()
    {
        $comments = new Comments;
        return $comments->Get_All_Non_Validated();
    }

    public function apply_urgent($id)
    {
        $comments = new Comments;
        $comments->ApplyUrgent($id);
        header("Location: ../index.php?action=blog");
        exit();
    }

    public function Get_Non_Validated_Comments_For_This_User_From_The_DataBase($post_id)
    {
        $comments = new Comments;
        $result = $comments->getNonValidatedComments($post_id, $_SESSION['user']->getEmail());
        return $result;
    }

    public function validate_comment($id)
    {
        $comment = new Comments();
        $comment->validate_comment($id);
        header("Location: ../index.php?action=admin");
        exit();
    }

    public function delete_comment()
    {
        $comment = new Comments();
        $comment->delete_comment();
        header("Location: index.php?action=blog");
    }

    public function signal_comment($id)
    {

        $comment = new Comments();
        $comment->signale( $id);

        header("Location: ../index.php?action=blog");

        exit();
    }


}