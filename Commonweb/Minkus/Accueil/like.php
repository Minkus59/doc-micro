<?php
// Redirection $_Session
require ('/homez.764/docmicro/www/Commonweb/Minkus/lib/script/redirect.inc.php');

// id
$id = $_GET['id'];
$Pseudo = $_SESSION['pseudo'];

// Connexion à la base de données
include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
											   
@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");

$select = mysql_query('SELECT count(*) FROM commonweb_actu_like WHERE pseudo_id="'.$Pseudo.'" AND id_actu="'.$id.'"');
$data = mysql_fetch_array($select);

if ($data[0]== 0) {

@mysql_query("INSERT INTO `commonweb_actu_like` (`id_actu`,`pseudo_id` )
VALUES ('$id', '".$Pseudo."')");
					
header('Location:http://www.doc-micro.fr/Commonweb/Minkus/Accueil/#'.$id);
}
else { 
header('Location:http://www.doc-micro.fr/Commonweb/Minkus/Accueil/');
}

?>