<?php

if (!defined("_ECRIRE_INC_VERSION")) return;

function nopub_insert_head_css($flux) {
	$flux .= '<link rel="stylesheet" type="text/css" href="'.find_in_path('css/nopub.css').'" media="all" />'."\n";
	return $flux;
}

function nopub_insert_head($flux) {
	$flux .= '<script src="'.find_in_path('js/nopub.js').'" type="text/javascript"></script>';
	return $flux;
}

?>