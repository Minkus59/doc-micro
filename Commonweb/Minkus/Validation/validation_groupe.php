<?php
$amis=$_GET['pseudo_amis'];
$Pseudo=$_GET['pseudo_id'];
$groupe1=$_GET['groupe1'];
$groupe2=$_GET['groupe2'];
$accept=$_GET['accept'];
$Groupe=$groupe1."¤".$groupe2;

			include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
										   
			@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
			@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
			
			
			$liste_amis_groupe = @mysql_query("SELECT pseudo_id FROM commonweb_groupe WHERE nom = '".$Groupe."'");
			$liste_amis_groupe2 = @mysql_query("SELECT pseudo_id FROM commonweb_groupe WHERE nom = '".$Groupe."'");
			// ajout du nouveau chez les membre du groupe seulement dans la confirmation du mail 
			while ($nom=mysql_fetch_array($liste_amis_groupe)) {
			
			@mysql_query("INSERT INTO commonweb_amis (pseudo_amis, created, groupe, accept, pseudo_id)
							VALUES ('".$amis."', NOW(),'".$Groupe."', '1', '".$nom['pseudo_id']."')");
			}
			
			// ajout au nouveaux les membre du groupe seulement dans la confirmation du mail 
			while ($nom2=mysql_fetch_array($liste_amis_groupe2)) {
			
			@mysql_query("INSERT INTO commonweb_amis (pseudo_amis, created, groupe, accept, pseudo_id)
							VALUES ('".$nom2['pseudo_id']."', NOW(),'".$Groupe."', '1', '".$amis."')");
			} 
			
			// ajoute le groupe a la liste de mon amis
			$invers_groupe = @mysql_query("INSERT INTO commonweb_groupe (nom, created, pseudo_id)
							VALUES ('".$Groupe."', NOW(), '".$amis."')");
							
			// ajout dans le groupe
			@mysql_query("INSERT INTO commonweb_amis (pseudo_amis, created, groupe, accept, pseudo_id)
							VALUES ('".$amis."', NOW(),'".$Groupe."', '1', '".$amis."')");
			mysql_close();
?>

<html>
<head>
<title>Validation email</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
Vous faite a présent partie du groupe : <?php echo $Groupe; ?>.
<p>Félicitation !!

</body>
</html>