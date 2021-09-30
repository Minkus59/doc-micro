<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php

$Pseudo = mysql_escape_string($_POST['pseudo']);

// Connexion à la base de données
			   
include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
			   
@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp) or die("Impossible de se connecter au serveur de bases de données.");

@mysql_select_db($BD_base) or die("Impossible de se connecter à la base de données.");

 // selection de l'utilisateur concerné

$sql = mysql_query("SELECT * FROM commonweb_user WHERE pseudo='$Pseudo'");				
$data = mysql_fetch_array($sql);

$sql1 = mysql_query("SELECT hote FROM commonweb_securite WHERE pseudo_id='$Pseudo'");				
$data2 = mysql_fetch_array($sql1);

$hash=$data['hash'];
$Email=$data['email'];
$Jour=$data['jour'];
$Mois=$data['mois'];
$Annee=$data['annee'];
$Genre=$data['genre'];
$Ip=$data2['hote'];

// Envoi mail confirmation --------------------------------------------------------------------------

$headers ='From: "no-reply@commonweb.com"<perso@doc-micro.fr>'."\n"; 
$headers .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 
$headers .='Content-Transfer-Encoding: 8bit'; 
						
$message ="<html><head><title>Validation d'inscription</title></head>
		<body><p>Validation d'inscription</p>
		<p>Veuillez cliquer sur le lien suivant pour valider votre inscription.</p>
		<p><a href='http://www.doc-micro.fr/Commonweb/Minkus/Validation/validation_mail.php?id=$hash&pseudo=$Pseudo&email=$Email&jour=$Jour&mois=$Mois&annee=$Annee&genre=$Genre&hote=$Ip>Cliquez ici</a><p>
		</body></html>"; 
	
						
if (!mail($Email, "Validation d'inscription", $message, $headers)) { 
						
echo "L'email de confirmation n'a pu etre envoyé, vérifiez que vous l'avez entré correctement !";
echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';
mysql_close(); }

	else {
		echo "Un nouvel email de confirmation vous a ete renvoyé<br />";
		echo '<br/><input type="button" value="Fermer" onclick="self.close()"><br/>';
		mysql_close(); }

?>
</body>
</html>
