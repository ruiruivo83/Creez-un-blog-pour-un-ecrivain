<?php








// DEFINE DATABASE PDO
try {
  $bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '');
} catch (Exception $e) {
  die('Erreur : ' . $e->getMessage());
}











// INSERT TO DATABASE
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









/*
// TEST01 FOR DATABASE
// NEW CONNECTION STRING
$conn = new mysqli("localhost", "root", "");

// Set the default database:
$conn->select_db("blog");

// Verify Database Connection
if ($conn->connect_error > 0) {
  trigger_error($db->connect_error);
} // else: successfully connected


$sql = "SELECT * FROM billets";


$result = array();
$row = "";

while ($row = $sql->fetch_array()) {
  var_dump($row);
}

while ($row = $result->fetch_array()) {
  echo 'test : ' . $row[0];
}
*/


// TEST02 FOR DATABASE MYSQLI
/*
$view = file_get_contents(("index_vue.html"));
$billets = file_get_contents("index_billets_content.html");


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM billets";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  $bloc_billet = "";



  while ($row = $result->fetch_assoc()) {
    // var_dump($row);

    $current_billet = $billets;
    $current_billet = str_replace("{DATE_BILLET}", $row["date_creation"], $current_billet);
    $current_billet = str_replace("{TITRE_BILLET}", $row["titre"], $current_billet);
    $current_billet = str_replace("{CONTENU_BILLET}", $row["contenu"], $current_billet);
    
    $bloc_billet .= $current_billet;

    // echo "billet: " .  $ row["id"]."<br>";
  }
} else {
  echo "0 results";
}
$conn->close();
*/



// TEST03 DATABASE CONNETION - PDO

$view = file_get_contents(("index_vue.html"));
$billets = file_get_contents("index_billets_content.html");

try {
  $bdd = new PDO('mysql:host=localhost;dbname=blog', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
} catch (Exception $e) {
  die('Erreur : ' . $e->getMessage());
}

$req = $bdd->prepare("SELECT * FROM billets ORDER BY date_creation DESC");

$req->execute();

// var_dump($req->fetchAll());

$result = $req->fetchAll();
$bloc_billet = "";
foreach ($result as $current_result) {
  $current_billet = $billets;
  $current_billet = str_replace("{DATE_BILLET}", $current_result["date_creation"], $current_billet);
  $current_billet = str_replace("{TITRE_BILLET}", $current_result["titre"], $current_billet);
  $current_billet = str_replace("{CONTENU_BILLET}", $current_result["contenu"], $current_billet);

  $bloc_billet .= $current_billet;
  // var_dump($current_result["titre"]);
  // var_dump($bloc_billet);
}








$view = str_replace("{LIST_BILLETS}", $bloc_billet, $view);






/* $req->execute(array($Titre, $Contenu)); */


echo $view;
