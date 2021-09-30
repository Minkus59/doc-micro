<?php
// Redirection $_Session
require ('/homez.764/docmicro/www/Commonweb/Minkus/lib/script/redirect.inc.php');

// Upload d'image
$chemin = $_FILES['avatar']['name'];
$rep = 'avatar/';
$fichier = basename($_FILES['avatar']['name']);
$taille_origin = filesize($_FILES['avatar']['tmp_name']);
$ext_gif = array('.gif');
$ext_png = array('.png');
$ext = array('.jpg', '.jpeg','.JPG','.JPEG');
$ext_origin = strchr($_FILES['avatar']['name'], '.');
$hash = md5(uniqid(rand(), true));
$Chemin_upload = "http://www.doc-micro.fr/Commonweb/Minkus/Renseignement/".$rep.$hash.$fichier."";
$TailleImageChoisie = @getimagesize($_FILES['avatar']['tmp_name']);

// Largeur et taiile max
$NouvelleLargeur = 50;
$taille_max = 2000000;

// Session
$Pseudo = ($_SESSION['pseudo']);

if(isset($_POST['ajouter'])) {

		if(!in_array($ext_origin, $ext)){
			$erreur = "erreur d'extention de fichier, seul l'extention .jpeg est autorisé pour le moment";
			}
			if($taille_origin>$taille_max){
			$erreur = "fichier trop volumineux, il ne doit dépassé les 2Mo taille conseillé : largeur 1400px sur 900px de hauteur";
			}
			if(!isset($erreur)){
				
					// Redimentionnement
					$ImageChoisie = imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
					$NouvelleHauteur = ( ($TailleImageChoisie[1] * (($NouvelleLargeur)/$TailleImageChoisie[0])) );
					$NouvelleImage = imagecreatetruecolor($NouvelleLargeur , $NouvelleHauteur) or die ("Erreur");
 					imagecopyresampled($NouvelleImage , $ImageChoisie, 0, 0, 0, 0, $NouvelleLargeur, $NouvelleHauteur, $TailleImageChoisie[0],$TailleImageChoisie[1]);
					imagedestroy($ImageChoisie);
					if(imagejpeg($NouvelleImage , $rep.$hash.$fichier, 85)){

						// Connexion à la base de données
						include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
											   
						@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
						@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
								
						//selection de l'ancien fichier pour suppression 
						
						$sql21 = mysql_query("SELECT avatar FROM commonweb_perso WHERE pseudo_id = '$Pseudo'");
						$data21 = mysql_fetch_array($sql21);
						
						
						if (!empty($data21['avatar'])) {
						unlink ($rep.basename($data21['avatar'])); }				
						
						// insertion
						
						$requete2 = 'INSERT INTO commonweb_perso (avatar,pseudo_id)									
						VALUES ("'.$Chemin_upload.'","'.$Pseudo.'")';
						$result2 = @mysql_query($requete2);
														
						$sql2 = 'UPDATE commonweb_perso SET avatar="'.$Chemin_upload.'" WHERE pseudo_id="'.($Pseudo).'"';
						$data2 = @mysql_query($sql2)or die('erreur');
						
						mysql_close();
						}
		
		else "L'avatar n'a pas été uploadé";
		}
	}
	if (isset($erreur)){
	echo $erreur;
	}

if (isset($_POST['modifier1'])) {

		// Connexion à la base de données
		include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
					   
		@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
		@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
		
		// Insertion
								
		$sql5 = 'UPDATE commonweb_perso SET site_web="'.$_POST['site_web'].'" WHERE pseudo_id="'.$Pseudo.'"';
		$data5 = @mysql_query($sql5)or die('erreur_update');
		
		$sql6 = 'UPDATE commonweb_perso SET proverbe="'.($_POST['proverbe']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data6 = @mysql_query($sql6)or die('erreur');
		
		$sql7 = 'UPDATE commonweb_perso SET mot_du_jour="'.($_POST['mot_du_jour']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data7 = @mysql_query($sql7)or die('erreur');
		
		$sql8 = 'UPDATE commonweb_perso SET situation_amour="'.($_POST['situation_amour']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data8 = @mysql_query($sql8)or die('erreur');
		
		$sql9 = 'UPDATE commonweb_perso SET situation_pro="'.($_POST['situation_pro']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data9 = @mysql_query($sql9)or die('erreur');
	
		$requete5 = 'INSERT INTO commonweb_perso (site_web,proverbe,mot_du_jour,situation_pro,situation_amour,pseudo_id)									
		VALUES ("'.$_POST['site_web'].'","'.$_POST['proverbe'].'","'.$_POST['mot_du_jour'].'","'.($_POST['situation_pro']).'","'.($_POST['situation_amour']).'",(SELECT pseudo FROM commonweb_user WHERE pseudo="'.$Pseudo.'"))';
		$result5 = @mysql_query($requete5);
						
		mysql_close();
}

if (isset($_POST['modifier2'])) {

		// Connexion à la base de données
		include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
					   
		@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
		@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
		
		// Insertion
		
		$sql10 = 'UPDATE commonweb_perso SET nom="'.($_POST['nom']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data10 = @mysql_query($sql10)or die('erreur');
		
		$sql11 = 'UPDATE commonweb_perso SET prenom="'.($_POST['prenom']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data11 = @mysql_query($sql11)or die('erreur');
		
		$sql12 = 'UPDATE commonweb_perso SET adresse="'.($_POST['adresse']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data12 = @mysql_query($sql12)or die('erreur');
		
		$sql13 = 'UPDATE commonweb_perso SET ville="'.($_POST['ville']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data13 = @mysql_query($sql13)or die('erreur');
		
		$sql14 = 'UPDATE commonweb_perso SET cp="'.($_POST['cp']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data14 = @mysql_query($sql14)or die('erreur');
		
		$sql15 = 'UPDATE commonweb_perso SET complement_adresse="'.($_POST['complement_adresse']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data15 = @mysql_query($sql15)or die('erreur');
		
		$sql16 = 'UPDATE commonweb_perso SET telephone_perso="'.($_POST['telephone_perso']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data16 = @mysql_query($sql16)or die('erreur');
		
		$sql17 = 'UPDATE commonweb_perso SET telephone_pro="'.($_POST['telephone_pro']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data17 = @mysql_query($sql17)or die('erreur');
		
		$sql18 = 'UPDATE commonweb_user SET email="'.($_POST['email']).'" WHERE pseudo="'.$Pseudo.'"';
		$data18 = @mysql_query($sql18)or die('erreur');
		
		$sql19 = 'UPDATE commonweb_perso SET email_secourt="'.($_POST['email_secourt']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data19 = @mysql_query($sql19)or die('erreur');
		
		$requete10 = 'INSERT INTO commonweb_perso (nom,prenom,adresse,ville,cp,complement_adresse,telephone_perso,telephone_pro,email_secourt,pseudo_id)									
		VALUES ("'.$_POST['nom'].'","'.$_POST['prenom'].'","'.$_POST['adresse'].'","'.$_POST['ville'].'","'.$_POST['cp'].'","'.$_POST['complement_adresse'].'","'.$_POST['telephone_perso'].'","'.$_POST['telephone_pro'].'","'.$_POST['email_secourt'].'",(SELECT pseudo FROM commonweb_user WHERE pseudo="'.$Pseudo.'"))';
		$result10 = @mysql_query($requete10);
				
		mysql_close();
}

if (isset($_POST['modifier3'])) {

		// Connexion à la base de données
		include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
					   
		@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
		@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
		
// fonction Taille des entrer
require ('/homez.764/docmicro/www/Commonweb/Minkus/lib/script/fonction_taille.inc.php');
		
		if ($_POST['mdp'] == ''){
		$erreur_mdp= 'Le champ "mot de passe" est vide !<br />';
		}
		
		elseif ($_POST['mdpre'] == ''){
		$erreur_mdp= 'Le champ "Retapez le mot de passe" est vide !<br />';
		}
		
		elseif ($_POST['mdp'] != $_POST['mdpre']){
		$erreur_mdp= 'Les champs mot de passe et retapez le mot de passe ne sont pas identique, veuillez corriger !<br />';
		}
		
		elseif ($_POST['mdpre'] == $_POST['pseudo']){
		$erreur_mdp= 'Votre mot de passe est identique au pseudo, veuillez corriger !<br />';
		}
		
		elseif (!taille_champ('mdp',8,25)){
		$erreur_mdp='Le mot de passe doit contenir entre 8 et 25 caractères !<br />';
		}
		
		else {
		$created= @mysql_fetch_array(@mysql_query('SELECT created FROM commonweb_user WHERE pseudo="'.$Pseudo.'"'));
		$salt=md5($created['created']);
		$mdp_crypt=crypt($_POST['mdp'], $salt);
		$sql20 = 'UPDATE commonweb_user SET mdp="'.$mdp_crypt.'" WHERE pseudo="'.$Pseudo.'"';
		$data20 = @mysql_query($sql20)or die('erreur');
		mysql_close();
	}
}

?>
<html>
<head>
<title>Renseignement</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../lib/css/commonweb2.css" rel="stylesheet" type="text/css" />
</head>

<body>
<center>
  	<div id="cadre_font"> 
    <div id="cadre_haut"><div id="cadre_menu">
		<?php
		// Menu ---------------------------------------------------------------------------------------------------------------------------------
		require ('/homez.764/docmicro/www/Commonweb/Minkus/lib/script/menu.inc.php');
		//---------------------------------------------------------------------------------------------------------------------------------------
		?>
	</div></div>
	<div id="dessous_middle">
    <div id="cadre_pub"><div id="cadre_int_pub"><div id="interrieur_titre">Publicité</div><div id="interrieur">BLABLABLABLABLABLABLAB LABLABLABLABLABLABL ABLABLABLABLAB LABLABLABLABLABLABLA BLABLABLABLAB LABLABLABLABLAB LABLABLABLABLABLAB LABLABLABLA BLABLABLABLABLABLABLABLABLABLAB LABLABLABLABLABLA</div></div></div>
    <div id="cadre_middle"><div id="cadre_post"><div id="interrieur_titre">Informations général</div><div id="interrieur">
<?php

		// selection de l'utilisateur concerné
		include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
						   
		@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
		@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");						
		$sql55 = mysql_query("SELECT * FROM commonweb_user WHERE pseudo='$Pseudo'");
		$data55 = mysql_fetch_array($sql55);
		
		$sql66 = mysql_query("SELECT * FROM commonweb_perso WHERE pseudo_id='$Pseudo'");
		$data66 = mysql_fetch_array($sql66);
		
		$Nom=$data66['nom'];
		mysql_close();
		
?>
<TABLE>
<TR><TD><img src='<?php echo $data66['avatar']?>'/></TD></TR>
<TR><TD>Pseudo :</TD><TD><?php echo $data55['pseudo']; ?></TD></TR>
<form action="" method="post" enctype="multipart/form-data"><TR><TD>Avatar :</TD><input type="hidden" name="avatar_name" value="http://www.doc-micro.fr/Commonweb/Minkus/Renseignement/avatar/<?php echo $chemin; ?>"/><TD><input type="file" name="avatar"/><input type="submit" name="ajouter" value="Ajouter"></TD></TR></form>
<TR><TD>Genre :</TD><TD><?php echo $data55['genre']; ?></TD></TR>
<TR><TD>Date de naissance :</TD><TD><?php echo $data55['jour']." / ".$data55['mois']." / ".$data55['annee']; ?></TD></TR>
<form action="" method="post"><TR><TD>Site web :</TD><TD><input type="text" name="site_web" value="<?php echo $data66['site_web']; ?>"/></TD></TR>
<TR><TD>Proverbe :</TD><TD><textarea name="proverbe" cols="40"><?php echo $data66['proverbe']; ?></textarea></TD></TR>
<TR><TD>Mot du jour :</TD><TD><textarea name="mot_du_jour" cols="40"><?php echo $data66['mot_du_jour']; ?></textarea></TD></TR>
<TR><TD>Etat civil :</TD><TD><select name="situation_amour"><option value="<?php echo $data66['situation_amour']; ?>"> <?php echo $data66['situation_amour']; ?> </option>
																								  <option value="Celibataire">Celibataire</option>
																								  <option value="Marié">Marié</option>
																								  <option value="Autre">Autre</option>
																								  </select></TD></TR>
<TR><TD>Situation professionel :</TD><TD><select name="situation_pro"><option value="<?php echo $data66['situation_pro']; ?>"> <?php echo $data66['situation_pro']; ?> </option>
																								  <option value="Sans Emploi">Sans emploi</option>
																								  <option value="CDI">CDI</option>
																								  <option value="CDD">CDD</option>
																								  <option value="Interim">Interim</option>
																								  <option value="Autre">Autre</option>
																								  </select></TD></TR>
<TR><TD></TD><TD><input type="submit" name="modifier1" value="Modifier"/></TD></TR></form>
</TABLE>

</div></div>
<div id="cadre_post"><div id="interrieur_titre">Informations personnelles</div><div id="interrieur">
<TABLE><form action="" method="post">
 <TR><TD>Nom :</TD><TD><input type="text" name="nom" value="<?php echo $data66['nom']; ?>"/></TD></TR>
<TR><TD>Prenom :</TD><TD><input type="text" name="prenom" value="<?php echo $data66['prenom']; ?>"/></TD></TR>
<TR><TD>Adresse :</TD><TD><input type="text" name="adresse" value="<?php echo $data66['adresse']; ?>"/></TD></TR>
<TR><TD>Ville :</TD><TD><input type="text" name="ville" value="<?php echo $data66['ville']; ?>"/></TD></TR>
<TR><TD>Code Postal :</TD><TD><input type="text" name="cp" value="<?php echo $data66['cp']; ?>"/></TD></TR>
<TR><TD>Complement d'adresse :</TD><TD><input type="text" name="complement_adresse" value="<?php echo $data66['complement_adresse']; ?>"/></TD></TR>
<TR><TD>Numero de telephone personnel :</TD><TD><input type="text" name="telephone_perso" value="<?php echo $data66['telephone_perso']; ?>"/></TD></TR>
<TR><TD>Numero de telephone professionnel :</TD><TD><input type="text" name="telephone_pro" value="<?php echo $data66['telephone_pro']; ?>"/></TD></TR>
<TR><TD>Email :</TD><TD><input type="text" name="email" value="<?php echo $data55['email']; ?>"/></TD></TR>
<TR><TD>Email de secourt:</TD><TD><input type="text" name="email_secourt" value="<?php echo $data66['email_secourt']; ?>"/></TD></TR>
<TR><TD></TD><TD><input type="submit" name="modifier2" value="Modifier"/></TD></TR></form>
</TABLE>
</div></div>
<div id="cadre_post"><div id="interrieur_titre">Changement de mot de passe</div><div id="interrieur">
<TABLE><form action="" method="post">
<?php 
if (isset($erreur_mdp)) { echo $erreur_mdp; }
?>
<TR><TD>Mot de passe :</TD><TD><input type="password" name="mdp"/></TD></TR>
<TR><TD>Retapez le mot de passe :</TD><TD><input type="password" name="mdpre"/></TD></TR>
<TR><TD></TD><TD><input type="submit" name="modifier3" value="Modifier"/></TD></TR></form>
</TABLE>
</div></div></div>
______________________________________________________________________________________________________________________________________________________
</div></div>
</center>
</body>
</html>
