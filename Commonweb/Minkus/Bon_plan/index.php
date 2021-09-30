<?php
// Redirection $_Session
require ('/homez.764/docmicro/www/Commonweb/Minkus/lib/script/redirect.inc.php');

// Upload d'image
$chemin = $_FILES['image']['name'];
$rep = 'mini_image/';
$fichier = basename($_FILES['image']['name']);
$taille_origin = filesize($_FILES['image']['tmp_name']);
$ext_gif = array('.gif','.GIF');
$ext_jpg = array('.jpg', '.jpeg','.JPG','.JPEG');
$ext = array('.jpg', '.jpeg','.JPG','.JPEG','.gif','.GIF');
$ext_origin = strchr($_FILES['image']['name'], '.');
$hash = md5(uniqid(rand(), true));
$Chemin_upload = "http://www.doc-micro.fr/Commonweb/Minkus/Bon_plan/".$rep.$hash.$fichier."";
$TailleImageChoisie = @getimagesize($_FILES['image']['tmp_name']);

// Largeur et taiile max
$NouvelleLargeur = 240;
$taille_max = 2000000;

// Session
$Pseudo = ($_SESSION['pseudo']);


// Si image gif
if(isset($_POST['ajouter'])&&($_FILES['image']['size']!=0)&&(in_array($ext_origin, $ext_gif))) {
		if($_POST['categorie']=="") {
			$erreur = "categorie vide !!";
			}
					if(!in_array($ext_origin, $ext)){
						$erreur = "erreur d'extention de fichier, seul les extentions .jpeg et .gif sont autorisé pour le moment";
						}
					elseif($taille_origin>$taille_max){
							$erreur = "fichier trop volumineux, il ne doit dépassé les 2Mo taille conseillé : largeur 1400px sur 900px de hauteur";
						}
					elseif(isset($erreur)){
							$erreur = "Une erreur est survenue veuillez rééssayer !";
							}
							else {
									$ImageChoisie = imagecreatefromgif($_FILES['image']['tmp_name']) or die ("Erreur1");
									$NouvelleHauteur = ( ($TailleImageChoisie[1] * (($NouvelleLargeur)/$TailleImageChoisie[0])) ) or die ("Erreur2");
									$NouvelleImage = imagecreatetruecolor($NouvelleLargeur , $NouvelleHauteur) or die ("Erreur3");
									$black = imagecolorallocate($NouvelleImage, 255, 255, 255);
									imagecolortransparent($NouvelleImage, $black);
									imagecopyresampled($NouvelleImage , $ImageChoisie, 0, 0, 0, 0, $NouvelleLargeur, $NouvelleHauteur, $TailleImageChoisie[0],$TailleImageChoisie[1]) or die ("Erreur4");
									imagedestroy($ImageChoisie) or die ("Erreur5");
									imagegif($NouvelleImage , $rep.$hash.$fichier, 80) or die ("Erreur6");
			
									// Connexion à la base de données
									include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
									@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
									@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
												
									$requete1 = 'INSERT INTO commonweb_bon_plan (nom, adresse, ville, cp, categorie, sous_categorie, complement_adresse, telephone, site_web, image, created, date_fin, pseudo_id)									
									VALUES ("'.($_POST['nom']).'", "'.($_POST['adresse']).'", "'.($_POST['ville']).'", "'.($_POST['cp']).'", "'.($_POST['categorie']).'", "'.($_POST['sous_categorie']).'", "'.($_POST['complement_adresse']).'", "'.($_POST['tel']).'", "'.($_POST['site_web']).'", "'.$Chemin_upload.'", NOW(), "'.($_POST['date_fin']).'", "'.($_SESSION['pseudo']).'")';
									$result1 = @mysql_query($requete1);
									mysql_close();
					}
			}

// Si image jpg
if(isset($_POST['ajouter'])&&($_FILES['image']['size']!=0)&&(in_array($ext_origin, $ext_jpg))) {
		if($_POST['categorie']=="") {
			$erreur = "categorie vide !!";
			}
					if(!in_array($ext_origin, $ext)){
						$erreur = "erreur d'extention de fichier, seul l'extention .jpeg est autorisé pour le moment";
						}
					elseif($taille_origin>$taille_max){
							$erreur = "fichier trop volumineux, il ne doit dépassé les 2Mo taille conseillé : largeur 1400px sur 900px de hauteur";
						}
					elseif(isset($erreur)){
							$erreur = "Une erreur est survenue veuillez rééssayer !";
							}
							else {
									$ImageChoisie = imagecreatefromjpeg($_FILES['image']['tmp_name']) or die ("Erreur1");
									$NouvelleHauteur = ( ($TailleImageChoisie[1] * (($NouvelleLargeur)/$TailleImageChoisie[0])) ) or die ("Erreur2");
									$NouvelleImage = imagecreatetruecolor($NouvelleLargeur , $NouvelleHauteur) or die ("Erreur3");
									imagecopyresampled($NouvelleImage , $ImageChoisie, 0, 0, 0, 0, $NouvelleLargeur, $NouvelleHauteur, $TailleImageChoisie[0],$TailleImageChoisie[1]) or die ("Erreur4");
									imagedestroy($ImageChoisie) or die ("Erreur5");
									imagejpeg($NouvelleImage , $rep.$hash.$fichier, 80) or die ("Erreur6");
			
									// Connexion à la base de données
									include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
									@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
									@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
												
									$requete1 = 'INSERT INTO commonweb_bon_plan (nom, adresse, ville, cp, categorie, sous_categorie, complement_adresse, telephone, site_web, image, created, date_fin, pseudo_id)									
									VALUES ("'.($_POST['nom']).'", "'.($_POST['adresse']).'", "'.($_POST['ville']).'", "'.($_POST['cp']).'", "'.($_POST['categorie']).'", "'.($_POST['sous_categorie']).'", "'.($_POST['complement_adresse']).'", "'.($_POST['tel']).'", "'.($_POST['site_web']).'", "'.$Chemin_upload.'", NOW(), "'.($_POST['date_fin']).'", "'.($_SESSION['pseudo']).'")';
									$result1 = @mysql_query($requete1);
									mysql_close();
					}
			}
			
// Si pas d'image	
if(isset($_POST['ajouter'])&&($_FILES['image']['size']==0)) {
		if($_POST['categorie']=="") {
			$erreur = "categorie vide !!";
			}
			else {
				$img= "http://www.doc-micro.fr/Commonweb/Minkus/doc/Bon_plan.jpg";
			
				// Connexion à la base de données
				include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
				@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
				@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
							
				$requete1 = 'INSERT INTO commonweb_bon_plan (nom, adresse, ville, cp, categorie, sous_categorie, complement_adresse, telephone, site_web, image, created, date_fin, pseudo_id)									
				VALUES ("'.($_POST['nom']).'", "'.($_POST['adresse']).'", "'.($_POST['ville']).'", "'.($_POST['cp']).'", "'.($_POST['categorie']).'", "'.($_POST['sous_categorie']).'", "'.($_POST['complement_adresse']).'", "'.($_POST['tel']).'", "'.($_POST['site_web']).'", "'.$img.'", NOW(), "'.($_POST['date_fin']).'", "'.($_SESSION['pseudo']).'")';
				$result1 = @mysql_query($requete1);
				mysql_close();
			}	
		}	
	if (isset($erreur)){
	echo $erreur."</br>";
	}

?>
<html>
<head>
<title>Bon plan</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<!-- Fonction DatePicker pour la selection de la date -->
	<link rel="stylesheet" href="http://www.doc-micro.fr/Commonweb/Minkus/lib/css/ui-darkness/jquery-ui.css" /> 
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
    <div id="cadre_middle">
	<div id="cadre_post"><div id="interrieur_titre">Ajouter un bon-plan</div><div id="interrieur">
<table><form action="" method="post" enctype="multipart/form-data">
<TR><TD>Photo associé : </TD><TD><input type="file" name="image"/></TD></TR>
<TR><TD>Categorie : </TD><TD><select name="categorie"/><option value="">-- --</option>	
														<option value="Restauration">Restauration</option>
														<option value="Informatique">Informatique</option>
														<option value="Autres">Autres</option></TD></TR>
<TR><TD>Sous-categorie : </TD><TD><select name="sous_categorie"/><option value="">-- --</option>	
														<option value="Pizzeria">Pizzeria</option>
														<option value="Detaillant">Detaillant</option>
														<option value="Autres">Autres</option></TD></TR>											
<TR><TD>Nom de l'enseigne : </TD><TD><input type="text" name="nom"/></TD></TR>
<TR><TD>Adresse : </TD><TD><input type="text" name="adresse"/></TD></TR>
<TR><TD>Complement d'adresse : </TD><TD><input type="text" name="complement_adresse"/></TD></TR>
<TR><TD>Ville : </TD><TD><input type="text" name="ville"/></TD></TR>
<TR><TD>Code postal : </TD><TD><input type="text" name="cp"/></TD></TR>
<TR><TD>Telephone : </TD><TD><input type="text" name="tel"/></TD></TR>
<TR><TD>Site web : </TD><TD><input type="text" name="site_web"/></TD></TR>
<TR><TD>Date fin : </TD><TD><input type="text" name="date_fin" id="datepicker"/></TD></TR>
<TR><TD></TD><TD><div align="right"><input type="submit" name="ajouter" value="Ajouter"/></div></TD></TR></form>
</TABLE>
</div></div>
<?php

	// Connexion à la base de données
	include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
				   
	@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
	@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
	
	$sql3 = mysql_query("SELECT * FROM commonweb_bon_plan ORDER BY id DESC");
?><div id="cadre_groupe"><div id="interrieur_titre">Bon plan</div><div id="interrieur">
<?php 
while ($data3 = mysql_fetch_array($sql3)) {
?>
<p>
<table>
<TR><TD>Photo associé : </TD><TD width="240"><img src="<?php echo $data3['image']; ?>"/></TD></TR>
<TR><TD>Categorie : </TD><TD><?php echo $data3['categorie']; ?></TD></TR>
<TR><TD>Sous-categorie : </TD><TD><?php echo $data3['sous_categorie']; ?></TD></TR>
<TR><TD>Nom : </TD><TD><?php echo $data3['nom']; ?></TD></TR>
<TR><TD>Adresse : </TD><TD><?php echo $data3['adresse']; ?></TD></TR>
<TR><TD>Complement d'adresse : </TD><TD><?php echo $data3['complement_adresse']; ?></TD></TR>
<TR><TD>Ville : </TD><TD><?php echo $data3['ville']; ?></TD></TR>
<TR><TD>Code postal : </TD><TD><?php echo $data3['cp']; ?></TD></TR>
<TR><TD>Telephone : </TD><TD><?php echo $data3['telephone']; ?></TD></TR>
<TR><TD>Site web : </TD><TD><?php echo $data3['site_web']; ?></TD></TR>
<TR><TD>Date fin : </TD><TD><?php echo $data3['date_fin']; ?></TD></TR>
<TR><TD>Auteur : </TD><TD><?php echo $data3['pseudo_id']; ?></TD></TR>

<?php
$select_like_ouinon = mysql_num_rows(@mysql_query("SELECT id FROM commonweb_bon_plan_like WHERE id_bon_plan='".$data3['id']."' AND pseudo_id='".$Pseudo."'"));
$select_like_num = mysql_num_rows(@mysql_query("SELECT id FROM commonweb_bon_plan_like WHERE id_bon_plan=".$data3['id'].""));

if ($select_like_ouinon > 0) { 
?><TR><TD><a href="like_no.php?id=<?php echo $data3['id']; ?> ">Je n'aime plus</a> : <?php echo $select_like_num; ?></TD></TR><?php
} 
else  { 
?><TR><TD><a href="like.php?id=<?php echo $data3['id']; ?> ">J'aime</a> : <?php echo $select_like_num; ?></TD></TR><?php } if ($Pseudo==$data3['pseudo_id']) {  ?><tr><TD><a href="suppr.php?id=<?php echo $data3['id']; ?> ">Suppprimer</a></TD></tr><?php 
} 

?>
</table>
<font color="#FF0000" size="1">Date de publication : <?php echo $data3['created']; ?> </font></p>
<?php } ?>
</div></div></div>
______________________________________________________________________________________________________________________________________________________

</div></div>
</center>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>    
	<script src="http://www.doc-micro.fr/Commonweb/Minkus/lib/js/jquery-ui.js"></script>
	<script> jQuery(function($) { 
	$( "#datepicker" ).datepicker({ 
	dateFormat : 'dd/mm/yy',
	 minDate : 0 
	 });
 }); </script>


</body>
</html>