/* Copyright (c) 2006-2008 MetaCarta, Inc., published under the Clear BSD
 * license.  See http://svn.openlayers.org/trunk/openlayers/license.txt for the
 * full text of the license. */

/* Translators (2009 onwards):
 *  - Als-Holder
 */

/**
 * @requires OpenLayers/Lang.js
 */

/**
 * Namespace: OpenLayers.Lang["gsw"]
 * Dictionary for Alemannisch.  Keys for entries are used in calls to
 *     <OpenLayers.Lang.translate>.  Entry bodies are normal strings or
 *     strings formatted for use with <OpenLayers.String.format> calls.
 */
OpenLayers.Lang["gsw"] = OpenLayers.Util.applyDefaults({

    'unhandledRequest': "Nit behandleti Aafrogsruckmäldig ${statusText}",

    'permalink': "Permalink",

    'Overlays': "Iberlagerige",

    'Base Layer': "Grundcharte",

    'sameProjection': "D Ibersichts-Charte funktioniert nume, wänn si di glych Projäktion brucht wie d Hauptcharte",

    'readNotImplemented': "Läse nit implementiert.",

    'writeNotImplemented': "Schrybe nit implementiert.",

    'noFID': "E Feature, wu s kei FID derfir git, cha nit aktualisiert wäre.",

    'errorLoadingGML': "Fähler bim Lade vu dr GML-Datei ${url}",

    'browserNotSupported': "Dyy Browser unterstitzt kei Vektordarstellig. Aktuäll unterstitzti Renderer:\n${renderers}",

    'componentShouldBe': "addFeatures : Komponänt sott dr Typ ${geomType} syy",

    'getFeatureError': "getFeatureFromEvent isch uf eme Layer ohni Renderer ufgruefe wore. Des heisst normalerwys, ass Du e Layer kaputt gmacht hesch, aber nit dr Handler, wu derzue ghert.",

    'minZoomLevelError': "D minZoomLevel-Eigeschaft isch nume dänk fir d Layer, wu vu dr FixedZoomLevels abstamme. Ass dää wfs-Layer minZoomLevel prieft, scih e Relikt us dr Vergangeheit. Mir chenne s aber nit ändere ohni OL_basierti Aawändige villicht kaputt gehn, wu dervu abhänge.  Us däm Grund het die Funktion d Eigeschaft \'deprecated\' iberchuu. D minZoomLevel-Priefig unte wird in dr Version 3.0 usegnuu. Bitte verwänd statt däm e min/max-Uflesig wie s do bschriben isch: http://trac.openlayers.org/wiki/SettingZoomLevels",

    'commitSuccess': "WFS-Transaktion: ERFOLGRYCH ${response}",

    'commitFailed': "WFS-Transaktion: FÄHLGSCHLAA ${response}",

    'googleWarning': "Dr Google-Layer het nit korräkt chenne glade wäre.\x3cbr\x3e\x3cbr\x3eGo die Mäldig nimi z kriege, wehl e andere Hintergrundlayer us em LayerSwitcher im rächte obere Ecke.\x3cbr\x3e\x3cbr\x3eDää Fähler git s seli hyfig, wel s Skript vu dr Google-Maps-Bibliothek nit yybunde woren isch oder wel s kei giltige API-Schlissel fir Dyy URL din het.\x3cbr\x3e\x3cbr\x3eEntwickler: Fir Hilf zum korräkte Yybinde vum Google-Layer \x3ca href=\'http://trac.openlayers.org/wiki/Google\' target=\'_blank\'\x3edoo drucke\x3c/a\x3e",

    'getLayerWarning': "Dr ${layerType}-Layer het nit korräkt chenne glade wäre.\x3cbr\x3e\x3cbr\x3eGo die Mäldig nimi z kriege, wehl e andere Hintergrundlayer us em LayerSwitcher im rächte obere Ecke.\x3cbr\x3e\x3cbr\x3eDää Fähler git s seli hyfig, wel s Skript vu dr \'${layerLib}\'-Bibliothek nit yybunde woren isch oder wel s kei giltige API-Schlissel fir Dyy URL din het.\x3cbr\x3e\x3cbr\x3eEntwickler: Fir Hilf zum korräkte Yybinde vu Layer \x3ca href=\'http://trac.openlayers.org/wiki/${layerLib}\' target=\'_blank\'\x3edoo drucke\x3c/a\x3e",

    'scale': "Maßstab = 1 : ${scaleDenom}",

    'W': "W",

    'E': "O",

    'N': "N",

    'S': "S",

    'layerAlreadyAdded': "Du hesch versuecht dää Layer in d Charte yyzfiege: ${layerName}, aber är isch schoi yygfiegt",

    'reprojectDeprecated': "Du bruchsch d \'reproject\'-Option bim ${layerName}-Layer. Die Option isch nimi giltig: si isch aagleit wore go   Date iber kommerziälli Grundcharte lege, aber des sott mer jetz mache mit dr Unterstitzig vu Spherical Mercator. Meh Informatione git s uf http://trac.openlayers.org/wiki/SphericalMercator.",

    'methodDeprecated': "Die Methode isch veraltet un wird us dr Version 3.0 usegnuu. Bitte verwäbnd statt däm ${newMethod}.",

    'boundsAddError': "Du muesch e x-Wärt un e y-Wärt yygee bi dr Zuefieg-Funktion",

    'lonlatAddError': "Du meusch e Lengi- un e Breiti-Grad yygee bi dr Zuefieg-Funktion.",

    'pixelAddError': "Du muesch x- un y-Wärt aagee bi dr Zuefieg-Funktion.",

    'unsupportedGeometryType': "Nit unterstitze Geometrii-Typ: ${geomType}",

    'pagePositionFailed': "OpenLayers.Util.pagePosition fählgschlaa: Elemänt mit ID ${elemId} isch villicht falsch gsetzt.",

    'filterEvaluateNotImplemented': "evaluiere isch nit implemäntiert in däm Filtertyp."

});