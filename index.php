<!DOCTYPE html>
<html>

<head>

  <meta charset="UTF-8">
  <link href="css/style.css" rel="stylesheet">
  <title>Mon Blog</title>

</head>

<body>

  <h1>Mon super blog !</h1>
  <p>Derniers billets du blog</p>

  <?php

  try {
    $bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
  } catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
  }


  // On récupère les 5 derniers billets
  $req = $bdd->query('SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%y à %Hh%imin%ss\') AS date_creation_fr FROM billets ORDER BY date_creation DESC LIMIT 0, 5');

  // GET DATA FROM THE $req vqriqble from database
  while ($donnees = $req->fetch()) {

  ?>

    <div class="news">
      <h3>
        <?php echo $donnees['date_creation_fr']; ?>
        <em>le <?php echo $donnees['date_creation_fr']; ?></em>
      </h3>

      <p>
        <?php
        // On affiche le contenu du billet
        echo nl2br(htmlspecialchars($donnees['contenu']));
        ?>
        <br />
        <em><a href="commentaires.php?billet=<?php echo $donnees['id']; ?>">commentaires</a></em>
        <div>Bouton Supprimer</div>
      </p>
    </div>


  <?php 

} $req->closeCursor();
?>




</body>

</html>