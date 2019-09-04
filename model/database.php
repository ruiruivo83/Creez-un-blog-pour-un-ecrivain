<!-- NECESSAIRE POUR LA SOUTENANCE -->


<?php

include "config.php";


// CLASSE DATABASE
class database
{



    // OBJET CONNETION
    function connection()
    {
        $Config = new config();
        // GET BILLETS FROM DATABASE
        try {
            $bdd = new PDO('mysql:host=localhost; dbname=' . $Config->Database_Name, $Config->Database_User, $Config->Database_Password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        return $bdd;
    }











}
