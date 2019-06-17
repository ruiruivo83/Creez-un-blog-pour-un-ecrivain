<?php

$bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
$req = $bdd->prepare('DELETE FROM billets WHERE id = :id');
$req->bindParam(':id', $_GET['id']);


$req->execute();

$url = "index.php";
header("Location: $url");

?>