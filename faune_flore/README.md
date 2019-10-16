MVIEWER Participatif
=============

Visualiseur géographique permettant la remontée d'informations basé sur le mviewer de GéoBretagne (https://github.com/geobretagne/mviewer)

Démo signalement anomalies sur sentiers de randonnée :
	- url test :
	- code source :
	
Démo inventaire participatif faune flore :
	- url test :
	- code source :
	
Déploiement
=============

Etape 1
-----------

Cloner le répertoire d'une application "participative" existante dans le dossier "apps"

Etape 2
-----------

Renommer / paramétrer le fichier config.xml (ici faune_flore_demo.xml)

### Personnaliser l'entête d'applciation


	<application title="Inventaire participatif faunistique et floristique (demo)"  
	showhelp="false"  
	logo="./apps/logo-via.png"  
	help="./apps/faune_flore_demo/mviewer_help_ff.html"  
	exportpng="false"  
	measuretools="true"  
	togglealllayersfromtheme="true"  
	style="css/themes/green.css"/>
 
 ### Personnaliser la couche "participative"
 
 
 #### Notamment les champs id / url / customcontrolpath
 
 
	  <layer id="obs_faune_flore_demo" name="Observations" visible="true" queryable="false"  
				type="customlayer" opacity="0.8" legendurl="img/blank.gif"  
				url="apps/faune_flore_demo/customlayers/obs_faune_flore_demo.js"  
				customcontrol="true"  
				customcontrolpath="apps/faune_flore_demo/customcontrols"  
				expanded="true"           
				attribution="Source: Val d'Ille-Aubigné"  
				metadata=""  
				metadata-csw="">  
	   </layer>
   
   
Etape 3
-----------

Renommer / paramétrer le fichier customcontrols/xxx.html (ici obs_faune_flore_demo.xml)


### Personnaliser les champs du formulaire

	<form id="formu">

		<div class="row">
			<div class="col-md-12 col-md-offset-0">
				<select id="categorie" class="form-control" >
				<option value="none" selected disabled>--Choisir une catégorie--</option>
				<optgroup label="Faune">
				<option value="Papillons - lépidoptères">Papillons - lépidoptères</option>
				<option value ="Abeillesmphibiens">Amphibiens</option>
				<option value ="Reptiles">Reptiles</option>
				<option value="Chauve souris - chiroptères">Chauve souris - chiroptères</option>
				<option value = "Libellules - odonates">Libellules - odonates</option>
				<option value = "Mammifères">Mammifères</option>
				<option value = "Abeilles - bourdons - frelons">Abeilles - bourdons - frelons</option>
				<option value = "Oiseau">Oiseau</option></option>
				<option value = "Araignées">Araignées</option>
				</optgroup>
				<optgroup label="Flore">
				<option value="Abres">Arbres</option>
				<option value ="Arbustes">Arbustes</option>
				<option value ="Plantes herbacées">Plantes herbacées</option>
				<option value="Plantes invasives"> Plantes invasives</option>
				<option value = "Lichens"> Lichens </option>
				<option value = "Mousses"> Mousses </option>
				<option value = "Graminées"> Graminées </option>
				<option value = "Fougères"> Fougères </option>
				<option value = "Autres plantes"> Autres plantes </option>
				</optgroup>
				</select>
			</div>
		</div>
		
		<br />
		
		<div class="form-group">
				<input id="espece" class="form-control" placeholder="Espèce..."></input>			
		</div>
		
		<div class="input-group date" data-provide="datepicker" data-date-end-date="0d" data-date-language="fr" data-date-format="dd/mm/yyyy" data-date-autoclose="true" data-date-today-btn="linked" data-date-today-highlight="true">
			<input type="text" class="form-control" id="date_observation" placeholder="Date d'observation...">
			<div class="input-group-addon">
				<span class="glyphicon glyphicon-calendar"></span>
			</div>
		</div>
		
		

		<br/>
		
		
		
		<div class="form-group">
				<textarea id="comment" class="form-control" rows="3" placeholder="Commentaires..."></textarea>
				
		</div>
		
		<!-- </br> -->
		<div class="form-group">
			<input id="nom" class="form-control" placeholder="Votre nom..."></input>
		</div>	
		
		<div class="form-group">
			<input type="email" class="form-control" id="mail" placeholder="Votre mail (nom@example.fr)...">
		</div>
		
	   <div class="dropzone dz-clickable" id="depose" onclick="getElementById('image').click();">
					<div id="casedepart" class="start">
						<div class="dz-default dz-message" >
						<span id="filename" class="fas fa-cloud-upload-alt fa-3x"></span><p>Cliquer pour sélectionner un fichier...</p><br/>
						</div>
					</div>
					<div id="casearrive" style="display:none">
					<img class="img-responsive" id="preview" src="#" alt="monimage" style="visibility:collapse"/>
					</div>
					<input type="file" id="image" name="image" class="file-upload" style="visibility:collapse" onchange="mviewer.customControls.obs_faune_flore_demo.getFileName();" accept="image/*">
		</div>
	<br/>	
	</form>

	<div align="justify">
	 <button class="btn btn-default" onclick="mviewer.customControls.obs_faune_flore_demo.getXY();">Lieu d'observation
			<span class="glyphicon glyphicon-flag" aria-hidden="true"></span>
	 </button>
	 
	 <button class="btn btn-default" onclick="mviewer.customControls.obs_faune_flore_demo.applyEvent();">Valider
			<span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
	 </button>
	 
	 </div>


Vous trouverez dans l'exemple des champs listes / mail / date / image / texte

#### Ne pas oublier de renommer tous les appels de fonctions javascript présents dans le formulaire ("mviewer.customControls.obs_faune_flore_demo.getFileName();" par exemple)


Etape 4
-----------
 Renommer / paramétrer le javascript (customcontrols/xxxx.js) pour mise en cohérence avec le fichier html
 
 #### Modifier la fonction applyEvent pour mise en cohérence avec les champs présents dans le formulaire :
 
	 applyEvent: function ()
			 {
			var newFile='';	
			var categorie = document.getElementById('categorie');
			var espece = document.getElementById('espece');
			var date_observation = document.getElementById('date_observation');
			var text = document.getElementById('comment');
			var nom = document.getElementById('nom');
			var mail = document.getElementById('mail');
			var image = document.getElementById("image").files[0];
			var erreur = "Merci de bien vouloir : <br/>" ;
			
			
			if (categorie.value=='none')
			erreur = erreur + "- Choisir une catégorie<br/>";
			
			if (espece.value=='')
			erreur = erreur + "- Remplir le champ espèce<br/>";
		
			if (date_observation.value=='')
			erreur = erreur + "- Renseigner une date<br/>";
			
			// if (text.value=='')
			// erreur = erreur + "- Remplir le champ commentaire<br/>";
		
			if (image != undefined)
				{
					var extension = image.name.split('.').pop();
					extension = extension.toLowerCase();

					var blob = image.slice(0,image.size, 'image/'+extension);

					var date_saisie = new Date();
					date_saisie = date_saisie.getTime();
					//definir le nom avec la date en place de name
					newFile= new File([blob], 'photo_'+date_saisie+'.'+extension, {type: 'image/'+extension});
				}
				
			if (_xy==undefined)
			erreur = erreur + "- Définir un lieu<br/>";
			
			if (erreur=="Merci de bien vouloir : <br/>")
			{mviewer.customControls.obs_faune_flore.send_comment(text.value,_xy[0],_xy[1],date_observation.value,categorie.value,mail.value,newFile, nom.value, espece.value);}
			else
			{mviewer.alert(erreur,"alert-info");}
			 },
 
 #### Modifier la fonction sendcomment pour mise en cohérence avec le formulaire html :
 
	 send_comment: function (comment,coord_x,coord_y,date_observation,categorie,mail,image,nom,espece)
			{
			var xhr = mviewer.customControls.obs_faune_flore.getXMLHttpRequest();
			
			var scomment = encodeURIComponent(comment);
			var coord_x = encodeURIComponent(coord_x);
			var coord_y = encodeURIComponent(coord_y);
			var date_observation = encodeURIComponent(date_observation);
			var categorie = encodeURIComponent(categorie);
			var mail = encodeURIComponent(mail);
			var imagename = encodeURIComponent(image.name);
			var nom = encodeURIComponent(nom);
			var espece = encodeURIComponent(espece);

			
			var formData = new FormData();
			formData.append("image", image);
			
			xhr.open("POST", "/mviewer/apps/faune_flore/savecomment.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send("comment="+scomment+"&coord_x="+coord_x+"&coord_y="+coord_y+"&date_observation="+date_observation+"&categorie="+categorie+"&mail="+mail+"&imagename="+imagename+"&nom="+nom+"&espece="+espece);
			
			var xhr = mviewer.customControls.obs_faune_flore.getXMLHttpRequest();
			xhr.open("POST", "/mviewer/apps/faune_flore/saveimage.php", true);
			xhr.send(formData);
			
			mviewer.alert("Merci d'avoir participé "+nom,"alert-info");
			document.getElementById("formu").reset();
			document.getElementById('casearrive').style.display='none';
			document.getElementById('casedepart').style.display='inline';
			document.getElementById('preview').src='#';
					
			mviewer.customControls.obs_faune_flore.destroy();
			
			},
 
 #### Ne pas oublier de modifier le nom du custom control sur la première ligne
 
		mviewer.customControls.obs_faune_flore_demo = (function() {

 
 #### Ne pas oublier de modifier le var_idlayer sur la cinquième ligne
 
		var _idlayer = 'obs_faune_flore_demo';
 
 
 Etape 5
-----------
 Renommer / paramétrer le javascript (customlayers/xxxx.js) pour mise en cohérence avec les fichiers modifiés précédemment.
 
 
	 {
	mviewer.customLayers.obs_faune_flore_demo = {};

	mviewer.customLayers.obs_faune_flore_demo.layer = new ol.layer.Vector({
		source: new ol.source.Vector()    
	});
	mviewer.customLayers.obs_faune_flore_demo.handle = false;
	}

 
 Etape 6
-----------
Paramétrer le fichier savecomment.php pour mise en cohérence avec les champs envoyés dans la fonction sendcomment du javascript modifié précédemment.

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

		// if ($comment)
		{
			$fd = fopen("observations.csv", "a");
				if ($fd !== false)
				{
					 $form_data = array(
						 'comment' => $comment,
						  'coord_x' => $coord_x,
						  'coord_y' => $coord_y,
						  'date_observation' => $date_observation,
						  'categorie'=>$categorie,
						  'mail'=>$mail,
						  'imagename'=>$imagename,
						  'nom'=>$nom,
						  'espece'=>$espece
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

 #### le nom du fichier csv où seront enregistrées les participations peut-être modifié dans la focntion fopen
 
		$fd = fopen("observations.csv", "a");
 