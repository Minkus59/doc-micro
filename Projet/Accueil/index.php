<?php
require('../lib/script/fonction_perso.inc.php');
require('../lib/script/redirect.inc.php');

function chrono() {
	$temps=explode(" ", microtime());
	return $temps[0] + $temps[1];
	}
	
	$debut=chrono();

// VAR ------------------------------------------------------------------------------------------------------------------------
session_start();
$Email = $_SESSION['email'];
$Hash = $_SESSION['hash'];
$Pseudo = $_SESSION['pseudo'];

// Upload d'image
$chemin = $_FILES['photo']['name'];
$rep = 'photo/';
$rep_mini = 'mini_photo/';
$fichier = basename($_FILES['photo']['name']);
$taille_origin = filesize($_FILES['photo']['tmp_name']);
$ext_gif = array('.gif','.GIF');
$ext_jpg = array('.jpg', '.jpeg','.JPG','.JPEG');
$ext = array('.jpg', '.jpeg','.JPG','.JPEG','.gif','.GIF');
$ext_origin = strchr($_FILES['photo']['name'], '.');
$hash = md5(uniqid(rand(), true));
$Chemin_mini_upload = "http://www.doc-micro.fr/Projet/Accueil/".$rep_mini.$hash.$fichier."";
$Chemin_upload = "http://www.doc-micro.fr/Projet/Accueil/".$rep.$hash.$fichier."";
$TailleImageChoisie = @getimagesize($_FILES['photo']['tmp_name']);

// Largeur et taiile max
$NouvelleLargeur = 320;
$taille_max = 5000000;

// Selecteur d'affichage ---------------------------------------------------------------------------------------------------------
$Groupe="Public";
if(isset($_GET['divers'])) {
		$Groupe=$_GET['divers'];
}

$upim= false;
if (isset($_GET['upim'])) {
	$upim= true;
}

// Si image gif
if(isset($_POST['publier_img'])&&($_FILES['photo']['size']!=0)&&(in_array($ext_origin, $ext_gif))) {

					if(!in_array($ext_origin, $ext)){
						$erreur_ext = "erreur d'extention de fichier, seul les extentions .jpeg et .gif sont autorisé pour le moment";
						}
					elseif($taille_origin>$taille_max){
							$erreur_taille = "fichier trop volumineux, il ne doit dépassé les 2Mo taille conseillé : largeur 1400px sur 900px de hauteur";
						}
					elseif(isset($erreur)){
							$erreur = "Une erreur est survenue veuillez rééssayer !";
							}
							else {
									// Redimentionnement
					
						$ImageChoisie = imagecreatefromgif($_FILES['photo']['tmp_name']) or die ("Erreur1");
						$NouvelleHauteur = ( ($TailleImageChoisie[1] * (($NouvelleLargeur)/$TailleImageChoisie[0])) ) or die ("Erreur2");
						$NouvelleImage = imagecreatetruecolor($NouvelleLargeur , $NouvelleHauteur) or die ("Erreur3");
						$black = imagecolorallocate($NouvelleImage, 255, 255, 255);
						imagecolortransparent($NouvelleImage, $black);
						imagecopyresampled($NouvelleImage , $ImageChoisie, 0, 0, 0, 0, $NouvelleLargeur, $NouvelleHauteur, $TailleImageChoisie[0],$TailleImageChoisie[1]) or die ("Erreur4");
						
						if(imagegif($NouvelleImage , $rep_mini.$hash.$fichier, 85)){
						if (move_uploaded_file($_FILES['photo']['tmp_name'], $rep.$hash.$fichier)){
						
						cnx();
						// insertion
						$requete2 = 'INSERT INTO commonweb_actu (mini_chemin, chemin, message, created, groupe, pseudo_id)									
						VALUES ("'.$Chemin_mini_upload.'", "'.$Chemin_upload.'", "'.$_POST['message'].'", NOW(), "'.$_POST['groupe'].'", "'.$_SESSION['pseudo'].'")';
						$result2 = @mysql_query($requete2)or die("erreur6");
						mysql_close();
					}
			}
	}
}

// Si image jpg
if(isset($_POST['publier_img'])&&($_FILES['photo']['size']!=0)&&(in_array($ext_origin, $ext_jpg))) {

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
									// Redimentionnement

								$ImageChoisie = imagecreatefromjpeg($_FILES['photo']['tmp_name']) or die ("Erreur1");
								$NouvelleHauteur = ( ($TailleImageChoisie[1] * (($NouvelleLargeur)/$TailleImageChoisie[0])) ) or die ("Erreur2");
								$NouvelleImage = imagecreatetruecolor($NouvelleLargeur , $NouvelleHauteur) or die ("Erreur3");
								imagecopyresampled($NouvelleImage , $ImageChoisie, 0, 0, 0, 0, $NouvelleLargeur, $NouvelleHauteur, $TailleImageChoisie[0],$TailleImageChoisie[1]) or die ("Erreur4");
								
								if(imagejpeg($NouvelleImage , $rep_mini.$hash.$fichier, 70)){
								if (move_uploaded_file($_FILES['photo']['tmp_name'], $rep.$hash.$fichier)){
								
									// Connexion à la base de données
									cnx();
									
									// insertion
									$requete2 = 'INSERT INTO commonweb_actu (mini_chemin, chemin, message, created, groupe, pseudo_id)									
									VALUES ("'.$Chemin_mini_upload.'", "'.$Chemin_upload.'", "'.$_POST['message'].'", NOW(), "'.$_POST['groupe'].'", "'.$_SESSION['pseudo'].'")';
									$result2 = @mysql_query($requete2)or die("erreur5");
									mysql_close();
					}
			}
	 } 
}		
// Si pas d'image	
if(isset($_POST['publier_img'])&&($_FILES['photo']['size']==0)) {
		if (!empty($_POST['message'])) {

				cnx();
										
				// insertion
					$requete2 = 'INSERT INTO commonweb_actu (message, created, groupe, pseudo_id)									
					VALUES ("'.$_POST['message'].'", NOW(), "'.$_POST['groupe'].'", "'.$_SESSION['pseudo'].'")';
					$result2 = @mysql_query($requete2)or die("erreur7");
					mysql_close();
			}	
	} 	

//--Ajout comm  --------------------------------------------------------------------------------------------------------------
if (isset($_POST['ajouter'])) {
	if(!empty($_POST['commentaire'])) {
	
		cnx();
				
		@mysql_query("INSERT INTO commonweb_actu_comm (`id_actu`, `message`, `created`, `pseudo_id`)
					VALUES ('".$_POST['id_actu']."', '".$_POST['commentaire']."', NOW(), '".$_POST['pseudo_id']."')");
		mysql_close();

	}
}

//--Ajout actu  --------------------------------------------------------------------------------------------------------------
if(isset($_POST['publier'])) {
		if(!$_POST['message']==''){
				
	// Connexion à la base de données -----------------------------------------------------------------------------------------
	cnx();

	$requete1 = 'INSERT INTO commonweb_actu (message, created, groupe, pseudo_id)									
	VALUES ("'.($_POST['message']).'", NOW(), "'.($_POST['groupe']).'", "'.$Pseudo.'")';
	
	$result1 = @mysql_query($requete1);
	
	mysql_close();
		
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

<link rel="shortcut icon" href="../lib/img/projet.ico" />
<link href="../lib/css/projet.css" rel="stylesheet" type="text/css"/>

<title>Projet</title>
</head>

<body>
<CENTER>
<div id="all">
<!-- *******************************
** HEADER **************************
******************************** -->
<div id="header">
<?php
require('../lib/script/Header.inc.php');
?>
</div>

<!-- *******************************
** MIDDLE **************************
******************************** -->
<div id="middle"><div id="int">

    <div id="cadre_middle"><div id="cadre_post"><div id="interrieur_titre"><div id="Titre">Publication</div> <div id="upim"><?php if (isset($_GET['upim'])) { echo '<a href="/Commonweb/Minkus/Accueil/">Ajouter un texte</a>'; } else { echo '<a href="/Commonweb/Minkus/Accueil/?upim">Ajouter une image</a>'; } ?></div></div><div id="interrieur"><div id="cadre_publier">
		<?php
		// Connexion à la base de données -----------------------------------------------------------------------------------------
		
		cnx();
		
		$groupe_list = mysql_query("SELECT * FROM commonweb_groupe WHERE pseudo_id = 'Admin' OR pseudo_id='".$Pseudo."'");
		
		// Publication -----------------------------------------------------------------------------------------------------------------------------------------------------
		
		if ($upim==true) {

		?><form action="" method="post" enctype="multipart/form-data">
		<input type="file" name="photo"/>
		<textarea name="message"></textarea>
		<select name="groupe"/><?php while ($list = mysql_fetch_array($groupe_list)) { $nom_groupe= explode("¤", $list['nom']); ?><option value="<?php echo $list['nom']; ?>"/><?php echo $nom_groupe[0]; } ?>
		<input class="bouton" type="submit" name="publier_img" value="Publier"/option></form></div>
		<?php
		}
		else { 
		?> 
		<form action="" method="post">
		<textarea name="message"></textarea>
		<select name="groupe"/><?php while ($list = mysql_fetch_array($groupe_list)) { $nom_groupe= explode("¤", $list['nom']); ?><option value="<?php echo $list['nom']; ?>"/><?php echo $nom_groupe[0]; } ?>
		<input class="bouton" type="submit" name="publier" value="Publier"/></form></div>
		<?php } ?>
      </div>
    </div>
    <div id="cadre_groupe"><div id="interrieur_titre">Groupe</div><div id="interrieur">
		<?php
		if (!isset($_GET['divers'])) {
		// affiche PUBLIC
		
		$sql2 = mysql_query("SELECT `commonweb_actu`.mini_chemin,`commonweb_actu`.chemin, `commonweb_actu`.id, `commonweb_actu`.titre,`commonweb_actu`.message, `commonweb_actu`.created, `commonweb_actu`.pseudo_id,`commonweb_actu`.groupe
							FROM `commonweb_user`
							INNER JOIN `commonweb_amis`
							ON `commonweb_amis`.`pseudo_id` = `commonweb_user`.`pseudo` 
							INNER JOIN `commonweb_actu` 
							ON `commonweb_actu`.`pseudo_id` = `commonweb_amis`.`pseudo_amis`
							WHERE `commonweb_user`.`pseudo` = '".$Pseudo."'
							AND `commonweb_amis`.`accept` = '1'
							AND `commonweb_actu`.`groupe` = '".$Groupe."'
							AND `commonweb_amis`.`pseudo_id` = '".$Pseudo."'
							AND `commonweb_amis`.`groupe` = '".$Groupe."'
							OR `commonweb_amis`.`groupe` = '".$Pseudo."'
							AND `commonweb_actu`.`groupe` = '".$Groupe."'									
							ORDER BY `commonweb_actu`.`id` DESC");
		}	
		if (isset($_GET['divers'])) {			
		// affiche non PUBLIC			
		$sql2 = @mysql_query("SELECT `commonweb_actu`.mini_chemin,`commonweb_actu`.chemin,`commonweb_actu`.id, `commonweb_actu`.titre,`commonweb_actu`.message, `commonweb_actu`.created, `commonweb_actu`.pseudo_id,`commonweb_actu`.groupe
							FROM `commonweb_user`
							INNER JOIN `commonweb_amis`
							ON `commonweb_amis`.`pseudo_id` = `commonweb_user`.`pseudo` 
							INNER JOIN `commonweb_actu` 
							ON `commonweb_actu`.`pseudo_id` = `commonweb_amis`.`pseudo_amis`
							WHERE `commonweb_user`.`pseudo` = '".$Pseudo."'
							AND `commonweb_amis`.`accept` = '1'
							AND `commonweb_actu`.`groupe` = '".$Groupe."'
							AND `commonweb_amis`.`pseudo_id` = '".$Pseudo."'
							AND `commonweb_amis`.`groupe` = '".$Groupe."'									
							ORDER BY `commonweb_actu`.`id` DESC");	
		}
		// Selecteur de groupe publication ---------------------------------------------------------------------------------------------------------------------
		$Groupe2=explode("¤",$Groupe);
		// Menu ---------------------------------------------------------------------------------------------------------------------------------
		// require ('../lib/script/actu_menu.inc.php');
		?> 
</div></div>
    <div id="cadre_actu"><div id="interrieur_titre">Actualité <?php echo $Groupe2[0]; ?></div><div id="interrieur">
		<?php while ($data2 = @mysql_fetch_array($sql2)) { 	?>
		<!-- Ancre --><div id="<?php echo $data2['id']; ?>"></div>
		<?php $sql4 = mysql_query("SELECT avatar FROM commonweb_perso WHERE pseudo_id='".$data2['pseudo_id']."'"); while ($data4 = mysql_fetch_array($sql4)) { ?>
		<div id="cadre_actu_prin"><div id="droite">
		<?php if ($_SESSION['pseudo']==$data2['pseudo_id']) { ?><a href="suppr.php?id=<?php echo $data2['id']; ?>"><div id="suppr">.</div></a><?php } ?>
        <?php echo " - ".$data2['pseudo_id'].""; ?></div>
		<div id="gauche"><img src='<?php echo $data4['avatar']; ?>'/></div><?php } ?>
		<div id="middle">
		<?php
		// recherche URL dans la chaine ------------------------------------------
		
		$pattern='#https?://(?:\w|[/.-])*#';
		$pattern_2='#https?://(?:www\.)?youtu(?:be\.com/watch\?(?:.*?&(?:amp;)?)?v=|\.be/)([\w??\-]+)(?:&(?:amp;)?[\w\?=]*)?#i';	
		$pattern_3='#https?://(?:\w|[/.-])*(?:png|jpg)#';	
	
		if (preg_match_all($pattern_3, $data2['message'],$matches_image)) {
		$count_image = count($matches_image[0]);
		$message = nl2br($data2['message']); echo $message;
		?><div id="ligne_lien">__________________________________________________________</div><?php
		for ($i=0;$i!=$count_image;$i++) { 
		$nom = parse_url($matches_image[0][$i]);
		$lien= "<a href='".$matches_image[0][$i]."' target='_blank'><img src='".$matches_image[0][$i]."' height='220' width='320'/></a>"; ?>
		
		<li> <?php echo $lien; ?> </li>
		<?php } }

		else if (preg_match_all($pattern_2, $data2['message'],$matches_youtube)) {
		$count_youtube = count($matches_youtube[0]);
		$message = nl2br($data2['message']); echo $message;
		?><div id="ligne_lien">__________________________________________________________</div><?php
		for ($p=0;$p!=$count_youtube;$p++) {   ?>
		<li><iframe width="320" height="220" src="http://www.youtube.com/embed/<?php echo $matches_youtube[1][$p]; ?>?feature=player_detailpage" frameborder="0" allowfullscreen></iframe></li></p>
		<?php } }

		else if (preg_match_all($pattern, $data2['message'],$matches)) {
		$count = count($matches[0]);
		$message = nl2br($data2['message']); echo $message;
		?><div id="ligne_lien">__________________________________________________________</div><?php
		for ($i=0;$i!=$count;$i++) { 
		$nom = parse_url($matches[0][$i]);
		$lien= "<a href='".$matches[0][$i]."' target=_blank>".$nom['host']."</a>"; ?>
		<li><< <?php echo $lien; ?> >></li>

		<?php } } 
		else { 
		 if (!empty($data2['mini_chemin'])) { ?><li><a href="<?php echo $data2['chemin']; ?>" target="_blank"><img src="<?php echo $data2['mini_chemin']; ?>"/></a></li><li>_________________________________________________________</li><?php } ?>
		<li><?php $message = nl2br($data2['message']); echo $message; ?></li>
		<?php }
		
		//------------------------------------------------------------------------
		$select_like_ouinon = mysql_num_rows(@mysql_query("SELECT id FROM commonweb_actu_like WHERE id_actu='".$data2['id']."' AND pseudo_id='".$Pseudo."'"));
		$select_like_num = mysql_num_rows(@mysql_query("SELECT id FROM commonweb_actu_like WHERE id_actu=".$data2['id'].""));
		?><?php
		if ($select_like_ouinon > 0) { ?><a href="like_no.php?id=<?php echo $data2['id']; ?>">Je n'aime plus</a> : <?php echo $select_like_num; ?></li><?php } 
		else  { ?><a href="like.php?id=<?php echo $data2['id']; ?>">J'aime</a> : <?php echo $select_like_num; ?></li><?php } ?><li>
		<font color="#FF0000" size="1">Publié le <?php echo date("j/m/Y à h:i:s",strtotime($data2['created'])); ?> </font></li></div></div>
		<?php 
		// Commentaire ---------------------------------------------------------------------------------------------------------------------------
		$sql6 = mysql_query("SELECT * FROM commonweb_actu_comm WHERE id_actu='".$data2['id']."'");
		while ($data6 = mysql_fetch_array($sql6)) { ?>
		<div id="ligne">______________________________________________________________________________</div><div id="cadre_comm">
		<!-- Ancre --><div id="<?php echo $data6['id']; ?>"></div>
		<?php $sql8 = mysql_query("SELECT avatar FROM commonweb_perso WHERE pseudo_id='".$data6['pseudo_id']."'"); while ($data8 = mysql_fetch_array($sql8)) { ?>
		<div id="droite"><?php if ($_SESSION['pseudo']==$data6['pseudo_id']) { ?><a href="suppr.php?id_comm=<?php echo $data6['id']; ?>"><div id="suppr">.</div></a><?php } ?>
		<?php  echo " - ".$data6['pseudo_id']; ?></div>
		<div id="gauche2"><img src='<?php echo $data8['avatar']?>'/><?php } ?></div>
		<div id="middle2"><?php
		
		if (preg_match_all($pattern_2, $data6['message'],$matches_youtube)) {
		$count_youtube = count($matches_youtube[0]);
		$message = nl2br($data6['message']); echo $message;
		?><div id="ligne_lien">___________________________________________________</div><?php
		for ($p=0;$p!=$count_youtube;$p++) {   ?>
		<li><iframe width="320" height="220" src="http://www.youtube.com/embed/<?php echo $matches_youtube[1][$p]; ?>?feature=player_detailpage" frameborder="0" allowfullscreen></iframe></li></p>
		<?php } }

		else if (preg_match_all($pattern, $data6['message'],$matches_com)) {
		$count_com = count($matches_com[0]);
		$message_com = nl2br($data6['message']); echo $message_com;
		?><div id="ligne_lien">___________________________________________________</div><?php
		for ($i=0;$i!=$count_com;$i++) { 
		$nom_com = parse_url($matches_com[0][$i]);
		$lien_com= "<a href='".$matches_com[0][$i]."' target=_blank>".$nom_com['host']."</a>"; ?>
		
		<li><< <?php echo $lien_com; ?> >></li>

		<?php } } else { ?>
		<li><?php $message_com = nl2br($data6['message']); echo $message_com; ?></li>
		<?php } ?>
		<li><?php 
		$select_like_ouinon = mysql_num_rows(@mysql_query("SELECT id FROM commonweb_actu_comm_like WHERE id_com_actu='".$data6['id']."' AND pseudo_id='".$Pseudo."'"));
		$select_like_com_num = mysql_num_rows(@mysql_query("SELECT id FROM commonweb_actu_comm_like WHERE id_com_actu='".$data6['id']."'"));
		?><?php
		if ($select_like_ouinon > 0) { ?><a href="like_no_comm.php?id_comm=<?php echo $data6['id']; ?>">Je n'aime plus</a> : <?php echo $select_like_com_num; ?></li><?php } 
		else  { ?><a href="like_comm.php?id_comm=<?php echo $data6['id']; ?>">J'aime</a> : <?php echo $select_like_com_num; ?></li><?php } ?><li>
		<li><font color="#FF0000" size="1">Publié le <?php echo date("j/m/Y à h:i:s",strtotime($data6['created'])); ?> </font></li></div></div>
		<?php }
		// reponse -------------------------------------------------------------------------------------------------------------------------------
		?>
		<form action="#<?php echo $data2['id']; ?>" method="post">
		<input type="hidden" name="pseudo_id" value="<?php echo $_SESSION['pseudo']; ?>"/>
		<input type="hidden" name="id_actu" value="<?php echo $data2['id']; ?>"/>
		<div id="rep"><textarea name="commentaire"></textarea>
		<input class="bouton" type="submit" name="ajouter" value="Publier"/></div></form>
		<li>_______________________________________________________________________________________</li>
		<?php } 
		@mysql_close();
		?>
		
<li><?php echo "la page à été générée en : " .round(chrono() - $debut, 6)." secondes"; ?></li>
		
</div></div>

</div></div>
<!-- *******************************
** FOOTER **************************
******************************** -->
<div id="footer"><div id="int">
<?php
require('../lib/script/Footer.inc.php');
?>
</div></div>

</div>
</CENTER>
</body>
</html>