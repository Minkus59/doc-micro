<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
$Pseudo=$_POST['pseudo'];
$New_mdp=$_POST['new_mdp'];
$New_mdp2=$_POST['new_mdp2'];
$Hash=$_POST['hash'];

// fonction Taille des entrer
require ('/homez.764/docmicro/www/Commonweb/Minkus/lib/script/fonction_taille.inc.php');

// VERIF

if ($New_mdp != $New_mdp2) {
	echo 'le champ Mote de passe et Retapez le mote de passe ne sont pas identique, veuillez corriger !!';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';
	}
elseif ($New_mdp == ''){
	echo 'Le champ "Mot de passe" est vide !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}
		
elseif (!taille_champ('new_mdp',8,25)){
	echo'Le mot de passe doit contenir entre 8 et 25 caractères !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}

elseif ($New_mdp == $Pseudo){
	echo 'Le mot de passe doit etre different du pseudo !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}
	
	else {

//Parametres de connexion à la base de données ----------------------------------------

include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');

//Cnx a la base ---------------------------------------------------------------------------
	
	@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
	@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
	
		$created= @mysql_fetch_array(@mysql_query('SELECT created FROM commonweb_user WHERE pseudo="'.$Pseudo.'"'));
		$salt=md5($created['created']);
		$mdp_crypt=crypt($New_mdp, $salt);
		$requete1 = 'UPDATE commonweb_user SET mdp="'.$mdp_crypt.'" WHERE hash="'.($Hash).'"';
		$result1 = @mysql_query($requete1)or die('erreur lors de la modification du mot de passe');
		
		
		echo "Votre mot de passe a ete enregistrer avec succee.<br />";
		echo "Vous pouvez des a present vous connecter.<br />";
		echo '<input type=button onClick=(parent.location="http://www.doc-micro.fr/Commonweb/Minkus/") value="Se connecter"><br/>';
		}
?>
</body>
</html>
