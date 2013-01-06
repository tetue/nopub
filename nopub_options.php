<?php
/**
 * Gestion de la publicite
 * N'afficher la pub que :
 * - si l'utilisateur vient d'un moteur de recherche connu (ou si ?var_pub=1 dans l'url : test-feature)
 * - et si l'utilisateur n'est pas identifie
 *
 * Sous chaque pub, un lien permet de desactiver la pub
 * dans ce cas on place un cookie et l'utilsateur ne voit plus la pub
 *
 * La pub doit etre inseree par une balise #PUB{identifiant-wwwxhhh}
 * ou pub/identifiant-wwwxhhh.html est le snippet de code a inserer,
 * et www et hhh la largeur et la hauteur de la pub
 */

$GLOBALS['var_pub'] = false;
define('_FAIRPUB_ENGINES',',^https?://(www\.|fr\.)?(google|bing|ask|mysearchresults|search\.(yahoo\.|msn\.|live\.|aol\.|babylon\.|conduit\.|incredimail\.|free\.)|sfr\.fr\/recherche|isearch\.avg),i');

if (
	_request('var_pub')
	OR (
	!isset($_COOKIE['spip_session']) // pub reserve aux non connectes
	AND (
		isset($_COOKIE['var_pub'])
		OR
		preg_match(_FAIRPUB_ENGINES,$_SERVER['HTTP_REFERER'])
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
