<?php
// Redirection $_Session
require ('/homez.764/docmicro/www/Commonweb/Minkus/lib/script/redirect.inc.php');

// Session
$Pseudo = ($_SESSION['pseudo']);
$Valid_pseudo=false;
//---------------------------------------------------------------------------------------------------
include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
							   
@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
				 
$sql2=@mysql_query('SELECT * FROM commonweb_groupe WHERE pseudo_id="'.$Pseudo.'" AND nom="'.$_POST['nom'].'"');
$data2=@mysql_num_rows($sql2);

$nouveau_nom= $_POST['nom']."¤".$Pseudo;
$verif_groupe=@mysql_fetch_array(@mysql_query("SELECT COUNT(*) AS verif_exist FROM commonweb_groupe WHERE nom LIKE '".$nouveau_nom."' AND pseudo_id = '".$Pseudo."'"));			

mysql_close();

$nodelete = array('Public', 'public', 'publiç', 'Publiç', 'PUBLIC', 'PUBLIç');
// Ajouter le control doublon
if (isset($_POST['Ajouter_groupe'])) {
	if (in_array($_POST['nom'], $nodelete)) {
		$erreur= "Il est impossible de crée le groupe Public, il existe deja !";
		}
	elseif($verif_groupe['verif_exist']) {
		$erreur= "Ce groupe existe deja ! <br/>";			
		}
	elseif ($data2>0) {
		$erreur= "Ce groupe existe deja !!";
		}		
	elseif (empty($_POST['nom'])) {
		$erreur= "Aucun nom n'a été saisie !!" ;
		}
		else {
			include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
							   
			@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
			@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
			
			$sql = 'INSERT INTO commonweb_groupe (nom, created, pseudo_id)
					VALUES ("'.$nouveau_nom.'", NOW(), "'.$Pseudo.'")';
					
			// je m'ajoute a la liste des membre du groupe
			@mysql_query("INSERT INTO commonweb_amis (pseudo_amis, created, groupe, accept, pseudo_id)
							VALUES ('".$Pseudo."', NOW(), '".$nouveau_nom."', '1', '".$Pseudo."')");
			$data = @mysql_query($sql);
			mysql_close();
		}
	}		
	
if (isset($_POST['rechercher'])) {
		if (empty($_POST['amis'])) {
		$erreur= "Aucun pseudo n'a été saisie !!" ;
		}
		else {
		include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
									   
		@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
		@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
		
		$recherche_pseudo=@mysql_query('SELECT pseudo FROM commonweb_user WHERE pseudo LIKE "%'.$_POST['amis'].'%"');
		$num_pseudo=@mysql_num_rows($recherche_pseudo);
		
		mysql_close();
		
		if ($num_pseudo>0) {
			$Valid_pseudo=true;
		}
	}
}
	
if (isset($erreur)) {
	echo $erreur;
}
?>
<html>
<head>
<title>Gestion des groupes et utilisateurs</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
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
    <div id="cadre_middle"><div id="cadre_post"><div id="interrieur_titre">Mes groupes</div><div id="interrieur">
<?php
include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');		   
@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");

$groupe = @mysql_query("SELECT * FROM commonweb_groupe WHERE pseudo_id = '".$Pseudo."' OR pseudo_id='Admin'");

while ($list_groupe = mysql_fetch_array($groupe)) {
$nom_groupe= explode("¤", $list_groupe['nom']);
?> <p><a href="groupe.php?groupe=<?php echo $list_groupe['nom']; ?>"><?php echo $nom_groupe[0]."</BR>"; ?></a><?php
// membre
$amis = mysql_query("SELECT * FROM commonweb_amis WHERE pseudo_id='".$Pseudo."' AND groupe='".$list_groupe['nom']."'");

while ($list_nom = mysql_fetch_array($amis)) {
	 echo $list_nom['pseudo_amis']. " / "; 
	 }
}
?>
</div></div>
<div id="cadre_groupe"><div id="interrieur_titre">Crée un groupe</div><div id="interrieur">
<form action="" method="post">
Nom du groupe : <input type="text" name="nom"/><input type="submit" name="Ajouter_groupe" value="Crée"/option></form>
</div></div>
<div id="cadre_amis"><div id="interrieur_titre">Rechercher des amis</div><div id="interrieur">

<form action="" method="post">
Pseudo de votre ami : <input type="text" name="amis"/>
<input type="submit" name="rechercher" value="Rechercher"/option></form>

<?php
if ($Valid_pseudo==true) {
	?><p><a><?php echo $num_pseudo; ?> pseudonyme correspond à votre recherche !</a></p>
    <?php
	while ($data=mysql_fetch_array($recherche_pseudo)) {
		?>
    <a href="amis.php?amis=<?php echo $data['pseudo']; ?>"><?php echo $data['pseudo']."</BR>"; ?></a>
    <?php
		}
}
?>
</div></div></div>
______________________________________________________________________________________________________________________________________________________

</div></div>
</center>
</body>
</html>
