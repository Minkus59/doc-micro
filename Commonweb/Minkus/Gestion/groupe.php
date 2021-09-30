<?php
// Redirection $_Session ------------------------------------------------------------------------------------------------------
require ('/homez.167/donweb/www/Commonweb/Minkus/lib/script/redirect.inc.php');
// VAR ------------------------------------------------------------------------------------------------------------------------
$Pseudo = ($_SESSION['pseudo']);
$Groupe = ($_GET['groupe']);
$nodelete = array('Public', 'public', 'publiç', 'Publiç', 'PUBLIC', 'PUBLIç');
$Nom=$_POST['nom'];
//Fonction cnx ----------------------------------------------------------------------------------------------------------------
function cnx() {
			include ('/homez.167/donweb/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
										   
			@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
			@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
}
//Verif pseudo ----------------------------------------------------------------------------------------------------------------
cnx();
$verif_pseudo=mysql_fetch_array(mysql_query("SELECT COUNT(*) AS verif_exist FROM commonweb_amis WHERE pseudo_amis='".$Nom."' AND pseudo_id='".$Pseudo."'"));
$verif_pseudo2=mysql_fetch_array(mysql_query("SELECT COUNT(*) AS verif_exist FROM commonweb_amis WHERE pseudo_amis='".$Nom."' AND groupe='".$Groupe."'"));
$Mail = mysql_fetch_array(mysql_query("SELECT email FROM commonweb_user WHERE pseudo= '".$Nom."'"));			

mysql_close();			
//------------------------------------------------------------------------------------------------------------------------------			
if (isset($_POST['Ajouter_amis'])) {

	if (empty($Nom)) {
		$erreur= "Aucun nom n'a été saisie !!" ;
		}
	elseif(!$verif_pseudo['verif_exist']) {
		$erreur= "Ce Pseudo n'existe pas dans votre liste d'amis ! <br/>";			
		}
	elseif($verif_pseudo2['verif_exist']) {
		$erreur= "Ce Pseudo existe deja pas dans votre groupe d'amis ! <br/>";			
		}
		else {
			$Groupe = explode("¤", $Groupe);
			// Envoi mail confirmation --------------------------------------------------------------------------
			
			$headers ='From: "no-reply@commonweb.com"<postmaster@3donweb.fr>'."\n"; 
			$headers .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 
			$headers .='Content-Transfer-Encoding: 8bit'; 
									
			$message ="<html><head><title>Confirmation d'ajout dans un groupe d'amis</title></head>
					<body><p>Confirmation d'ajout dans un groupe d'amis</p>
					<p>Veuillez cliquer sur le lien suivant pour valider votre inscription.</p>
					<p><a href='http://www.3donweb.fr/Commonweb/Minkus/Validation/validation_groupe.php?pseudo_amis=$Nom&accept=1&groupe1=$Groupe[0]&groupe2=$Groupe[1]&pseudo_id=$Pseudo'>Cliquez ici</a><p>
					</body></html>"; 
				
									
			if (!mail($Mail['email'], "Confirmation d'ajout dans un groupe d'amis", $message, $headers)) { 
									
			echo "L'email de confirmation n'a pu etre envoyé, vérifiez que vous l'avez entré correctement !";
			echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';
			}
			
			else {
			$erreur= "Un email de confirmation a été envoyé a votre ami, une fois accepté il apparaitra dans votre groupe";
			}
	}
}

if (isset($_POST['Supprimer_amis'])) {
			if (empty($Nom)) {
				$erreur= "Aucun nom n'a été saisie !!" ;
				}
			elseif ($Nom==$Pseudo) {
				$erreur= "Vous ne pouvez pas vous supprimer du groupe !!" ;
				}
				else {
					cnx();
					@mysql_query('DELETE FROM commonweb_groupe WHERE pseudo_id="'.$Nom.'" AND nom="'.$Groupe.'"');
					@mysql_query('DELETE FROM commonweb_amis WHERE pseudo_id="'.$Nom.'" AND groupe="'.$Groupe.'"');
	 				@mysql_query('DELETE FROM commonweb_amis WHERE pseudo_amis="'.$Nom.'" AND groupe="'.$Groupe.'"');
					@mysql_query('DELETE FROM commonweb_actu WHERE pseudo_id="'.$Nom.'" AND groupe="'.$Groupe.'"');
					@mysql_query('DELETE FROM commonweb_album WHERE pseudo_id="'.$Nom.'" AND groupe="'.$Groupe.'"');
					mysql_close();
	}	
}
		
if (isset($_POST['rechercher'])) {
		if (empty($_POST['amis'])) {
		$erreur= "Aucun pseudo n'a été saisie !!" ;
		}
		else {
		cnx();
		
		$recherche_pseudo=@mysql_query('SELECT pseudo FROM commonweb_user WHERE pseudo LIKE "%'.$_POST['amis'].'%"');
		$num_pseudo=@mysql_num_rows($recherche_pseudo);
		
		mysql_close();
		
		if ($num_pseudo>0) {
			$Valid_pseudo=true;
			
			}
	}
}

if (isset($_POST['Supprimer_groupe'])) {
	if (empty($_POST['nom'])) {
		$erreur= "Aucun nom n'a été saisie !!" ;
		}
				else {
					cnx();
					
					$verif_exist_membre=@mysql_num_rows(@mysql_query('SELECT * FROM commonweb_amis WHERE groupe="'.$Groupe.'"'));
				
					if ($verif_exist_membre>1) {
					$erreur= "Le groupe n'est pas vide, vous devez d'abort supprimer les membres du groupe !" ;
					}	 
					else {
						@mysql_query('DELETE FROM commonweb_groupe WHERE nom="'.$Nom.'"');
						@mysql_query('DELETE FROM commonweb_amis WHERE groupe="'.$Nom.'"');
						@mysql_query('DELETE FROM commonweb_actu WHERE groupe="'.$Nom.'"');
						@mysql_query('DELETE FROM commonweb_album WHERE pseudo_id="'.$Nom.'"');
						mysql_close();
		}
	}	
}
if (isset($_POST['Supprimer_amis_deff'])) {
	if (empty($_POST['nom'])) {
		$erreur= "Aucun nom n'a été saisie !!" ;
		}
				else {
					cnx();

					@mysql_query('DELETE FROM commonweb_amis WHERE pseudo_id="'.$Pseudo.'" AND pseudo_amis = "'.$Nom.'" AND groupe= "Public"');
					@mysql_query('DELETE FROM commonweb_amis WHERE pseudo_id="'.$Nom.'" AND pseudo_amis = "'.$Pseudo.'" AND groupe= "Public"');
					mysql_close();
		}	
}

if (isset($erreur)) {
	echo $erreur;
}

?>
<html>
<head>
<title>Gestion des amis</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#999999">
<?php
// Menu ---------------------------------------------------------------------------------------------------------------------------------
require ('/homez.167/donweb/www/Commonweb/Minkus/lib/script/menu.inc.php');
//---------------------------------------------------------------------------------------------------------------------------------------
$Groupe2=explode("¤",$Groupe);				
?> <h1>Groupe <?php echo $Groupe2[0]; ?></h1>
<?php
if ((!in_array($Groupe, $nodelete))&&($Groupe2[1]==$Pseudo)) { ?>
	<table><form action="" method="post">
	<input type="hidden" name="nom" value="<?php echo $Groupe; ?>"/>
	<tr><td><input type="submit" name="Supprimer_groupe" value="Supprimer ce groupe"/option></td></tr></form>
	</table></p>
<?php
}
?>
<p><h1>Mes groupe</h1></p>
<?php
cnx();

$groupe = @mysql_query("SELECT * FROM commonweb_groupe WHERE pseudo_id = '".$Pseudo."' OR pseudo_id='Admin'");

while ($list_groupe = mysql_fetch_array($groupe)) {
$nom_groupe= explode("¤", $list_groupe['nom']);
?> <a href="groupe.php?groupe=<?php echo $list_groupe['nom']; ?>"><?php echo $nom_groupe[0]."</BR>"; ?></a><?php
// membre
$amis = mysql_query("SELECT * FROM commonweb_amis WHERE pseudo_id='".$Pseudo."' AND groupe='".$list_groupe['nom']."'");

while ($list_nom = mysql_fetch_array($amis)) {
	 echo "--> ".$list_nom['pseudo_amis']."</BR>"; 
	 }
}
?>
<p>
<table border="1"><form action="" method="post">
<tr><td>Pseudo :</td><td><input type="text" name="nom"/></td></tr>
<tr><td><?php if (!in_array($Groupe, $nodelete)) { ?><input type="submit" name="Ajouter_amis" value="Ajouter un ami"/option><?php } ?></td><td><?php if ((!in_array($Groupe, $nodelete))&&($Groupe2[1]==$Pseudo)) { echo ?><input type="submit" name="Supprimer_amis" value="Supprimer un ami"/option></td><?php } ?><?php if (in_array($Groupe, $nodelete)) { echo ?><input type="submit" name="Supprimer_amis_deff" value="Supprimer un ami definitivement"/option></td><?php } ?></tr></form>
</table></p>

<p><h1>Rechercher des amis</h1></p>
<table border="1"><form action="" method="post">
<tr><td>Pseudo de votre ami :</td><td><input type="text" name="amis"/></td></tr>
<tr><td></td><td><input type="submit" name="rechercher" value="Rechercher"/option></td></tr></form>
</table>
<p>
<?php
if ($Valid_pseudo==true) {
	?><p><a><?php echo $num_pseudo; ?> pseudonyme correspond à votre recherche !</a></p><?php
	while ($data=mysql_fetch_array($recherche_pseudo)) {
		?> <a href="amis.php?amis=<?php echo $data['pseudo']; ?>"><?php echo $data['pseudo']."</BR>"; ?></a><?php
		}
}

?>

</body>
</html>
