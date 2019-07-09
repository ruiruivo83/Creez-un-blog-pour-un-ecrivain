<!-- ROUTER -->

<?php

// IMPORT
require 'controller/index_controller.php';

if (isset($_GET['action'])) {

    // ACCUEIL
    if ($_GET['action'] == 'accueil') {
        accueil();
    }

    // LOGOUT
    elseif (($_GET['action'] == 'logout')) {
        logout();
    }

    // BIOGRAPHIE
    elseif (($_GET['action'] == 'biographie')) {
        biographie();
    }

    // BLOG
    elseif (($_GET['action'] == 'blog')) {
        blog();
    }

    // CONTACT
    elseif (($_GET['action'] == 'contact')) {
        contact();
    }

    // SE CONNECTER - ACCES ADMIN
    elseif ($_GET['action'] == 'login') {
        login();
    }

    // S'ENREGISTRER
    elseif ($_GET['action'] == 'register') {
        register();
    }

    // SE CONNECTER - ACCES ADMIN
    elseif ($_GET['action'] == 'acces_admin') {
        acces_admin();
    }

    // EDIT POST
    elseif ($_GET['action'] == 'edit_post') {

        if (isset($_GET['id']) && $_GET['id'] > 0) {
            edit_post($_GET['id']);
        } else {
            echo 'Erreur : aucun identifiant de billet envoyé';
        }
        
    }

    // DELETE POST
    elseif ($_GET['action'] == 'delete_post') {

        if (isset($_GET['id']) && $_GET['id'] > 0) {
            delete_post($_GET['id']);
        } else {
            echo 'Erreur : aucun identifiant de billet envoyé';
        }

    }

} else {

    accueil();

}