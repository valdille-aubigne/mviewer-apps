<?php // Connection à la base de données
header("Content-Type: text/plain");
try {
		$dbconn=new PDO("pgsql:host=localhost;port=5432;dbname=xxxxxx","xxxxxx","xxxxxxxxxx") or die('Connexion impossible');
		$dbconn->exec("SET CHARACTER SET utf8");
		$dbconn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
// S'il existe un problème de connection, on obtient le message d'erreur
} catch(PDOException $erreur) {
		$erreur->getMessage(); // Supprimer en production
		echo 'erreur';
}
if($dbconn){
	$sqlCount="SELECT idkey FROM environnement.observation_faune_flore";
	$reqCount=$dbconn->prepare($sqlCount);
	$reqCount->execute();
	$nbObjet=$reqCount->rowCount();
	if($nbObjet>0){
	// Exécution de la requête SQL GeoJSON
	$sqlGeoJSON="SELECT json_build_object(
		'type', 'FeatureCollection',
		'crs',  json_build_object(
			'type',      'name',
			'properties', json_build_object(
			'name', 'EPSG:2154')),
		'features', json_agg(
			json_build_object(
				'type',       'Feature',
				'id',         idkey,
				'geometry',   ST_AsGeoJSON(the_geom)::json,
				'properties', json_build_object(
					'nom', nom,
					'categories', categories,
					'description', description,
					'date_obs', date_obs,
					'picture', picture
				)
			)
		)
	) AS objet_geosjon FROM environnement.observation_faune_flore;";
	$reqGeoJSON=$dbconn->prepare($sqlGeoJSON);
	$reqGeoJSON->execute();
	$dataGeoJSON=$reqGeoJSON->fetch();
	if($dataGeoJSON){
		$objetGeoJSON=$dataGeoJSON['objet_geosjon'];
//			echo $objetGeoJSON;

		// Ouverture du fichier en écriture
		$fichier = fopen("observations.geojson", "w") or die("Problème d\'ouverture de fichier");
		// Ecriture du résultat de la requête
		if(fwrite($fichier, $objetGeoJSON)){
			echo "Le fichier GeoJSON a été construit correctement!";
		}else{
			echo "Un problème s\'est déroulé lors de l\'écriture du fichier.";
		}
		// Fermeture du fichier
		fclose($fichier);

	}else{
		echo 'erreur';
	}
}else{
		echo 'NaN';
	}
}
?>
