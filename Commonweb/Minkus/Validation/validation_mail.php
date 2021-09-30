<html>
<head>
<title>Validation email</title>
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
$Hote_url_created=$_GET['hote'];



//Parametres de connexion à la base de données ----------------------------------------

include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');

//Cnx a la base ---------------------------------------------------------------------------
	
@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp) or die("Impossible de se connecter au serveur de bases de données.");
@mysql_select_db($BD_base) or die("Impossible de se connecter à la base de données.");

// Verif donnee ---------------------------------------------------------------------------

$sql = mysql_query("SELECT * FROM commonweb_user WHERE hash='$Hash_url'" );
$sql1 = mysql_query("SELECT * FROM commonweb_securite WHERE hote='$Hote_url_created'" );

$data = mysql_fetch_array($sql);
$data2 = mysql_fetch_array($sql1);

if ($data['pseudo']!=$Pseudo_url) {
	echo "Le lien de verification a été modifié, verifier qu'il correspond a celui recu dans le mail de confirmation1"; }
elseif ($data['email']!=$Email_url) {
	echo "Le lien de verification a été modifié, verifier qu'il correspond a celui recu dans le mail de confirmation2"; }
elseif ($data['jour']!=$Jour_url) {
	echo "Le lien de verification a été modifié, verifier qu'il correspond a celui recu dans le mail de confirmation3"; }
elseif ($data['mois']!=$Mois_url) {
	echo "Le lien de verification a été modifié, verifier qu'il correspond a celui recu dans le mail de confirmation4"; }
elseif ($data['annee']!=$Annee_url) {
	echo "Le lien de verification a été modifié, verifier qu'il correspond a celui recu dans le mail de confirmation5"; }
elseif ($data['genre']!=$Genre_url) {
	echo "Le lien de verification a été modifié, verifier qu'il correspond a celui recu dans le mail de confirmation6"; }	
elseif ($data2['hote']!=$Hote_url_created) {
	echo "Le lien de verification a été modifié, verifier qu'il correspond a celui recu dans le mail de confirmation7"; }
	
	elseif ($data['email_validation']=='1') {
		echo 'Votre compte est deja actif vous pouvez des a present vous connecter.<br />';
		echo '<input type=button onClick=(parent.location="http://www.doc-micro.fr/Commonweb/Minkus/Connexion/cnx.php/") value="Se connecter"><br/>'; }
		
	else {   
		$requete1 = "UPDATE commonweb_user SET email_validation =1 WHERE hash='$Hash_url'";
		$result1 = @mysql_query($requete1);
		echo "Merci d'avoir validé votre compte.<br />";
		echo "Vous pouvez des a present vous connecter.<br />";
		echo '<input type=button onClick=(parent.location="http://www.doc-micro.fr/Commonweb/Minkus/") value="Se connecter"><br/>';

		mysql_close();

		}
?>
</body>
</html>