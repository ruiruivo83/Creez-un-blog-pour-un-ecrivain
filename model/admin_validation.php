<?php

if (isset($_POST['mot_de_passe']) and $_POST['mot_de_passe'] == "pass") {

  // index.php?action=admin
  header('Location: ../index.php?action=acces_admin');

  // ADD SESSION TOCKEN FOR THIS IP AND 1 min timer
  // TODO

} else {

   // index.php
   header('Location: ../index.php');
  
}
