<?php
// MENU ---------------------------------------------------------------------------------------------------------------------------------
include ('/homez.167/donweb/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
				   
@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
	 
$menu = mysql_query("SELECT * FROM commonweb_rencontre_menu");
mysql_close();

?>
<p><table border="1"><TR>
<?php while ($bouton = mysql_fetch_array($menu)) { ?> <TD>&nbsp;<a href="<?php echo $bouton['lien']; ?>"><?php echo $bouton['nom']; ?></a>&nbsp;</TD><?php }
?></TR></table></p><?php
//---------------------------------------------------------------------------------------------------------------------------------------
?> 