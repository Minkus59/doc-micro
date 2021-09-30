<?php
// fonction taille des entrées -------------------------------------------------------------
function taille_champ($champ,$taille_min=0,$taille_max=0){
global $_POST;
	if(!isset($_POST[$champ])){
		return false;}
	elseif (strlen($_POST[$champ])<$taille_min){
		return false;}
	elseif(strlen($_POST[$champ])>$taille_max){
		return false;}
return true; }
?>