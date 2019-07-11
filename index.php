<!-- ROUTER -->

<?php

// IMPORT
require 'controller/index_controller.php';

if (isset($_GET['action'])) {

    if ($_GET['action'] == 'accueil') {
        accueil();
    }

    if ($_GET['action'] == 'biographie') {
        biographie();
    }

    if ($_GET['action'] == 'blog') {
        blog();
    }

    if ($_GET['action'] == 'contact') {
        contact();
    }

    if ($_GET['action'] == 'login') {
        login();
    }

    if ($_GET['action'] == 'register') {
        register();
    }

    if ($_GET['action'] == 'admin') {
        admin();
    }

    // EDIT POST
    if ($_GET['action'] == 'edit_post') {

        if (isset($_GET['id']) && $_GET['id'] > 0) {
            edit_post($_GET['id']);
        } else {
            echo 'Aucun identifiant de billet envoyé';
        }

    }

    // DELETE POST
    if ($_GET['action'] == 'delete_post') {

        if (isset($_GET['id']) && $_GET['id'] > 0) {
            delete_post($_GET['id']);
        } else {
            echo 'Erreur : aucun identifiant de billet envoyé';
        }

    }

    // DELETE COMMENT
    if ($_GET['action'] == 'delete_comment') {

        if (isset($_GET['id']) && $_GET['id'] > 0) {
            delete_comment($_GET['id']);
        } else {
            echo 'Erreur : aucun identifiant de commentaire envoyé';
        }

    }


    // VALIDATE COMMENT
    if ($_GET['action'] == 'validate_comment') {

   

        if (isset($_GET['id']) && $_GET['id'] > 0) {
            validate_comment($_GET['id']);
        } else {
            echo 'Erreur : aucun identifiant de commentaire envoyé';
        }

    }

} else {

    accueil();

}