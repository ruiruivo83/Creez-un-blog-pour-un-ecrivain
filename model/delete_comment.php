<?php

function delete_comment_model($id)
{

    include 'config.php';

    $bdd = new PDO('mysql:host=localhost;dbname=' . $Database_Name, $Database_User, $Database_Password);
    $req = $bdd->prepare('DELETE FROM comments WHERE id = :id');
    $req->bindParam(':id', $_GET['id']);

    $req->execute();

    header("Location: index.php?action=admin");

}
