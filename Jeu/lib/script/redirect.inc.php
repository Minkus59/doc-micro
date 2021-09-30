<?php

// recup correspondance pseudo hash
session_start();
$Hash=$_SESSION['hash'];

cnx();
$VerifHash =  @mysql_fetch_array(@mysql_query("SELECT hash AS verif_exist FROM express_user WHERE hash='".$Hash."'"));
@mysql_close();
	
if (!$VerifHash['verif_exist']) {			
	header('Location:https://www.livre-express.fr/');
	$Cnx_Ok=false;	
}

elseif (!isset($Hash)) {
	header('Location:https://www.livre-express.fr/');
	$Cnx_Ok=false;
}

else {
	$Cnx_Ok=true;
}
?>