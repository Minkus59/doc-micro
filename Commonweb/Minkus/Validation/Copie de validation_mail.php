<html>
<head>
<title>Validation email</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<?php
			
			
			$liste_amis_groupe = @mysql_query("SELECT pseudo_id FROM commonweb_groupe WHERE nom = '".$Groupe."'");
			$liste_amis_groupe2 = @mysql_query("SELECT pseudo_id FROM commonweb_groupe WHERE nom = '".$Groupe."'");
			// ajout du nouveau chez les membre du groupe seulement dans la confirmation du mail 
			while ($nom=mysql_fetch_array($liste_amis_groupe)) {
			
			@mysql_query("INSERT INTO commonweb_amis (pseudo_amis, created, groupe, accept, pseudo_id)
							VALUES ('".$_POST['nom']."', NOW(),'".$Groupe."', '1', '".$nom['pseudo_id']."')");
			}
			
			// ajout au nouveaux les membre du groupe seulement dans la confirmation du mail 
			while ($nom2=mysql_fetch_array($liste_amis_groupe2)) {
			
			@mysql_query("INSERT INTO commonweb_amis (pseudo_amis, created, groupe, accept, pseudo_id)
							VALUES ('".$nom2['pseudo_id']."', NOW(),'".$Groupe."', '1', '".$_POST['nom']."')");
			} 
			
			// ajoute le groupe a la liste de mon amis
			$invers_groupe = @mysql_query("INSERT INTO commonweb_groupe (nom, created, pseudo_id)
							VALUES ('".$Groupe."', NOW(), '".$_POST['nom']."')");
							
			// ajout dans le groupe
			@mysql_query("INSERT INTO commonweb_amis (pseudo_amis, created, groupe, accept, pseudo_id)
							VALUES ('".$_POST['nom']."', NOW(),'".$Groupe."', '1', '".$_POST['nom']."')");
			
?>
</body>
</html>