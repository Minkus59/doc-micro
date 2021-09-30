<?php
require('/homez.585/docmicro/www/Jeu/lib/script/fonction_perso.inc.php');

$Mdp=trim($_POST['mdp']);
$Email=trim($_POST['email']);
$Hash=trim($_POST['hash']);

if ((isset($_POST['Recevoir']))&&($_POST['Recevoir']=="Recevoir")) {

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

	if (!preg_match("#^[A-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $Email)) { 
		$Erreur="L'adresse e-mail n'est pas valide !";
	}

	elseif (!mail($Email, "Validation d'inscription", $Message, $Entete)) { 							
		$Erreur="L'e-mail de confirmation n'a pu être envoyé, vérifiez que vous l'avez entré correctement !<br />";
		$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';
	}
				
	else {
		$Erreur="Un E-mail de confirmation vous a été envoyé à l'adresse suivante : ".$Email."<br />";
		$Erreur.="Veuillez valider votre adresse e-mail avant de vous connecter !";					
	}
}

if (isset($_POST['Jouer'])) {
	if ((isset($Email))&&(!empty($Email))&&(isset($Mdp))&&(!empty($Mdp))) {

		if (!preg_match("#^[A-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $Email)) { 
			$Erreur="L'adresse e-mail n'est pas valide !<br />";
			$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';
		}

		elseif (strlen($Mdp)<=7) { 
			$Erreur="Le mot de passe n'est pas conforme !<br />";
			$Erreur.="Le mot de passe doit contenir au moin 8 caractères !<br />";
			$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';
		}

		else {	

			$VerifEmail=$cnx->prepare("SELECT (email) FROM projet_user WHERE email=:email");
			$VerifEmail->bindParam(':email', $Email, PDO::PARAM_STR);
			$VerifEmail->execute();
			$NbRowsEmail=$VerifEmail->rowCount();	

			if ($NbRowsEmail!=1) { 			
				$Erreur="Erreur d'authentification !<br />";
				$Erreur.="Cette adresse e-mail ne correspond à aucun compte !<br />";
				$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';	
			}

			else {
				$Recup=$cnx->prepare("SELECT * FROM projet_user WHERE email=:email");
				$Recup->bindParam(':email', $Email, PDO::PARAM_STR);
				$Recup->execute();
				$User=$Recup->fetch(PDO::FETCH_OBJ);

				$Salt=md5($User->created);
				$MdpCrypt=crypt($Mdp, $Salt);

				$VerifMdp=$cnx->prepare("SELECT * FROM projet_user WHERE email=:email AND mdp=:mdpcrypt");
				$VerifMdp->bindParam(':mdpcrypt', $MdpCrypt, PDO::PARAM_STR);
				$VerifMdp->bindParam(':email', $Email, PDO::PARAM_STR);
				$VerifMdp->execute();
				$NbRowsCouple=$VerifMdp->rowCount();	
				
				if ($NbRowsCouple!=1) {
					$Erreur="Erreur d'authentification !<br />";
					$Erreur.="Le Mot de passe n'est pas valide !<br />";
					$Erreur.='<input type=button value=Retour onclick=javascript:history.back()><br />';
				}

				elseif ($User->valid!=1) {
					$Erreur="Votre adresse e-mail n'a pas été validé !<br />";
					$Erreur.="Lors de votre inscription un e-mail vous a été envoyé<br />";
					$Erreur.="Veuillez valider votre adresse e-mail en cliquant sur le lien reçu par e-mail.<br />";
					$Erreur.="vous pouvais toujours recevoir le mail a nouveau en cliquant sur ' recevoir '<br />";
			 		$Erreur.="<form action='' method='post'/><input type='hidden' name='hash' value='".$User->hash."'><input type='hidden' name='email' value='".$User->email."'><input type='submit' name='Recevoir' value='Recevoir'/></form>";
				}

				elseif ((!$VerifEmail)||(!$Recup)||(!$VerifMdp)) {
					$Erreur="Erreur Interne, veuillez réessayer ultérieurement !";
				}

				else {
					$InsertCnx=$cnx->prepare("UPDATE projet_user SET derniere_cnx = NOW() WHERE user=:hash");
					$InsertCnx->bindParam(':hash', $user->hash, PDO::PARAM_STR);
					$InsertCnx->execute();
				
					session_start();
					$_SESSION['hash'] = $User->hash;
					header('Location: http://www.doc-micro.fr/Jeu/Univers/');
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
<H1>Connexion au jeu</H1>
<p><?php if (isset($Erreur)){ echo $Erreur; } ?></p>

<form id="form_connexion" action="" method="POST">

<label class="col_1" for="email">Adresse e-mail</label></br>
<input type="email" name="email" id="email" required="required"/>
</p>
<label class="col_1" for="mdp">Mot de passe</label></br>
<input type="password" name="mdp" id="mdp" required="required"/>
</p>
<span class="col_1"></span>
<input type="submit" name="Jouer" value="Jouer"/>
</form>
</div>
</div>
<footer>

</footer>

</div>

</CENTER>
</body>
</html>