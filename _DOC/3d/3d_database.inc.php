<?php
//***************************************
// PROGRAMME..... moteur 3D
// MODULE........ librairie pour:
//                fichiers texte,
//                MySQL 3.23.32 (ou plus).
// AUTEUR........ N.Dupont-Bloch, 2003
// VERSION....... 1.1
//***************************************

//***************************************
class Database {
//***************************************
   var $type_database;
   
   function Database() {
   //***************
      $this->type_database = NULL;
      }
      
   function ouvrir()
   //*************
      { }

   function fermer()
   //*************
      { }

   function requete()
   //**************
      { }
   }

//***************************************
class Csv extends Database {
//***************************************
   var $file_descr;
   var $mode_acces;

   function Csv()
   //**********
      {
      $this->mode_acces = 'r';
      $this->type_database = 'CSV';
      }
   
   function ouvrir($n) {
   //*************
      $this->file_descr = fopen($n, $this->mode_acces);
      return $this->file_descr;
      }

   function fermer() {
   //*************
      fclose($this->file_descr);
      }

   function requete($r) {
   //**************
      // fonction vide, pour compatibilité de code applicatif avec 
      // les autres sous-classes de Database.
      // utilisable +tard par ex. pour décrire un format de fichier CSV.
      }

   function lire() {
   //***********
      if (!feof($this->file_descr)) {
         return fgets($this->file_descr, 4096);
         }
      else {
         return NULL;
         }
      }
   }


//***************************************
class Mysql extends Database
//***************************************
   {
   var $lien;
   var $resultat;
   
   function Mysql() {
   //*************
      $this->type_database = 'MYSQL';
      }
   
   function ouvrir($s, $n, $u, $p) {
   //*************
      $this->lien = mysql_connect($s, $u, $p)
         or die ('mysql_connect(): ' .mysql_error());
      mysql_select_db($n)
         or die ('mysql_select(): ' .mysql_error());
      return $this->lien;
      }

   function fermer() {
   //*************
      mysql_close($this->lien);
      }

   function requete($r) {
   //**************
      $this->resultat = mysql_query($r)
         or die ('mysql_query(): ' .mysql_error());
      return $this->resultat;
      }

   function lire() {
   //***********
      $row = mysql_fetch_array($this->resultat);
      return $row['act'];
      }
   }

?>
