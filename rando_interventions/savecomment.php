<?php
header("Content-Type: text/plain");
$comment = (isset($_POST["comment"])) ? $_POST["comment"] : NULL;
$coord_x = (isset($_POST["coord_x"])) ? $_POST["coord_x"] : NULL;
$coord_y = (isset($_POST["coord_y"])) ? $_POST["coord_y"] : NULL;
$date_observation = (isset($_POST["date_observation"])) ? $_POST["date_observation"] : NULL;
$mail = (isset($_POST["mail"])) ? $_POST["mail"] : NULL;
$imagename = (isset($_POST["imagename"])) ? $_POST["imagename"] : NULL;
$nom = (isset($_POST["nom"])) ? $_POST["nom"] : NULL;

// if ($comment)
{
	$fd = fopen("interventions.csv", "a");
		if ($fd !== false)
		{
			 $form_data = array(
			     'comment' => $comment,
				  'coord_x' => $coord_x,
				  'coord_y' => $coord_y,
				  'date_observation' => $date_observation,
				  'mail'=>$mail,
				  'imagename'=>$imagename,
				  'nom'=>$nom
			  );
			  fputcsv($fd, $form_data);
		}
		else
			echo "bug";
	echo "OK";
}
// else
// {
	// echo "FAIL";
// }

?>
