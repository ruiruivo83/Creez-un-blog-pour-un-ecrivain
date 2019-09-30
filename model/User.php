<?php

require 'model/Database.php';

class User
{

    // Variables en Private pour ne pas les modifier depuis l'exterieur de la class, seulement le "getter" peux les lir depuis l'exterieur.
    private $id;
    private $psw;
    private $email;
    private $nom;
    private $prenom;
    private $admin;


    public function __construct($id, $psw, $email, $nom, $prenom, $admin = false)
    {
        $this->id = $id;
        $this->psw = $psw;
        $this->email = $email;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->admin = $admin;
    }

    /**
     * @return mixed
     */
    public function getPsw()
    {
        return $this->psw;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->admin;
    }


    public static function findByEmail($email)
    {
        $bdd = Database::getBdd();

        // GET DATA FROM DATABASE

        $req = $bdd->prepare("SELECT * FROM users WHERE email =  ?   ");
        $req->execute(array($email));
        $numresult = $req->rowCount();
        if ($numresult > 0) {
            # code...
            $result = $req->fetch();
            return new User(
                (int)$result['id'],
                $result['psw'],
                $result['email'],
                $result['nom'],
                $result['prenom'],
                ((int)$result['admin']) == 1
            );
        } else {
            return null;
        }

        $result = $req->fetchall();
        return $result;
    }





    public function addUser()
    {
        $bdd = Database::getBdd();

        $req = $bdd->prepare("INSERT INTO users(prenom, nom, email, psw, date_creation ) values (?, ?, ?, ?, NOW()) ");
        $req->execute(array($this->prenom, $this->nom, $this->email, $this->psw));

    }




}