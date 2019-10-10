<?php

class Comments
{

    public function add_comment($Valide, $Signale, $UserName, $Comment, $Post_ID)
    {
        $bdd = Database::getBdd();
        $req = $bdd->prepare("INSERT INTO comments(valide, signale, username, contenu, post_id, date_creation) values (?, ?, ?, ?, ?, NOW()) ");
        $req->execute(array($Valide, $Signale, $UserName, $Comment, $Post_ID));
    }


    public function GetComments($post_id)
    {
        $bdd = Database::getBdd();
        $req = $bdd->prepare("SELECT * FROM comments WHERE post_id = '$post_id' AND valide = 1 AND signale = 0 ORDER BY date_creation DESC");
        $req->execute();
        $result = $req->fetchall();
        return $result;
    }

    public function Get_All_Non_Validated()
    {
        $bdd = Database::getBdd();
        $req = $bdd->prepare("SELECT * FROM comments WHERE valide = 0 OR signale = 1 ORDER BY date_creation DESC");
        $req->execute();
        $result = $req->fetchall();
        return $result;
    }

    public function ApplyUrgent($id)
    {
        $bdd = Database::getBdd();
        $req = $bdd->prepare("UPDATE comments SET date_creation = (NOW()) WHERE id = ?");
        $req->execute(array($id));
    }

    public function getNonValidatedComments($post_id, $email)
    {
        $bdd = Database::getBdd();
        $req = $bdd->prepare("SELECT * FROM comments WHERE post_id = '$post_id' AND valide = 0 AND username = '$email' ORDER BY date_creation DESC");
        $req->execute();
        $result = $req->fetchall();
        return $result;
    }

    public function validate_comment($id)
    {
        $bdd = Database::getBdd();
        $req = $bdd->prepare("UPDATE comments SET valide=1, signale=0 WHERE id=?");
        $req->execute(array($id));
    }

    public function delete_comment()
    {
        $bdd = Database::getBdd();
        $req = $bdd->prepare('DELETE FROM comments WHERE id = :id');
        $req->bindParam(':id', $_GET['id']);
        $req->execute();
    }

    public function signale($id)
    {
        $bdd = Database::getBdd();
        $req = $bdd->prepare("UPDATE comments SET signale=1 WHERE id=?");
        $req->execute(array($id));
    }

    public function getAuthorForThisCommentId($id)
    {
        $bdd = Database::getBdd();
        $req = $bdd->prepare("SELECT username FROM comments WHERE id = :id");
        $req->bindParam(':id', $id);
        $req->execute();
        $result = $req->fetchall();
        return $result;
    }

}
