<?php
// Redirection $_Session ------------------------------------------------------------------------------------------------------
require ('/homez.167/donweb/www/Commonweb/Minkus/lib/script/redirect.inc.php');

// VAR ------------------------------------------------------------------------------------------------------------------------
$Pseudo = ($_SESSION['pseudo']);
?>
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFE6FF">
<?php
// Menu ---------------------------------------------------------------------------------------------------------------------------------
require ('/homez.167/donweb/www/Commonweb/Minkus/lib/script/menu.inc.php');
//---------------------------------------------------------------------------------------------------------------------------------------
function cnx() {
			include ('/homez.167/donweb/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
										   
			@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
			@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
}
?>
<h1>Rencontre</h1>

<p><?php cnx(); require("/homez.167/donweb/www/Commonweb/Minkus/lib/script/rencontre_menu.inc.php"); ?>
</body>
</html>
