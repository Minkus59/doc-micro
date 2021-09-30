<?php
// Redirection $_Session ------------------------------------------------------------------------------------------------------
require ('/homez.764/docmicro/www/Commonweb/Minkus/lib/script/redirect.inc.php');
// Fonction cnx ------------------------------------------------------------------------------------------------------------------------
function cnx() {
			include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
										   
			@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
			@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
}
// VAR ------------------------------------------------------------------------------------------------------------------------
$Pseudo = ($_SESSION['pseudo']);

if (isset($_POST['valider'])) {
	
	cnx();

	@mysql_query('INSERT INTO commonweb_commun (nom, pseudo_id)
				VALUE ("'.$_POST['modelisme'].'", "'.$Pseudo.'")');
	}
?>
<html>
<head>
<title>Point commun</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#999999">
<?php
// Menu ---------------------------------------------------------------------------------------------------------------------------------
require ('/homez.764/docmicro/www/Commonweb/Minkus/lib/script/menu.inc.php');
//---------------------------------------------------------------------------------------------------------------------------------------
?>
<h1>Point commun</h1>
<p>Selectionné les differents points commun dans la liste ci-dessous afin de partagé se que vous aimez avec les personnes qui aimemons aussi

<p>Loisir 
<table><form action="" method="post">
<tr><td><input type="checkbox" name="modelisme">Modelisme</td></tr>
<tr><td><input type="checkbox" name="cuisine">cuisine</td></tr>
<tr><td><input type="checkbox" name="Bricolage">Bricolage</td></tr>
<tr><td><input type="checkbox" name="Broderie">Broderie</td></tr>
<tr><td><input type="checkbox" name="Couture">Couture</td></tr>
<tr><td><input type="checkbox" name="Jardinage">Jardinage</td></tr>
<tr><td><input type="checkbox" name="Jeux-video">Jeux-video</td></tr>
<tr><td><input type="checkbox" name="Musique">Musique</td></tr>
<tr><td><input type="checkbox" name="Voyage">Voyage</td></tr>
<tr><td><input type="submit" name="valider" value="Valider"></td></tr></form>
</table>
</body>
</html>
