<?php
//***************************************
// PROGRAMME..... moteur 3D
// MODULE........ paramétrage
// AUTEUR........ N.Dupont-Bloch, 2003
// VERSION....... 9.3
// PARAMETRES.... 
//***************************************
class Parametres
//**************
   {
   var $dir_racine;
   var $dir_generateurs;
   var $dir_modules;
   var $dir_anims;
   var $dir_objets;
   var $dir_productions;
   var $database;
   var $serveur_database;
   var $port_serveur_database;
   var $user_database;
   var $password_database;
   var $serveur_moteur_3d;
   var $port_serveur_moteur_3d;
   var $coul_fond;
   var $coul_fond1;
   var $coul_fond2;
   var $coul_fond3;
   var $coul_fond4;
   
   function Parametres()
   //*****************
      {
      $this->dir_racine      = '3d_v5';
      $this->dir_browsers    = 'browsers/';
      $this->dir_generateurs = 'generators/';
      $this->dir_modules     = 'modules/';
      $this->dir_anims       = 'anims/';
      $this->dir_objets      = 'objects/';
      $this->dir_productions = 'productions/';
      $this->database               = '3d_anim';
      $this->serveur_database       = 'localhost';
      $this->user_database          = '';
      $this->password_database      = '';
      $this->serveur_moteur_3d      = 'localhost:81';
      $this->coul_fond              = '#FFFFFF';
      $this->coul_fond1             = '#FFFFFF'; // 7EABFE
      $this->coul_fond2             = '#FAFAFA'; // 6D9AED
      $this->coul_fond3             = '#EFEFEF'; // BFCDFF
      $this->coul_fond4             = '#FCFCFC'; // CFDEFF
      }
   }
?>
