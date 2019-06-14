<!-- 1 - CONTROLLER -->
<?php

// IMPORT MAIN HTML FOR INDEX
$view = file_get_contents("index.html");

// IMPORT HTML CODE FOR ARTICLE REPRESENTATION
$article_view = file_get_contents("index_article_content.html");

// LIST_ARTICLE
$bloc_article = "";
for ($i=0; $i < 10 ; $i++) { 
  $bloc_article .= $article_view;  
}
$view = str_replace("{LIST_ARTICLE}", $bloc_article, $view);

// NEW VARIABLE - Date
$date_article = "10/06/2019";

// REMPLACE DANS LE CODE HTML LA STRING PAR LA DATE EN QUESTION
$view = str_replace("{DATE_ARTICLE}", $date_article, $view);

// SEND FINAL PAGE TO BROWSER
echo $view;
