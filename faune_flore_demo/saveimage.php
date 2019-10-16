
<?php
if(isset($_FILES['image']))
{ 	 $dossier = "images/";
     $fichier = basename($_FILES['image']['name']);
     if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
          echo 'Upload effectué avec succès !';
     }
     else //Sinon (la fonction renvoie FALSE).
     {
          echo 'Echec de l\'upload !';
     }
}
$ext = new SplFileInfo($fichier);
var_dump($ext->getExtension());

?>