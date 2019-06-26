<?php




function update_post()
{

    require "../config.php";

    // BOUTON - VALIDER - TO DATABASE


    // var_dump($_SERVER['REQUEST_METHOD']);


    if ($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST["update_post"])) {
        
        // echo "INSIDE";

        $Titre = $_POST["titre"];

        $Contenu = $_POST["contenu"];
        // $Contenu = "test";

        $id = $_POST['id'];

        $bdd = new PDO('mysql:host=localhost;dbname=' . $Database_Name, $Database_User, $Database_Password);
        $req = $bdd->prepare("UPDATE billets SET titre=? , contenu=? WHERE id=?");

        // $req = $bdd->prepare("UPDATE INTO billets(titre, contenu, date_creation) values (?, ?, NOW())");
        $req->execute(array($Titre, $Contenu, $id));

        // var_dump($bdd->errorInfo());
        // var_dump($req->errorInfo());

    }

   header('Location: ../index.php');

    
    exit();
}



update_post();
