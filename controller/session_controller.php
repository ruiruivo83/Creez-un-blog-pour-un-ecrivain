<?php

// require 'model/db.php';

class session_controller
{

    public function sessionTest($view)
    {
        // REPLACE MENU OPTIONS - {LOGIN} {REGISTER} {ADMIN} {USER_INFO}
        $loginURL = '<a class="nav-link active" href="index.php?action=login">Login</a>';
        $registerURL = '<a class="nav-link active" href="index.php?action=register">Register</a>';
        $adminURL = '<a class="nav-link active" href="index.php?action=admin">Admin</a>';
        

        if (isset($_SESSION["db_admin"])) {
            $userInfoANDlogout_button = "<i class=\"fas fa-user\"></i>" . $_SESSION['db_email'] . "<a class=\"nav-link\" href=\"index.php?action=logout\">" . file_get_contents("view/backend/button_logout.html") . "</a>";

            // IF USER IS ADMIN
            if ($_SESSION["db_admin"] == "1") {
                $view = str_replace("{LOGIN}", "", $view);
                $view = str_replace("{REGISTER}", "", $view);
                $view = str_replace("{ADMIN}", $adminURL, $view);
                $view = str_replace("{USER_INFO}", $userInfoANDlogout_button, $view);
            }

            // IF USER IS NOT ADMIN
            if ($_SESSION["db_admin"] == "0") {
                $view = str_replace("{LOGIN}", "", $view);
                $view = str_replace("{REGISTER}", "", $view);
                $view = str_replace("{ADMIN}", "", $view);
                $view = str_replace("{USER_INFO}", $userInfoANDlogout_button, $view);
            }

        } else {
            // IF USER IS NOT LOGED IN
            $view = str_replace("{LOGIN}", $loginURL, $view);
            $view = str_replace("{REGISTER}", $registerURL, $view);
            $view = str_replace("{ADMIN}", "", $view);
            $view = str_replace("{USER_INFO}", "", $view);
        }

        return $view;

    }

    /*
public function ApplySession($view)
{

// $_SESSION['db_email'] = "ruivo.rui@gmail.com";
// $_SESSION['db_email'] = null;
if (isset($_SESSION['db_email'])) {
$logout_button = "<a class=\"nav-link\" href=\"index.php?action=user_info\"><i class=\"fas fa-user\"></i></a>" . "<a class=\"nav-link\" href=\"index.php?action=user_info\">" . $_SESSION['db_email'] . "</a>" . "<a class=\"nav-link\" href=\"index.php?action=contact\">" . file_get_contents("view/backend/button_logout.html") . "</a>";
$view = str_replace("{USER_BLOC}", $logout_button, $view);
// IF USER IS ADMIN SHOW ADMIN LINK
if (isset($_SESSION['db_admin']) && $_SESSION['db_admin'] == 1) {
$admin_link = "<a class=\"nav-link\" href=\"index.php?action=admin\">Admin</a>";
$view = str_replace("{ADMIN}", $admin_link, $view);
} else {
$admin_link = "";
$view = str_replace("{ADMIN}", $admin_link, $view);
}
} else {
$view = str_replace("{USER_BLOC}", file_get_contents("view/nav_user_option.html"), $view);
$admin_link = "";
$view = str_replace("{ADMIN}", $admin_link, $view);
}
// session_destroy();

return $view;
}
 */

}
