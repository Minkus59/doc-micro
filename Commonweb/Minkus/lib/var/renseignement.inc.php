<?php

// Upload d'image
$chemin = $_FILES['avatar']['name'];
$rep = 'avatar/';
$fichier = basename($_FILES['avatar']['name']);
$taille_origin = filesize($_FILES['avatar']['tmp_name']);
$ext_gif = array('.gif');
$ext_png = array('.png');
$ext = array('.jpg', '.jpeg','.JPG','.JPEG');
$ext_origin = strchr($_FILES['avatar']['name'], '.');
$hash = md5(uniqid(rand(), true));
$Chemin_upload = "http://www.3donweb.fr/Commonweb/Minkus/Renseignement/".$rep.$hash.$fichier."";
$TailleImageChoisie = getimagesize($_FILES['avatar']['tmp_name']);

// Largeur et taiile max
$NouvelleLargeur = 50;
$taille_max = 2000000;

// Session
$Pseudo = mysql_real_escape_string($_SESSION['pseudo']);

?>