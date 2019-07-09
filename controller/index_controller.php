<!-- INDEX CONTROLLER -->

<?php

// IMPORT
// require "model/fill_list_posts.php";
// LISTER TOUT LES BILLETS

function accueil()
{
    if (session_status() == PHP_SESSION_NONE) {

        session_start();
    }

    // 1st call - IMPORT VIEWS
    $view = file_get_contents("view/_layout.html");

    // Verify Session
    $view = Verify_Session($view);

    $default_code = "ACCUEIL";

    echo ReplaceContent($default_code, $view);

}

function logout()
{

    // session_start();

    // Détruit toutes les variables de session
    $_SESSION = array();

    // Si vous voulez détruire complètement la session, effacez également
    // le cookie de session.
    // Note : cela détruira la session et pas seulement les données de session !
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finalement, on détruit la session.
    session_destroy();

    header('Location: ../index.php');

}

function biographie()
{
    // REQUIRED FOR SESSION
    if (session_status() == PHP_SESSION_NONE) {

        session_start();
    }

    // IMPORT VIEWS
    $view = file_get_contents("view/_layout.html");
    $default_code = "BIOGRAPHIE";

    // REQUIRED IN EVERY PAGE LOAD - Verify Session
    $view = Verify_Session($view);

    echo ReplaceContent($default_code, $view);
}

function blog()
{
    // REQUIRED FOR SESSION
    if (session_status() == PHP_SESSION_NONE) {

        session_start();
    }

    // IMPORT VIEWS
    $view = file_get_contents("view/_layout.html");
    $default_code = "BLOG";

    // APPLY SESSION TO _LAYOUT VIEW
    $view = Verify_Session($view);

    echo ReplaceContent($default_code, $view);
}

function contact()
{
    // REQUIRED FOR SESSION
    if (session_status() == PHP_SESSION_NONE) {

        session_start();
    }

    // IMPORT VIEWS
    $view = file_get_contents("view/_layout.html");
    $default_code = "CONTACT";

    // APPLY SESSION TO _LAYOUT VIEW
    $view = Verify_Session($view);

    echo ReplaceContent($default_code, $view);
}

function login()
{
    // REQUIRED FOR SESSION
    if (session_status() == PHP_SESSION_NONE) {

        session_start();
    }

    // IMPORT VIEWS
    $view = file_get_contents("view/_layout.html");
    $default_code = file_get_contents("view/login_default_code.html");

    echo ReplaceContent($default_code, $view);
}

function register()
{

    // IMPORT VIEWS
    $view = file_get_contents("view/_layout.html");
    $default_code = file_get_contents("view/register_default_code.html");

    echo ReplaceContent($default_code, $view);
}

function acces_admin()
{
    // REQUIRED FOR SESSION
    if (session_status() == PHP_SESSION_NONE) {

        session_start();
    }

    // IMPORT VIEWS
    $view = file_get_contents("view/_layout.html");
    $default_code = file_get_contents("view/admin_panel_default_code.html");
    $add_post = file_get_contents("view/add_post_default_code.html");

    // APPLY SESSION TO _LAYOUT VIEW
    $view = Verify_Session($view);

    $view = ReplaceContent($default_code, $view);
    $view = ReplaceAddPost($add_post, $view);
    $view = ReplacePostList($view);

    echo $view;

}

// EDITER UN BILLET
function edit_post($id)
{
    // REQUIRED FOR SESSION
    if (session_status() == PHP_SESSION_NONE) {

        session_start();
    }

    require "model/edit_post.php";
    $view = file_get_contents("view/_layout.html");
    $view = str_replace("{CONTENT}", edit_post_model($id), $view);
    echo $view;
}

// EFFACER UN BILLET
function delete_post($id)
{
    // REQUIRED FOR SESSION
    if (session_status() == PHP_SESSION_NONE) {

        session_start();
    }

    require "model/delete_post.php";
    delete_post_model($id);
}

// ____________________________________________________________
//
// ____________________________________________________________

// UNIVERSAL REPLACE {CONTENT}
function ReplaceContent($default_code, $view)
{
    $view = str_replace("{CONTENT}", $default_code, $view);
    return $view;
}

// UNIVERSAL REPLACE {ADD_POST}
function ReplaceAddPost($default_code, $view)
{
    $view = str_replace("{ADD_POST}", $default_code, $view);
    return $view;
}

// UNIVERSAL REPLACE {POST_LIST}
function ReplacePostList($view)
{

    require "model/get_posts.php";

    $post_default_code = file_get_contents("view/post_default_code.html");

    $result = get_posts();

    $bloc_billet = "";
    foreach ($result as $current_result) {
        $current_billet = $post_default_code;
        // REPLACE {DATE_BILLET} - BY $current_result["date_creation"] - INSIDE $current_billet
        $current_billet = str_replace("{POST_DATE}", $current_result["date_creation"], $current_billet);
        $current_billet = str_replace("{POST_TITLE}", $current_result["titre"], $current_billet);
        $current_billet = str_replace("{POST_CONTENT}", $current_result["contenu"], $current_billet);
        $current_billet = str_replace("{ID}", $current_result["id"], $current_billet);
        $current_billet = str_replace("{POST_ID}", $current_result["id"], $current_billet);

        // AJOUTER ICI LA FONCTION POUR CALCULER LE TOTAL DES COMMENTAIRES DANS LA BD pour chaque billet

        $bloc_billet .= $current_billet;
    }

    $bloc_billet = str_replace("{POST_LIST}", $bloc_billet, $view);

    return $bloc_billet;

}

function Verify_Session($view)
{

    if (isset($_SESSION['db_email']) != null) {

        $view = str_replace("{HELLO_USER}", "Hello " . $_SESSION['db_email'] . "<a href=\"index.php?action=logout\"> LogOut</a>", $view);
    } else {

        $view = str_replace("{HELLO_USER}", "Veuillez vous loger.", $view);
    }

    return $view;

}