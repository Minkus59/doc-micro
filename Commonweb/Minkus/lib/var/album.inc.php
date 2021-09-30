<?php

// Upload d'image
$chemin = $_FILES['photo']['name'];
$rep = 'photo/';
$rep_mini = 'mini_photo/';
$fichier = basename($_FILES['photo']['name']);
$taille_origin = filesize($_FILES['photo']['tmp_name']);
$ext_gif = array('.gif','.GIF');
$ext_jpg = array('.jpg', '.jpeg','.JPG','.JPEG');
$ext = array('.jpg', '.jpeg','.JPG','.JPEG','.gif','.GIF');
$ext_origin = strchr($_FILES['photo']['name'], '.');
$hash = md5(uniqid(rand(), true));
$Chemin_mini_upload = "http://www.3donweb.fr/Commonweb/Minkus/Album/".$rep_mini.$hash.$fichier."";
$Chemin_upload = "http://www.3donweb.fr/Commonweb/Minkus/Album/".$rep.$hash.$fichier."";
$TailleImageChoisie = getimagesize($_FILES['photo']['tmp_name']);

// Largeur et taiile max
$NouvelleLargeur = 200;
$taille_max = 2000000;

// Session
$Pseudo = $_SESSION['pseudo'];

// suppr
$id = $_GET["id"];
$rep = 'photo/';
$rep_mini = 'mini_photo/';

?>