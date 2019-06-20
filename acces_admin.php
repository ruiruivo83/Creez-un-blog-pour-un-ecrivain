<?php

require 'config.php';

$view = file_get_contents(("acces_admin.html"));

//// REMPLACE {HTML_DEFAULT_START} BY CODE
$html_default_start = file_get_contents("html_default_start.html");
$view = str_replace("{HTML_DEFAULT_START}", $html_default_start, $view);

//// REMPLACE {HTML_DEFAULT_END} BY CODE
$html_default_end = file_get_contents("html_default_end.html");
$view = str_replace("{HTML_DEFAULT_END}", $html_default_end, $view);
























// MONTRE TOUT LE CODE DE LA PAGE
echo $view;
