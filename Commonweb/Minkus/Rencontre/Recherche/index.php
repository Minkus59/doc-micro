<?php
// Redirection $_Session ------------------------------------------------------------------------------------------------------
require ('/homez.167/donweb/www/Commonweb/Minkus/lib/script/redirect.inc.php');

function cnx() {
			include ('/homez.167/donweb/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
										   
			@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
			@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");
}

// VAR ------------------------------------------------------------------------------------------------------------------------
$Pseudo = ($_SESSION['pseudo']);

$Genre= $_POST['genre'];
$Region=$_POST['region'];
$Age_min=$_POST['age_min'];
$Age_max=$_POST['age_max'];

if (isset($_POST['rechercher'])) {
	cnx();
	
	@mysql_query("UPDATE commonweb_rencontre_rechercher SET genre= '".$Genre."' WHERE pseudo_id = '".$Pseudo."'");
	@mysql_query("UPDATE commonweb_rencontre_rechercher SET region= '".$Region."' WHERE pseudo_id = '".$Pseudo."'");
	@mysql_query("UPDATE commonweb_rencontre_rechercher SET age_min= '".$Age_min."' WHERE pseudo_id = '".$Pseudo."'");
	@mysql_query("UPDATE commonweb_rencontre_rechercher SET age_max= '".$Age_max."' WHERE pseudo_id = '".$Pseudo."'");
	
	@mysql_query('INSERT INTO commonweb_rencontre_rechercher (genre, region, age_min, age_max, pseudo_id)
				VALUES ("'.$Genre.'", "'.$Region.'", "'.$Age_min.'", "'.$Age_max.'", "'.$Pseudo.'")');
	
	mysql_close();
}

//cnx
cnx();
// recuperation des parametre de recherche du client
$recherche=@mysql_query("SELECT * FROM commonweb_rencontre_rechercher WHERE pseudo_id='".$Pseudo."'");
$data=mysql_fetch_array($recherche);
mysql_close();
?>
<html>
<head>
<title>Recherche</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFE6FF">
<?php
// Menu ---------------------------------------------------------------------------------------------------------------------------------
require ('/homez.167/donweb/www/Commonweb/Minkus/lib/script/menu.inc.php');
//---------------------------------------------------------------------------------------------------------------------------------------
?>
<h1>Rencontre</h1>

<p><?php cnx(); require("/homez.167/donweb/www/Commonweb/Minkus/lib/script/rencontre_menu.inc.php"); ?>

<p>Selectionnée vos critères de selection

<p>
<TABLE border="1">
  <form action="" method="post">
  <tr>
  <td>Je cherche un(e) : </td><td><select name="genre"><option value="<?php echo $data['genre']; ?>"> 
         										 <?php echo $data['genre']; ?> </option>
  												<option value="Femme">Femme</option>
  												<option value="Homme">Homme</option>
												</select></td>
  </tr>
    <tr>
      <td>Tranche d'age : </td>
      <td> 
        <select name="age_min"></option>
		<option value="<?php echo $data['age_min']; ?>"> 
          <?php echo $data['age_min']; ?> </option>
          <option value="18" >18 </option>
          <option value="19" >19</option>
          <option value="20" >20</option>
          <option value="21" >21</option>
          <option value="22" >22 </option>
          <option value="23" >23</option>
          <option value="24" >24</option>
          <option value="25" >25</option>
          <option value="26" >26 </option>
          <option value="27" >27</option>
          <option value="28" >28</option>
          <option value="29" >29</option>
          <option value="30" >30 </option>
          <option value="31" >31</option>
          <option value="32" >32</option>
          <option value="33" >33</option>
          <option value="34" >34 </option>
          <option value="35" >35</option>
          <option value="36" >36</option>
          <option value="37" >37</option>
          <option value="38" >38 </option>
          <option value="39" >39</option>
          <option value="40" >40</option>
          <option value="41" >41</option>
          <option value="42" >42 </option>
          <option value="43" >43</option>
          <option value="44" >44</option>
          <option value="45" >45</option>
          <option value="46" >46 </option>
          <option value="47" >47</option>
          <option value="48" >48</option>
          <option value="49" >49</option>
          <option value="50" >50 </option>
          <option value="51" >51</option>
          <option value="52" >52</option>
          <option value="53" >53</option>
          <option value="54" >54 </option>
          <option value="55" >55</option>
          <option value="56" >56</option>
          <option value="57" >57</option>
          <option value="58" >58 </option>
          <option value="59" >59</option>
          <option value="60" >60</option>
          <option value="61" >61</option>
          <option value="62" >62 </option>
          <option value="63" >63</option>
          <option value="64" >64</option>
          <option value="65" >65</option>
          <option value="66" >66 </option>
          <option value="67" >67</option>
          <option value="68" >68</option>
          <option value="69" >69</option>
          <option value="70" >70 </option>
          <option value="71" >71</option>
          <option value="72" >72</option>
          <option value="73" >73</option>
          <option value="74" >74 </option>
          <option value="75" >75</option>
          <option value="76" >76</option>
          <option value="77" >77</option>
          <option value="78" >78 </option>
          <option value="79" >79</option>
          <option value="80" >80</option>
          <option value="81" >81</option>
          <option value="82" >82 </option>
          <option value="83" >83</option>
          <option value="84" >84</option>
          <option value="85" >85</option>
          <option value="86" >86 </option>
          <option value="87" >87</option>
          <option value="88" >88</option>
          <option value="89" >89</option>
          <option value="90" >90 </option>
          <option value="91" >91</option>
          <option value="92" >92</option>
          <option value="93" >93</option>
          <option value="94" >94 </option>
          <option value="95" >95</option>
          <option value="96" >96</option>
          <option value="97" >97</option>
          <option value="98" >98 </option>
          <option value="99" >99</option>
        </select>
        à
        <select name="age_max">
		<option value="<?php echo $data['age_max']; ?>"> 
          <?php echo $data['age_max']; ?> </option>
          <option value="19" >19</option>
          <option value="20" >20</option>
          <option value="21" >21</option>
          <option value="22" >22 </option>
          <option value="23" >23</option>
          <option value="24" >24</option>
          <option value="25" >25</option>
          <option value="26" >26 </option>
          <option value="27" >27</option>
          <option value="28" >28</option>
          <option value="29" >29</option>
          <option value="30" >30 </option>
          <option value="31" >31</option>
          <option value="32" >32</option>
          <option value="33" >33</option>
          <option value="34" >34 </option>
          <option value="35" >35</option>
          <option value="36" >36</option>
          <option value="37" >37</option>
          <option value="38" >38 </option>
          <option value="39" >39</option>
          <option value="40" >40</option>
          <option value="41" >41</option>
          <option value="42" >42 </option>
          <option value="43" >43</option>
          <option value="44" >44</option>
          <option value="45" >45</option>
          <option value="46" >46 </option>
          <option value="47" >47</option>
          <option value="48" >48</option>
          <option value="49" >49</option>
          <option value="50" >50 </option>
          <option value="51" >51</option>
          <option value="52" >52</option>
          <option value="53" >53</option>
          <option value="54" >54 </option>
          <option value="55" >55</option>
          <option value="56" >56</option>
          <option value="57" >57</option>
          <option value="58" >58 </option>
          <option value="59" >59</option>
          <option value="60" >60</option>
          <option value="61" >61</option>
          <option value="62" >62 </option>
          <option value="63" >63</option>
          <option value="64" >64</option>
          <option value="65" >65</option>
          <option value="66" >66 </option>
          <option value="67" >67</option>
          <option value="68" >68</option>
          <option value="69" >69</option>
          <option value="70" >70 </option>
          <option value="71" >71</option>
          <option value="72" >72</option>
          <option value="73" >73</option>
          <option value="74" >74 </option>
          <option value="75" >75</option>
          <option value="76" >76</option>
          <option value="77" >77</option>
          <option value="78" >78 </option>
          <option value="79" >79</option>
          <option value="80" >80</option>
          <option value="81" >81</option>
          <option value="82" >82 </option>
          <option value="83" >83</option>
          <option value="84" >84</option>
          <option value="85" >85</option>
          <option value="86" >86 </option>
          <option value="87" >87</option>
          <option value="88" >88</option>
          <option value="89" >89</option>
          <option value="90" >90 </option>
          <option value="91" >91</option>
          <option value="92" >92</option>
          <option value="93" >93</option>
          <option value="94" >94 </option>
          <option value="95" >95</option>
          <option value="96" >96</option>
          <option value="97" >97</option>
          <option value="98" >98 </option>
          <option value="99" >99
        </select>
        ans</td>
</tr>
    <TR>
      <TD>Region :</TD>
	  <TD><select name="region">
	  <option value="<?php echo $data['region']; ?>"> 
          <?php echo $data['region']; ?> </option>
          <option value="Alsace">Alsace</option>
          <option value="Aquitaine">Aquitaine</option>
          <option value="Auvergne">Auvergne</option>
          <option value="Bourgogne">Bourgogne</option>
          <option value="Bretagne">Bretagne</option>
          <option value="Centre">Centre</option>
          <option value="Champagne Ardenne">Champagne Ardenne</option>
          <option value="Corse">Corse</option>
          <option value="Franche Comté">Franche Comté</option>
          <option value="Haute Normandie">Haute Normandie</option>
          <option value="Ile de France">Ile de France</option>
          <option value="Languedoc Roussillon">Languedoc Roussillon</option>
          <option value="Limousin">Limousin</option>
          <option value="Lorraine">Lorraine</option>
          <option value="Midi Pyrénées">Midi Pyrénées</option>
          <option value="Nord Pas de Calais">Nord Pas de Calais</option>
          <option value="PACA">PACA</option>
          <option value="Pays de la Loire">Pays de la Loire</option>
          <option value="Picardie">Picardie</option>
          <option value="Poitou Charentes<">Poitou Charentes</option>
          <option value="Rhône Alpes">Rhône Alpes</option>
        </select></TD>
    </TR>
      <TD></TD>
      <TD><input type="submit" name="rechercher" value="Rechercher"/></TD>
    </TR>
  </form>
</TABLE>

<h1>Résultat</h1>

<?php
cnx();
$date=date(Y);
$Annee_min=$date-$data['age_min'];
$Annee_max=$date-$data['age_max'];

$sql1=@mysql_query("SELECT * FROM commonweb_rencontre_perso WHERE region ='".$data['region']."'AND annee BETWEEN '".$Annee_max."' AND '".$Annee_min."' AND genre ='".$data['genre']."'");
$requete4=@mysql_num_rows($sql1);

echo  $requete4; ?> profil corresponde a votre recherche</p>

<?php
while ($requete3=mysql_fetch_array($sql1)) { ?>
<img src="<?php echo $requete3['photo']; ?>"/><?php
echo $requete3['pseudo_id'];
echo $requete3['region'];
echo $requete3['description'];
echo $requete3['motdujour'];
}
mysql_close();

?>
</body>
</html>
