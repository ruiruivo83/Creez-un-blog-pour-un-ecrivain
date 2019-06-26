<?php

function add_post_model()
{
    $add_post_default_code = file_get_contents("view/add_post_default_code.html");
    return $add_post_default_code;
}
