<?php
// Redirection $_Session
require ('/homez.167/donweb/www/Commonweb/Minkus/lib/script/redirect.inc.php');
// Var 
$Pseudo_amis=($_GET['amis']);
$Pseudo=($_SESSION['pseudo']);

if (isset($_POST['oui'])) {

	if ($Pseudo_amis==$Pseudo) {
		$erreur="Vous ne pouvez pas vous ajouter en amis!!</BR>
				<a href='http://www.3donweb.fr/Commonweb/Minkus/Gestion/'>Retour</a>";
		}
	// cnx ---------------------------------------------------------------------------------------------------------------------------------
	include ('/homez.167/donweb/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
						   
	@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
	@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
		
	$doublon_verif=@mysql_fetch_array(@mysql_query('SELECT COUNT(*) AS verif_exist FROM commonweb_amis WHERE pseudo_id="'.$Pseudo.'" AND pseudo_amis="'.$Pseudo_amis.'" AND groupe= "Public" AND accept="0" OR accept = "1"'));
		
	if ($doublon_verif['verif_exist']) {
		$erreur="Cette personne fais deja partie de vos amis mais elle n'a pas peut etre encore acceptée votre demande !!</BR>
				<a href='http://www.3donweb.fr/Commonweb/Minkus/Gestion/'>Retour</a>";
	}
	else {
	
	@mysql_query('INSERT INTO commonweb_amis (pseudo_amis, groupe, created, accept, pseudo_id)
				VALUES ("'.$Pseudo_amis.'", "Public", NOW(), "0", "'.$Pseudo.'")');
				
	$data_email=@mysql_fetch_array(@mysql_query('SELECT email FROM commonweb_user WHERE pseudo = "'.$Pseudo_amis.'"'));
	
	mysql_close();
				
	// declaration du mail
								
	$headers1 ='From: "no-reply@commonweb.com"<postmaster@3donweb.fr>'."\n"; 						
	$headers1 .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 						
	$headers1 .='Content-Transfer-Encoding: 8bit'; 																		
	$message1 ="<html><head><title>Ajout d'amis</title></head>						
				<body><p>Demande d'ajout d'amis</p>					
				<p>Une personne souhaite devenir votre amis, vous la connaissez peut-etre !</p>						
				<p>cliquer ci dessous pour confirmer sa demande et l'ajouter a vos amis<p>
				<p><a href='http://www.3donweb.fr/Commonweb/Minkus/Validation/validation_amis.php?pseudo=".$Pseudo."&pseudo_amis=".$Pseudo_amis."&valid=1'>Cliquer ici</a></p>						
				</body></html>" ; 						

	// Envoi mail confirmation --------------------------------------------------------------------------
							
	if (!mail($data_email['email'], "Demande d'ajout d'amis", $message1, $headers1)) { 							
		$erreur= "L'email n'a pu etre envoyé, veullez reesayer ulterieurement !";															
		}

	else {
header('Location: http://www.3donweb.fr/Commonweb/Minkus/Gestion/');
		}
	}
}
if(isset($_POST['non'])) {  
	header('Location: http://www.3donweb.fr/Commonweb/Minkus/Gestion/');
}

?>
<html>
<head>
<title>Confirmation d'ajout d'amis</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#999999">
<?php
if (isset($erreur)) {
	echo $erreur;
	}

if (!isset($erreur)) {
?>
<p>Etes-vous sur de vouloir Ajouter cette personne a vos amis ? </p>
<p>Une demande d'ajout lui sera envoyer ! </p>
<TABLE width="300">
  <form action="" method="POST">
<TR><TD align="center"><input name="oui" type="submit" value="OUI"></TD><TD align="center"><input name="non" type="submit" value="NON"/></TD></TR></form>
</TABLE>
<?php
}
?>
</body>
</html>
