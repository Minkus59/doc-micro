<?php
$Pseudo=addslashes($_GET['pseudo']);
$Pseudo_amis=addslashes($_GET['pseudo_amis']);
$Valid=$_GET['valid'];
$valid=false;

if (isset($_POST['oui'])) {
	
	// cnx ---------------------------------------------------------------------------------------------------------------------------------
	include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
						   
	@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
	@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
	// validation 
	$inser='UPDATE commonweb_amis SET accept="'.$Valid.'" WHERE pseudo_id="'.$Pseudo.'" AND pseudo_amis="'.$Pseudo_amis.'"';
	$data = @mysql_query($inser);
	//ajout inversement
	@mysql_query('INSERT INTO commonweb_amis (pseudo_amis, groupe, created, accept, pseudo_id)
						VALUES ("'.$Pseudo.'", "Public", NOW(), "'.$Valid.'", "'.$Pseudo_amis.'")');
	// recherche mail amis			
	$data_email=@mysql_fetch_array(@mysql_query('SELECT email FROM commonweb_user WHERE pseudo = "'.$Pseudo.'"'));
	
		// declaration du mail
								
	$headers1 ='From: "no-reply@commonweb.com"<perso@doc-micro.fr>'."\n"; 						
	$headers1 .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 						
	$headers1 .='Content-Transfer-Encoding: 8bit'; 																		
	$message1 ="<html><head><title>Ajout d'amis</title></head>						
				<body><p>Confirmation de demande d'ajout d'amis</p>					
				<p>'".$Pseudo_amis."' à accepté vos demande</p>						
				<p>'".$Pseudo_amis."' figure a present dans votre liste d'amis<p>
				</body></html>"; 						

	// Envoi mail confirmation --------------------------------------------------------------------------
							
	if (!mail($data_email['email'], "Confirmation de demande d'ajout d'amis", $message1, $headers1)) { 							
		$erreur= "L'email n'a pu etre envoyé, veullez reesayer ulterieurement !";															
		}
			
	echo "Vous venez d'ajouter ".$Pseudo_amis." a votre liste d'amis. </BR>";
	echo "<input type='button' value='Fermer' onclick='self.close()'>";
	$valid=true;
}
?>
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<?php
if ($valid==false) {
?>
<p>Etes-vous sur de vouloir Ajouter cette personne a vos amis ? </p>
<p>Une confirmation lui sera envoyer ! </p>
<TABLE width="300">
  <form action="" method="POST">
<TR><TD align="center"><input name="oui" type="submit" value="OUI"></TD><TD align="center"><input name="non" type="submit" value="NON"/></TD></TR></form>
</TABLE>
<?php
}
?>
</body>
</html>
