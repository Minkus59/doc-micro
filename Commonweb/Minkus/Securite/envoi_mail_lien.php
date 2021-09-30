<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php

$Email=$_POST['email'];

if ($Email == ''){
	echo 'Le champ "Email" est vide !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}
		
elseif (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $Email)){ 
	echo "<br/>L'adresse email n'est pas valide";
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}

else {
// Connexion à la base de données
			   
 include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
			   
	@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
	@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
               
        // insertion hote
$sql = mysql_query("SELECT * FROM commonweb_user WHERE email='$Email'");
$data = mysql_fetch_array($sql);				

		
$hash_user=$data['hash'];
$Pseudo_user=$data['pseudo'];
$Mdp_user=$data['mdp'];
$Email_user=$data['email'];
$Jour_user=$data['jour'];
$Mois_user=$data['mois'];
$Annee_user=$data['annee'];
$Genre_user=$data['genre'];


$sql1 = mysql_query("SELECT * FROM commonweb_securite WHERE pseudo_id='$Pseudo_user'");
$data2 = mysql_fetch_array($sql1);

$Ip_user=$data2['hote'];
// Envoi mail confirmation --------------------------------------------------------------------------

$headers ='From: "no-reply@commonweb.com"<perso@doc-micro.fr>'."\n"; 
$headers .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 
$headers .='Content-Transfer-Encoding: 8bit'; 
						
$message ="<html><head><title>Redeffinition du mot de passe</title></head>
		<body><p>Bonjour</p>
		<p>Veuillez cliquer sur le lien suivant pour enregistrer un nouveau mot de passe.</p>
		<p><a href:'http=//www.doc-micro.fr/Commonweb/Minkus/Securite/form_renvoi_mdp.php?id=$hash_user&pseudo=$Pseudo_user&mdp=$Mdp_user&email=$Email_user&jour=$Jour_user&mois=$Mois_user&annee=$Annee_user&genre=$Genre_user&hote=$Ip_user'>Cliquer ici</a><p>
		</body></html>"; 
	
						
if (!mail($Email, "Procedure de changement de mot de passe", $message, $headers)) { 
						
echo "L'email de confirmation n'a pu etre envoyé, vérifiez que vous l'avez entré correctement !";
echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';
mysql_close(); }

	else {
		echo"Bonjour ".$Pseudo_user."<br />";
		echo "Un nouvel email avec la procedure a suivre pour votre changement de mote de passe vous a ete renvoyé<br />";
		echo '<br/><input type="button" value="Fermer" onclick="self.close()"><br/>';
		mysql_close(); }

}
?>
</body>
</html>
