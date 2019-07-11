<?php

session_start();

// NOT NECESSARY
/*
$_SESSION['db_email'] = null;
$_SESSION['db_prenom'] = null;
$_SESSION['db_nom'] = null;
$_SESSION['db_date_creation'] = null;
*/


session_destroy();

header('Location: ../index.php');
