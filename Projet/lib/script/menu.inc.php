<?php
// MENU ---------------------------------------------------------------------------------------------------------------------------------
include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
				   
@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
	 
$menu = mysql_query("SELECT * FROM commonweb_menu");
mysql_close();

?>
<li><?php while ($bouton = mysql_fetch_array($menu)) { ?><a href="<?php echo $bouton['lien']; ?>"><?php echo $bouton['nom']; ?></a><?php } ?></li>
