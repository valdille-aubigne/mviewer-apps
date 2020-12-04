{
mviewer.customLayers.obs_faune_flore = {};

$.ajax({
				url: 'apps/faune_flore/createjson.php',
			});

//couche geojson
mviewer.customLayers.obs_faune_flore.layer = new ol.layer.Vector({
   source: new ol.source.Vector({
		url: './apps/faune_flore/observations.geojson?dc=' + new Date().getTime(),
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

mviewer.customLayers.obs_faune_flore.handle = false;
}
