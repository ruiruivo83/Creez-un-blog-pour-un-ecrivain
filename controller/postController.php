<?php

require 'model/Posts.php';


class postController
{



    public function ReplacePostList()
    {
        $post_default_code = file_get_contents("view/backend/post_default_code.html");
        $post = new Posts(null,null , null);
        $result = $post->getPosts(Database::getBdd()); // FROM MODEL
        $bloc_billet = "";
        foreach ($result as $current_result) {
            $current_billet = $post_default_code;
            // ADD COMMENT FORM - IF USER IS REGISTERED
            if (isset($_SESSION['user'])) {
                $current_billet = str_replace("{ADD_COMMENTS}", file_get_contents("view/backend/add_comment.html"), $current_billet);
                // REPLACE {POST_ID} - FOR COMMENT POST_ID
                $current_billet = str_replace("{POST_ID}", $current_result["id"], $current_billet);
                // REPLACE USERNAME for identificatin
                $current_billet = str_replace("{USERNAME}", $_SESSION['user']->getEmail(), $current_billet);

                if ($_SESSION['user']->isAdmin()) {
                    // DECLARE edit and delete post buttons code
                    $button_edit_post = "<a href=\"index.php?action=edit_post&id={ID}\" class=\"btn btn-secondary btn-sm\">Editer</a>";
                    $button_delete_post = "<a href=\"index.php?action=delete_post&id={ID}\" class=\"btn btn-danger btn-sm\">Supprimer</a>";
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

            // GET COMMENTS FO SPECIFIC POST ID
            $comments = new commentController;
            $current_billet = str_replace("{COMMENTS_BODY}", $comments->GetCommentsforPost($current_result["id"]), $current_billet);

            // GET COMMENTS WAITING FOR THIS USER
            $current_billet = str_replace("{COMMENTS_WAITING}", $comments->GetCommentsforThisUser($current_result["id"]), $current_billet);

            // AJOUTER ICI LA FONCTION POUR CALCULER LE TOTAL DES COMMENTAIRES DANS LA BD pour chaque billet
            $bloc_billet .= $current_billet;
        }
        $bloc_billet = str_replace("{POST_LIST}", $bloc_billet, $bloc_billet);
        return $bloc_billet;
    }

    public function GetPostListHTMLTable()
    {
        $HTMLPostListTable = "";
        $bloc_post_list = file_get_contents("view/backend/post_list_table_default_code_main_bloc.html");
        $post_default_code = file_get_contents("view/backend/post_list_table_default_code.html");
        $post = new Posts(null, null,null );
        $result = $post->getPosts(); // FROM MODEL
        $bloc_billet = "";
        foreach ($result as $current_result) {
            $current_billet = $post_default_code;
            $current_billet = str_replace("{POST_TITLE}", $current_result["titre"], $current_billet);
            $current_billet = str_replace("{BUTTON_DELETE}", "<a href=\"index.php?action=delete_post&id=" . $current_result["id"] . "\" class=\"btn btn-danger btn-sm\">Supprimer</a>", $current_billet);
            $current_billet = str_replace("{BUTTON_EDIT}", "<a href=\"index.php?action=edit_post&id=" . $current_result["id"] . "\" class=\"btn btn-secondary btn-sm\">Editer</a>", $current_billet);
            $HTMLPostListTable .= $current_billet;
        }
        $bloc_post_list = str_replace("{ADMIN_LIST_BLOC}", $HTMLPostListTable, $bloc_post_list);
        return $bloc_post_list;
    }

    public function editPost($id)
    {
        if (isset($_SESSION['user']) && $_SESSION["user"]->isAdmin()) {
            // REQUIRED FOR SESSION
            $view = file_get_contents("view/frontend/_layout.html");
            $view = str_replace("{CONTENT}", $this->edit_post_code($id), $view);
            $IndexController = new sessionController();
            $view = $IndexController->replaceMenuIfSessionIsOpen($view);
            echo $view;
        }
    }

    public function edit_post_code($id)
    {
        $edit_post_default_code = file_get_contents("view/backend/edit_post_default_code.html");
        $edit_post_default_code = $this->edit_post_query($id, $edit_post_default_code);
        return $edit_post_default_code;
    }

    private function edit_post_query($id, $edit_post_default_code)
    {
        $post = new Posts(null, null, null);
        $result = $post->edit_post_query($id, $edit_post_default_code);

        foreach ($result as $current_result) {
            $edit_post_default_code = str_replace("{INPUT_TITRE}", '<input type="text" name="titre" id="Titre" value="' . $current_result["titre"] . '" required>', $edit_post_default_code);
            $edit_post_default_code = str_replace("{INPUT_ID}", '<input type="text" name="id" id="Id" value="' . $id . '" required hidden>', $edit_post_default_code);
            $edit_post_default_code = str_replace("{INPUT_CONTENU}", '<div class="input_text"> <textarea id="textarea" name="contenu" required>' . $current_result["contenu"] . '</textarea></div>', $edit_post_default_code);
            $edit_post_default_code = str_replace("{ID}", $id, $edit_post_default_code);
        }
        return $edit_post_default_code;

    }
    public function updatePost()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["update_post"])) {
            // echo "INSIDE";
            $Titre = $_POST["titre"];
            $Contenu = $_POST["contenu"];
            $id = $_POST['id'];
            $post = new Posts($id, $Titre, $Contenu);
            $post->update();
            // update_post_query($Titre, $Contenu, $id);
        }
        header("Location: ../index.php?action=blog");
        exit();
    }

    public function delete_post($id)
    {
        if (isset($_SESSION['user']) && $_SESSION["user"]->isAdmin()) {
            $post = new Posts($id, null, null);
            $post->delete_post_query( /* ??? */);
            header("Location: index.php?action=blog");
        }
    }

    public function addPost()
    {
        if (isset($_SESSION['user']) && $_SESSION["user"]->isAdmin()) {
            if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["Titre"])) {
                $Titre = $_POST["Titre"];
                $Contenu = $_POST["Contenu"];
                // INSERT INTO DATABASE
                $post = new Posts(null, null, null);
                $post->addPost($Titre, $Contenu);
                header('Location: ../index.php?action=blog');
                exit();
            }
        }
    }


}