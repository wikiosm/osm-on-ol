<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<title>Wikipedia on OpenStreetMap</title>

<?php
#Contributors: Kolossos, Peter Körner, Alexrk2, Magnus Manske, master 
#Supporters:  river, Raymond, 32X, Markus B., ALE!, Stefan Kühn, dispenser, ...

#License:GPL

require_once ( "geo_param.php" ) ;
require_once ( "helpers.php" ) ;
@include "mapsources.php" ;	// Nux: Seems to be missing... Is that needed?

$TSisDOWN=0;

$lang=addslashes(urldecode(param_get('lang', 'en')));
$uselang=addslashes(urldecode(param_get('uselang')));
if ($uselang=="") {$uselang=$lang;}
if ($lang=="wikidata") {$langwiki=$uselang;} else {$langwiki=$lang;}

$coatsinsert=$thumbsinsert=$popinsert=$styleinsert=$photoinsert=$sourceinsert=$notsourceinsert=$classesinsert = '';
$thumbs=addslashes(param_get('thumbs'));
if (($thumbs=="no") or ($thumbs=="yes")) {$thumbsinsert=", 'thumbs' : '$thumbs'";} else {$thumbsinsert=", 'thumbs' : '0'";}
$coats=addslashes(param_get('coats'));
if (($coats=="no") or ($coats=="yes")) {$coatsinsert=", 'coats' : '$coats'";} else {$coatsinsert=", 'coats' : '0'";}
$pop=addslashes(param_get('pop'));
if (($pop<>"")) {$popinsert=", 'pop' : '$pop'";} 
$style=addslashes(param_get('style'));
if (($style<>"")) {$styleinsert=", 'style' : '$style'";}
$photo=addslashes(param_get('photo'));
if (($photo=="no") or ($photo=="yes")) {$photoinsert=", 'photo' : '$photo'";}
$source=addslashes(param_get('source'));
if (($source<>"")) {$sourceinsert=", 'source' : '$source'";} 
$notsource=addslashes(param_get('notsource'));
if (($notsource<>"")) {$notsourceinsert=", 'notsource' : '$notsource'";} 

$title=urldecode(param_get('title'));

$title=urldecode(param_get('title'));
if ($title=="") {$title=urldecode(param_get('pagename'));} #hack for nl.wp

$classes=urldecode(param_get('classes'));
if ($classes<>"") {$classesinsert=", 'classes' : '$classes'";} 

// Geohack

$theparams = '';
if ( isset ( $_REQUEST['params'] ) ) {
	$theparams =$_REQUEST['params'];
	$p = new geo_param(  $_REQUEST['params'] , "Dummy" ); ;
	$x = floatval($p->londeg) ;
	$y = floatval($p->latdeg) ;
}
// overwrite lat&lon from perm-link params
if ( isset ( $_GET['lat'] ) && isset ( $_GET['lon'] ) ) {
	$x = floatval($_GET['lon']) ;
	$y = floatval($_GET['lat']) ;
}

$typeleftcut = strstr($theparams,"type:");
$type = substr ($typeleftcut, 5 );
if (strpos($type,"(")>0) {$type = substr($type,0,strpos($type,"("));}
if (strpos($type,"_")>0) {$type = substr($type,0,strpos($type,"_"));}

$default_scale = array(
	'country'        => 10000000, # 10 mill
	'satellite'      => 10000000, # 10 mill
	'state'          =>  3000000, # 3 mill
	'adm1st'         =>  1000000, # 1 mill
	'adm2nd'         =>   300000, # 300 thousand
	'adm3rd'         =>   100000, # 100 thousand
	'city'           =>   100000, # 100 thousand
	'isle'           =>   100000, # 100 thousand
	'mountain'       =>   100000, # 100 thousand
	'river'          =>   100000, # 100 thousand
	'waterbody'      =>   100000, # 100 thousand
	'event'          =>    50000, # 50 thousand
	'forest'         =>    50000, # 50 thousand
	'glacier'        =>    50000, # 50 thousand
	'town'           =>    50000, # 50 thousand
	'airport'        =>    30000, # 30 thousand
	'railwaystation' =>    10000, # 10 thousand
	'edu'            =>    10000, # 10 thousand
	'pass'           =>    10000, # 10 thousand
	'landmark'       =>    10000, # 10 thousand
	'building'       =>     3000,
);
if (!isset($default_scale[$type])) {
	$type = 'city';
}
$zoomtype=18 - ( round(log($default_scale[$type],2) - log(1693,2)) );

$dimleftcut = strstr($theparams,"dim:");
$dim = substr ($dimleftcut, 4 );
if (strpos($dim,"(")>0) {$dim = substr($dim,0,strpos($dim,"("));}
if (strpos($dim,"_")>0) {$dim = substr($dim,0,strpos($dim,"_"));}
$dim = floatval(str_replace(array("km","m"),array("000",""),$dim));

if ($dim>0)
{$zoomtype=18 - ( round(log($dim/0.1,2) - log(1693,2)) );}

if ($zoomtype>18) {$zoom=18;$zoomtype=18;}
if ($zoomtype>2 ) {$zoom=$zoomtype;} else {$zoom=12;}

$position= "  var zoom = $zoom;";
if (!empty($x)){$position.="
		args.lon = $x;
		args.lat = $y; ";}

echo "<!-- //position:".$position."\n dim:".$dim."\n zoomtype:".$zoomtype." -->\n";
// Geohack end
?>

<script src="https://osm.toolforge.org/libs/jquery/latest/jquery-min.js" type="text/javascript"></script>
<script src="https://osm.toolforge.org/libs/openlayers/2.12/OpenLayers.js" type="text/javascript"></script>
<!--script src="//openlayers.org/dev/OpenLayers.js" type="text/javascript"></script-->
<!--script src="//toolserver.org/~osm/libs/openstreetmap/latest/OpenStreetMap.js" type="text/javascript"></script-->
<script src="./Lang/<?php echo $uselang;?>.js" type="text/javascript"></script>
		
<script type="text/javascript">
// map object
var map;
var poiLayerHttp;
var pois;
var osmLabelsLang;
var forcelocal;

<?php echo "var lang = '$lang';";?>
if(lang=="hsb"||lang=="ru") {forcelocal=false;} else {forcelocal=false;}



// initiator
function init()
{
	OpenLayers.Lang.setCode('<?php echo $uselang;?>');

	/*
	// show an error image for missing tiles
	OpenLayers.Util.onImageLoadError = function ()
	{
		if (urlRegex.test(this.src))
		{
			var style = RegExp.$2;
			if (style == 'osm' || style == 'osm-no-labels')
			{
				var tile = RegExp.$3;
				var inst = RegExp.$1;
				this.src = '//' + inst + '.tile.openstreetmap.org/' + tile;;

				if (window.console && console.log)
					console.log('redirecting request for ' + tile + ' to openstreetmap.org: ' + this.src);

				return;
			}
			if (style == 'osm-labels-ru')
			{
				this.src = '//toolserver.org/~osm/libs/openlayers/latest/img/blank.gif';
				return;
			}

			this.src = '//www.openstreetmap.org/openlayers/img/404.png';
		}
	};
	*/

	// get the request-parameters
	var args = OpenLayers.Util.getParameters();

	// main map object
	map = new OpenLayers.Map ("map", {
		controls: [
			new OpenLayers.Control.Navigation(),
			new OpenLayers.Control.PanZoomBar(),
			new OpenLayers.Control.Attribution(),
			new OpenLayers.Control.LayerSwitcher(),
			new OpenLayers.Control.Permalink(),
			new OpenLayers.Control.ScaleLine({geodesic:true})
			//new OpenLayers.Control.KeyboardDefaults()
		],
		
		// mercator bounds
		maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34),
		maxResolution: 156543.0399,
		
		numZoomLevels: 19,
		units: 'm',
		projection: new OpenLayers.Projection("EPSG:900913"),
		displayProjection: new OpenLayers.Projection("EPSG:4326")
	});


	// create the custom layer
	OpenLayers.Layer.OSM.Toolserver = OpenLayers.Class(OpenLayers.Layer.OSM, {
		
		initialize: function(name, options) {
			var url = [
				"//a.toolserver.org/tiles/" + name + "/${z}/${x}/${y}.png",
				"//b.toolserver.org/tiles/" + name + "/${z}/${x}/${y}.png",
				"//c.toolserver.org/tiles/" + name + "/${z}/${x}/${y}.png"
			];
			
			options = OpenLayers.Util.extend({numZoomLevels: 19}, options);
			OpenLayers.Layer.OSM.prototype.initialize.apply(this, [name, url, options]);
		},
		
		CLASS_NAME: "OpenLayers.Layer.OSM.Toolserver"
	});

	map.addLayer(new OpenLayers.Layer.OSM(OpenLayers.i18n("Translated names map"),
		"https://maps.wikimedia.org/osm-intl/${z}/${x}/${y}.png?lang=<?php echo $lang;?>",
		{
			attribution:'<?php echo translate('map-by',$uselang);?> © <a target="_blank" href="//www.openstreetmap.org/copyright"><?php echo translate('openstreetmap-contributors',$uselang);?></a>',
			type: 'png', serviceVersion:'',layername:'',
			visibility: false,
			tileOptions: { crossOriginKeyword: null },
			transitionEffect: 'resize' 
		}
	));



	var urlRegex = new RegExp('^//([abc]).toolserver.org/tiles/([^/]+)/(.*)$');

	var osm = new OpenLayers.Layer.OSM(OpenLayers.i18n("Local names map"),
		"https://maps.wikimedia.org/osm-intl/${z}/${x}/${y}.png",
		{
			attribution:'<?php echo translate('map-by',$uselang);?> © <a target="_blank" href="//www.openstreetmap.org/copyright"><?php echo translate('openstreetmap-contributors',$uselang);?></a>',
			transitionEffect: 'resize',
			tileOptions: {
				'eventListeners': {
					'loaderror': function(evt) {
							
						if(urlRegex.test(this.url))
						{
							var style = RegExp.$2;
							if(style == 'osm'||style == 'osm-no-labels')
							{
								var tile = RegExp.$3;
								var inst = RegExp.$1;
								this.setImgSrc('//'+inst+'.tile.openstreetmap.org/'+tile);
					
								if(window.console && console.log)
									console.log('redirecting request for '+tile+' to openstreetmap.org: '+this.url);
									// alert ('test:URL' + this.url + '  //'+inst+'.tile.openstreetmap.org/'+tile );
								return;
							}
							if(style == 'osm-labels-ru')
							{
								this.setImgSrc('//toolserver.org/~osm/libs/openlayers/latest/img/blank.gif');
								return;
							}

							this.setImgSrc('//www.openstreetmap.org/openlayers/img/404.png');
						}
					}
				},
				crossOriginKeyword: null
			}
		}
	)
	osm.setIsBaseLayer(true);
	osm.setVisibility(true);
	map.addLayer(osm);
	var osmNoLabels = new OpenLayers.Layer.OSM.Toolserver('osm-no-labels',{
			attribution:'<?php echo translate('map-by',$uselang);?> © <a target="_blank" href="//www.openstreetmap.org/copyright"><?php echo translate('openstreetmap-contributors',$uselang);?></a>',
			visibility: false,
			tileOptions: { crossOriginKeyword: null }
		} ,
		{isBaseLayer:true}
	);
	// un-maintained, doesn't work
	/**
	osmNoLabels.setIsBaseLayer(true);
	osmNoLabels.setVisibility(false);
	map.addLayer(osmNoLabels);
	/**/

	//Place for OSM.org
	map.addLayer(new OpenLayers.Layer.OSM(OpenLayers.i18n("OSM.org map"),
		"//a.tile.openstreetmap.org/${z}/${x}/${y}.png",
		{attribution:'<?php echo translate('map-by',$uselang);?> © <a target="_blank" href="//www.openstreetmap.org/copyright"><?php echo translate('openstreetmap-contributors',$uselang);?></a>',visibility: false, tileOptions: { crossOriginKeyword: null },transitionEffect: 'resize' }));


	map.addLayer(new OpenLayers.Layer.OSM(OpenLayers.i18n("Public transport map"),
		"http://tile.memomaps.de/tilegen/${z}/${x}/${y}.png",  {attribution:'Map © OpenStreetMap contributors',visibility: false, tileOptions: { crossOriginKeyword: null },transitionEffect: 'resize' }));

	//map.addLayer(new OpenLayers.Layer.OSM("hires",
	//	 "//mull.geofabrik.de/osm2x/${z}/${x}/${y}.png",
	//	    {attribution:' <a target="_blank" href="//www.geofabrik.de">Geofabrik experiemental</a>',
	//	visibility: false, tileOptions: { crossOriginKeyword: null },transitionEffect: 'resize' }));

	<?php if($lang=="de" and empty($_SERVER["HTTP_X_TS_SSL"])) {echo "map.addLayer(new OpenLayers.Layer.OSM('germany','http://tile.openstreetmap.de/tiles/osmde/".'${z}/${x}/${y}'.".png',  {attribution:'Karte © OpenStreetMap Mitwirkenden',visibility: false, tileOptions: { crossOriginKeyword: null },transitionEffect: 'resize' }));";}?>
	

	osmLabelsLang = new OpenLayers.Layer.OSM('osm-labels-<?php echo $lang;?>',"//tiles.wmflabs.org/osm-multilingual/<?php echo $lang;?>,_/${z}/${x}/${y}.png", {isBaseLayer: false, visibility: false, tileOptions: { crossOriginKeyword: null }, attribution:''});
	// unmainted, doesn't work
	// map.addLayers([osmLabelsLang]);


	// Wikipedia-World Layer			
	var bboxStrategy = new OpenLayers.Strategy.BBOX( {
		ratio : 1.1,
		resFactor: 1
	});


	poiLayerHttp = new OpenLayers.Protocol.HTTP({
		url: "//wp-world.toolforge.org/marks.php?",
		params: { 'LANG' : <?php echo "'".$langwiki."'".$coatsinsert.$thumbsinsert.$popinsert.$styleinsert.$photoinsert.$sourceinsert.$notsourceinsert.$classesinsert;?>},
		format: new OpenLayers.Format.KML({
		extractStyles: true,
		extractAttributes: true
			})
	});
	pois = new OpenLayers.Layer.Vector(OpenLayers.i18n("Wikipedia World"), {
		attribution:'<a target="_blank" href="//de.wikipedia.org/wiki/Wikipedia:WikiProjekt_Georeferenzierung/Wikipedia-World/en"><?php echo translate('wikipedia',$uselang);?></a> (CC-BY-SA)',
		projection: new OpenLayers.Projection("EPSG:4326"),
		strategies: [bboxStrategy],
		protocol: poiLayerHttp
	});
	
	map.addLayer(pois);

<?php 
if (!empty($title) and detect_not_ie()){
	echo "var lang=". json_encode($lang) .";";
?>
	//OSM objects Layer : Object with the Wikipedia-Tag matching with article-name
	var styleMap = new OpenLayers.StyleMap({'pointRadius': 7,
						'strokeWidth': 3,
						'strokeColor': '#ff0000',
						'fillColor': '#ff0000',
						'fillOpacity': .3
	});

	var vector_layer = new OpenLayers.Layer.Vector(OpenLayers.i18n("OSM objects (loading...)"),{
					styleMap: styleMap,
					attribution:' <a target="_blank" href="//wiki.openstreetmap.org/wiki/WIWOSM">WIWOSM</a> (<a target="_blank" href="//opendatacommons.org/licenses/odbl/">ODbL</a>) '
	
										});
	map.addLayer(vector_layer);
	window.osm_object_layer = vector_layer;	// expose

	// construct API url to get JSON of a shape
	var urlData = new URL(location.href);
	var isWikidata = (lang === 'wikidata');
	// TODO: make this proxy permanent (not a test.php)
	var apiPath = isWikidata ? '/osmjson/test.php' : '/osmjson/getGeoJSON.php';
	var jsonUrl = new URL(apiPath, location.href);
	jsonUrl.searchParams.append('lang', lang);
	jsonUrl.searchParams.append('article', urlData.searchParams.get('title'));
	if (!isWikidata) {
		var action = urlData.searchParams.get('action');
		if (action === 'purge') {
			jsonUrl.searchParams.append('action', action);
		}
	}
	console.log('jsonUrl: ', jsonUrl.toString());

	OpenLayers.Request.GET({
		url: jsonUrl.toString(),
		callback: function (response) {

			if (response.status == 404) {
				vector_layer.setVisibility(false);
				vector_layer.setName(OpenLayers.i18n("OSM objects (not found)"));
				map.removeLayer(vector_layer);
			} else {
				// fallback to wiwosm server
				if (!isWikidata) {
					var gformat = new OpenLayers.Format.GeoJSON();
					var gg = '{"type":"FeatureCollection", "features":[{"geometry": ' +
						response.responseText + '}]}';
					var feats = gformat.read(gg);
				// shape data from wikimedia maps
				} else {
					var gformat = new OpenLayers.Format.GeoJSON({
						'internalProjection': new OpenLayers.Projection("EPSG:3857"),
						'externalProjection': new OpenLayers.Projection("EPSG:4326"),
					});
					var feats = gformat.read(response.responseText);
				}

				vector_layer.addFeatures(feats);
				vector_layer.setName(OpenLayers.i18n("OSM objects (WIWOSM)"));
				document.title = args.title + " on OpenStreetMap";

				if (vector_layer.getDataExtent().getHeight() > 500) {
					map.zoomToExtent(vector_layer.getDataExtent(), false);
				}

				if (!args.lon && vector_layer.getDataExtent().getHeight() <= 500) {
					map.setCenter(vector_layer.getDataExtent().getCenterLonLat(), 17);
				}
			}
		}
	});
<?php } ?>

  

	var feature = null;
	var highlightFeature = null;
	var tooltipTimeout = false;
	var lastFeature = null;
	var selectPopup = null;
	var tooltipPopup = null;

	var selectCtrl = new OpenLayers.Control.SelectFeature(pois, {
		toggle:true,
		clickout: true
	});
	pois.events.on({ "featureselected": onMarkerSelect, "featureunselected": onMarkerUnselect});

	map.events.register("zoomend", map, zoomEnd);

	function onMarkerSelect  (evt) {
		eventTooltipOff(evt);
		if(selectPopup != null) {
			map.removePopup(selectPopup);
			selectPopup.feature=null;
			if(feature != null && feature.popup != null){
				feature.popup = null;
			}
		}
		feature = evt.feature;
		//console.log("feature selected", feature) ;
		//console.log("features in layer", pois.features.length);
		selectPopup = new OpenLayers.Popup.AnchoredBubble("activepopup",
			feature.geometry.getBounds().getCenterLonLat(),
			new OpenLayers.Size(220,170),
			text='<b>'+feature.attributes.name +'</b><br>'+ feature.attributes.description,
			null, true, onMarkerPopupClose );
		
		selectPopup.closeOnMove = false;
		selectPopup.autoSize = false;
		feature.popup = selectPopup;
		selectPopup.feature = feature;
		map.addPopup(selectPopup);
	}

	function onMarkerUnselect  (evt) {
		feature = evt.feature;
		if(feature != null && feature.popup != null){
			selectPopup.feature = null;
			map.removePopup(feature.popup);
			feature.popup = null;
		}
	}
	

	function onMarkerPopupClose(evt) {
		if(selectPopup != null) {
			map.removePopup(selectPopup);
			selectPopup.feature = null;
			if(feature != null && feature.popup != null) {
				feature.popup = null;
			}
		}
		selectCtrl.unselectAll();
	}


	var highlightCtrl = new OpenLayers.Control.SelectFeature(pois, {
		hover: true,
		highlightOnly: true,
		renderIntent: "temporary",
		eventListeners: {
		featurehighlighted: eventTooltipOn,
		featureunhighlighted: eventTooltipOff
		}
	});

	function eventTooltipOn  (evt) {
		highlightFeature = evt.feature;
		if(tooltipPopup != null) {
			map.removePopup(tooltipPopup);
			tooltipPopup.feature=null;
			if(lastFeature != null) {
				lastFeature.popup = null;
			}
		}
		lastFeature = highlightFeature;
		
		//document.getElementById("map_OpenLayers_Container").style.cursor = "pointer";
		
		tooltipPopup = new OpenLayers.Popup("activetooltip",
			highlightFeature.geometry.getBounds().getCenterLonLat(),
			new OpenLayers.Size(220,20),
			highlightFeature.attributes.name, null, false, null );
		if(tooltipTimeout) clearTimeout(tooltipTimeout);
		tooltipPopup.closeOnMove = true;
		tooltipPopup.autoSize = true;
		map.addPopup(tooltipPopup);
	}

	function eventTooltipOff  (evt) {
		highlightFeature = evt.feature;
		//document.getElementById("map_OpenLayers_Container").style.cursor = "default";
		
		if(tooltipPopup)
		{
			tooltipTimeout = setTimeout(function() {
				map.removePopup(tooltipPopup);
				tooltipPopup = null;
			}, 500);
		}

		
		if(highlightFeature != null && highlightFeature.popup != null){
			map.removePopup(highlightFeature.popup);
			highlightFeature.popup = null;
			tooltipPopup = null;
			lastFeature = null;
		}
	}

	function zoomEnd() {
		var scale = map.getScale();
		//alert (lang);
		if (scale>10000000) {
			$(".olControlScaleLine").css('display', 'none');
		} else {
			$(".olControlScaleLine").css('display', 'block');
		}
		
		/**
		// below zoom 6 we switch from layer "osm" to layer "osm-no-labels" + "osm-labels-de"
		if((map.getZoom() <= 6 || forcelocal) && map.baseLayer.id == osm.id)
		{
			map.setBaseLayer(osmNoLabels);
			osmLabelsLang.setVisibility(true);
		}
		if(map.getZoom() <= 6 || forcelocal)
		{
			osmLabelsLang.displayInLayerSwitcher=true;
			
			osmNoLabels.displayInLayerSwitcher=true;
		}
		
		// above zoom 6 we switch back to the usual osm layer
		else if(map.getZoom() > 6 && map.baseLayer.id == osmNoLabels.id  && !forcelocal)
		{
			map.setBaseLayer(osm);
			osmLabelsLang.setVisibility(false);
		}
		if(map.getZoom() > 6  && !forcelocal && map.baseLayer.id == osm.id)
		{
			osmLabelsLang.displayInLayerSwitcher=false;
			osmNoLabels.displayInLayerSwitcher=false;
		}
	
		if (map.baseLayer.name=='Satellite' && (forcelocal || lang=='de'))
			{osmLabelsLang.setVisibility(false);}
		if (!(map.baseLayer.name=='Satellite' || map.baseLayer.id == osmNoLabels.id))
			{osmLabelsLang.setVisibility(false);}
		/**/
	}
	



	map.addControl(highlightCtrl);
	map.addControl(selectCtrl);
	highlightCtrl.activate();
	selectCtrl.activate();

	zoomEnd();



	// default zoom and lon/lat
	<?php echo $position;?>

	// lat/lon requestes
	if(args.lon && args.lat)
	{
		// zoom requested
		if(args.zoom)
		{
			zoom = parseInt(args.zoom);
			var maxZoom = map.getNumZoomLevels();
			if (zoom >= maxZoom) zoom = maxZoom - 1;
		}
		
		// transform center
		var center = new OpenLayers.LonLat(parseFloat(args.lon), parseFloat(args.lat)).
			transform(map.displayProjection, map.getProjectionObject())
		
		// move to
		map.setCenter(center, zoom);
	}

	// bbox requestet
	else if (args.bbox)
	{
		// transform bbox
		var bounds = OpenLayers.Bounds.fromArray(args.bbox).
			transform(map.displayProjection, map.getProjectionObject());
		
		// move to
		map.zoomToExtent(bounds)
	}

	// default center
	else
	{
		// set the default center
		var center = new OpenLayers.LonLat(0, 0).
			transform(map.displayProjection, map.getProjectionObject());
		
		// move to
		map.setCenter(center, zoom);
	}
	var markers = new OpenLayers.Layer.Markers( OpenLayers.i18n("Marker"), {
		attribution:' <a target="_blank" href="//<?php echo $lang;?>.wikipedia.org/wiki/Help:OpenStreetMap"> <?php echo translate('help',$uselang);?> </a>'
	} );
	map.addLayer(markers);

	var size = new OpenLayers.Size(16,16);
	var offset = new OpenLayers.Pixel(-(size.w/2), -(size.h/2));
	var icon = new OpenLayers.Icon('Ol_icon_red_example.png',size,offset);
	markers.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat(map.center.lon,map.center.lat),icon));

} // init()


//
// Thumbnails and coats markers (large images)
//
// show/hide image-markers options
function hideInsetMenu() {
	$('#mapInsetMenuDropdown').css('visibility', 'hidden');
}
function showInsetMenu() {
	$('#mapInsetMenuDropdown').css('visibility', 'visible');
}
// load image-markers wikipedias list
$.ajax({
	type: "GET",
	url: "lang-select.php",
	data: "lang=<?=$uselang?>",
	success: function (html) {
		$('#mapInsetMenu-languages').empty();
		$(html).appendTo('#mapInsetMenu-languages');
	}
});
// image-markers interactions
$(function () {
	$('#mapInsetMenu-thumbs').change(function () {
		if ($(this).attr('checked') == true) {
			poiLayerHttp.params[$(this).val()] = 'yes';
			$('#mapInsetMenu-coats').removeAttr('checked');
			poiLayerHttp.params[$('#mapInsetMenu-coats').val()] = 'no';
		} else {
			poiLayerHttp.params[$(this).val()] = 'no';
		}
		pois.redraw(true);
	});

	$('#mapInsetMenu-coats').change(function () {
		if ($(this).attr('checked') == true) {
			poiLayerHttp.params[$(this).val()] = 'yes';
			$('#mapInsetMenu-thumbs').removeAttr('checked');
			poiLayerHttp.params[$('#mapInsetMenu-thumbs').val()] = 'no';

		} else {
			poiLayerHttp.params[$(this).val()] = 'no';
		}
		pois.redraw(true);
	});

	$('#mapInsetMenu-languages').change(function () {

		poiLayerHttp.params.LANG = $(this).val();
		/**
		if ($(this).val() != '') {
			osmLabelsLang.name = "osm-labels-" + $(this).val();
			osmLabelsLang.url = [
				"//a.toolserver.org/tiles/" + osmLabelsLang.name + "/${z}/${x}/${y}.png",
				"//b.toolserver.org/tiles/" + osmLabelsLang.name + "/${z}/${x}/${y}.png",
				"//c.toolserver.org/tiles/" + osmLabelsLang.name + "/${z}/${x}/${y}.png"
			];
			osmLabelsLang.redraw(true);
		}
		/**/
		pois.redraw(true);
	});
});
</script>

<style type="text/css">
			body {
				padding: 0;
				margin: 0;
			}

			.olImageLoadError { 
			    display: none;
			}
			
			.olControlAttribution
			{
				bottom: 5px !important;
				right: 80px !important;
			}
			
			.olControlPermalink {
				bottom: 5px !important;
				right: 5px !important;
				width: 60px;
				text-align: center;
			}
			
			.olControlAttribution, .olControlPermalink {
				background-color: white;
				border-color: black;
				border-style: solid;
				border-width: 1px;
				cursor: pointer;
				padding: 2px 4px;
				
				opacity: 0.5;
			}
			
			.olPopupContent, .olControlAttribution, .olControlPermalink {
				font-family: arial, sans-serif;
				font-size: 12px;
			}
			
			.olControlAttribution:hover, .olControlPermalink:hover {
				opacity: 1.0;
			}
			
			.olPopupContent a, .olControlAttribution a, .olControlPermalink a {
				color: #0645AD;
				text-decoration: none;
			}
			
			.olPopupContent a:hover, .olControlAttribution a:hover, .olControlPermalink a:hover {
				text-decoration: underline;
			}
			
			#activetooltip {
				background-color: #ffffcb !important;
				overflow: hidden;
				
				border: 1px solid #DBDBD3 !important;
				
				font-family: arial, sans-serif;
				font-size: 12px;
				height: 8px;
				text-align: center;
			}
			
			#activetooltip .olPopupContent {
				padding: 5px 0 0 0 !important;
			}
			
			.olPopupContent {
				
			}

			.mapBtnOuter {
			border: 1px solid #444;
			background-color: #fff;
			z-index: 2000;
			}
			.mapBtnInner {
			cursor: pointer;
			font-size: 12px;
			font-family: arial, sans-serif;
			border-color:white #bbb #bbb white;
			border-style:solid;
			border-width:1px;
			padding: 2px 4px 2px 4px ;
			}
			#mapInsetMenu {
			position: absolute;
			left: 50px;
			top: 7px;
			}

			div.olLayerDiv {
			  -khtml-user-select: none;
			}

			.olControlScaleLineBottom {
			  display: none;
			}

			#mapInsetMenuDropdown { 
			visibility: hidden;
			padding: 2px 4px 2px 4px ;
			font-size: 12px;
			font-family: arial, sans-serif;
			background-color: #fff;
			border-color: #444;
			border-style:solid;
			border-width:1px;
			position: absolute;
			left: -1px;
			top: 20px;
			width: 250px;
			box-shadow: 2px 2px 2px #666;
			-moz-box-shadow: 2px 2px 2px #666;
			}
			#mapInsetMenuDropdown label {
			font-weight: bold;
			}
			#mapInsetMenuDropdown .languages label {
			display: block;
			padding: .5em 0;
			}
			#mapInsetMenuDropdown .languages {
			padding-top: 0;
			margin-top: 0;
			margin-inline: .3em;
			}
			#mapInsetMenu-languages {
			width: 100%;
			box-sizing: border-box;
			}
		</style>

	</head>
	 
	<body onload="init();">

<div id="mapContainer" style="width: 100%; height: 100%">
	<div style="width: 100%; height: 100%" id="map">

	</div>

	<div id="mapInsetMenu" class="mapBtnOuter" onmouseout="hideInsetMenu()"
		onmouseover="showInsetMenu()">
		<div id="mapInsetMenuI" class="mapBtnInner">
			<?=translate('options',$uselang)?>
		</div>
		<div id="mapInsetMenuDropdown">
			<p class="languages">
				<label for="mapInsetMenu-languages"><?=translate('languages',$uselang)?></label>
				<select id="mapInsetMenu-languages" size="5">
					<option value="">ALL</option>
					<option value="de">Deutsch</option>
					<option value="en">English</option>
				</select>
			</p>
			<p class="thumbs">
				<input  id="mapInsetMenu-thumbs" type="checkbox" value="thumbs" />
				<label for="mapInsetMenu-thumbs"><?=translate('thumbnails',$uselang)?></label>
			</p>
			<p class="coats">
				<input  id="mapInsetMenu-coats" type="checkbox" value="coats" />
				<label for="mapInsetMenu-coats"><?=translate('coat-of-arms',$uselang)?></label>
			</p>
		</div>

	</div>

</div>

	</body>
</html> 
 
