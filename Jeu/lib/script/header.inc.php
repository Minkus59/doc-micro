<?php

//Calcul ressource a la seconde----------------------------------------------------------------

$Ressources=$cnx->query("SELECT * FROM projet_ressource WHERE user=$User");
$ressource=$Ressources->fetch(PDO::FETCH_OBJ);

$temps_ancien=$ressource->temps;

$tempsActuel=time();
$Nb_seconde=$tempsActuel-$temps_ancien;

$ProdTitane=(($GainMTitane*pow($CoeffBatimentMine,$NTitane)*pow($CoeffTechnoMine,$NTTitane))/60);
$ProdHelium=(($GainMHelium*pow($CoeffBatimentMine,$NHelium)*pow($CoeffTechnoMine,$NTHelium))/60);
$ProdTorium=(($GainMTorium*pow($CoeffBatimentMine,$NTorium)*pow($CoeffTechnoMine,$NTTorium))/60);
$ProdFer=(($GainMFer*pow($CoeffBatimentMine,$NFer)*pow($CoeffTechnoMine,$NTFer))/60);
$ProdAcier=(($GainMAcier*pow($CoeffBatimentMine,$NAcier)*pow($CoeffTechnoMine,$NTAcier))/60);

$TitaneJ=round($ProdTitane,2)*$Nb_seconde;
$HeliumJ=round($ProdHelium,2)*$Nb_seconde;
$ToriumJ=round($ProdTorium,2)*$Nb_seconde;
$FerJ=round($ProdFer,2)*$Nb_seconde;
$AcierJ=round($ProdAcier,2)*$Nb_seconde;

$TotalRTitane=$ressource->titane+$TitaneJ;
$TotalRHelium=$ressource->helium3+$HeliumJ;
$TotalRTorium=$ressource->torium+$ToriumJ;
$TotalRFer=$ressource->fer+$FerJ;
$TotalRAcier=$ressource->acier+$AcierJ;

$AjoutRessource=$cnx->query("UPDATE projet_ressource SET titane=$TotalRTitane, fer=$TotalRFer, torium=$TotalRTorium, helium3=$TotalRHelium, acier=$TotalRAcier, temps=$tempsActuel WHERE user=$User");

//-----------------------------------
?>

<div id=hprofil>
</div>
<div id=hressource>
	<li><acronym title="Titane"><?php echo $ressource->titane; ?></acronym></li>
	<li><acronym title="Helium3"><?php echo $ressource->helium3; ?></acronym></li>
	<li><acronym title="Torium"><?php echo $ressource->torium; ?></acronym></li>
	<li><acronym title="Fer"><?php echo $ressource->fer; ?></acronym></li>
	<li><acronym title="Acier"><?php echo $ressource->acier; ?></acronym></li>
	<li><acronym title="Carbone"><?php echo $ressource->carbone; ?></acronym></li>
	<li><acronym title="Tritium"><?php echo $ressource->tritium; ?></acronym></li>
</div>
<div id=hcred>
</div>