mviewer.customControls.itv_rando = (function() {
    /*
     * Private
     */
    var _idlayer = 'itv_rando';

    var _draw; // global so we can remove it later

    var _source;

    var _vector;

    var _xy;



    var _showResult = function (data) {
        var parcours = $('.isochrone-values').filter(function() { return this.value == data.time });
        var fill = parcours.attr("data-fill");
        var stroke = parcours.attr("data-stroke");
        var format = new ol.format.WKT();
        var feature = format.readFeature(data.wktGeometry, {
            dataProjection: 'EPSG:4326',
            featureProjection: 'EPSG:3857'
          });
        var style = new ol.style.Style({
            fill: new ol.style.Fill({color:fill}),
            stroke: new ol.style.Stroke({color:stroke, width: 2}),
             zIndex: parseInt(10000 / data.time)
        });
        feature.setStyle(style);
        mviewer.customLayers.itv_rando.layer.getSource().addFeature(feature);
    };

    return {
        /*
         * Public
         */

        init: function () {
            // mandatory - code executed when panel is opened
            $(".isochrone-values").each(function(i, item) {
                $(item).css("background-color", $(item).attr("data-fill"));
                $(item).css("border", ["solid", $(item).attr("data-stroke"), "2px"].join (" "));
            });
        },


		getFileName: function ()
			{
			var x = document.getElementById('image')
			var y = document.getElementById('casedepart');
			var z = document.getElementById('preview');

			y.style.display = 'none';

			var reader = new FileReader();

			 reader.addEventListener("load", function () {
				z.src = reader.result;
			  }, false);

			  if (x.files[0]) {
				reader.readAsDataURL(x.files[0]);
			  }

			document.getElementById('casearrive').style.display='inline';
			document.getElementById('preview').style.visibility='visible';

			},

		applyEvent: function ()
		 {
		var newFile='';
		var date_observation = document.getElementById('date_observation');
		var text = document.getElementById('comment');
		var nom = document.getElementById('nom');
		var mail = document.getElementById('mail');
		var image = document.getElementById("image").files[0];
		var erreur = "Merci de bien vouloir : <br/>" ;
		var date_saisie = new Date();

		date_saisie = date_saisie.getTime();

		if (date_observation.value=='')
		erreur = erreur + "- Renseigner une date<br/>";

		if (text.value=='')
		erreur = erreur + "- Renseigner une description<br/>";

		if (image != undefined)
			{
				var extension = image.name.split('.').pop();
				extension = extension.toLowerCase();

				var blob = image.slice(0,image.size, 'image/'+extension);

				//definir le nom avec la date en place de name
				newFile= new File([blob], 'photo_'+date_saisie+'.'+extension, {type: 'image/'+extension});
			}

		if (_xy==undefined)
		erreur = erreur + "- Définir un lieu<br/>";

		if (erreur=="Merci de bien vouloir : <br/>")
		{mviewer.customControls.itv_rando.send_comment(text.value,_xy[0],_xy[1],date_observation.value,mail.value,newFile, nom.value, date_saisie);}
		else
		{mviewer.alert(erreur,"alert-info");}
		 },

		send_comment: function (comment,coord_x,coord_y,date_observation,mail,image,nom, id)
		{
		var xhr = mviewer.customControls.itv_rando.getXMLHttpRequest();

		var scomment = encodeURIComponent(comment);
		var coord_x = encodeURIComponent(coord_x);
		var coord_y = encodeURIComponent(coord_y);
		var date_observation = encodeURIComponent(date_observation);
		var mail = encodeURIComponent(mail);
		var imagename = encodeURIComponent(image.name);
		var nom = encodeURIComponent(nom);
		var id = encodeURIComponent(id);


		var formData = new FormData();
		formData.append("image", image);

		xhr.open("POST", "/mviewer/apps/rando_interventions/savecomment.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send("comment="+scomment+"&coord_x="+coord_x+"&coord_y="+coord_y+"&date_observation="+date_observation+"&mail="+mail+"&imagename="+imagename+"&nom="+nom+"&id="+id);

		var xhr = mviewer.customControls.itv_rando.getXMLHttpRequest();
		xhr.open("POST", "/mviewer/apps/rando_interventions/saveimage.php", true);
		xhr.send(formData);

		mviewer.alert("Merci d'avoir participé "+nom,"alert-info");
		document.getElementById("formu").reset();
		document.getElementById('casearrive').style.display='none';
		document.getElementById('casedepart').style.display='inline';
		document.getElementById('preview').src='#';

    var xhr = mviewer.customControls.itv_rando.getXMLHttpRequest();
    xhr.open("GET", "/mviewer/apps/rando_interventions/createjson.php", true);
    xhr.send();
    xhr.onload = function() {
        if (xhr.status != 200) { // analyze HTTP status of the response
          alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
        } else {
          //répétition de la commande de création du geojson pour gagner du temps
          // dans la prise en compte de la saisie à l'affichage
          //Améliorable !!!
          $.ajax({
            url: 'apps/rando_interventions/createjson.php',
            complete: function(resultat,statut){
              mviewer.customControls.itv_rando.destroy();
              mviewer.customLayers.itv_rando.layer.getSource().clear();
              mviewer.customLayers.itv_rando.layer.getSource().refresh();
              mviewer.customLayers.itv_rando.layer.getSource().changed();
            }
          });
        }
      };

		},

		getXMLHttpRequest: function ()
			{
			var xhr = null;

			if (window.XMLHttpRequest || window.ActiveXObject)
			{
				if (window.ActiveXObject)
				{
					try
					{
						xhr = new ActiveXObject("Msxml2.XMLHTTP");
					}
					catch(e)
					{
						xhr = new ActiveXObject("Microsoft.XMLHTTP");
					}
				}
				else
				{
					xhr = new XMLHttpRequest();
				}
			}
			else
			{
				alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
				return null;
			}

			return xhr;
		},

        getXY: function () {
              info.disable();
              _draw = new ol.interaction.Draw({
                type: 'Point'
              });
              _draw.on('drawend', function(event) {
                 _xy = ol.proj.transform(event.feature.getGeometry().getCoordinates(),'EPSG:3857', 'EPSG:4326');
                 mviewer.getMap().removeInteraction(_draw);
                 mviewer.showLocation('EPSG:4326', _xy[0], _xy[1]);
                 info.enable();
              });
              mviewer.getMap().addInteraction(_draw);

        },

        destroy: function () {
            // mandatory - code executed when panel is closed
            _xy = null;
            mviewer.hideLocation();
      //      mviewer.customLayers.itv_rando.layer.getSource().clear();
        },


     };



}());
