<?php

// gestion de la pub
$GLOBALS['var_pub'] = false;
define('_ENGINES',',^http://(www\.)?(google|bing|search\.(yahoo\.|msn\.|live\.|aol\.)|sfr\.fr\/do\/gsa\/search),i');

if (
	_request('var_pub')
	OR (
	!isset($_COOKIE['spip_session']) // pub reserve aux non connectes
	AND (
		isset($_COOKIE['var_pub'])
		OR
		preg_match(_ENGINES,$_SERVER['HTTP_REFERER'])
		)
	)){
	$GLOBALS['var_pub'] = true;
	setcookie('var_pub',$_COOKIE['var_pub'] = 1);
}
else {
	// annuler le cookie
	if (isset($_COOKIE['var_pub'])){
		setcookie('var_pub','',time()-1000);
		unset($_COOKIE['var_pub']);
	}
}

?>
