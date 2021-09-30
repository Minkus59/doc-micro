<?php
// Redirection $_Session ------------------------------------------------------------------------------------------------------
require ('/homez.167/donweb/www/Commonweb/Minkus/lib/script/redirect.inc.php');

// VAR ------------------------------------------------------------------------------------------------------------------------
$Pseudo = ($_SESSION['pseudo']);

// Upload d'image
$chemin = $_FILES['photo_principal']['name'];
$rep = 'photo/';
$fichier = basename($_FILES['photo_principal']['name']);
$taille_origin = filesize($_FILES['photo_principal']['tmp_name']);
$ext = array('.jpg', '.jpeg','.JPG','.JPEG');
$ext_origin = strchr($_FILES['photo_principal']['name'], '.');
$hash = md5(uniqid(rand(), true));
$Chemin_upload = "http://www.3donweb.fr/Commonweb/Minkus/Rencontre/Profil/".$rep.$hash.$fichier."";
$TailleImageChoisie = getimagesize($_FILES['photo_principal']['tmp_name']);

// Largeur et taiile max
$NouvelleLargeur = 125;
$taille_max = 4000000;

function cnx() {
			include ('/homez.167/donweb/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
										   
			@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
			@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
}

if (isset($_POST['modifier'])) {
	
		// Connexion à la base de données
		cnx();
		
		$sql2 = mysql_query("SELECT * FROM commonweb_user WHERE pseudo='".$Pseudo."'");
		$data2 = mysql_fetch_array($sql2);

		// Insertion
		
		$sql7 = 'UPDATE commonweb_rencontre_perso SET physique="'.($_POST['physique']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data7 = @mysql_query($sql7);
		
		$sql8 = 'UPDATE commonweb_rencontre_perso SET region="'.($_POST['region']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data8 = @mysql_query($sql8);
		
		$sql9 = 'UPDATE commonweb_rencontre_perso SET ville="'.($_POST['ville']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data9 = @mysql_query($sql9);
		
		$sql10 = 'UPDATE commonweb_rencontre_perso SET etat_civil="'.($_POST['etat_civil']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data10 = @mysql_query($sql10);
		
		$sql11 = 'UPDATE commonweb_rencontre_perso SET orientation="'.($_POST['orientation']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data11 = @mysql_query($sql11);
		
		$sql12 = 'UPDATE commonweb_rencontre_perso SET motivation="'.($_POST['motivation']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data12 = @mysql_query($sql12);
		
		$sql13 = 'UPDATE commonweb_rencontre_perso SET description="'.($_POST['description']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data13 = @mysql_query($sql13);
		
		$sql13 = 'UPDATE commonweb_rencontre_perso SET motdujour="'.($_POST['motdujour']).'" WHERE pseudo_id="'.$Pseudo.'"';
		$data13 = @mysql_query($sql13);
	
		$requete13 = 'INSERT INTO commonweb_rencontre_perso (annee,genre,physique,region,ville,etat_civil,orientation,motivation,description,motdujour,pseudo_id)									
		VALUES ("'.$data2['annee'].'","'.$data2['genre'].'","'.$_POST['physique'].'","'.$_POST['region'].'","'.$_POST['ville'].'","'.$_POST['etat_civil'].'","'.$_POST['orientation'].'","'.$_POST['motivation'].'","'.($_POST['description']).'","'.($_POST['motdujour']).'",(SELECT pseudo FROM commonweb_user WHERE pseudo="'.$Pseudo.'"))';
		$result13 = @mysql_query($requete13);
						
		mysql_close();
}

if(isset($_POST['ajouter'])) {

		if(!in_array($ext_origin, $ext)){
			$erreur = "erreur d'extention de fichier, seul l'extention .jpeg est autorisé pour le moment";
			}
			if($taille_origin>$taille_max){
			$erreur = "fichier trop volumineux, il ne doit dépassé les 2Mo taille conseillé : largeur 1400px sur 900px de hauteur";
			}
			if(!isset($erreur)){
				
					// Redimentionnement
					$ImageChoisie = imagecreatefromjpeg($_FILES['photo_principal']['tmp_name']);
					$NouvelleHauteur = ( ($TailleImageChoisie[1] * (($NouvelleLargeur)/$TailleImageChoisie[0])) );
					$NouvelleImage = imagecreatetruecolor($NouvelleLargeur , $NouvelleHauteur) or die ("Erreur");
 					imagecopyresampled($NouvelleImage , $ImageChoisie, 0, 0, 0, 0, $NouvelleLargeur, $NouvelleHauteur, $TailleImageChoisie[0],$TailleImageChoisie[1]);
					imagedestroy($ImageChoisie);
					if(imagejpeg($NouvelleImage , $rep.$hash.$fichier, 80)){

						cnx();
							
						//selection de l'ancien fichier pour suppression 
						$sql21 = mysql_query("SELECT photo FROM commonweb_rencontre_perso WHERE pseudo_id = '$Pseudo'");
						$data21 = mysql_fetch_array($sql21);
						
						
						if (!empty($data21['photo'])) {
						unlink ($rep.basename($data21['photo'])); }				
						
						// insertion
						
						$requete2 = 'INSERT INTO commonweb_rencontre_perso (photo,pseudo_id)									
						VALUES ("'.$Chemin_upload.'","'.$Pseudo.'")';
						$result2 = @mysql_query($requete2);
														
						$sql2 = 'UPDATE commonweb_rencontre_perso SET photo="'.$Chemin_upload.'" WHERE pseudo_id="'.($Pseudo).'"';
						$data2 = @mysql_query($sql2)or die('erreur');
						
						mysql_close();
						}
		
		else "La photo n'a pas été uploadé";
		}
	}
	if (isset($erreur)){
	echo $erreur;
	}
?>
<html>
<head>
<title>Mon profil</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFE6FF">
<?php
// Menu ---------------------------------------------------------------------------------------------------------------------------------
require ('/homez.167/donweb/www/Commonweb/Minkus/lib/script/menu.inc.php');
//---------------------------------------------------------------------------------------------------------------------------------------
cnx();

$sql2 = mysql_query("SELECT * FROM commonweb_user WHERE pseudo='".$Pseudo."'");
$data2 = mysql_fetch_array($sql2);

$sql3 = mysql_query("SELECT nom,prenom FROM commonweb_perso WHERE pseudo_id='".$Pseudo."'");
$data3 = mysql_fetch_array($sql3);
		
$sql = mysql_query("SELECT * FROM commonweb_rencontre_perso WHERE pseudo_id='".$Pseudo."'");
$data = mysql_fetch_array($sql);
?>
<h1>Rencontre</h1>
<p><?php require("/homez.167/donweb/www/Commonweb/Minkus/lib/script/rencontre_menu.inc.php"); ?>

<h1>Mon profil</h1>
<?php if (isset($erreur_pseudo)) {
		echo $erreur_pseudo;
		} ?>
<p>Informations personnelles</p>
<TABLE border="1">
<TR><TD><img src='<?php echo $data['photo']?>'/></TD></TR>
<form action="" method="post" enctype="multipart/form-data"><TR><TD>Photo principal :</TD><input type="hidden" name="photo_name" value="http://www.3donweb.fr/Commonweb/Minkus/Renseignement/avatar/<?php echo $chemin; ?>"/><TD><input type="file" name="photo_principal"/><input type="submit" name="ajouter" value="Ajouter"></TD></TR></form>
<TR><TD>Genre :</TD><TD><?php echo $data2['genre']; ?></TD></TR>
<TR><TD>Date de naissance :</TD><TD><?php echo $data2['jour']." / ".$data2['mois']." / ".$data2['annee']; ?></TD></TR>
<TR><TD>Nom :</TD><TD><?php echo $data3['nom']; ?></TD></TR>
<TR><TD>Prénom :</TD><TD><?php echo $data3['prenom']; ?></TD></TR>
<TR><TD>Pseudo :</TD><TD><?php echo $data2['pseudo']; ?></TD></TR>
<TR><TD>Physique :</TD><form action="" method="post"><TD><select name="physique"><option value="<?php echo $data['physique']; ?>"> <?php echo $data['physique']; ?> </option>
													<option value="Mince">Mince</option>
													<option value="Proportionnel">Proportionnel</option>
													<option value="Athlétique">Athlétique</option>
													<option value="Musclé">Musclé</option>
													<option value="Enrobé">Enrobé</option>
													<option value="Taille forte">Taille forte</option>
													<option value="Handicapé">Handicapé</option>
													</select></TD></TR>
<TR><TD>Region :</TD><TD><select name="region"><option value="<?php echo $data['region']; ?>"> <?php echo $data['region']; ?> </option>
												<option value="Alsace">Alsace</option>
												<option value="Aquitaine">Aquitaine</option>
												<option value="Auvergne">Auvergne</option>
												<option value="Bourgogne">Bourgogne</option>
												<option value="Bretagne">Bretagne</option>
												<option value="Centre">Centre</option>
												<option value="Champagne Ardenne">Champagne Ardenne</option>
												<option value="Corse">Corse</option>
												<option value="Franche Comté">Franche Comté</option>
												<option value="Haute Normandie">Haute Normandie</option>
												<option value="Ile de France">Ile de France</option>
												<option value="Languedoc Roussillon">Languedoc Roussillon</option>
												<option value="Limousin">Limousin</option>
												<option value="Lorraine">Lorraine</option>
												<option value="Midi Pyrénées">Midi Pyrénées</option>
												<option value="Nord Pas de Calais">Nord Pas de Calais</option>
												<option value="PACA">PACA</option>
												<option value="Pays de la Loire">Pays de la Loire</option>
												<option value="Picardie">Picardie</option>
												<option value="Poitou Charentes<">Poitou Charentes</option>
												<option value="Rhône Alpes">Rhône Alpes</option>
												</select></TD></TR>
<TR><TD>Ville :</TD><TD><input type="text" name="ville" value="<?php echo $data['ville']; ?>"/></TD></TR>
<TR><TD>Etat civil :</TD><TD><select name="etat_civil"><option value="<?php echo $data['etat_civil']; ?>"> <?php echo $data['etat_civil']; ?> </option>
															<option value="Celibataire">Celibataire</option>
															<option value="Marié">Marié</option>
															<option value="Autre">Autre</option>
															</select></TD></TR>
<TR><TD>Orientation :</TD><TD><select name="orientation"><option value="<?php echo $data['orientation']; ?>"> <?php echo $data['orientation']; ?> </option>
														  <option value="Hétérosexuel">Hétérosexuel</option>
														  <option value="Homosexuel">Homosexuel</option>
														  <option value="Bisexuel">Bisexuel</option>
														  </select></TD></TR>
<TR><TD>Motivation :</TD><TD><select name="motivation"><option value="<?php echo $data['motivation']; ?>"> <?php echo $data['motivation']; ?> </option>
														<option value="Rencontre sérieuse">Rencontre sérieuse</option>
														<option value="Rencontre éphémère">Rencontre éphémère</option>
														<option value="Rencontre amicale">Rencontre amicale</option>
														</select></TD></TR>
<tr><td>Description rapide : </td><td><textarea name="description" cols="40"><?php echo $data['description']; ?></textarea></td></tr>
<tr>
      <td>Phrase du jour : </td>
      <td><textarea name="motdujour" cols="40"><?php echo $data['motdujour']; ?></textarea></td></tr>																											
<TR><TD></TD><TD><input type="submit" name="modifier" value="Modifier"/></TD></TR></form>
</TABLE>
</body>
</html>
