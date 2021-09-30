<?php

// Upload d'image
$chemin = $_FILES['image']['name'];
$rep = 'mini_image/';
$fichier = basename($_FILES['image']['name']);
$taille_origin = filesize($_FILES['image']['tmp_name']);
$ext_gif = array('.gif','.GIF');
$ext_jpg = array('.jpg', '.jpeg','.JPG','.JPEG');
$ext = array('.jpg', '.jpeg','.JPG','.JPEG','.gif','.GIF');
$ext_origin = strchr($_FILES['image']['name'], '.');
$hash = md5(uniqid(rand(), true));
$Chemin_upload = "http://www.3donweb.fr/Commonweb/Minkus/Bon_plan/".$rep.$hash.$fichier."";
$TailleImageChoisie = getimagesize($_FILES['image']['tmp_name']);

// Largeur et taiile max
$NouvelleLargeur = 240;
$taille_max = 2000000;

// Session
$Pseudo = $_SESSION['pseudo'];

// suppr
$id = $_GET["id"];

?>