<?php
$User=1;

$MTitane=$cnx->query("SELECT * FROM projet_batiment WHERE id=1");
$DMTitane=$MTitane->fetch(PDO::FETCH_OBJ);

$NomMTitane=$DMTitane->nom;
$DescriptionMTitane=$DMTitane->description;
$EquipageMTitane=$DMTitane->equipage;
$ConsoMTitane=$DMTitane->consomation;
$GainMTitane=$DMTitane->gain;
$TempsMTitane=$DMTitane->temps;
$CoutMTitane=$DMTitane->cout;

$MHelium=$cnx->query("SELECT * FROM projet_batiment WHERE id=2");
$DMHelium=$MHelium->fetch(PDO::FETCH_OBJ);

$NomMHelium=$DMHelium->nom;
$DescriptionMHelium=$DMHelium->description;
$EquipageMHelium=$DMHelium->equipage;
$ConsoMHelium=$DMHelium->consomation;
$GainMHelium=$DMHelium->gain;
$TempsMHelium=$DMHelium->temps;
$CoutMHelium=$DMHelium->cout;

$MTorium=$cnx->query("SELECT * FROM projet_batiment WHERE id=3");
$DMTorium=$MTorium->fetch(PDO::FETCH_OBJ);

$NomMTorium=$DMTorium->nom;
$DescriptionMTorium=$DMTorium->description;
$EquipageMTorium=$DMTorium->equipage;
$ConsoMTorium=$DMTorium->consomation;
$GainMTorium=$DMTorium->gain;
$TempsMTorium=$DMTorium->temps;
$CoutMTorium=$DMTorium->cout;

$MFer=$cnx->query("SELECT * FROM projet_batiment WHERE id=4");
$DMFer=$MFer->fetch(PDO::FETCH_OBJ);

$NomMFer=$DMFer->nom;
$DescriptionMFer=$DMFer->description;
$EquipageMFer=$DMFer->equipage;
$ConsoMFer=$DMFer->consomation;
$GainMFer=$DMFer->gain;
$TempsMFer=$DMFer->temps;
$CoutMFer=$DMFer->cout;

$MAcier=$cnx->query("SELECT * FROM projet_batiment WHERE id=5");
$DMAcier=$MAcier->fetch(PDO::FETCH_OBJ);

$NomMAcier=$DMAcier->nom;
$DescriptionMAcier=$DMAcier->description;
$EquipageMAcier=$DMAcier->equipage;
$ConsoMAcier=$DMAcier->consomation;
$GainMAcier=$DMAcier->gain;
$TempsMAcier=$DMAcier->temps;
$CoutMAcier=$DMAcier->cout;

$MCarbone=$cnx->query("SELECT * FROM projet_batiment WHERE id=6");
$DMCarbone=$MCarbone->fetch(PDO::FETCH_OBJ);

$NomMCarbone=$DMCarbone->nom;
$DescriptionMCarbone=$DMCarbone->description;
$EquipageMCarbone=$DMCarbone->equipage;
$ConsoMCarbone=$DMCarbone->consomation;
$GainMCarbone=$DMCarbone->gain;
$TempsMCarbone=$DMCarbone->temps;
$CoutMCarbone=$DMCarbone->cout;

$MTritium=$cnx->query("SELECT * FROM projet_batiment WHERE id=7");
$DMTritium=$MTritium->fetch(PDO::FETCH_OBJ);

$NomMTritium=$DMTritium->nom;
$DescriptionMTritium=$DMTritium->description;
$EquipageMTritium=$DMTritium->equipage;
$ConsoMTritium=$DMTritium->consomation;
$GainMTritium=$DMTritium->gain;
$TempsMTritium=$DMTritium->temps;
$CoutMTritium=$DMTritium->cout;

$CoeffBatimentMine=1.2;
$CoeffTechnoMine=1.1;
$CoeffBatmentTemps=2.4;
$CoeffBatimentDroide=0.7;
$CoeffBatimentAndroide=0.5;

//Variable Niveau Batiment User--------------------------------------------------------------
$NBTitane=$cnx->query("SELECT * FROM projet_batiment_mine_titane WHERE user=$User");
$NiveauBTitane=$NBTitane->fetch(PDO::FETCH_OBJ);
$NBHelium=$cnx->query("SELECT * FROM projet_batiment_mine_helium3 WHERE user=$User");
$NiveauBHelium=$NBHelium->fetch(PDO::FETCH_OBJ);
$NBTorium=$cnx->query("SELECT * FROM projet_batiment_mine_torium WHERE user=$User");
$NiveauBTorium=$NBTorium->fetch(PDO::FETCH_OBJ);
$NBFer=$cnx->query("SELECT * FROM projet_batiment_mine_fer WHERE user=$User");
$NiveauBFer=$NBFer->fetch(PDO::FETCH_OBJ);
$NBAcier=$cnx->query("SELECT * FROM projet_batiment_mine_acier WHERE user=$User");
$NiveauBAcier=$NBAcier->fetch(PDO::FETCH_OBJ);
$NBCarbone=$cnx->query("SELECT * FROM projet_batiment_mine_carbone WHERE user=$User");
$NiveauBCarbone=$NBCarbone->fetch(PDO::FETCH_OBJ);
$NBTritium=$cnx->query("SELECT * FROM projet_batiment_mine_tritium WHERE user=$User");
$NiveauBTritium=$NBTritium->fetch(PDO::FETCH_OBJ);

$NTitane=$NiveauBTitane->titane;
$NHelium=$NiveauBHelium->helium3;
$NTotium=$NiveauBTotium->torium;
$NFer=$NiveauBFer->fer;
$NAcier=$NiveauBAcier->acier;
$NCarbone=$NiveauBCarbone->cabone;
$NTritium=$NiveauBTritium->tritium;

//Variable Niveau Technologie User--------------------------------------------------------------
$NTTitane=$cnx->query("SELECT * FROM projet_techno_mine_titane WHERE user=$User");
$NiveauTitane=$NTTitane->fetch(PDO::FETCH_OBJ);
$NTHelium=$cnx->query("SELECT * FROM projet_techno_mine_helium3 WHERE user=$User");
$NiveauHelium=$NTHelium->fetch(PDO::FETCH_OBJ);
$NTTorium=$cnx->query("SELECT * FROM projet_techno_mine_torium WHERE user=$User");
$NiveauTorium=$NTTorium->fetch(PDO::FETCH_OBJ);
$NTFer=$cnx->query("SELECT * FROM projet_techno_mine_fer WHERE user=$User");
$NiveauFer=$NTFer->fetch(PDO::FETCH_OBJ);
$NTAcier=$cnx->query("SELECT * FROM projet_techno_mine_acier WHERE user=$User");
$NiveauAcier=$NTAcier->fetch(PDO::FETCH_OBJ);
$NTCarbone=$cnx->query("SELECT * FROM projet_techno_mine_carbone WHERE user=$User");
$NiveauCarbone=$NTCarbone->fetch(PDO::FETCH_OBJ);
$NTTritium=$cnx->query("SELECT * FROM projet_techno_mine_tritium WHERE user=$User");
$NiveauTritium=$NTTritium->fetch(PDO::FETCH_OBJ);

$NTTitane=$NiveauTTitane->titane;
$NTHelium=$NiveauTHelium->helium3;
$NTTotium=$NiveauTTotium->torium;
$NTFer=$NiveauTFer->fer;
$NTAcier=$NiveauTAcier->acier;
$NTCarbone=$NiveauTCarbone->cabone;
$NTTritium=$NiveauTTritium->tritium;

?>