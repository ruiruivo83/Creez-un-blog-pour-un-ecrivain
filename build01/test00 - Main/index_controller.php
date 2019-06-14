<?php
// Premier appel au fichier index avec la vue principale
$view = file_get_contents("index_vue.html");

// IMPORT des fichiers secondaires necessaires pour toute la page a reproduire
$article_view = file_get_contents("index_article.html");

// Code pour représenter le code final.
$bloc_article = "";
for ($i = 0; $i<10; $i++) {
    $bloc_article .= $article_view; // comme +=
}
$view = str_replace("{LIST_ARTICLE}", $bloc_article, $view);

// NEW ASSIGN SATE TO VARIABLE DATE
$date_article = "10/06/2019";

// REMPLACE DANS LE CODE HTML LA STRING PAR LA DATE EN QUESTION
$view = str_replace("{DATE_ARTICLE}", $date_article, $view);


echo $view;