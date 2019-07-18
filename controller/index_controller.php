<?php

require "model/posts.php";
require "model/comments.php";
require "model/login.php";
require "model/logout.php";
require "model/validationbuild.php";
require "model/register.php";

class index_controller
{

    public function accueil()
    {
        $view = file_get_contents("view/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/accueil.html"), $view);
        $view = $this->ApplySession($view);
        echo $view;
    }

    public function biographie()
    {
        $view = file_get_contents("view/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/biographie.html"), $view);
        $view = $this->ApplySession($view);
        echo $view;
    }

    public function blog()
    {
        $view = file_get_contents("view/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/blog.html"), $view);
        $view = $this->ApplySession($view);

        // ADD POST - IF ADMIN
        if (isset($_SESSION['db_email']) && $_SESSION['db_admin'] == 1) {
            $view = str_replace("{ADD_POST}", file_get_contents("view/add_post.html"), $view);
        } else {
            $view = str_replace("{ADD_POST}", "", $view);
        }

        $view = str_replace("{POST_LIST}", $this->ReplacePostList($view), $view);
        // $view = ReplacePostList($view);

        echo $view;
    }

    public function contact()
    {
        $view = file_get_contents("view/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/contact.html"), $view);
        $view = $this->ApplySession($view);
        echo $view;
    }

    public function login()
    {

        $view = file_get_contents("view/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/login.html"), $view);
        $view = $this->ApplySession($view);
        echo $view;

    }

    public function logout()
    {
        $Logout = new Logout();
        $Logout->userLogout();
    }

    public function login_validation()
    {

        $Login = new Login();
        $Login->loginvalidation();

        /*
    $view = file_get_contents("view/_layout.html");
    $view = str_replace("{CONTENT}", file_get_contents("view/login.html"), $view);
    $view = $this->ApplySession($view);
    echo $view;
     */
    }

    public function registerNewUser()
    {
        $Register = new Register();
        $Register->RegisterNewUser();

        /*
    $view = file_get_contents("view/_layout.html");
    $view = str_replace("{CONTENT}", file_get_contents("view/register.html"), $view);
    $view = $this->ApplySession($view);
    echo $view;
     */
    }

    public function register()
    {

        $view = file_get_contents("view/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/register.html"), $view);
        $view = $this->ApplySession($view);
        echo $view;

    }

    public function admin()
    {
        $view = file_get_contents("view/_layout.html");
        $view = str_replace("{CONTENT}", file_get_contents("view/admin.html"), $view);
        $view = $this->ApplySession($view);

        // TODO
        // GET NON VALIDATED COMMENTS ORDER DESC by date

        if (isset($_SESSION['db_email']) && $_SESSION['db_admin'] == 1) {
            $ValidationBuild = new ValidationBuild();
            $list_non_validated_comments = $ValidationBuild->BuildNonValidatedCommentList_model();
            $view = str_replace("{COMMENTS_TO_VALIDATE}", $list_non_validated_comments, $view);
        }

        // Build button to validate comments

        echo $view;
    }

    public function add_comment()
    {
        $comments = new Comments();
        $comments->add_Comment();
    }

    public function add_post()
    {
        $post = new Posts();
        $post->add_post();
    }

    public function edit_post($id)
    {
        $post = new Posts();
        $post->edit_post($id);
    }

    public function update_post()
    {
        $post = new Posts();
        $post->UpdatePost();
    }

    public function delete_post($id)
    {
        $post = new Posts();
        $post->DeletePost($id);
    }

    public function validate_comment($id)
    {
        $post = new Comments();
        $post->ValidateComment($id);
    }

    // _________________________________________________
    // _________________________________________________
    // _________________________________________________

    // APPLY SESSION
    public function ApplySession($view)
    {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // $_SESSION['db_email'] = "ruivo.rui@gmail.com";
        // $_SESSION['db_email'] = null;
        if (isset($_SESSION['db_email'])) {
            $logout_button = "<a class=\"nav-link\" href=\"index.php?action=contact\"><i class=\"fas fa-user\"></i></a>" . "<a class=\"nav-link\" href=\"index.php?action=contact\">" . $_SESSION['db_email'] . "</a>" . "<a class=\"nav-link\" href=\"index.php?action=contact\">" . file_get_contents("view/button_logout.html") . "</a>";
            $view = str_replace("{USER_BLOC}", $logout_button, $view);
            // IF USER IS ADMIN SHOW ADMIN LINK
            if (isset($_SESSION['db_admin']) && $_SESSION['db_admin'] == 1) {
                $admin_link = "<a class=\"nav-link\" href=\"index.php?action=admin\">Admin</a>";
                $view = str_replace("{ADMIN}", $admin_link, $view);
            } else {
                $admin_link = "";
                $view = str_replace("{ADMIN}", $admin_link, $view);
            }
        } else {
            $view = str_replace("{USER_BLOC}", file_get_contents("view/nav_user_option.html"), $view);
            $admin_link = "";
            $view = str_replace("{ADMIN}", $admin_link, $view);
        }
        // session_destroy();

        return $view;
    }

    // REPLACE POST LIST
    public function ReplacePostList()
    {
        $post_default_code = file_get_contents("view/post_default_code.html");
        $post = new Posts();
        $result = $post->get_posts(); // FROM MODEL
        $bloc_billet = "";
        foreach ($result as $current_result) {
            $current_billet = $post_default_code;
            // ADD COMMENT FORM - IF USER IS REGISTERED
            if (isset($_SESSION['db_email'])) {
                $current_billet = str_replace("{ADD_COMMENTS}", file_get_contents("view/add_comment.html"), $current_billet);
                // REPLACE {POST_ID} - FOR COMMENT POST_ID
                $current_billet = str_replace("{POST_ID}", $current_result["id"], $current_billet);
                // REPLACE USERNAME for identificatin
                $current_billet = str_replace("{USERNAME}", $_SESSION['db_email'], $current_billet);

                if ($_SESSION['db_admin'] == 1) {
                    // DECLARE edit and delete post buttons code
                    $button_edit_post = "<a href=\"index.php?action=edit_post&id={ID}\" class=\"btn btn-secondary\">Editer</a>";
                    $button_delete_post = "<a href=\"index.php?action=delete_post&id={ID}\" class=\"btn btn-danger\">Supprimer</a>";
                    // REPLACE {BOUTON_EDITER_BILLET}
                    $current_billet = str_replace("{BUTTON_EDIT_POST}", $button_edit_post, $current_billet);
                    // REPLACE {BOUTON_SUPPRIMER_BILLET}
                    $current_billet = str_replace("{BUTTON_DELETE_POST}", $button_delete_post, $current_billet);
                } else {
                    $current_billet = str_replace("{BUTTON_EDIT_POST}", "", $current_billet);
                    $current_billet = str_replace("{BUTTON_DELETE_POST}", "", $current_billet);
                }
            } else {
                $current_billet = str_replace("{ADD_COMMENTS}", "", $current_billet);
                $current_billet = str_replace("{BUTTON_EDIT_POST}", "", $current_billet);
                $current_billet = str_replace("{BUTTON_DELETE_POST}", "", $current_billet);
            }

            $current_billet = str_replace("{POST_DATE}", $current_result["date_creation"], $current_billet);
            $current_billet = str_replace("{POST_TITLE}", $current_result["titre"], $current_billet);
            $current_billet = str_replace("{POST_CONTENT}", $current_result["contenu"], $current_billet);

            // {ID} only exists after first {Bouton...} replacement
            $current_billet = str_replace("{ID}", $current_result["id"], $current_billet);
            $current_billet = str_replace("{POST_ID}", $current_result["id"], $current_billet);

            $current_billet = str_replace("{COMMENTS_BODY}", $this->GetCommentsforPost($current_result["id"]), $current_billet);

            // GET COMMENTS WAITING FOR THIS USER
            $current_billet = str_replace("{COMMENTS_WAITING}", $this->GetCommentsforThisUser($current_result["id"]), $current_billet);

            // AJOUTER ICI LA FONCTION POUR CALCULER LE TOTAL DES COMMENTAIRES DANS LA BD pour chaque billet
            $bloc_billet .= $current_billet;
        }
        $bloc_billet = str_replace("{POST_LIST}", $bloc_billet, $bloc_billet);
        return $bloc_billet;
    }

    public function GetCommentsforPost($post_id)
    {

        $comment_default_code = file_get_contents("view/comment_default_code.html");
        $Comments = new Comments();
        $result = $Comments->Get_Comments_From_Database($post_id);
        $List_Comments = "";
        foreach ($result as $current_result) {
            $current_comment = $comment_default_code;
            $current_comment = str_replace("{COMMENT_USER}", $current_result["username"], $current_comment);
            $current_comment = str_replace("{COMMENT_DATE_CREATION}", $current_result["date_creation"], $current_comment);
            $current_comment = str_replace("{COMMENT_CONTENT}", $current_result["contenu"], $current_comment);

            if (isset($_SESSION['db_email']) && $_SESSION['db_admin'] == 1) {
                $id = $current_result["id"];
                $delete_comment_button = "<a href=\"index.php?action=delete_comment&id=" . $id . "\" class=\"btn btn-danger\">Supprimer</a>";
                $current_comment = str_replace("{DELETE_COMMENT}", $delete_comment_button, $current_comment);
            } else {
                $current_comment = str_replace("{DELETE_COMMENT}", "", $current_comment);
            }

            $List_Comments .= $current_comment;
        }
        return $List_Comments;
    }

    public function GetCommentsforThisUser($post_id)
    {
        $comment_default_code = file_get_contents("view/non_validated_comment_default_code.html");
        $Comments = new Comments();
        $List_Comments = "";
        if (isset($_SESSION['db_email'])) {
            $result = $Comments->Get_Non_Validated_Comments_For_This_User_From_The_DataBase($post_id);

            foreach ($result as $current_result) {
                $current_comment = $comment_default_code;
                $current_comment = str_replace("{COMMENT_USER}", $current_result["username"], $current_comment);
                $current_comment = str_replace("{COMMENT_DATE_CREATION}", $current_result["date_creation"], $current_comment);
                $current_comment = str_replace("{COMMENT_CONTENT}", $current_result["contenu"], $current_comment);

                $id = $current_result["id"];
                $link_to_apply_urgent = "<a href=\"index.php?action=apply_urgent&id=" . $id . "\" class=\"btn btn-danger\">Demander a valider</a>";
                $current_comment = str_replace("{APPLY_URGENT}", $link_to_apply_urgent, $current_comment);

                /*
                if (isset($_SESSION['db_email']) && $_SESSION['db_admin'] == 1) {
                $id = $current_result["id"];
                $delete_comment_button = "<a href=\"index.php?action=delete_comment&id=" . $id . "\" class=\"btn btn-danger\">Supprimer</a>";
                $current_comment = str_replace("{DELETE_COMMENT}", $delete_comment_button, $current_comment);
                } else {
                $current_comment = str_replace("{DELETE_COMMENT}", "", $current_comment);
                }
                 */

                $List_Comments .= $current_comment;
            }
        }

        return $List_Comments;
    }

}
