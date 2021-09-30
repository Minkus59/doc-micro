<?php
if (isset($_POST['ajouter'])) {

						// Connexion à la base de données
	include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
									   
	@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
	@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
		
	@mysql_query("INSERT INTO commonweb_categorie_list (nom) VALUES ('".$_POST['nom']."')");
	
	mysql_close();
	
}
if (isset($_POST['ajouter_sous'])) {
	if (!empty($_POST['id'])) {

						// Connexion à la base de données
	include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
									   
	@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
	@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
		
	@mysql_query("INSERT INTO commonweb_sous_categorie_list (nom,id_cat) VALUES ('".$_POST['nom']."', '".$_POST['id']."')");
	
	mysql_close();
	}
}
?>
<html>
<head>
<title>Ajout categorie</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<form action="" method="post">
nom: <input type="text" name="nom">
<input type="submit" name="ajouter" value="Ajouter">
</form>
<form action="" method="post">
nom: <input type="text" name="nom">
id: <input type="text" name="id">
<input type="submit" name="ajouter_sous" value="Ajouter">
</form>
<?php
	include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
									   
	@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
	@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
	
	$sql=mysql_query("SELECT * FROM commonweb_categorie_list");
	
	while ($data=mysql_fetch_array($sql)) {
	echo $data['id']." - " .$data['nom']."<br>";
	?><ul><?php
	$sql2=mysql_query("SELECT * FROM commonweb_sous_categorie_list WHERE id_cat='".$data['id']."'");
	while ($data2=mysql_fetch_array($sql2)) {
	echo $data2['nom']." / ";
	} ?></ul><?php
	}
?>
</body>
</html>
