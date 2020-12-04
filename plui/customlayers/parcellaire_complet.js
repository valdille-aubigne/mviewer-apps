{
mviewer.customLayers.parcellaire_complet = {};

mviewer.customLayers.parcellaire_complet.layer = new ol.layer.Tile({
    source: new ol.source.TileWMS({
	url : "https://10.69.33.208/geoserver/valdilleaubigne/wms",
	params: {LAYERS:'valdilleaubigne:parcellaire_complet', TILED: true}
	}),
	
});
mviewer.customLayers.parcellaire_complet.handle = function(feature) {
	JSON.parse(feature.zonage)
};
}