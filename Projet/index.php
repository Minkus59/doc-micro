<?php
require('./lib/script/fonction_perso.inc.php');

$Mdp=trim(htmlspecialchars($_POST['mdp']));
$Email=htmlspecialchars($_POST['email']);

if ((isset($_POST['Valider']))&&($_POST['Valider']=="Connexion")) {
	if ((isset($Email))&&(!empty($Email))&&(isset($Mdp))&&(!empty($Mdp))) {

		if (!preg_match("#^[A-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $Email)) { 
			$Erreur="L'adresse email n'est pas valide !<br />";
		}

		elseif (!TailleChamp($Mdp,5,11)) {
			$Erreur="Le mot de passe doit contenir entre 5 et 11 caractères !<br />";
		}

		else {		
			cnx();
			$VerifEmail=@mysql_fetch_array(@mysql_query("SELECT COUNT(*) AS verif_exist FROM commonweb_user WHERE email='".$Email."'"));
			@mysql_close();

			if (!$VerifEmail['verif_exist']) {			
				$Erreur="Cette adresse E-mail n'existe pas !<br />";	
			}

			else {
				cnx();
				$Recup=@mysql_fetch_array(@mysql_query("SELECT pseudo, hash, email_validation, created, email FROM commonweb_user WHERE email='".$Email."'"));

				$Salt=md5($Recup['created']);
				$MdpCrypt=crypt($Mdp, $Salt);

				$VerifMembre = @mysql_fetch_array(@mysql_query('SELECT COUNT(*) AS verif_exist FROM commonweb_user WHERE email = "'.$Email.'" AND mdp ="'.$MdpCrypt.'"'));
				@mysql_close();

				if (!$VerifMembre['verif_exist']) {
					$Erreur="Erreur d'authentification !<br />";
					$Erreur.="Le Mot de passe n'est pas valide !<br />";
				}

				elseif ($Recup['email_validation']!=1) {
					$Erreur="Votre adresse e-mail n'a pas été validé !<br />";
					$Erreur.="Lors de votre inscription un e-mail vous a été envoyé,<br />";
					$Erreur.="Veuillez valider votre adresse e-mail en cliquant sur le lien.<br />";
				}

				elseif ((!$VerifEmail)||(!$Recup)||(!$VerifMembre)) {
					$Erreur="L'enregistrement des données à echouée, veuillez réessayer ultérieurement !<br />";
				}

				else {
					cnx();
					@mysql_query("UPDATE commonweb_user SET derniere_cnx = NOW() WHERE email = '".$Email."'");
					mysql_close();
				
					session_start();
					$_SESSION['email'] = $Recup['email'];
					$_SESSION['hash'] = $Recup['hash'];
					$_SESSION['pseudo'] = $Recup['pseudo'];
					header('Location: http://www.doc-micro.fr/Projet/Accueil/');
				}
						
			}				
		}			

	}
}

?>
<!-- *******************************
***** Site réalisé par Doc-Micro ***
********* www.doc-micro.fr *********
*********************************-->
<!DOCTYPE html>
<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META name="description" content="" >
<META name="category" content="Accueil">
<META name="keywords" content="">
<META name="robots" content="index, follow">
<META name="author" content="Doc-Micro">
<META name="publisher" content="">
<META name="reply-to" content="">
<META name="copyright" content=""> 
<META name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />

<link rel="shortcut icon" href="./lib/img/projet.ico" />
<link href="./lib/css/projet.css" rel="stylesheet" type="text/css"/>

<title>Projet</title>
</head>

<body>
<CENTER>
<div id="all">
<!-- *******************************
** HEADER **************************
******************************** -->
<div id="Maintenance">Site en Maintenance</div>
<div id="Bouton1"></div><div id="Bouton2"></div><div id="Bouton3"></div>
<!-- *******************************
** MIDDLE **************************
******************************** -->
<div id="middle"><div id="int">

<H1>Connexion</H1>

<div id="erreur"><?php
if (isset($Erreur)) {
	echo $Erreur;
}
?></div>

<form id="form_cnx" action="" method="POST">

<label class="col_1" for="email">Adresse E-mail :</label>
<input type="text" name="email" id="email"/>
<span class="erreur">L'E-mail doit être saisie et conforme !</span>
</br>
<label class="col_1" for="mdp">Mot de passe :</label>
<input type="password" name="mdp" id="mdp"/>
<span class="erreur">Le mot de passe doit contenir entre 5 et 11 caractères !</span>
</br>
<span class="col_1"></span>
<input type="submit" name="Valider" value="Connexion"/>
</form>
<script type="text/javascript" src="./lib/js/cnx.js"></script>

</div></div>
<!-- *******************************
** FOOTER **************************
******************************** -->
<div id="footer">
<PRE><a href="./Accueil/"><img src="./lib/img/Accueil.png"/></a><a href="./Accueil/"><img src="./lib/img/Accueil.png"/></a></PRE>
</div>

</div>
</CENTER>
</body>
</html>