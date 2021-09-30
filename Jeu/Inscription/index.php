<?php
require('/homez.585/docmicro/www/Jeu/lib/script/fonction_perso.inc.php');

$Pseudo=$_POST['pseudo'];
$Email=$_POST['email'];
$Mdp=trim(htmlspecialchars($_POST['mdp']));
$Mdp2=trim(htmlspecialchars($_POST['mdp2']));
$Valid=$_POST['valid'];
$Ip=$_SERVER['REMOTE_ADDR'];
$Hash = md5(uniqid(rand(), true));

		$VerifEmail=$cnx->prepare("SELECT (email) FROM projet_user WHERE email=:email");
		$VerifEmail->bindParam(':email', $Email, PDO::PARAM_STR);
		$VerifEmail->execute();
		$NbRowsEmail=$VerifEmail->rowCount();

		$VerifPseudo=$cnx->prepare("SELECT (pseudo) FROM projet_user WHERE pseudo=:pseudo");
		$VerifPseudo->bindParam(':pseudo', $Pseudo, PDO::PARAM_STR);
		$VerifPseudo->execute();
		$NbRowsPseudo=$VerifPseudo->rowCount();

if (isset($_POST['Valider'])) {
	if (!preg_match("#^[A-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $Email)) { 
		$Erreur="L'adresse e-mail n'est pas conforme !<br />";
		$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';
	}

	elseif (strlen($Pseudo)<=5) { 
		$Erreur="Le pseudonyme n'est pas conforme !<br />";
		$Erreur.="Le pseudonyme doit contenir au moin 6 caractères !<br />";
		$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';
	}

	elseif (!preg_match("#[A-Z]{1}+[A-z._-]{1,}[0-9]{1,}#", $Mdp)) { 
		$Erreur="Le mot de passe n'est pas conforme !<br />";
		$Erreur.="Le mot de passe doit commencé par 1 Majuscule contenir au moin 1 lettres et 1 chiffre !<br />";
		$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';
	}

	elseif (strlen($Mdp)<=7) { 
		$Erreur="Le mot de passe n'est pas conforme !<br />";
		$Erreur.="Le mot de passe doit contenir au moin 8 caractères !<br />";
		$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';
	}

	elseif ($Mdp!=$Mdp2) {
		$Erreur="Les mot de passe saisie doivent êtres identique !<br />";
		$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';
	}

	elseif ($Valid!="on") {
		$Erreur="Vous devez accepter les conditions d'utilisation !<br />";
		$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';
	}

	else {

		if ($NbRowsEmail==1) { 			
			$Erreur="Cette adresse E-mail existe déjà, veuillez en choisir une autre !<br />";
			$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';	
		}

		elseif ($NbRowsPseudo==1) {			
			$Erreur="Ce pseudonyme existe déjà, veuillez en choisir une autre !<br />";
			$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';	
		}

		else {

			$InsertUser=$cnx->prepare("INSERT INTO projet_user (pseudo, email, user, valided, created) VALUES (:pseudo, :email, :hash, 1, NOW())");
			$InsertUser->bindParam(':pseudo', $Pseudo, PDO::PARAM_STR);
			$InsertUser->bindParam(':email', $Email, PDO::PARAM_STR);
			$InsertUser->bindParam(':hash', $Hash, PDO::PARAM_STR);
			$InsertUser->execute();

			$InsertSecu=$cnx->prepare("INSERT INTO projet_securite (hote, user, created) VALUES (:hote, :hash, NOW())");
			$InsertSecu->bindParam(':hote', $Ip, PDO::PARAM_STR);
			$InsertSecu->bindParam(':hash', $Hash, PDO::PARAM_STR);
			$InsertSecu->execute();
			
			$RecupCreated=$cnx->prepare("SELECT created FROM projet_user WHERE user=:hash");
			$RecupCreated->bindParam(':hash', $Hash, PDO::PARAM_STR);
			$RecupCreated->execute();

			$DateCrea=$RecupCreated->fetch(PDO::FETCH_OBJ);
			$Salt=md5($DateCrea->created);
			$MdpCrypt=crypt($Mdp2, $Salt);

			$InsertMdp=$cnx->prepare("UPDATE projet_user SET mdp=:mdpcrypt WHERE user=:hash");
			$InsertMdp->bindParam(':mdpcrypt', $MdpCrypt, PDO::PARAM_STR);
			$InsertMdp->bindParam(':hash', $Hash, PDO::PARAM_STR);
			$InsertMdp->execute();

			if ((!$InsertUser)||(!$InsertSecu)||(!$InsertMdp)||(!$RecupCreated)||(!$VerifEmail)||(!$VerifPseudo)) {

				$DeleteUser=$cnx->prepare("DELETE FROM projet_user WHERE user=:hash");
				$DeleteUser->bindParam(':hash', $Hash, PDO::PARAM_STR);
				$DeleteUser->execute();

				$DeleteSecu=$cnx->prepare("DELETE FROM projet_securite WHERE user=:hash");
				$DeleteSecu->bindParam(':hash', $Hash, PDO::PARAM_STR);
				$DeleteSecu->execute();

				$Erreur="L'enregistrement des données à échouée, veuillez réessayer ultérieurement !<br />";
				$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';
			}

			else {
				$Entete ='From: "no-reply@pdoc-micro.cc"<postmaster@doc-micro.cc>'."\n"; 						
				$Entete .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 						
				$Entete .='Content-Transfer-Encoding: 8bit'; 																				
				$Message ="<html><head><title>Validation d'inscription</title></head>						
					<body>
					<img src='https://www.doc-micro.cc/lib/img/banniere.png'/></p>
					<p>Validation d'inscription</p>					
					<p>Veuillez cliquer sur le lien suivant pour valider votre inscription sur www.doc-micro.fr .</p>						
					<p><a href='http://www.doc-micro.fr/Jeu/Validation/?id=$Hash&Valid=1'>Cliquez ici</a></p>						
					____________________________________________________</p>
					Cordialement<br />
					www.doc-micro.cc </p>
					<font color='#FF0000'>Cet e-mail contient des informations confidentielles et / ou protégées par la loi. Si vous n'en êtes pas le véritable destinataire ou si vous l'avez reçu par erreur, informez-en immédiatement son expéditeur et détruisez ce message. La copie et le transfert de cet e-mail sont strictement interdits.</font>					
					</body></html>";

				if (!mail($Email, "Validation d'inscription", $Message, $Entete)) { 							
					$Erreur="L'e-mail de confirmation n'a pu être envoyé, vérifiez que vous l'avez entré correctement !<br />";
					$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';							
				}
							
				else {

					$Erreur="Bonjour, ".$Pseudo."<br />";
					$Erreur.="Merci de vous êtres inscrit sur www.doc-micro.fr<br />";
					$Erreur.="Un E-mail de confirmation vous a été envoyé à l'adresse suivante : ".$Email."<br />";
					$Erreur.="Veuillez valider votre adresse e-mail avant de vous connecter !";					
				}
			}
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META name="description" content="" >
<META name="category" content="">
<META name="keywords" content="">
<META name="robots" content="noindex, nofollow">
<META name="author" content="">
<META name="publisher" content="">
<META name="reply-to" content="">
<META name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />

<link rel="shortcut icon" href="" />
<link href="http://www.doc-micro.fr/Jeu/lib/css/projet_accueil.css" rel="stylesheet" type="text/css"/>

<title></title>
</head>

<body>
<CENTER>
<div id="all">

<header>

</header>

<nav>
<div id="int_nav">
<li><a href="http://www.doc-micro.fr/Jeu/Inscription">Inscription</a></li>
<li><a href="http://www.doc-micro.fr/Jeu/Connexion">Connexion</a></li>
</div>
</nav>

<div id="content">
<div id="int_content">

<H1>Inscription</H1>
<p><?php if (isset($Erreur)){ echo $Erreur; } ?></p>

<form id="form_inscription" action="" method="POST">

<label class="col_1" for="pseudo">Pseudonyme</label></br>
<input type="text" name="pseudo" id="pseudo" required="required"/>
</p>
<label class="col_1" for="email">Votre adresse e-mail actuel</label></br>
<input type="email" name="email" id="email" required="required"/>
</p>
<label class="col_1" for="mdp">Crée un mot de passe</label></br>
<input type="password" name="mdp" id="mdp" required="required"/>
</p>
<label class="col_1" for="mdp2">Confirmez le mot de passe</label></br>
<input type="password" name="mdp2" id="mdp2" required="required"/>
</p>
<label class="col_1" for="valid">J'accepte les <a href="../Conditions">conditions d'utilisation</a> :</label>
<input type="checkbox" name="valid" id="valid" required="required" value"J'accepte les conditions d'utilisation"/>
</p>
<span class="col_1"></span>
<input type="submit" name="Valider" value="M'inscrire"/>
<!--
<PRE><font color='#FF0000'>
Les inscriptions sont fermée pour le moment.
Elle seront disponible prochainement !
</font></PRE>
-->
</form>
</div>
</div>

<footer>

</footer>

</div>

</CENTER>
</body>
</html>