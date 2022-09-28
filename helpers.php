<?php
/** Safe $_GET. */
function param_get($name, $default='') {
	return isset($_GET[$name]) ? $_GET[$name] : $default;
}
/** Safe $_REQUEST. */
function param_req($name, $default='') {
	return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
}

/** I18n with fallback. */
function translate($word,$lang)
{
	include "kml-on-ol.i18n.php";
	$withprefix="ts-kml-on-ol-".$word;
	if (!isset($messages[$lang])) {
		$lang = 'en';
	}
	$a = isset($messages[$lang][$withprefix]) ? $messages[$lang][$withprefix] : $word;
	return $a;
}

/** Simple IE browser detector. Avoid using this. */
function detect_not_ie()
{
    if (isset($_SERVER['HTTP_USER_AGENT']) && 
    (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        return false;
    else
        return true;
}
