<!DOCTYPE html>
<html>

<head>

  <meta charset="UTF-8">
  <link href="css/style.css" rel="stylesheet">
  <title>Mon Blog</title>

</head>

<body>

  <h1>Ajouter un billet</h1>


  <?php

  try {
    // DEFINIR LA BASE DE DONNEE
    $bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
  } catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
  }

  // Code executer si non validon le formulaire avec le bontou
  if (isset($_POST["Titre"])) {
    $Titre =  $_POST["Titre"];
    $Contenu = $_POST["Contenu"];
    // echo $Titre;

    // SQL QUERY TO INSERT INTO DATABASE - CETTE METHODE N'AIME pas les guimets simple
    // $req = $bdd->query("INSERT INTO billets(titre, contenu, date_creation) values ('".$Titre."', '".$Contenu."', NOW())");

    // CORRECTION ERREUR GUIMET - Prepare la requette tout seule
    $req = $bdd->prepare("INSERT INTO billets(titre, contenu, date_creation) values (?, ?, NOW())");
    $req->execute(array($Titre, $Contenu));
  }



  // On récupère les 5 derniers billets
  // $req = $bdd->query('SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%y à %Hh%imin%ss\') AS date_creation_fr FROM billets ORDER BY date_creation DESC LIMIT 0, 5');

  ?>

  <form action="" method="post" class="form-example">

    <div class="form-example">
      <label for="name">Titre du billet: </label>
      <input type="text" name="Titre" id="Titre" required>
    </div>

    <div class="form-example">
      <label for="name">Contenu: </label>
      <input type="text" name="Contenu" id="Contenu" required>
    </div>
    
    <div class="form-example">
      <input type="submit" value="Valider" >
    </div>
  </form>




</body>

</html>