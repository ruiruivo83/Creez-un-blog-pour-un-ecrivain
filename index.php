<!-- ROUTER -->

<?php

// IMPORT
require 'controller/post_controller.php';

if (isset($_GET['action'])) {

    // LIST ALL POSTS
    if ($_GET['action'] == 'list_posts') {

        list_posts();
    }

    // ADD POST TO DATABASE
    elseif (($_GET['action'] == 'add_post')) {
        add_post();
    }

    // SHOW POST DETAILS
    elseif ($_GET['action'] == 'show_post_details') {

        if (isset($_GET['id']) && $_GET['id'] > 0) {
            show_post_details($_GET['id']);
        } else {
            echo 'Erreur : aucun identifiant de billet envoyé';
        }
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

    // SHOW NEW POST FORM
    elseif (($_GET['action'] == 'show_new_post_form')) {
        show_new_post_form();
    }

    // ADD COMMENT TO POST
    elseif ($_GET['action'] == 'add_comment') {

        if (isset($_GET['id']) && $_GET['id'] > 0) {
            if (!empty($_POST['author']) && !empty($_POST['comment'])) {
                addComment($_GET['id'], $_POST['author'], $_POST['comment']);
            } else {
                echo 'Erreur : tous les champs ne sont pas remplis !';
            }
        } else {
            echo 'Erreur : aucun identifiant de billet envoyé';
        }

    }

    // ACCES ADMIN
    elseif (($_GET['action'] == 'admin')) {
        admin();
    }

} else {

    list_posts();

}
