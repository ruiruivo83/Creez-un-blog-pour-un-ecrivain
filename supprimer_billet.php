<?php

include 'config.php';

$bdd = new PDO('mysql:host=localhost;dbname='.$Database_Name, $Database_User, $Database_Password);
$req = $bdd->prepare('DELETE FROM billets WHERE id = :id');
$req->bindParam(':id', $_GET['id']);

$req->execute();

$url = "index.php";
header("Location: $url");
