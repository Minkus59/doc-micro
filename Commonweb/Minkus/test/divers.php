<?php
// Redirection $_Session ------------------------------------------------------------------------------------------------------
require ('/homez.167/donweb/www/Commonweb/Minkus/lib/script/redirect.inc.php');
// Fonction cnx ------------------------------------------------------------------------------------------------------------------------
function cnx() {
			include ('/homez.167/donweb/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
										   
			@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
			@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
}
// VAR ------------------------------------------------------------------------------------------------------------------------
$Pseudo = ($_SESSION['pseudo']);
$nodelete = array('Public');

// Selecteur ---------------------------------------------------------------------------------------------------------
$Groupe=$_GET['divers'];
//------------------------------------------------------------------------------------------------------------------------------

if(isset($_POST['publier'])) {
		if(!$_POST['message']==''){
		
	// Connexion à la base de données -----------------------------------------------------------------------------------------
	cnx();

	$requete1 = 'INSERT INTO commonweb_actu (titre, message, created, groupe, pseudo_id)									
	VALUES ("'.($_POST['titre']).'", "'.($_POST['message']).'", NOW(), "'.($_POST['groupe']).'", "'.$Pseudo.'")';
	
	$result1 = @mysql_query($requete1);
	
	mysql_close();
		
	} 
}
?>
<html>
<head>
<title>Acceuil</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#999999">
<?php
// Menu ---------------------------------------------------------------------------------------------------------------------------------
require ('/homez.167/donweb/www/Commonweb/Minkus/lib/script/menu.inc.php');
//---------------------------------------------------------------------------------------------------------------------------------------
// Connexion à la base de données -----------------------------------------------------------------------------------------
cnx();
//---------------------------------------------------------------------------------------------------------------------------------------

// affiche non PUBLIC			
$sql2 = mysql_query("SELECT `commonweb_actu`.id, `commonweb_actu`.titre,`commonweb_actu`.message, `commonweb_actu`.created, `commonweb_actu`.pseudo_id,`commonweb_actu`.groupe
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
	
$groupe_list = mysql_query("SELECT * FROM commonweb_groupe WHERE pseudo_id = 'Admin' OR pseudo_id='".$Pseudo."'");
$groupe_list2 = mysql_query("SELECT * FROM commonweb_groupe WHERE pseudo_id = 'Admin' OR pseudo_id='".$Pseudo."'");

// Publication -----------------------------------------------------------------------------------------------------------------------------------------------------
?> 
<TABLE BORDER=1><form action="" method="post">
<TR><TD>Titre : </TD><TD><input type="text" name="titre"/></TD></TR>
<tr><td>Groupe :</td><td><select name="groupe"/><?php while ($list = mysql_fetch_array($groupe_list)) { $nom_groupe= explode("¤", $list['nom']); ?><option value="<?php echo $list['nom']; ?>"/><?php echo $nom_groupe[0]; } ?></td></tr>
<TR><TD>Publication : </TD><TD><textarea name="message" cols="60" rows="3"></textarea></TD></TR>
<TR><TD></TD><TD><div align="right"><input type="submit" name="publier" value="Publier"/></div></TD></TR></form>
</TABLE>
<?php	
// Selecteur de groupe publication ---------------------------------------------------------------------------------------------------------------------
?>
</p><TABLE BORDER=1><form action="" method="post">
<TR><TD>Groupe d'amis :</TD><TD><select name="groupe2"/><?php while ($list2 = mysql_fetch_array($groupe_list2)) { $nom_groupe= explode("¤", $list2['nom']); ?><option value="<?php echo $list2['nom']; ?>"/><?php echo $nom_groupe[0]; } ?></td><TD><div align="right"><input type="submit" name="afficher" value="Afficher"/></div></TD></tr></form>
</TABLE>
<?php
$Groupe2=explode("¤",$Groupe);
// Menu ---------------------------------------------------------------------------------------------------------------------------------
require ('/homez.167/donweb/www/Commonweb/Minkus/lib/script/actu_menu.inc.php');

// Actualite ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
?> 
<h1>Actualité <?php echo $Groupe2[0]; ?></h1> <?php 
while ($data2 = mysql_fetch_array($sql2)) {
?>
<p>
<div style="width:600px; border:solid 1px green;">
<li><?php $sql4 = mysql_query("SELECT avatar FROM commonweb_perso WHERE pseudo_id='".$data2['pseudo_id']."'"); while ($data4 = mysql_fetch_array($sql4)) { ?><img src='<?php echo $data4['avatar']?>'/><?php echo "<strong> - ".$data2['pseudo_id']."</strong>"; ?><?php } ?></li>
<li><?php echo "<strong>".$data2['titre']."</strong>"; ?></li>
<li><?php $message = wordwrap($data2['message'], 100, "<br />\n",true); echo $message; ?></li>
<?php 
$select_like_ouinon = mysql_num_rows(@mysql_query("SELECT id FROM commonweb_actu_like WHERE id_actu='".$data2['id']."' AND pseudo_id='".$Pseudo."'"));
$select_like_num = mysql_num_rows(@mysql_query("SELECT id FROM commonweb_actu_like WHERE id_actu=".$data2['id'].""));

if ($select_like_ouinon > 0) { echo ?><li><a href="like_no.php?id=<?php echo $data2['id']; ?>">Je n'aime plus</a> : <?php echo $select_like_num; ?></li><?php } 
else  { echo ?><li><a href="like.php?id=<?php echo $data2['id']; ?>">J'aime</a> : <?php echo $select_like_num; ?></li><?php } 
if ($_SESSION['pseudo']==$data2['pseudo_id']) {  echo ?><li><a href="suppr.php?id=<?php echo $data2['id'];  ?>">Suppprimer</a></li><?php } ?>
<li><font color="#FF0000" size="1">Date de publication : <?php echo $data2['created']; ?> </font></li>
</div></ul>
<?php 
// Commentaire ---------------------------------------------------------------------------------------------------------------------------
$sql6 = mysql_query("SELECT * FROM commonweb_actu_comm WHERE id_actu='".$data2['id']."'");
while ($data6 = mysql_fetch_array($sql6)) { 
?>
<ul>
<div style="width:500px; border:solid 1px yellow;">
<li><?php $sql8 = mysql_query("SELECT avatar FROM commonweb_perso WHERE pseudo_id='".$data6['pseudo_id']."'"); while ($data8 = mysql_fetch_array($sql8)) { ?><img src='<?php echo $data8['avatar']?>'/><?php } ?><?php  echo " - ".$data6['pseudo_id']; ?></li>
<li><?php $message = wordwrap($data6['message'], 85, "<br />\n",true); echo $message; ?></li>
<?php if ($_SESSION['pseudo']==$data6['pseudo_id']) {  echo ?><li><a href="suppr.php?id_comm=<?php echo $data6['id']; ?>">Suppprimer</a></li><?php } ?>
<li><font color="#FF0000" size="1">Date de publication : <?php echo $data6['created']; ?> </font></li>
</div></ul>
<?php 
}
// reponse -------------------------------------------------------------------------------------------------------------------------------
?>
<div style="width:600px; border:solid 1px black;">
<TABLE BORDER=1><form action="comm.php" method="post">
<input type="hidden" name="pseudo_id" value="<?php echo $_SESSION['pseudo']; ?>"/>
<input type="text" name="groupe_select" value="<?php echo $_POST['groupe2']; ?>"/>
<input type="hidden" name="id_actu" value="<?php echo $data2['id']; ?>"/>
<TR><TD>Commentaire</TD><TD><textarea name="commentaire" cols="59" rows="2"></textarea></TD></TR>
<TR><TD></TD><TD><div align="right"><input type="submit" name="ajouter" value="Ajouter"/></div></TD></TR></form>
</TABLE>
</div>
</div>
</p>
<?php } 
mysql_close();
?>
</body>
</html>