<!-- ////////////////////////// -->
<!-- ///////// ROUTER ///////// -->
<!-- ////////////////////////// -->

<?php

// IMPORT CONTROLLERS
require 'controller/pagesController.php';
require 'controller/postController.php';
require 'controller/commentController.php';
require 'controller/userController.php';
require 'controller/adminController.php';

class Router
{

    public function __construct()
    {
        // SESSION OPEN FOR ALL USERS
        // IMPLEMENT SESSION VERIFICATION
        // On démarre la session AVANT d'écrire du code HTML
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function main()
    {

        $pagesController = new pagesController();
        $postController = new postController();
        $commentController = new commentController();
        $userController = new userController();
        $adminController = new adminController();


        if (isset($_GET['action'])) {

            //////////////////////////////////////////////////////////////////
            //////////////////////// ROUTER PAGES ////////////////////////////
            //////////////////////////////////////////////////////////////////

            // ACCUEIL
            if ($_GET['action'] == 'accueil') {
                $pagesController->accueil();
            }

            // BIOGRAPHIE
            if ($_GET['action'] == 'biographie') {
                $pagesController->biographie();
            }

            // BLOG
            if ($_GET['action'] == 'blog') {
                $pagesController->blog();
            }

            // EDIT POST
            if ($_GET['action'] == 'edit_post') {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $postController->editPost($_GET['id']);
                } else {
                    echo 'Aucun identifiant de billet envoyé';
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
            if ($_GET['action'] == 'admin') {
                $adminController->admin();
            }

            // PAGE POLITIQUE
            if ($_GET['action'] == 'politique') {
                $pagesController->politique();
            }


            ////////////////////////////////////////////////////////////////////
            ////////////////////// ROUTER FUNCTIONS ////////////////////////////
            ////////////////////////////////////////////////////////////////////

            // LOGIN VALIDATION
            if ($_GET['action'] == 'login_validation') {
                $userController->loginValidation();
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
                $postController->addPost();
            }

            // UPDATE POST
            if ($_GET['action'] == 'update_post') {
                $postController->updatePost();
            }

            // DELETE POST
            if ($_GET['action'] == 'delete_post') {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $postController->delete_post($_GET['id']);
                } else {
                    echo 'Aucun identifiant de billet envoyé';
                }
            }

            // ADD COMMENT
            if ($_GET['action'] == 'add_comment') {
                $commentController->add_Comment();
            }

            // SIGNAL COMMENT
            if ($_GET['action'] == 'signal_comment') {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $commentController->signal_comment($_GET['id']);
                } else {
                    echo 'Aucun identifiant de commentaire envoyé';
                }
            }

            // VALIDATE COMMENT
            if ($_GET['action'] == 'validate_comment') {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $commentController->validate_comment($_GET['id']);
                } else {
                    echo 'Aucun identifiant de billet envoyé';
                }
            }

            // DELETE COMMENT
            if ($_GET['action'] == 'delete_comment') {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $commentController->delete_comment();
                } else {
                    echo 'Erreur : aucun identifiant de commentaire envoyé';
                }
            }

            // APPLY URGENCY
            if ($_GET['action'] == 'apply_urgent') {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $commentController->apply_urgent($_GET['id']);
                } else {
                    echo 'Erreur : aucun identifiant de commentaire envoyé';
                }
            }

        } else {
            $pagesController->accueil();
        }


    }

}

// Main Call
$router = new Router;
$router->main();
