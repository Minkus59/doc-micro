<?php

//SESSION
session_start();
if ((!isset($_SESSION['Admin']))&&($_SESSION['Admin']!="CQDFX301")) {
   header('location:http://www.doc-micro.fr/Admin/');
}

require('/homez.764/docmicro/www/lib/script/cnx.inc.php');

$Id=$_GET['id'];

cnx();

$recup=@MySQL_query('SELECT * FROM docmicro_devis2 WHERE id="'.$Id.'"');

while ($data=@MySQL_fetch_array($recup)) {

$Client=$data['client'];
$Nom=$data['nom'];
$Societe=$data['societe'];
$Adresse=$data['adresse'];
$Cp_Ville=$data['cp_ville'];
$Livraison=$data['livraison'];
$Type=$data['type'];
$Option1=$data['option1'];
$Option2=$data['option2'];
$Option3=$data['option3'];
$Option4=$data['option4'];
$Option5=$data['option5'];
$Option6=$data['option6'];
$Option7=$data['option7'];
$Created=$data['created'];
$Fa2=$data['fa2'];
$Fa4=$data['fa4'];
$Fa6=$data['fa6'];
$Fa8=$data['fa8'];

if ($Client!="OFF") {

$recup=@MySQL_query('SELECT * FROM docmicro_client WHERE id= "'.$Client.'"');
while ($lien=@MySQL_fetch_array($recup)) {
$Nom2=$lien['nom'];
$Societe2=$lien['societe'];
$Adresse2=$lien['adresse'];
$Cp_Ville2=$lien['cp_ville'];
 }
}

if (empty($data['remise'])) {
 $Remise=0;
}
else { 
 $Remise=$data['remise']; 
 }
}

$facilite=false;

if ($Fa2=="on") {
$facilite=true;
}

if ($Fa4=="on") {
$facilite=true;
}

if ($Fa6=="on") {
$facilite=true;
}

if ($Fa8=="on") {
$facilite=true;
}

if ($Option1=="on") {
$Prix_option1=80;
}
else {
$Prix_option1=0;
}

if ($Option2=="on") {
$Prix_option2=150;
}
else {
$Prix_option2=0;
}

if ($Option3=="on") {
$Prix_option3=80;
}
else {
$Prix_option3=0;
}

if ($Option4=="on") {
$Prix_option4=30;
}
else {
$Prix_option4=0;
}

if ($Option5=="on") {
$Prix_option5=250;
}
else {
$Prix_option5=0;
}

if ($Option6=="on") {
$Prix_option6=150;
}
else {
$Prix_option6=0;
}

if ($Option7=="on") {
$Prix_option7=350;
}
else {
$Prix_option7=0;
}

if ($Type==1) {
$Prix_type=250;
}

if ($Type==2) {
$Prix_type=500;
}

if ($Type==3) {
$Prix_type=1000;
}

if ($Type==4) {
$Prix_type=2500;
}

$Prix_total_remise = (($Prix_type + $Prix_option1 + $Prix_option2 + $Prix_option3 + $Prix_option4 + $Prix_option5 + $Prix_option6 + $Prix_option7) * $Remise / 100);
$Prix_total = round(($Prix_type + $Prix_option1 + $Prix_option2 + $Prix_option3 + $Prix_option4 + $Prix_option5 + $Prix_option6 + $Prix_option7) - $Prix_total_remise);
$x2=round($Prix_total / 2, 0, PHP_ROUND_HALF_DOWN);
$x2_der=($Prix_total - $x2);
$x4=round($Prix_total / 4, 0, PHP_ROUND_HALF_DOWN);
$x4_der=($Prix_total - ($x4 * 3));
$x6=round($Prix_total / 6, 0, PHP_ROUND_HALF_DOWN);
$x6_der=($Prix_total - ($x6 * 5));
$x8=round($Prix_total / 8, 0, PHP_ROUND_HALF_DOWN);
$x8_der=($Prix_total - ($x8 * 7));

?>

<html>
<head>
<title>Doc-Micro - Devis_<?php echo $Id; ?></title>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<link rel="shortcut icon" href="../lib/img/doc-micro.ico" />
<META name="robots" content="noindex, nofollow">
<link href="/lib/css/docmicro_devis.css" rel="stylesheet" type="text/css"/>
</head>

<body>
<div id='all'>

<div id='info_perso'><span class="bleu">
<li>Doc-Micro</li>
<li>7 rue du Commandant POTHIER</li>
<li>51200 Epernay</li>
<li>SIREN : 797 723 145</li></span>
<span class="petit">
<li>Dispensé d’immatriculation au registre du commerce</li>
<li>et des sociétés (RCS) et au répertoire des métiers (RM).</li>
</span>
</div>

<div id='logo'>
<img src='/lib/img/robot.png' width="250" />
</div>

<?php if ($Client=="OFF") { ?>
<div id='info_client'>
<li><?php echo $Nom; ?></li>
<li><?php echo $Societe; ?></li>
<li><?php echo $Adresse; ?></li>
<li><?php echo $Cp_Ville; ?></li>
</div><?php
}
?>

<?php if ($Client!="OFF") { ?>
<div id='info_client'>
<li><?php echo $Nom2; ?></li>
<li><?php echo $Societe2; ?></li>
<li><?php echo $Adresse2; ?></li>
<li><?php echo $Cp_Ville2; ?></li>
</div><?php
}
?>

<div id='num_devis'>
Facture n °: <?php echo $Id; ?>
</div>

<div id='date_devis'>
Le <?php echo date("j/m/Y",strtotime($Created)); ?>
</div>

<div id='prestation'>

<table>
  <tr bgcolor="#173955">
    <td width="445"><span class="blanc Style1"> Désignation des prestations </span></td>
    <td width="70"><div align="center" class="Style1"><span class="blanc"> Quantité </span></div></td>
    <td width="120"><div align="center" class="Style1"><span class="blanc"> Prix unitaire HT </span></div></td>
    <td width="65"><div align="center" class="Style1"><span class="blanc"> Total HT </span></div></td>
  </tr>

<?php
if ($Type==1) {
?>
  <tr>
    <td><strong>Forfait site carte de visite</strong></td>
    <td><div align="center">1</div></td>
    <td><div align="center"><?php echo $Prix_type."€"; ?></div></td>
    <td><div align="center"><?php echo $Prix_type."€"; ?></div></td>
  </tr>
  <tr>
    <td>- Affichage de votre carte de visite en grand format sur une page unique</td>
  </tr>
  <tr>
    <td>- 1 plan d'accés Google-map interactif</td>
  </tr>
  <tr bgcolor="#173955">
    <td><strong><span class="blanc">Option</span></strong></td>
  </tr>
  <tr>
    <td>- Module Devis (sous forme de formulaire, devis reçu par E-mail).</td>
    <td><div align="center"><?php if ($Option2=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">150€</div></td>
    <td><div align="center"><?php if ($Option2=="on") { echo $Prix_option2."€"; } else { echo $Prix_option2."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module Contact (Pour vos clients sans adresse E-mail).</td>
    <td><div align="center"><?php if ($Option3=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">80€</div></td>
    <td><div align="center"><?php if ($Option3=="on") { echo "80€"; } else { echo $Prix_option3."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module Compteur (Compteur de visites).</td>
    <td><div align="center"><?php if ($Option4=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">30€</div></td>
    <td><div align="center"><?php if ($Option4=="on") { echo "30€"; } else { echo $Prix_option4."€"; } ?></div></td>
  </tr>

<?php
}

if ($Type==2) {

 ?>
  <tr>
    <td><strong>Forfait site vitrine</strong></td>
    <td><div align="center">1</div></td>
    <td><div align="center"><?php echo $Prix_type."€"; ?></div></td>
    <td><div align="center"><?php echo $Prix_type."€"; ?></div></td>
  </tr>
  <tr>
    <td>- 1 Thème graphique adapté a votre activité</td>
  </tr>
  <tr>
    <td>- Detail de vos activitées par pages</td>
  </tr>
  <tr>
    <td>- 1 plan d'accés Google-map interactif</td>
  </tr>
  <tr bgcolor="#173955">
    <td><strong><span class="blanc">Option</span></strong></td>
  </tr>
  <tr>
    <td>- Module Devis (sous forme de formulaire, devis reçu par E-mail).</td>
    <td><div align="center"><?php if ($Option2=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">150€</div></td>
    <td><div align="center"><?php if ($Option2=="on") { echo $Prix_option2."€"; } else { echo $Prix_option2."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module Contact (Pour vos clients sans adresse E-mail).</td>
    <td><div align="center"><?php if ($Option3=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">80€</div></td>
    <td><div align="center"><?php if ($Option3=="on") { echo $Prix_option3."€"; } else { echo $Prix_option3."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module Compteur (Compteur de visites).</td>
    <td><div align="center"><?php if ($Option4=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">30€</div></td>
    <td><div align="center"><?php if ($Option4=="on") { echo $Prix_option4."€"; } else { echo $Prix_option4."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module de modification du contenu (Horaire, texte etc..).</td>
    <td><div align="center"><?php if ($Option1=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">80€</div></td>
    <td><div align="center"><?php if ($Option1=="on") { echo $Prix_option1."€"; } else { echo $Prix_option1."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module de newsletter (Actualité en temps réel pour vos clients).</td>
    <td><div align="center"><?php if ($Option5=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">250€</div></td>
    <td><div align="center"><?php if ($Option5=="on") { echo $Prix_option5."€"; } else { echo $Prix_option5."€"; } ?></div></td>
  </tr>

<?php
}

if ($Type==3) {
?>
  <tr>
    <td><strong>Forfait site Catalogue</strong></td>
    <td><div align="center">1</div></td>
    <td><div align="center"><?php echo $Prix_type."€"; ?></div></td>
    <td><div align="center"><?php echo $Prix_type."€"; ?></div></td>
  </tr>
  <tr>
    <td>- 1 Thème graphique adapté a votre activité</td>
  </tr>
  <tr>
    <td>- Détail de vos articles par pages</td>
  </tr>
  <tr>
    <td>- 1 page d'administration</td>
  </tr>
  <tr>
    <td>- Module d'ajout de vos articles</td>
  </tr>
  <tr>
    <td>- 1 plan d'accés Google-map interactif</td>
  </tr>
  <tr bgcolor="#173955">
    <td><strong><span class="blanc">Option</span></strong></td>
  </tr>
  <tr>
    <td>- Module Contact (Pour vos clients sans adresse E-mail).</td>
    <td><div align="center"><?php if ($Option3=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">80€</div></td>
    <td><div align="center"><?php if ($Option3=="on") { echo $Prix_option3."€"; } else { echo $Prix_option3."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module Compteur (Compteur de visites).</td>
    <td><div align="center"><?php if ($Option4=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">30€</div></td>
    <td><div align="center"><?php if ($Option4=="on") { echo $Prix_option4."€"; } else { echo $Prix_option4."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module de modification du contenu (Horaires, textes, articles etc..).</td>
    <td><div align="center"><?php if ($Option1=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">80€</div></td>
    <td><div align="center"><?php if ($Option1=="on") { echo $Prix_option1."€"; } else { echo $Prix_option1."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module de newsletter (Actualité en temps réel pour vos clients).</td>
    <td><div align="center"><?php if ($Option5=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">250€</div></td>
    <td><div align="center"><?php if ($Option5=="on") { echo $Prix_option5."€"; } else { echo $Prix_option5."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module de modification et classement des articles (Modification et classement facile et rapide).</td>
    <td><div align="center"><?php if ($Option6=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">150€</div></td>
    <td><div align="center"><?php if ($Option6=="on") { echo $Prix_option6."€"; } else { echo $Prix_option6."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module de recherche rapide (Moteur de recherche interne au site internet).</td>
    <td><div align="center"><?php if ($Option7=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">350€</div></td>
    <td><div align="center"><?php if ($Option7=="on") { echo $Prix_option7."€"; } else { echo $Prix_option7."€"; } ?></div></td>
  </tr>

<?php

}

if ($Type==4) {
?>
  <tr>
    <td><strong>Forfait site E-commerce</strong></td>
    <td><div align="center">1</div></td>
    <td><div align="center"><?php echo $Prix_type."€"; ?></div></td>
    <td><div align="center"><?php echo $Prix_type."€"; ?></div></td>
  </tr>
  <tr>
    <td>- 1 Thème graphique adapté a votre activité</td>
  </tr>
  <tr>
    <td>- Détail de vos articles par catégorie</td>
  </tr>
  <tr>
    <td>- 1 page d'administration</td>
  </tr>
  <tr>
    <td>- Module d'ajout de vos articles</td>
  </tr>
  <tr>
    <td>- 1 page de gestion clients / litige</td>
  </tr>
  <tr>
    <td>- 1 Module de paiement en ligne</td>
  </tr>
  <tr>
    <td>- 1 plan d'accés Google-map interactif</td>
  </tr>
  <tr bgcolor="#173955">
    <td><strong><span class="blanc">Option</span></strong></td>
  </tr>
  <tr>
    <td>- Module Contact (Pour vos clients sans adresse E-mail).</td>
    <td><div align="center"><?php if ($Option3=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">80€</div></td>
    <td><div align="center"><?php if ($Option3=="on") { echo $Prix_option3."€"; } else { echo $Prix_option3."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module Compteur (Compteur de visites).</td>
    <td><div align="center"><?php if ($Option4=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">30€</div></td>
    <td><div align="center"><?php if ($Option4=="on") { echo $Prix_option4."€"; } else { echo $Prix_option4."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module de modification du contenu (Horaire, texte etc..).</td>
    <td><div align="center"><?php if ($Option1=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">80€</div></td>
    <td><div align="center"><?php if ($Option1=="on") { echo $Prix_option1."€"; } else { echo $Prix_option1."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module de newsletter (Actualité en temps réel pour vos clients).</td>
    <td><div align="center"><?php if ($Option5=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">250€</div></td>
    <td><div align="center"><?php if ($Option5=="on") { echo $Prix_option5."€"; } else { echo $Prix_option5."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module de modification et classement des articles (Modification et classement facile et rapide).</td>
    <td><div align="center"><?php if ($Option6=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">150€</div></td>
    <td><div align="center"><?php if ($Option6=="on") { echo $Prix_option6."€"; } else { echo $Prix_option6."€"; } ?></div></td>
  </tr>
  <tr>
    <td>- Module de recherche rapide (Moteur de recherche interne au site internet).</td>
    <td><div align="center"><?php if ($Option7=="on") { echo "1"; } else { echo "0"; } ?></div></td>
    <td><div align="center">350€</div></td>
    <td><div align="center"><?php if ($Option7=="on") { echo $Prix_option7."€"; } else { echo $Prix_option7."€"; } ?></div></td>
  </tr>

<?php

}

?>
   <tr>
    <td></td>
    <td><div align="center"></div></td>
    <td bgcolor="#173955"><div align="center"><span class="blanc">Remise</span></div></td>
    <td bgcolor="#173955"><div align="center"><span class="blanc"><?php echo $Remise."%"; ?></span></div></td>
  </tr>
   <tr>
    <td></td>
    <td><div align="center"></div></td>
    <td bgcolor="#173955"><div align="center"><span class="blanc">TOTAL TTC</span></div></td>
    <td bgcolor="#173955"><div align="center"><span class="blanc"><?php echo $Prix_total."€"; ?></span></div></td>
  </tr>
</table>

<li>Facilité de paiement  : <input name="fa2" type="checkbox" <?php if ($Fa2=="on") { ?> checked <?php } ?> />2 fois, <input name="fa4" type="checkbox" <?php if ($Fa4=="on") { ?> checked <?php } ?> />4 fois, <input name="fa6" type="checkbox" <?php if ($Fa6=="on") { ?> checked <?php } ?> />6 fois, <input name="fa8" type="checkbox" <?php if ($Fa8=="on") { ?> checked <?php } ?> />8 fois</li>
<li><span class="petit">(à partir de 1000€)</span></li>

<?php if (($facilite==true)&&($Fa2=="on")) { ?>
<table border=0>
<tr width="700">
 <td width="350" bgcolor="#173955"><span class="blanc">Echelonage :</span></td>
 <td width="185" bgcolor="#173955"><span class="blanc"><div align="center">Première mensualitées</div></span></td>
 <td width="185" bgcolor="#173955"><span class="blanc"><div align="center">dernière mensualitée</div></span></td>
</tr>
<tr>
 <td width="300" bgcolor="#173955"><span class="blanc">Montant : (prélevé le 5em jour de chaque mois)</span></td>
 <td width="185"><div align="center"><?php echo $x2."€"; ?></div></td>
 <td width="185"><div align="center"><?php echo $x2_der."€"; ?></div></td>
</tr>
</table>
<?php
}

if (($facilite==true)&&($Fa4=="on")) { ?>
<table border=0>
<tr width="700">
 <td width="350" bgcolor="#173955"><span class="blanc">Echelonage :</span></td>
 <td width="185" bgcolor="#173955"><span class="blanc"><div align="center">3 première mensualitées</div></span></td>
 <td width="185" bgcolor="#173955"><span class="blanc"><div align="center">dernière mensualitée</div></span></td>
</tr>
<tr>
 <td width="300" bgcolor="#173955"><span class="blanc">Montant : (prélevé le 5em jour de chaque mois)</span></td>
 <td width="185"><div align="center"><?php echo $x4."€"; ?></div></td>
 <td width="185"><div align="center"><?php echo $x4_der."€"; ?></div></td>
</tr>
</table>
<?php
}

if (($facilite==true)&&($Fa6=="on")) { ?>
<table border=0>
<tr width="700">
 <td width="350" bgcolor="#173955"><span class="blanc">Echelonage :</span></td>
 <td width="185" bgcolor="#173955"><span class="blanc"><div align="center">5 première mensualitées</div></span></td>
 <td width="185" bgcolor="#173955"><span class="blanc"><div align="center">dernière mensualitée</div></span></td>
</tr>
<tr>
 <td width="300" bgcolor="#173955"><span class="blanc">Montant : (prélevé le 5em jour de chaque mois)</span></td>
 <td width="185"><div align="center"><?php echo $x6."€"; ?></div></td>
 <td width="185"><div align="center"><?php echo $x6_der."€"; ?></div></td>
</tr>
</table>
<?php
}

if (($facilite==true)&&($Fa8=="on")) { ?>
<table border=0>
<tr width="700">
 <td width="350" bgcolor="#173955"><span class="blanc">Echelonage :</span></td>
 <td width="185" bgcolor="#173955"><span class="blanc"><div align="center">7 première mensualitées</div></span></td>
 <td width="185" bgcolor="#173955"><span class="blanc"><div align="center">dernière mensualitée</div></span></td>
</tr>
<tr>
 <td width="300" bgcolor="#173955"><span class="blanc">Montant : (prélevé le 5em jour de chaque mois)</span></td>
 <td width="185"><div align="center"><?php echo $x8."€"; ?></div></td>
 <td width="185"><div align="center"><?php echo $x8_der."€"; ?></div></td>
</tr>
</table>
<?php
}
?>
</div>

<div id='info_legal'>

<div id='cadre'>
<table border=0>
<TR><TD> Délai de livraison : </TD><TD><?php echo $Livraison; ?></TD></TR>
<TR><TD> Date limite de paiement : </TD><TD>45 jours fin de mois</TD></TR>
<TR><TD> Pénalité de retard : </TD><TD> 0,12% </TD></TR>
<TR><TD> Conditions d'escompte (Arrhes) : </TD><TD> <?php if ($facilite==true) { echo "1ère mensualitée"; } else { echo "30,00%"; } ?> </TD></TR>
</table>
</div>

<div id='info'><span class="petit">
<li>TVA non applicable en vertu de l’article 293 B du CGI</li>
</div>

</div>

<div id='bas_page'><span class="petit">
Doc-Micro – 06 52 66 06 45 - 7 rue du Commandant POTHIER, 51200 Epernay – SIREN : 797 723 145</span>
</div>

</div>
</body>		