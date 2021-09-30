<?php

require('/homez.585/docmicro/www/Jeu/impinfbdd/ovh.inc.php');
$cnx = new PDO($Ovh_serv_bDd, $uTil_bDd_serv, $mDp_bDd_serv);

// fonction taille des entrées
function TailleChamp($champ,$taille_min=0,$taille_max=0){

	if(!isset($champ)) {
	return false;
	}
	elseif (strlen($champ)<$taille_min) {
	return false;
	}
	elseif(strlen($champ)>$taille_max) {
	return false;
	}
return true; 
}

?>
