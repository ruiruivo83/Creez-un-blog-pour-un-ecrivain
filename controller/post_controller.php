<!-- CONTROLLER -->


<?php

// IMPORT

require "model/fill_list_posts.php";
require "model/edit_post.php";
require "model/delete_post.php";
require "model/add_post.php";
require "model/acces_admin.php";


function list_posts()
{

    $view = file_get_contents("view/_layout.html");

    $view = str_replace("{CONTENT}", fill_list_posts($view), $view);

    echo $view;

}

function edit_post($id)
{

    $view = file_get_contents("view/_layout.html");

    $view = str_replace("{CONTENT}", edit_post_model($id), $view);

    echo $view;

}

function delete_post($id)
{

    delete_post_model($id);

}

function add_post()
{

  $view = file_get_contents("view/_layout.html");

  $view = str_replace("{CONTENT}", add_post_model(), $view);

  echo $view;

}

function admin()
{

  $view = file_get_contents("view/_layout.html");

  $view = str_replace("{CONTENT}", acces_admin_model(), $view);

  echo $view;

}