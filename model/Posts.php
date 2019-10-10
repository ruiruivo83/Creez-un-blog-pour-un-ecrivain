<?php

require 'model/Comments.php';

class Posts
{

    private $bdd;
    private $id;
    private $Titre;
    private $Contenu;


    public function __construct($id, $Titre, $Contenu)
    {
        $this->setId($id);
        $this->setContenu($Contenu);
        $this->setTitre($Titre);
    }


    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $Titre
     */
    public function setTitre($Titre)
    {
        $this->Titre = $Titre;
    }

    /**
     * @param mixed $Contenu
     */
    public function setContenu($Contenu)
    {
        $this->Contenu = $Contenu;
    }

    // GET ALL POSTS FROM DATABASE, ORDER BY DESC DATE
    public function getPosts()
    {
        $bdd = Database::getBdd();
        // PREPARE QUERY - utilise prepare pour les accents sur les lettres
        $req = $bdd->prepare("SELECT * FROM billets ORDER BY date_creation DESC");
        $req->execute();
        $result = $req->fetchall();
        return $result;
    }

    // ADD POSTS TO DATABASE
    public function addPost($Titre, $Contenu)
    {
        $bdd = Database::getBdd();
        $req = $bdd->prepare("INSERT INTO billets(titre, contenu, date_creation) values (?, ?, NOW()) ");
        $req->execute(array($Titre, $Contenu));
    }

    // EDIT POST
    public function edit_post_query($id)
    {
        $bdd = Database::getBdd();
        $req = $bdd->prepare('SELECT titre, contenu FROM billets WHERE id = :id');
        $req->bindParam(':id', $id);
        $req->execute();
        $result = $req->fetchall();
        return $result;
    }

    // UPDATE POST
    public function update()
    {
        $bdd = Database::getBdd();
        $req = $bdd->prepare("UPDATE billets SET titre=? , contenu=? WHERE id=?");
        $req->execute(array($this->Titre, $this->Contenu, $this->id));
    }

    // DELETE POST
    public function delete()
    {
        $bdd = Database::getBdd();
        // DELETE POST ID
        $req = $bdd->prepare('DELETE FROM billets WHERE id = :id');
        $req->bindParam(':id', $_GET['id']);
        $req->execute();
        // DELETE COMMENTS FOR POST ID
        $req = $this->bdd->prepare('DELETE FROM comments WHERE post_id = :id');
        $req->bindParam(':id', $_GET['id']);
        $req->execute();
    }

}