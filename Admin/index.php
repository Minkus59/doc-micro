<?php
require('../lib/script/cnx.inc.php');

$Id=htmlspecialchars($_GET['id']);
$Client=htmlspecialchars($_POST['client']);
$Nom=htmlspecialchars($_POST['nom']);
$Societe=htmlspecialchars($_POST['societe']);
$Adresse=htmlspecialchars($_POST['adresse']);
$Cp_Ville=htmlspecialchars($_POST['cp_ville']);
$Email=htmlspecialchars($_POST['email']);
$Livraison=htmlspecialchars($_POST['livraison']);
$Type=$_POST['type'];
$Option1=$_POST['option1'];
$Option2=$_POST['option2'];
$Option3=$_POST['option3'];
$Option4=$_POST['option4'];
$Option5=$_POST['option5'];
$Option6=$_POST['option6'];
$Option7=$_POST['option7'];
$Remise=$_POST['remise'];
$Fa2=$_POST['fa2'];
$Fa4=$_POST['fa4'];
$Fa6=$_POST['fa6'];
$Fa8=$_POST['fa8'];

$Id3=htmlspecialchars($_POST['id2']);
$Nom3=htmlspecialchars($_POST['nom2']);
$Societe3=htmlspecialchars($_POST['societe2']);
$Adresse3=htmlspecialchars($_POST['adresse2']);
$Cp_Ville3=htmlspecialchars($_POST['cp_ville2']);
$Email3=htmlspecialchars($_POST['email2']);
$Livraison3=htmlspecialchars($_POST['livraison2']);
$Type3=$_POST['type2'];
$Option13=$_POST['option12'];
$Option23=$_POST['option22'];
$Option33=$_POST['option32'];
$Option43=$_POST['option42'];
$Option53=$_POST['option52'];
$Option63=$_POST['option62'];
$Option73=$_POST['option72'];
$Remise3=$_POST['remise2'];
$Fa23=$_POST['fa22'];
$Fa43=$_POST['fa42'];
$Fa63=$_POST['fa62'];
$Fa83=$_POST['fa82'];

cnx();
$devis_cible=@MySQL_query("SELECT * FROM docmicro_devis2 WHERE id= '$Id'");

while ($donnee=@MySQL_fetch_array($devis_cible)) {

$Client2=$donnee['client'];

if ($Client2=="OFF") {
$Nom2=$donnee['nom'];
$Societe2=$donnee['societe'];
$Adresse2=$donnee['adresse'];
$Cp_Ville2=$donnee['cp_ville'];
}
else {

 $recup_client=@MySQL_query('SELECT * FROM docmicro_client WHERE id= "'.$Client2.'"');
 while ($lien=@MySQL_fetch_array($recup_client)) {
 $Nom2=$lien['nom'];
 $Societe2=$lien['societe'];
 $Adresse2=$lien['adresse'];
 $Cp_Ville2=$lien['cp_ville'];
 $Email2=$lien['email'];
 }

}

$Livraison2=$donnee['livraison'];
$Type2=$donnee['type'];
$Option12=$donnee['option1'];
$Option22=$donnee['option2'];
$Option32=$donnee['option3'];
$Option42=$donnee['option4'];
$Option52=$donnee['option5'];
$Option62=$donnee['option6'];
$Option72=$donnee['option7'];
$Remise2=$donnee['remise'];
$Fa22=$donnee['fa2'];
$Fa42=$donnee['fa4'];
$Fa62=$donnee['fa6'];
$Fa82=$donnee['fa8'];
}

MySQL_close();

$Code=htmlspecialchars($_POST['code']);

if ((isset($_POST['cnx']))&&($Code=="CQDFX301")) {
      
         session_start();
         $_SESSION['Admin']=$Code;
         header("location:http://www.doc-micro.fr/Admin/?page=Liste");
}

if (isset($_POST['Valider'])) {

        cnx();

        $insert=@MySQL_query('INSERT INTO docmicro_devis2 (client, nom, societe, adresse, cp_ville, email, livraison, type, option1, option2, option3, option4, option5, option6, option7, fa2, fa4, fa6, fa8, remise, created)
                                         VALUES("'.$Client.'", "'.$Nom.'", "'.$Societe.'", "'.$Adresse.'", "'.$Cp_Ville.'", "'.$Email.'", "'.$Livraison.'", "'.$Type.'", "'.$Option1.'", "'.$Option2.'", "'.$Option3.'", "'.$Option4.'", "'.$Option5.'", "'.$Option6.'", "'.$Option7.'", "'.$Fa2.'", "'.$Fa4.'", "'.$Fa6.'", "'.$Fa8.'", "'.$Remise.'", NOW())');

        $recup=@MySQL_query('SELECT * FROM docmicro_devis2 WHERE nom="'.$Nom.'" AND societe="'.$Societe.'"');

        while($data=@MySQL_fetch_array($recup)) {
         
         $Id=$data['id'];
        }

        header("location:http://www.doc-micro.fr/Admin/?page=Liste");  
        MySQL_close();
}

if (isset($_POST['Modifier'])) {

        cnx();
        if ($Client2=="OFF") {
        $modif=@MySQL_query('UPDATE docmicro_devis2 SET nom="'.$Nom3.'", societe="'.$Societe3.'", adresse="'.$Adresse3.'", cp_ville="'.$Cp_Ville3.'", email="'.$Email3.'", livraison="'.$Livraison3.'", type="'.$Type3.'", option1="'.$Option13.'", option2="'.$Option23.'", option3="'.$Option33.'", option4="'.$Option43.'", option5="'.$Option53.'", option6="'.$Option63.'", option7="'.$Option73.'", fa2="'.$Fa23.'", fa4="'.$Fa43.'", fa6="'.$Fa63.'", fa8="'.$Fa83.'", remise="'.$Remise3.'", modified=NOW() WHERE id="'.$Id3.'"');
        
        header("location:http://www.doc-micro.fr/Admin/?page=Liste");
        }
        else {
        $modif2=@MySQL_query('UPDATE docmicro_devis2 SET livraison="'.$Livraison3.'", type="'.$Type3.'", option1="'.$Option13.'", option2="'.$Option23.'", option3="'.$Option33.'", option4="'.$Option43.'", option5="'.$Option53.'", option6="'.$Option63.'", option7="'.$Option73.'", fa2="'.$Fa23.'", fa4="'.$Fa43.'", fa6="'.$Fa63.'", fa8="'.$Fa83.'", remise="'.$Remise3.'", modified=NOW() WHERE id="'.$Id3.'"');        
        $modif3=@MySQL_query('UPDATE docmicro_client SET nom="'.$Nom3.'", societe="'.$Societe3.'", adresse="'.$Adresse3.'", cp_ville="'.$Cp_Ville3.'", email="'.$Email3.'" WHERE id="'.$Client2.'"');

        header("location:http://www.doc-micro.fr/Admin/?page=Liste");
        }
        MySQL_close();
}

if ((isset($_GET['suppr']))&&($_GET['suppr']=="Supprimer")) {

          cnx();
          @mysql_query("DELETE FROM docmicro_devis2 WHERE id = ".$Id );
          MySQL_close();
    }

cnx();

$recup_client=@MySQL_query('SELECT * FROM docmicro_client ORDER BY societe, nom ASC');

Mysql_close();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Doc-Micro - Admin</title>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<META name="robots" content="noindex, nofollow">
<link rel="shortcut icon" href="../lib/img/doc-micro.ico" />

<link href="../lib/css/docmicro.css" rel="stylesheet" type="text/css"/>
</head>

<body>
<CENTER>
<div id="all" class='hiver'>
<div id="head">
</div>

<div id="left">
<div id="int">
<?php
require('../lib/script/menu_gauche.inc.php');
?>
</div>
</div>

<div id="middle">

<?php
//SESSION
session_start();
if ((isset($_SESSION['Admin']))&&($_SESSION['Admin']=="CQDFX301")) {


if ((isset($_GET['page']))&&($_GET['page']=="Nouveau")) {

?>
<div id="middle_int">
<div id="titre_assis">
Nouveau devis.<hr></hr></div>

<table><form action="" method="post">
<tr><td>Client : </td><td><select name="client"/>
<option value="OFF"/><<<<<<<<< Client démarché >>>>>>>>>><?php
while($client=@MySQL_fetch_array($recup_client)) {
?><option value="<?php echo $client['id']; ?>"/><?php echo $client['societe']; ?> / <?php echo $client['nom'];
}
?></td></tr>
<tr><td>Nom / Prénom : </td><td><input type="text" name="nom"/></td></tr>
<tr><td>Société : </td><td><input type="text" name="societe"/></td></tr>
<tr><td>Adresse : </td><td><input type="text" name="adresse"/></td></tr>
<tr><td>Code postal / Ville: </td><td><input type="text" name="cp_ville"/></td></tr>
<tr><td>email : </td><td><input type="text" name="email"/></td></tr>
<tr><td>Délai de livraison : </td><td><input type="text" name="livraison"/></td></tr>
<tr><td>Type de site internet : </td><td><select name="type"/><option value="1"/>Site carte de visite
<option value="2"/>Site vitrine
<option value="3"/>Site catalogue
<option value="4"/>Site E-commerce</td></tr>
<tr><td>Option : </td><td><input type="checkbox" name="option2"/>Module de formulaire de devis</td></td></tr>
<tr><td></td><td><input type="checkbox" name="option3"/>Module de formulaire de contact (E-mail)</tr>
<tr><td></td><td><input type="checkbox" name="option4"/>Module de compteur de visite</td></tr>
<tr><td></td><td><input type="checkbox" name="option1"/>Module de modification du contenu</td></tr>
<tr><td></td><td><input type="checkbox" name="option5"/>Module de newsletter</td></tr>
<tr><td></td><td><input type="checkbox" name="option6"/>Module de modification et classement des article (site catalogue)</td></tr>
<tr><td></td><td><input type="checkbox" name="option7"/>Module de recherche rapide (site catalogue)</td></tr>
<tr><td>Remise : </td><td><input type="text" name="remise"/></td></tr>
<tr><td>Facilité de paiement  : </td><td><input name="fa2" type="checkbox"/>2 fois, <input name="fa4" type="checkbox"/>4 fois, <input name="fa6" type="checkbox"/>6 fois, <input name="fa8" type="checkbox"/>8 fois</td></tr>
<tr><td></td><td><input type="submit" name="Valider" value="Valider"/></td></tr>
</form></table>
</div>
<?php
}

if ((isset($_GET['page']))&&($_GET['page']=="Modifier")) {

?>
<div id="middle_int">
<div id="titre_assis">
Modifier le devis n°: <?php echo $Id; ?>.<hr></hr></div>

<table><form action="" method="post">
<input type="hidden" name="id2" value="<?php echo $Id; ?>"/>
<tr><td>Nom / Prénom : </td><td><input type="text" name="nom2" value="<?php echo $Nom2; ?>"/></td></tr>
<tr><td>Société : </td><td><input type="text" name="societe2" value="<?php echo $Societe2; ?>"/></td></tr>
<tr><td>Adresse : </td><td><input type="text" name="adresse2" value="<?php echo $Adresse2; ?>"/></td></tr>
<tr><td>Code postal / ville: </td><td><input type="text" name="cp_ville2" value="<?php echo $Cp_Ville2; ?>"/></td></tr>
<tr><td>E-mail : </td><td><input type="text" name="email2" value="<?php echo $Email2; ?>"/></td></tr>
<tr><td>Délai de livraison : </td><td><input type="text" name="livraison2" value="<?php echo $Livraison2; ?>"/></td></tr>
<tr><td>Type de site internet : </td><td><select name="type2"/><option value="1" <?php if ($Type2=="1") { ?> SELECTED <?php } ?> />Site carte de visite
<option value="2" <?php if ($Type2=="2") { ?> SELECTED <?php } ?> />Site vitrine
<option value="3" <?php if ($Type2=="3") { ?> SELECTED <?php } ?> />Site catalogue
<option value="4" <?php if ($Type2=="4") { ?> SELECTED <?php } ?> />Site E-commerce</td></tr>
<tr><td>Option : </td><td><input type="checkbox" name="option22" <?php if ($Option22=="on") { ?> CHECKED <?php } ?> />Module de formulaire de devis</td></td></tr>
<tr><td></td><td><input type="checkbox" name="option32" <?php if ($Option32=="on") { ?> CHECKED <?php } ?> />Module de formulaire de contact (E-mail)</tr>
<tr><td></td><td><input type="checkbox" name="option42"<?php if ($Option42=="on") { ?> CHECKED <?php } ?> />Module de compteur de visite</td></tr>
<tr><td></td><td><input type="checkbox" name="option12"<?php if ($Option12=="on") { ?> CHECKED <?php } ?> />Module de modification du contenu</td></tr>
<tr><td></td><td><input type="checkbox" name="option52"<?php if ($Option52=="on") { ?> CHECKED <?php } ?> />Module de newsletter</td></tr>
<tr><td></td><td><input type="checkbox" name="option62"<?php if ($Option62=="on") { ?> CHECKED <?php } ?> />Module de modification et classement des article (site catalogue)</td></tr>
<tr><td></td><td><input type="checkbox" name="option72"<?php if ($Option72=="on") { ?> CHECKED <?php } ?> />Module de recherche rapide (site catalogue)</td></tr>
<tr><td>Remise : </td><td><input type="text" name="remise2" value="<?php echo $Remise2; ?>"/></td></tr>
<tr><td>Facilité de paiement  : </td><td><input name="fa22" type="checkbox" <?php if ($Fa22=="on") { ?> CHECKED <?php } ?> />2 fois, <input name="fa42" type="checkbox" <?php if ($Fa42=="on") { ?> CHECKED <?php } ?> />4 fois, <input name="fa62" type="checkbox" <?php if ($Fa62=="on") { ?> CHECKED <?php } ?> />6 fois, <input name="fa82" type="checkbox" <?php if ($Fa82=="on") { ?> CHECKED <?php } ?> />8 fois</td></tr>
<tr><td></td><td><input type="submit" name="Modifier" value="Valider"/></td></tr>
</form></table>
</div>
<?php
}

if ((isset($_GET['page']))&&($_GET['page']=="Liste")) {

?>
<div id="middle_int">
<div id="titre_assis">
Liste des devis.<hr></hr></div>

<?php
cnx();

$liste_devis=@MySQL_query("SELECT * FROM docmicro_devis2");

while ($devis=@MySQL_fetch_array($liste_devis)) {

if ($devis['client']=="OFF") {
 ?>
<li><?php echo $devis['id']; ?> / <?php echo $devis['nom'];?> / <?php echo $devis['societe']; ?> / <?php echo $devis['type']; ?>/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a Href="/Admin/Visualisation/?id=<?php echo $devis['id']; ?>" target="_blank">Visualiser</a>&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<a Href="/Admin/?page=Modifier&id=<?php echo $devis['id']; ?> ">Modifier</a>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;<a Href="/Admin/?page=Liste&suppr=Supprimer&id=<?php echo $devis['id']; ?> ">Supprimer</a>/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a Href="/Admin/Facture/?id=<?php echo $devis['id']; ?>" target="_blank">Facture</a></li>
<?php
 }
if ($devis['client']!="OFF") {
$liste_client=@MySQL_query("SELECT * FROM docmicro_client WHERE id= '".$devis['client']."'");
while ($devis2=@MySQL_fetch_array($liste_client)) {
?>
<li><?php echo $devis['id']; ?> / <?php echo $devis2['nom'];?> / <?php echo $devis2['societe']; ?> / <?php echo $devis['type']; ?>/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a Href="/Admin/Visualisation/?id=<?php echo $devis['id']; ?>" target="_blank">Visualiser</a>&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<a Href="/Admin/?page=Modifier&id=<?php echo $devis['id']; ?> ">Modifier</a>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;<a Href="/Admin/?page=Liste&suppr=Supprimer&id=<?php echo $devis['id']; ?> ">Supprimer</a>/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a Href="/Admin/Facture/?id=<?php echo $devis['id']; ?>" target="_blank">Facture</a></li>
<?php
  }
 }
}
MySQL_close();
?>
</div>
<?php
}
}
else {
?>
<div id="middle_int">
<div id="titre_assis">
Nouveau devis.<hr></hr></div>

<form id="form_cnx" action="" method="POST">
<label class="col_1" for="code">Mot de passe :</label>
<input name="code" id="code" type="password" />
<span class="erreur">Le mot de passe ne doit pas faire moins de 6 caractères</span>
<br /><br />
<span class="col_1"></span>
<input type="submit" name="cnx" value="Connexion"/>
</form>

<script type="text/javascript" src="cnx.js"></script>
</div>
<?php
}
?>
</div>
<div id="vide"><div id="mention">
<li><a Href="../Mentions-legales/">Mentions-légales</a></li>
</div></div>

<div id="footer">
<div id="bouton">
<ul>
<p>
<li><a Href="../Admin/?page=Nouveau">Nouveau</a></li>
<li><a Href="../Admin/?page=Liste">Liste</a></li>
</ul>
</div>
</div>

</div>
</CENTER>
</body>
</html>	