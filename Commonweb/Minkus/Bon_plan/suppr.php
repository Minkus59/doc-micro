<?php
// Redirection $_Session
require ('/homez.764/docmicro/www/Commonweb/Minkus/lib/script/redirect.inc.php');

// suppr
$id = $_GET["id"];

if(isset($_POST['oui'])) {

// Connexion à la base de données
include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
											   
@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
					
@mysql_query("DELETE FROM commonweb_bon_plan WHERE id = ".$id );
@mysql_query("DELETE FROM commonweb_bon_plan_like WHERE id_bon_plan = ".$id );
mysql_close();

header('Location:http://www.doc-micro.fr/Commonweb/Minkus/Bon_plan/');
}
if(isset($_POST['non'])) {  
	header('Location:http://www.doc-micro.fr/Commonweb/Minkus/Bon_plan/');
	}
?>

<html>
<head>
<title>Confirmation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
Etes-vous sur de vouloir supprimer ce bon-plan ?

<TABLE width="300">
  <form action="" method="POST">
<TR><TD align="center"><input name="oui" type="submit" value="OUI"></TD><TD align="center"><input name="non" type="submit" value="NON"/></TD></TR></form>
</TABLE>
</body>
</html>
