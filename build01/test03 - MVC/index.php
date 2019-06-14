<?php

$view = file_get_contents("index_vue.html");
$article_view = file_get_contents("index_article.html");

$bloc_article = "";
for ($i=0; $i < 10 ; $i++) { 
  $bloc_article .= $article_view;
}

$view = str_replace("{LIST_ARTICLE}", $bloc_article, $view);

$date_article = "10/06/2019";

$view = str_replace("{DATE_ARTICLE}", $date_article, $view);

echo $view;