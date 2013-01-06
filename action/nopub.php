<?php
/*
 * Plugin xxx
 * (c) 2009 xxx
 * Distribue sous licence GPL
 *
 */

function action_nopub_dist(){
		setcookie('var_pub','',time()-1000);
		unset($_COOKIE['var_pub']);
}

?>