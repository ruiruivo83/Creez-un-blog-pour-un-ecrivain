<?php

// 1
// Premier appel - Charge en memoire le fichier index avec la vue principale
$view = file_get_contents("index_vue.html");

// 2
// IMPORT des fichiers secondaires necessaires pour toute la page a reproduire
$article_view = file_get_contents("index_article.html");

// 2.1 PREPARE LE CODE POUR LA VUE
// String Code pour représenter le code final.
$bloc_article = "";
for ($i = 0; $i<10; $i++) {
    $bloc_article .= $article_view; // comme +=
}
$view = str_replace("{LIST_ARTICLE}", $bloc_article, $view);

// 2.2 Done d'autres valeurs a d'aurtes variables necessaires
// NEW ASSIGN CURRENT DATE TO VARIABLE DATE
$date_article = "10/06/2019";



// 3 
// ORDONE LE REMPLACEMENT DANS LE CODE HTML DE LA STRING {DATE_ARTICLE} PAR TOUTES LES STRINGS NECESSAIRES
$view = str_replace("{DATE_ARTICLE}", $date_article, $view);

echo $view;