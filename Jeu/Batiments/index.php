<?php

require('/homez.585/docmicro/www/Jeu/lib/script/fonction_perso.inc.php');
require('/homez.585/docmicro/www/Jeu/lib/script/variable.inc.php');

//calcul du cout de niveau en ressource



// calcul du temps de construction par niveau

//temps de prod = temps de base *pow(coeff temps, niveau +1) *pow(coeff droide ,niveau droide) *pow(coeff androide, niveau androide)

$TempsProdTitane=($TempsMTitane*pow($CoeffBatmentTemps,($NTitane+1))*pow($CoeffBatimentDroide,0)*pow($CoeffBatimentAndroide,0))/60;
$TempsProdHelium=($TempsMHelium*pow($CoeffBatmentTemps,($NHelium+1))*pow($CoeffBatimentDroide,0)*pow($CoeffBatimentAndroide,0))/60;
$TempsProdTorium=($TempsMTorium*pow($CoeffBatmentTemps,($NTorium+1))*pow($CoeffBatimentDroide,0)*pow($CoeffBatimentAndroide,0))/60;
$TempsProdFer=($TempsMFer*pow($CoeffBatmentTemps,($NFer+1))*pow($CoeffBatimentDroide,0)*pow($CoeffBatimentAndroide,0))/60;
$TempsProdAcier=($TempsMAcier*pow($CoeffBatmentTemps,($NAcier+1))*pow($CoeffBatimentDroide,0)*pow($CoeffBatimentAndroide,0))/60;
$TempsProdCarbone=($TempsMCarbone*pow($CoeffBatmentTemps,($NCarbone+1))*pow($CoeffBatimentDroide,0)*pow($CoeffBatimentAndroide,0))/60;
$TempsProdTritium=($TempsMTritium*pow($CoeffBatmentTemps,($NTritium+1))*pow($CoeffBatimentDroide,0)*pow($CoeffBatimentAndroide,0))/60;

$totalTitane=round($TempsProdTitane,0); // en secondes
$secondesTitane=$totalTitane/60;
$resteSTitane=$totalTitane%60;
$minutesTitane=$secondesTitane/60;
$resteMTitane=$secondesTitane%60;
$heuresTitane=$minutesTitane/24;
$resteHTitane=$minutesTitane%24;
$joursTitane=$heuresTitane/365;
$resteJTitane=$heuresTitane%365;
$resteATitane=$joursTitane%365;

$totalHelium=round($TempsProdHelium,0); // en secondes
$secondesHelium=$totalHelium/60;
$resteSHelium=$totalHelium%60;
$minutesHelium=$secondesHelium/60;
$resteMHelium=$secondesHelium%60;
$heuresHelium=$minutesHelium/24;
$resteHHelium=$minutesHelium%24;
$joursHelium=$heuresHelium/365;
$resteJHelium=$heuresHelium%365;
$resteAHelium=$joursHelium%365;

$totalTorium=round($TempsProdTorium,0); // en secondes
$secondesTorium=$totalTorium/60;
$resteSTorium=$totalTorium%60;
$minutesTorium=$secondesTorium/60;
$resteMTorium=$secondesTorium%60;
$heuresTorium=$minutesTorium/24;
$resteHTorium=$minutesTorium%24;
$joursTorium=$heuresTorium/365;
$resteJTorium=$heuresTorium%365;
$resteATorium=$joursTorium%365;

$totalFer=round($TempsProdFer,0); // en secondes
$secondesFer=$totalFer/60;
$resteSFer=$totalFer%60;
$minutesFer=$secondesFer/60;
$resteMFer=$secondesFer%60;
$heuresFer=$minutesFer/24;
$resteHFer=$minutesFer%24;
$joursFer=$heuresFer/365;
$resteJFer=$heuresFer%365;
$resteAFer=$joursFer%365;

$totalAcier=round($TempsProdAcier,0); // en secondes
$secondesAcier=$totalAcier/60;
$resteSAcier=$totalAcier%60;
$minutesAcier=$secondesAcier/60;
$resteMAcier=$secondesAcier%60;
$heuresAcier=$minutesAcier/24;
$resteHAcier=$minutesAcier%24;
$joursAcier=$heuresAcier/365;
$resteJAcier=$heuresAcier%365;
$resteAAcier=$joursAcier%365;

$totalCarbone=round($TempsProdCarbone,0); // en secondes
$secondesCarbone=$totalCarbone/60;
$resteSCarbone=$totalCarbone%60;
$minutesCarbone=$secondesCarbone/60;
$resteMCarbone=$secondesCarbone%60;
$heuresCarbone=$minutesCarbone/24;
$resteHCarbone=$minutesCarbone%24;
$joursCarbone=$heuresCarbone/365;
$resteJCarbone=$heuresCarbone%365;
$resteACarbone=$joursCarbone%365;

$totalTritium=round($TempsProdTritium,0); // en secondes
$secondesTritium=$totalTritium/60;
$resteSTritium=$totalTritium%60;
$minutesTritium=$secondesTritium/60;
$resteMTritium=$secondesTritium%60;
$heuresTritium=$minutesTritium/24;
$resteHTritium=$minutesTritium%24;
$joursTritium=$heuresTritium/365;
$resteJTritium=$heuresTritium%365;
$resteATritium=$joursTritium%365;

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
<link href="http://www.doc-micro.fr/Jeu/lib/css/projet_jeu.css" rel="stylesheet" type="text/css"/>

<title></title>
</head>

<body>
<CENTER>
<div id="all">

<header>
	<?php require("/homez.585/docmicro/www/Jeu/lib/script/header.inc.php"); ?>
</header>

<nav>
	<?php require("/homez.585/docmicro/www/Jeu/lib/script/nav.inc.php"); ?>
</nav>

<div id="content">
<div id="int_content">

<div id="batiments">
<div id="nom">
Mine de titane
</div>
<div id="image">
<img src="http://www.doc-micro.fr/Jeu/lib/img/batiments/1.jpg"/>
</div>
<div id="description">

</div>
<div id="cred_ress">
<div id="credit">

</div>
<div id="temps">
<?php
if ($resteATitane>1) {
	echo $resteATitane." ans, ";
}
if ($resteATitane==1) {
	echo $resteATitane." an, ";
}

if ($resteJTitane>1) {
	echo $resteJTitane." jours, ";
}
if ($resteJTitane==1) {
	echo $resteJTitane." jour, ";
}

if ($resteHTitane>1) {
	echo $resteHTitane." heures, ";
}
if ($resteHTitane==1) {
	echo $resteHTitane." heure, ";
}

if ($resteMTitane>1) {
	echo $resteMTitane." minutes, ";
}
if ($resteMTitane==1) {
	echo $resteMTitane." minute, ";
}

if ($resteSTitane>1) {
	echo $resteSTitane." secondes";
}
if ($resteSTitane==1) {
	echo $resteSTitane." seconde";
}
?>
</div>
</div>
<div id="bouton">
<form action="" method="POST">
<input type="submit" value="Construire"/>
</form>
</div>
</div>

<div id="batiments">
<div id="nom">
Mine d'helium3
</div>
<div id="image">
<img src="http://www.doc-micro.fr/Jeu/lib/img/batiments/2.jpg"/>
</div>
<div id="description">

</div>
<div id="cred_ress">
<div id="credit">

</div>
<div id="temps">
<?php
if ($resteAHelium>1) {
	echo $resteAHelium." ans, ";
}
if ($resteAHelium==1) {
	echo $resteAHelium." an, ";
}

if ($resteJHelium>1) {
	echo $resteJHelium." jours, ";
}
if ($resteJHelium==1) {
	echo $resteJHelium." jour, ";
}

if ($resteHHelium>1) {
	echo $resteHHelium." heures, ";
}
if ($resteHHelium==1) {
	echo $resteHHelium." heure, ";
}

if ($resteMHelium>1) {
	echo $resteMHelium." minutes, ";
}
if ($resteMHelium==1) {
	echo $resteMHelium." minute, ";
}

if ($resteSHelium>1) {
	echo $resteSHelium." secondes";
}
if ($resteSHelium==1) {
	echo $resteSHelium." seconde";
}
?>
</div>
</div>
<div id="bouton">
<form action="" method="POST">
<input type="submit" value="Construire"/>
</form>
</div>
</div>

<div id="batiments">
<div id="nom">
Mine de torium
</div>
<div id="image">
<img src="http://www.doc-micro.fr/Jeu/lib/img/batiments/3.jpg"/>
</div>
<div id="description">

</div>
<div id="cred_ress">
<div id="credit">

</div>
<div id="temps">
<?php
if ($resteATorium>1) {
	echo $resteATorium." ans, ";
}
if ($resteATorium==1) {
	echo $resteATorium." an, ";
}

if ($resteJTorium>1) {
	echo $resteJTorium." jours, ";
}
if ($resteJTorium==1) {
	echo $resteJTorium." jour, ";
}

if ($resteHTorium>1) {
	echo $resteHTorium." heures, ";
}
if ($resteHTorium==1) {
	echo $resteHTorium." heure, ";
}

if ($resteMTorium>1) {
	echo $resteMTorium." minutes, ";
}
if ($resteMTorium==1) {
	echo $resteMTorium." minute, ";
}

if ($resteSTorium>1) {
	echo $resteSTorium." secondes";
}
if ($resteSTorium==1) {
	echo $resteSTorium." seconde";
}
?>
</div>
</div>
<div id="bouton">
<form action="" method="POST">
<input type="submit" value="Construire"/>
</form>
</div>
</div>

<div id="batiments">
<div id="nom">
Mine de fer
</div>
<div id="image">
<img src="http://www.doc-micro.fr/Jeu/lib/img/batiments/4.jpg"/>
</div>
<div id="description">

</div>
<div id="cred_ress">
<div id="credit">

</div>
<div id="temps">
<?php
if ($resteAFer>1) {
	echo $resteAFer." ans, ";
}
if ($resteAFer==1) {
	echo $resteAFer." an, ";
}

if ($resteJFer>1) {
	echo $resteJFer." jours, ";
}
if ($resteJFer==1) {
	echo $resteJFer." jour, ";
}

if ($resteHFer>1) {
	echo $resteHFer." heures, ";
}
if ($resteHFer==1) {
	echo $resteHFer." heure, ";
}

if ($resteMFer>1) {
	echo $resteMFer." minutes, ";
}
if ($resteMFer==1) {
	echo $resteMFer." minute, ";
}

if ($resteSFer>1) {
	echo $resteSFer." secondes";
}
if ($resteSFer==1) {
	echo $resteSFer." seconde";
}
?>
</div>
</div>
<div id="bouton">
<form action="" method="POST">
<input type="submit" value="Construire"/>
</form>
</div>
</div>

<div id="batiments">
<div id="nom">
Mine d'acier
</div>
<div id="image">
<img src="http://www.doc-micro.fr/Jeu/lib/img/batiments/5.jpg"/>
</div>
<div id="description">

</div>
<div id="cred_ress">
<div id="credit">

</div>
<div id="temps">
<?php
if ($resteAAcier>1) {
	echo $resteAAcier." ans, ";
}
if ($resteAAcier==1) {
	echo $resteAAcier." an, ";
}

if ($resteJAcier>1) {
	echo $resteJAcier." jours, ";
}
if ($resteJAcier==1) {
	echo $resteJAcier." jour, ";
}

if ($resteHAcier>1) {
	echo $resteHAcier." heures, ";
}
if ($resteHAcier==1) {
	echo $resteHAcier." heure, ";
}

if ($resteMAcier>1) {
	echo $resteMAcier." minutes, ";
}
if ($resteMAcier==1) {
	echo $resteMAcier." minute, ";
}

if ($resteSAcier>1) {
	echo $resteSAcier." secondes";
}
if ($resteSAcier==1) {
	echo $resteSAcier." seconde";
}
?>
</div>
</div>
<div id="bouton">
<form action="" method="POST">
<input type="submit" value="Construire"/>
</form>
</div>
</div>

<div id="batiments">
<div id="nom">
Mine de carbone
</div>
<div id="image">
<img src="http://www.doc-micro.fr/Jeu/lib/img/batiments/6.jpg"/>
</div>
<div id="description">

</div>
<div id="cred_ress">
<div id="credit">

</div>
<div id="temps">
<?php
if ($resteACarbone>1) {
	echo $resteACarbone." ans, ";
}
if ($resteACarbone==1) {
	echo $resteACarbone." an, ";
}

if ($resteJCarbone>1) {
	echo $resteJCarbone." jours, ";
}
if ($resteJCarbone==1) {
	echo $resteJCarbone." jour, ";
}

if ($resteHCarbone>1) {
	echo $resteHCarbone." heures, ";
}
if ($resteHCarbone==1) {
	echo $resteHCarbone." heure, ";
}

if ($resteMCarbone>1) {
	echo $resteMCarbone." minutes, ";
}
if ($resteMCarbone==1) {
	echo $resteMCarbone." minute, ";
}

if ($resteSCarbone>1) {
	echo $resteSCarbone." secondes";
}
if ($resteSCarbone==1) {
	echo $resteSCarbone." seconde";
}
?>
</div>
</div>
<div id="bouton">
<form action="" method="POST">
<input type="submit" value="Construire"/>
</form>
</div>
</div>

<div id="batiments">
<div id="nom">
Mine de tritium
</div>
<div id="image">
<img src="http://www.doc-micro.fr/Jeu/lib/img/batiments/7.jpg"/>
</div>
<div id="description">

</div>
<div id="cred_ress">
<div id="credit">

</div>
<div id="temps">
<?php
if ($resteATritium>1) {
	echo $resteATritium." ans, ";
}
if ($resteATritium==1) {
	echo $resteATritium." an, ";
}

if ($resteJTritium>1) {
	echo $resteJTritium." jours, ";
}
if ($resteJTritium==1) {
	echo $resteJTritium." jour, ";
}

if ($resteHTritium>1) {
	echo $resteHTritium." heures, ";
}
if ($resteHTritium==1) {
	echo $resteHTritium." heure, ";
}

if ($resteMTritium>1) {
	echo $resteMTritium." minutes, ";
}
if ($resteMTritium==1) {
	echo $resteMTritium." minute, ";
}

if ($resteSTritium>1) {
	echo $resteSTritium." secondes";
}
if ($resteSTritium==1) {
	echo $resteSTritium." seconde";
}
?>
</div>
</div>
<div id="bouton">
<form action="" method="POST">
<input type="submit" value="Construire"/>
</form>
</div>
</div>


</div>
</div>

<footer>
	<?php require("/homez.585/docmicro/www/Jeu/lib/script/footer.inc.php"); ?>
</footer>

</div>

</CENTER>
</body>
</html>