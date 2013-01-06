<?php
/**
 * Plugin FairPub
 * Gestion de la publicite respectueuse
 * Distribue sous licence GPL
 *
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
 *
 * Un titre peut etre fourni optionnellement en second argument :
 * [(#PUB{demo-160x600,'<span class="titre">(pub)</span>'})]
 * 
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

/**
 * #PUB{mypub-250x250}
 * #PUB{mypub-250x250,'<h2>Publicite</h2>'}
 * (titre optionnel en second argument)
 *
 * @param object $p
 * @return mixed
 */
function balise_PUB_dist($p){
	$_fond = interprete_argument_balise(1,$p);
	$_titre = interprete_argument_balise(2,$p);
	$p->code = "'<'.'?php if (\$GLOBALS[\'var_pub\']) { ?'.'>'";
	$p->code .= ".fairpub_affiche_pub($_fond, $_titre)";
	$p->code .= ".'<'.'?php } ?'.'>'";
	$p->interdire_scripts = false;
	return $p;
}

/**
 * Afficher un bloc de pub avec les div, les classes englobantes
 * + un width+height pour eviter le reflow (webperf)
 * + un lien pour desactiver la pub
 *
 * @param string $fond
 * @return string
 */
function fairpub_affiche_pub($fond, $titre=""){
	$url_nopub = generer_url_action("nopub","",false,true);
	$width = $height = "";
	if (preg_match(",(\d+)(?:x(\d+))?$,",$fond,$m)){
		$width = $m[1];
		$height = $m[2];
	}

	$class = "pub pub-$fond";
	$inner_style = "";
	if ($width) {$class.=" pub-w$width";$inner_style .= "width:{$width}px;";}
	if ($height) {$class.=" pub-h$height";$inner_style .= "height:{$height}px;";}
	if ($inner_style) $inner_style = " style=\"$inner_style\"";

	$html = "<div class=\"$class\">$titre<div class=\"pub-inner\"$inner_style>"
		. recuperer_fond("pub/$fond")
		. "</div>"
		. "<small class=\"pub-link-nopub\"><a href=\"#\" onclick=\"jQuery('.pub').fadeOut();jQuery.get('$url_nopub');return false;\">"
		. _T('fairpub:info_pas_de_pub_svp')
	  . "</a></small>"
		. "</div>";

	return $html;
}
