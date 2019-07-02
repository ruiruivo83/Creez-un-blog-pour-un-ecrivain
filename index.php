<!-- ROUTER -->

<?php

// IMPORT
require 'controller/index_controller.php';

if (isset($_GET['action'])) {

    // ACCUEIL
    if ($_GET['action'] == 'accueil') {
        accueil();
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
    elseif ($_GET['action'] == 'se_connecter') {
        se_connecter();
    }

    
    // SE CONNECTER - ACCES ADMIN
    elseif ($_GET['action'] == 'acces_admin') {
        acces_admin();
    }

} else {

    accueil();

}