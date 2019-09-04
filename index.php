<!-- ////////////////////////// -->
<!-- ///////// ROUTER ///////// -->
<!-- ////////////////////////// -->

<?php

// IMPORT CONTROLLERS
require 'controller/pages_controller.php';
require 'controller/post_controller.php';
require 'controller/comment_controller.php';
require 'controller/user_controller.php';
require 'controller/admin_controller.php';

class router
{

    public function __construct()
    {
        // SESSION
        // IMPLEMENT SESSION VERIFICATION
        // On démarre la session AVANT d'écrire du code HTML
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function main()
    {

        $pagesController = new pages_controller();
        $postController = new post_controller();
        $commentController = new comment_controller();
        $userController = new user_controller();
        $adminController = new admin_controller();

        if (isset($_GET['action'])) {

            //////////////////////////////////////////////////////////////////
            //////////////////////// ROUTER PAGES ////////////////////////////
            //////////////////////////////////////////////////////////////////

            // ACCUEIL OK
            if ($_GET['action'] == 'accueil') {
                $pagesController->accueil();
            }

            // BIOGRAPHIE OK
            if ($_GET['action'] == 'biographie') {
                $pagesController->biographie();
            }

            // BLOG OK
            if ($_GET['action'] == 'blog') {
                $pagesController->blog();
            }

            // EDIT POST
            if (isset($_SESSION["db_admin"]) and $_SESSION["db_admin"] == 1) {
                if ($_GET['action'] == 'edit_post') {
                    if (isset($_GET['id']) && $_GET['id'] > 0) {
                        $postController->edit_post($_GET['id']);
                    } else {
                        echo 'Aucun identifiant de billet envoyé';
                    }
                }
            }

            // LOGIN
            if ($_GET['action'] == 'login') {
                $pagesController->login();
            }

            // REGISTER
            if ($_GET['action'] == 'register') {
                $pagesController->register();
            }

            // PAGE ADMIN
            if (isset($_SESSION["db_admin"]) and $_SESSION["db_admin"] == 1) {
                if ($_GET['action'] == 'admin') {
                    $adminController->admin();
                }
            }

            ////////////////////////////////////////////////////////////////////
            ////////////////////// ROUTER FUNCTIONS ////////////////////////////
            ////////////////////////////////////////////////////////////////////

            // LOGIN VALIDATION
            if ($_GET['action'] == 'login_validation') {
                $userController->login_validation();
            }

            // PAGE REGISTER NEW USER
            if ($_GET['action'] == 'registernewuser') {
                $userController->registerNewUser();
            }

            // LOGOUT
            if ($_GET['action'] == 'logout') {
                $userController->logout();
            }

            // ADD POST
            if ($_GET['action'] == 'add_Post') {
                $postController->add_post();
            }

            // UPDATE POST
            if ($_GET['action'] == 'update_post') {
                $postController->update_post();
            }

            // DELETE POST
            if (isset($_SESSION["db_admin"])) {
                if ($_GET['action'] == 'delete_post') {
                    if (isset($_GET['id']) && $_GET['id'] > 0) {
                        $postController->delete_post($_GET['id']);
                    } else {
                        echo 'Aucun identifiant de billet envoyé';
                    }
                }
            }

            // ADD COMMENT
            if (isset($_SESSION["db_email"])) {
                if ($_GET['action'] == 'add_comment') {
                    $commentController->add_comment();
                }
            }

            // SIGNAL COMMENT
            if (isset($_SESSION["db_email"])) {
                if ($_GET['action'] == 'signal_comment') {
                    if (isset($_GET['id']) && $_GET['id'] > 0) {
                        $commentController->signal_comment($_GET['id']);
                    } else {
                        echo 'Aucun identifiant de commentaire envoyé';
                    }
                }
            }

            // VALIDATE COMMENT
            if (isset($_SESSION["db_admin"])) {
                if ($_GET['action'] == 'validate_comment') {
                    if (isset($_GET['id']) && $_GET['id'] > 0) {
                        $commentController->validate_comment($_GET['id']);
                    } else {
                        echo 'Aucun identifiant de billet envoyé';
                    }
                }
            }

            // DELETE COMMENT
            if (isset($_SESSION["db_admin"])) {
                if ($_GET['action'] == 'delete_comment') {

                    if (isset($_GET['id']) && $_GET['id'] > 0) {
                        $commentController->delete_comment($_GET['id']);
                    } else {
                        echo 'Erreur : aucun identifiant de commentaire envoyé';
                    }
                }

            }

            // APPLY URGENCY
            if (isset($_SESSION["db_email"])) {

                if ($_GET['action'] == 'apply_urgent') {

                    if (isset($_GET['id']) && $_GET['id'] > 0) {
                        $commentController->apply_urgent($_GET['id']);
                    } else {
                        echo 'Erreur : aucun identifiant de commentaire envoyé';
                    }
                }
            }
        } else {
            $pagesController->accueil();
        }

    }
}

// Main Call
$route = new Router();
$route->main();
