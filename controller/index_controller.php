<!-- INDEX CONTROLLER -->

<?php

// IMPORT
// require "model/fill_list_posts.php";
// LISTER TOUT LES BILLETS

function accueil()
{
    // IMPORT VIEWS
    $view = file_get_contents("view/_layout.html");
    $default_code = "ACCUEIL";

    echo ReplaceContent($default_code, $view);

}

function biographie()
{
    // IMPORT VIEWS
    $view = file_get_contents("view/_layout.html");
    $default_code = "BIOGRAPHIE";

    echo ReplaceContent($default_code, $view);
}

function blog()
{
    // IMPORT VIEWS
    $view = file_get_contents("view/_layout.html");
    $default_code = "BLOG";

    echo ReplaceContent($default_code, $view);
}

function contact()
{
    // IMPORT VIEWS
    $view = file_get_contents("view/_layout.html");
    $default_code = "CONTACT";

    echo ReplaceContent($default_code, $view);
}

function se_connecter()
{
    // IMPORT VIEWS
    $view = file_get_contents("view/_layout.html");
    $default_code = file_get_contents("view/formulaire_default_code.html");

    echo ReplaceContent($default_code, $view);
}

function acces_admin()
{
    // IMPORT VIEWS
    $view = file_get_contents("view/_layout.html");
    $default_code = file_get_contents("view/admin_panel_default_code.html");
    $add_post = file_get_contents("view/add_post_default_code.html");

    $view = ReplaceContent($default_code, $view);

    $view = ReplaceAddPost($add_post, $view);

    $view = ReplacePostList($view);

    echo $view;
}

// ____________________________________________________________
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

        // AJOUTER ICI LA FONCTION POUR CALCULER LE TOTAL DES COMMENTAIRES DANS LA BD pour chaque billet

        $bloc_billet .= $current_billet;
    }

    $bloc_billet = str_replace("{POST_LIST}", $bloc_billet, $view);

    return $bloc_billet;

}
