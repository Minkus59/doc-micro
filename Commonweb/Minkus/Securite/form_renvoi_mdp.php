<html>
<head>
<title>nouveau mot de passe</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<?php

$Hash_url=$_GET['id'];
$Pseudo_url=$_GET['pseudo'];
$Mdp_url=$_GET['mdp'];
$Email_url=$_GET['email'];
$Jour_url=$_GET['jour'];
$Mois_url=$_GET['mois'];
$Annee_url=$_GET['annee'];
$Genre_url=$_GET['genre'];
$Hote_url=$_GET['hote'];

//Parametres de connexion à la base de données ----------------------------------------

include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');

//Cnx a la base ---------------------------------------------------------------------------
	
	@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
	@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");

// Verif donnee ---------------------------------------------------------------------------

$sql = mysql_query("SELECT * FROM commonweb_user WHERE hash='$Hash_url'" );
$sql1 = mysql_query("SELECT * FROM commonweb_securite WHERE pseudo_id='$Pseudo_url'" );

$data = mysql_fetch_array($sql);
$data2 = mysql_fetch_array($sql1);

if ($data['pseudo']!=$Pseudo_url) {
	echo "Le lien de verification a été modifié, verifier qu'il correspond a celui recu dans le mail de confirmation"; }
if ($data['mdp']!=$Mdp_url) {
	echo "Le lien de verification a été modifié, ou le mot de passe a deja ete changer, sinon verifier qu'il correspond a celui recu dans le mail de confirmation"; }
elseif ($data['email']!=$Email_url) {
	echo "Le lien de verification a été modifié, verifier qu'il correspond a celui recu dans le mail de confirmation"; }
elseif ($data['jour']!=$Jour_url) {
	echo "Le lien de verification a été modifié, verifier qu'il correspond a celui recu dans le mail de confirmation"; }
elseif ($data['mois']!=$Mois_url) {
	echo "Le lien de verification a été modifié, verifier qu'il correspond a celui recu dans le mail de confirmation"; }
elseif ($data['annee']!=$Annee_url) {
	echo "Le lien de verification a été modifié, verifier qu'il correspond a celui recu dans le mail de confirmation"; }
elseif ($data['genre']!=$Genre_url) {
	echo "Le lien de verification a été modifié, verifier qu'il correspond a celui recu dans le mail de confirmation"; }	
elseif ($data2['hote']!=$Hote_url) {
	echo "Le lien de verification a été modifié, verifier qu'il correspond a celui recu dans le mail de confirmation"; }
	
	else { 
	
?>		<p>Bonjour <?php echo $Pseudo_url; ?></p>
		<p>Entrer un nouveau mot de passe</p>
		
		<TABLE BORDER="0"><form action='renvoi_mdp.php' method='post'/>
		  <TR><TD> Mot de passe<strong><font color="#FF0000" size="1">*</font></strong> : </TD><TD> <input type='password' name='new_mdp'/> </TD></TR>
		  <TR><TD> Retapez le mot de passe<strong><font color="#FF0000" size="1">*</font></strong> : </TD><TD> <input type='password' name='new_mdp2'/> </TD> </TR>
		  <input type='hidden' name='hash' value='<?php echo $Hash_url; ?>'/>
		  <input type='hidden' name='pseudo' value='<?php echo $Pseudo_url; ?>'/>
		  <TR><TD><input type='reset' name='effacer' value="Effacer"/></TD><TD><input type='submit' name='enregistrer' value="Enregistrer"/></TD> </TR></form> 
		</TABLE>
		
		
<?php   mysql_close(); } ?>

</body>
</html>