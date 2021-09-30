<?php
//***************************************
// PROGRAMME..... moteur de visualisation 3D
// MODULE........ menu
// AUTEUR........ N.Dupont-Bloch, 2003
// VERSION....... 10.0
// PARAMETRES.... 
//***************************************
require('3d_config.inc.php');
$parametres = new Parametres();
?>

<html>
<head>
<meta http-equiv="Content-Type"
content="text/html; charset=iso-8859-1">
<title>3D-Commandes (10.0)</title>
</head>

<body>
<?php
//****** Browsers
echo '<font face="Arial"><strong>Browsers</strong></font></br>';
$handle = opendir($parametres->dir_browsers);
while ($file = readdir($handle)) {
   //*** tous les *.htm ne commençant pas par '_'.
   if ((substr($file, -4) == '.htm') &&
       (substr($file, 0, 1) != '_'))
      {
      $titre = substr($file, 0, strlen($file)-4);
      echo '<a href="' .$parametres->dir_browsers .$file .'" target="_blank"><font face="Arial">' .$titre .'</font></a></br>';
      }

   //*** tous les php
   if ((substr($file, -4) == '.php') &&
       (substr($file, 0, 1) != '_'))
      {
      $titre = substr($file, 0, strlen($file)-4);
      echo '<a href="' .$parametres->dir_browsers .$file .'" target="_blank"><font face="Arial">' .$titre .'</font></a></br>';
      }
   }
closedir($handle);
echo '<hr>';

//******
echo '<font face="Arial"><strong>Modules</strong></font></br>';
$handle = opendir($parametres->dir_modules);
while ($file = readdir($handle)) {
   //*** tous les *.htm ne commençant pas par '_'.
   if ((substr($file, -4) == '.htm') &&
       (substr($file, 0, 1) != '_'))
      {
      $titre = substr($file, 0, strlen($file)-4);
      echo '<a href="' .$parametres->dir_modules .$file .'" target="action"><font face="Arial">' .$titre .'</font></a></br>';
      }

   if ((substr($file, -4) == '.php') &&
       (substr($file, 0, 1) != '_'))
      {
      $titre = substr($file, 0, strlen($file)-4);
      echo '<a href="' .$parametres->dir_modules .$file .'" target="action"><font face="Arial">' .$titre .'</font></a></br>';
      }
   }
closedir($handle);
echo '<hr>';

//******
echo '<font face="Arial"><strong>Generators</strong></font></br>';
$handle = opendir($parametres->dir_generateurs);
while ($file = readdir($handle)) {
   //*** tous les *.htm ne commençant pas par '_'.
   if ((substr($file, -4) == '.htm') &&
       (substr($file, 0, 1) != '_'))
      {
      $titre = substr($file, 0, strlen($file)-4);
      echo '<a href="' .$parametres->dir_generateurs .$file .'" target="action"><font face="Arial">' .$titre .'</font></a></br>';
      }

   if ((substr($file, -4) == '.php') &&
       (substr($file, 0, 1) != '_'))
      {
      $titre = substr($file, 0, strlen($file)-4);
      echo '<a href="' .$parametres->dir_generateurs .$file .'" target="action"><font face="Arial">' .$titre .'</font></a></br>';
      }
   }
closedir($handle);
?>
</body>
</html>
