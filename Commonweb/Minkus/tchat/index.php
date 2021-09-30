<?php
// Redirection $_Session ------------------------------------------------------------------------------------------------------
require ('/homez.167/donweb/www/Commonweb/Minkus/lib/script/redirect.inc.php');
// VAR ------------------------------------------------------------------------------------------------------------------------
$Pseudo = ($_SESSION['pseudo']);
//------------------------------------------------------------------------------------------------------------------------------

if(isset($_POST['publier'])) {
		if(!$_POST['message']==''){
		
	// Connexion à la base de données -----------------------------------------------------------------------------------------
	include ('/homez.167/donweb/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
				   
	@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
	@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");

	$requete1 = 'INSERT INTO commonweb_tchat ( message, created, pseudo_id)									
	VALUES ("'.($_POST['message']).'", NOW(), "'.$Pseudo.'")';
	
	$result1 = @mysql_query($requete1);
	mysql_close();

		}
	} 
// Connexion à la base de données -----------------------------------------------------------------------------------------
include ('/homez.167/donweb/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
				   
@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
	
$sql3 = mysql_query("SELECT * FROM commonweb_tchat ORDER BY id DESC ");
mysql_close();
?>

<html>
<head>
<title>Tchat</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#999999">
<?php
// Menu ---------------------------------------------------------------------------------------------------------------------------------
require ('/homez.167/donweb/www/Commonweb/Minkus/lib/script/menu.inc.php');
//---------------------------------------------------------------------------------------------------------------------------------------
?>
<TABLE BORDER=1><form action="" method="post">
<TR><TD width="56">Message</TD><TD width="323"><input width="323" type="text" name="message"/></TD></TR>
<TR><TD><div align="right"><input type="submit" name="publier" value="Publier"/></div></TD></TR></form>
</TABLE>
<?php
while ($data3 = mysql_fetch_array($sql3)) {
	echo $data3['pseudo_id']." : ".$data3['message']."</BR>";
	echo '<font color="#FF0000" size="1">'.$data3['created'].'</font></BR>';
}
?>

</body>
</html>
