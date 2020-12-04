<?php
header("Content-Type: text/plain");
$comment = (isset($_POST["comment"])) ? $_POST["comment"] : NULL;
$coord_x = (isset($_POST["coord_x"])) ? $_POST["coord_x"] : NULL;
$coord_y = (isset($_POST["coord_y"])) ? $_POST["coord_y"] : NULL;
$date_observation = (isset($_POST["date_observation"])) ? $_POST["date_observation"] : NULL;
$categorie = (isset($_POST["categorie"])) ? $_POST["categorie"] : NULL;
$mail = (isset($_POST["mail"])) ? $_POST["mail"] : NULL;
$imagename = (isset($_POST["imagename"])) ? $_POST["imagename"] : NULL;
$nom = (isset($_POST["nom"])) ? $_POST["nom"] : NULL;
$espece = (isset($_POST["espece"])) ? $_POST["espece"] : NULL;

echo $imagename ;
if ($imagename=='undefined') {
	$imagename='';
	} else {
	$imagename='https://geo.valdille-aubigne.fr/mviewer/apps/faune_flore/images/'.$imagename ;
	}

$dbmdp = "xxxxxxxx";
$dbuser = "xxxxxxx";
$dbhost = "xxxxxxxx";
$dbname = "xxxxxx";
$conn_string = "host=".$dbhost." port=5432 dbname=".$dbname." user=".$dbuser." password=".$dbmdp;
$pgsql_conn = pg_connect($conn_string);

if ($pgsql_conn) {
    echo "Successfully connected to database: " . pg_dbname($pgsql_conn) .
     " on " .  pg_host($pgsql_conn) . "</br>\n";
} else {
    echo pg_last_error($pgsql_conn);
    exit;
}

$query = "INSERT INTO environnement.faune_flore(nom, categories, description, date_obs, picture, submiter_n, submiter_e, etat, the_geom)
				VALUES('".$espece."', '".$categorie."', '".$comment."', '".$date_observation."', '".$imagename."', '".$nom."', '".$mail."', 'S', ST_Transform(ST_SetSRID(ST_MakePoint(".$coord_x.",".$coord_y."),4326),2154))";
if (pg_query($pgsql_conn,$query)){
    echo "saved";
} else {
    echo "error insering data";
   echo pg_last_error();
}

//Envoi mail
	   // Destinataires
     $to  = 'camille.jamet@valdille-aubigne.fr; joseph.villiermet@valdille-aubigne.fr'; //  virgule si plusieurs
     // Sujet
     $subject = 'Nouvelle observation faune - flore';
     // message
     $message = '
     <html>
      <head>
       <title>Nouvelle observation faune - flore</title>
      </head>
      <body>
       <p>
			 		Bonjour, <br/>
					Une nouvelle observation a été déposée sur l\'inventaire faune - flore ! <br/><br/>
			 		Catégorie : '.$categorie.'<br/>
					Espèce : '.$espece.'<br/>
					Date d\'observation : '.$date_observation.'<br/>
					Commentaire : '.$comment.'<br/><br/>
					Nom : '.$nom.'<br/>
					Mail :'.$mail.'<br/><br/>
			<img src="'.$imagename.'" height="200">
			</p>
      </body>
     </html>
     ';

     // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
     $headers[] = 'MIME-Version: 1.0';
     $headers[] = 'Content-type: text/html; charset=UTF-8';

     // En-têtes additionnels
     $headers[] = 'From: ne-pas-repondre@valdille-aubigne.fr';
     $headers[] = 'Cc: pascal.barille@valdille-aubigne.fr; tanguy.jacq@valdille-aubigne.fr';
    // $headers[] = 'Bcc: anniversaire_verif@example.com';

     // Envoi
     mail($to, $subject, $message, implode("\r\n", $headers));






//mail("pascal.barille@valdille-aubigne.fr", "Nouvelle observation faune-flore",
	//		"Catégorie : ".$categorie."\n Espèce : ".$espece."\n Date d''observation : ".$date_observation."\n Commentaire : ".$comment."\n\n Nom : ".$nom."\n Mail :".$mail);

// if ($comment)
//{
	//$fd = fopen("observations.csv", "a");
		//if ($fd !== false)
		//{
			// $form_data = array(
			  //   'comment' => $comment,
				  //'coord_x' => $coord_x,
				  //'coord_y' => $coord_y,
				  //'date_observation' => $date_observation,
				  //'categorie'=>$categorie,
				  //'mail'=>$mail,
				  //'imagename'=>$imagename,
				  //'nom'=>$nom,
				  //'espece'=>$espece
			  //);
			  //fputcsv($fd, $form_data);
		//}
		//else
			//echo "bug";
	//echo "OK";
//}
// else
// {
	// echo "FAIL";
// }

?>
