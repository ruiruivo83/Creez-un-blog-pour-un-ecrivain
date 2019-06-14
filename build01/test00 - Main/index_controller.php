<?php

// 1 - DECLARE et Charge en memoire la vue principale et tout les fichiers necessaires au remplissage de la page.
$view = file_get_contents("index_vue.html");
$article_view = file_get_contents("index_article.html");

// 2.1 - String Code pour représenter la liste des articles a présenter.
$bloc_article = ""; // String vide a remplir
for ($i = 0; $i<10; $i++) { // boucle pour remplir la string de code necessaire a présenter
    $bloc_article .= $article_view; // remplissage a chaque boucle
}

// ASSIGNE -> REMPLACE {LIST_ARTICLE} PAR LE CODE $bloc_article dans la $view
$view = str_replace("{LIST_ARTICLE}", $bloc_article, $view);

// NOUVELLE STRING POUR LA DATE
$date_article = "10/06/2019";

// REMPLACE DATE_ARTICLE par $date_article dans la $view
$view = str_replace("{DATE_ARTICLE}", $date_article, $view);

// AFICHE LA VUE FINAL AVEC LE CODE
echo $view;