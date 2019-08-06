<!-- ROUTER -->
<?php

// IMPORT
require 'controller/index_controller.php';

class Router
{

    public function main()
    {

        $c = new index_controller();

        if (isset($_GET['action'])) {

            // ALL _GET ACTIONS HERE

            if ($_GET['action'] == 'accueil') {
                $c->accueil();
            }

            if ($_GET['action'] == 'biographie') {
                $c->biographie();
            }

            if ($_GET['action'] == 'blog') {
                $c->blog();
            }

            if ($_GET['action'] == 'login') {
                $c->login();
            }

            if ($_GET['action'] == 'logout') {
                $c->logout();
            }

            if ($_GET['action'] == 'register') {
                $c->register();
            }

            if ($_GET['action'] == 'registernewuser') {
                $c->registerNewUser();
            }

            if ($_GET['action'] == 'admin') {
                $c->admin();
            }

            if ($_GET['action'] == 'user_info') {
                $c->user_info();
            }

            // VALIDATE COMMENT
            if ($_GET['action'] == 'validate_comment') {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $c->validate_comment($_GET['id']);
                } else {
                    echo 'Aucun identifiant de billet envoyé';
                }
            }

            // SIGNAL COMMENT
            if ($_GET['action'] == 'signal_comment') {

                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $c->signal_comment($_GET['id']);
                } else {
                    echo 'Aucun identifiant de commentaire envoyé';
                }
            }

            if ($_GET['action'] == 'login_validation') {
                $c->login_validation();
            }

            if ($_GET['action'] == 'add_comment') {
                $c->add_comment();
            }

            if ($_GET['action'] == 'add_Post') {
              
                $c->add_post();
            }

            if ($_GET['action'] == 'addMessage') {
                $c->add_message();
            }

            // EDIT POST
            if ($_GET['action'] == 'edit_post') {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $c->edit_post($_GET['id']);
                } else {
                    echo 'Aucun identifiant de billet envoyé';
                }
            }

            // UPDATE POST
            if ($_GET['action'] == 'update_post') {
                $c->update_post();
            }

            // DELETE POST
            if ($_GET['action'] == 'delete_post') {

                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $c->delete_post($_GET['id']);
                } else {
                    echo 'Aucun identifiant de billet envoyé';
                }
            }

            // DELETE COMMENT
            if ($_GET['action'] == 'delete_comment') {

                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $c->delete_comment($_GET['id']);
                } else {
                    echo 'Erreur : aucun identifiant de commentaire envoyé';
                }
            }

            // APPLY URGENCE
            if ($_GET['action'] == 'apply_urgent') {

                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $c->apply_urgent($_GET['id']);
                } else {
                    echo 'Erreur : aucun identifiant de commentaire envoyé';
                }
            }

        } else {
            $c->accueil();
        }

    }

}

// Main Call
$route = new Router();
$route->main();