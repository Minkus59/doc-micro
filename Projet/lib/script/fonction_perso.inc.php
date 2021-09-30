<?php
// fonction cnx
function cnx() {
        require('../impinfbdd/ovh.inc.php');
	@mysql_pconnect($Ovh_serv_bDd, $uTil_bDd_serv, $mDp_bDd_serv);
	@mysql_select_db($bAse_bDd_serv);
}

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
