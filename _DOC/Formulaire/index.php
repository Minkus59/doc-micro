<?php

if (isset($_POST['submit'])) {
   echo "hello";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>formulaire</title>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <head>
    <meta charset="utf-8" />
    <title>TP : Un formulaire interactif</title>
    <link href="css.css" rel="stylesheet" type="text/css"/>
  </head>
  
  <body>
    
    <form id="myForm" method="POST" action="">

      <span class="form_col">Sexe :</span>
      <label><input name="sex" type="radio" value="H" />Homme</label>
      <label><input name="sex" type="radio" value="F" />Femme</label>
      <span class="tooltip">Vous devez s�lectionnez votre sexe</span>
      <br /><br />

      <label class="form_col" for="lastName">Nom :</label>
      <input name="lastName" id="lastName" type="text" />
      <span class="tooltip">Un nom ne peut pas faire moins de 2 caract�res</span>
      <br /><br />

      <label class="form_col" for="firstName">Pr�nom :</label>
      <input name="firstName" id="firstName" type="text" />
      <span class="tooltip">Un pr�nom ne peut pas faire moins de 2 caract�res</span>
      <br /><br />

      <label class="form_col" for="age">�ge :</label>
      <input name="age" id="age" type="text" />
      <span class="tooltip">L'�ge doit �tre compris entre 5 et 140</span>
      <br /><br />

      <label class="form_col" for="login">Pseudo :</label>
      <input name="login" id="login" type="text" />
      <span class="tooltip">Le pseudo ne peut pas faire moins de 4 caract�res</span>
      <br /><br />

      <label class="form_col" for="pwd1">Mot de passe :</label>
      <input name="pwd1" id="pwd1" type="password" />
      <span class="tooltip">Le mot de passe ne doit pas faire moins de 6 caract�res</span>

      <br /><br />

      <label class="form_col" for="pwd2">Mot de passe (confirmation) :</label>
      <input name="pwd2" id="pwd2" type="password" />
      <span class="tooltip">Le mot de passe de confirmation doit �tre identique � celui d'origine</span>
      <br /><br />

      <label class="form_col" for="country">Pays :</label>

      <select name="country" id="country">
        <option value="none">S�lectionnez votre pays de r�sidence</option>
        <option value="en">Angleterre</option>
        <option value="us">�tats-Unis</option>
        <option value="fr">France</option>
      </select>
      <span class="tooltip">Vous devez s�lectionner votre pays de r�sidence</span>

      <br /><br />

      <span class="form_col"></span>
      <label><input name="news" type="checkbox" /> Je d�sire recevoir la newsletter chaque mois.</label>
      <br /><br />

      <span class="form_col"></span>
      <input type="submit" value="M'inscrire" name="submit"/> <input type="reset" value="R�initialiser le formulaire" />

    </form>
    <script type="text/javascript" src="cnx.js"></script>
  </body>
</html>