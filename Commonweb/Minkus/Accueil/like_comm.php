<?php
// Redirection $_Session
require ('/homez.764/docmicro/www/Commonweb/Minkus/lib/script/redirect.inc.php');

// id
$id_comm = $_GET['id_comm'];
$Pseudo = $_SESSION['pseudo'];

// Connexion à la base de données
include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
											   
@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");

$select = mysql_query('SELECT count(*) FROM commonweb_actu_comm_like WHERE pseudo_id="'.$Pseudo.'" AND id_com_actu="'.$id_comm.'"');
$data = mysql_fetch_array($select);

if ($data[0]== 0) {

@mysql_query("INSERT INTO `commonweb_actu_comm_like` (`id_com_actu`,`pseudo_id` )
VALUES ('$id_comm', '".$Pseudo."')");
					
header('Location:http://www.doc-micro.fr/Commonweb/Minkus/Accueil/#'.$id_comm);
}
else { 
header('Location:http://www.doc-micro.fr/Commonweb/Minkus/Accueil/');
}

?>