{
mviewer.customLayers.itv_rando = {};

$.ajax({
				url: 'apps/rando_interventions/createjson.php',
			});

//couche geojson
mviewer.customLayers.itv_rando.layer = new ol.layer.Vector({
   source: new ol.source.Vector({
		url: './apps/rando_interventions/interventions.geojson?dc=' + new Date().getTime(),
		format: new ol.format.GeoJSON()
	}),
	style: new ol.style.Style({
            image: new ol.style.Circle({
                fill: new ol.style.Fill({
                    color: '#ff6e40'
                }),
                stroke: new ol.style.Stroke({
                    color: "#ffffff",
                    width: 2
                }),
                radius: 7
            })
        }) 
});

mviewer.customLayers.itv_rando.handle = false;
}
