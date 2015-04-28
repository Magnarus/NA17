<?php
	
if(!isset($engineLoaded))
{
	$needHTML = true;
	require("engine.php");
}

require("include/verifAuteur.php");

require("include/contenuArticle.php");
require("include/traitementRedaction.php");

if(isset($needHTML))
{
	$titre = "Rédaction";
	require("header.php");
}
?>

<form method="POST">
<div class="sizeForm">
	<div class="input-group">
		<span class="input-group-addon" id="sizing-addon2">Titre : </span>
		<input type="text" class="form-control"  name="titre" id="titre" value="<?php echo defaultPost('titre', (isset($article)?$article->titre:''))?>"/>
	</div>
	<div class="input-group">
		<span class="input-group-addon" id="sizing-addon2">Rubrique : </span>
		<select class="form-control"  name="rubrique">
			<?php

			$def = defaultPost('rubrique', (isset($article)?$article->rubrique:''));

			function selected($valeur)
			{
				global $def;
				if($def == $valeur)
					return "selected='selected'";
				else
					return "";
			}
			?>
			<option value="" <?php echo selected(""); ?>>-- Aucune --</option>
			<?php


			$res = pg_query($vConn,"SELECT nom, rub_mere FROM rubrique");
				while($row = pg_fetch_object($res))
				{
					?>
					<option value="<?php echo $row->nom?>" <?php echo selected($row->nom); ?>><?php echo $row->nom?></option>
					<?php
				}
			?>
		</select>
	</div>
	<div class="input-group">
		<span class="input-group-addon" id="sizing-addon2">Mots clés : </span>
		<input type="text" class="form-control"  name="keywords" id="keywords" value="<?php echo defaultPost('keywords', (isset($keywords)?implode($keywords,', '):''))?>"/>
	</div>
<?php
	if(isset($article) && isset($version))
	{
		?>
		<div class="input-group">
			<span class="input-group-addon" id="sizing-addon2">Date publication : </span>
			<input type="text" class="form-control"  name="date" id="date" value="<?php echo $article->date_publi?>" disabled="disabled"/>
		</div>
		<div class="input-group">
			<span class="input-group-addon" id="sizing-addon2">Date modification : </span>
			<input type="text" class="form-control"  name="date" id="date" value="<?php echo $version->date_modif?>" disabled="disabled"/>
		</div>
		<div class="input-group">
			<span class="input-group-addon" id="sizing-addon2">Version : </span>
			<input type="text" class="form-control"  name="version" id="version" value="<?php echo $version->id_version?>" disabled="disabled"/>
		</div>
		<?php
	}
?>
</div>
	<br/>
	<input type="submit" value="Publier l'article" class="btn btn-default navbar-btn" /><br/><br/>

	<input type="button" value="+" onclick="addContenuText(-1)" class="btn btn-default"/>
	<div id="contenuContainer"></div>

	<br/>
	<input type="submit" value="Publier l'article"  class="btn btn-default navbar-btn" />


</form>

<script>
<?php
	function remplace($texte)
	{
		$texte = str_replace("'","\'", $texte);
		$texte = str_replace("\n","\\n", $texte);
		$texte = str_replace("\r","\\r", $texte);
		return $texte;
	}

	if(isset($article) && isset($version) && !isset($_POST['contenuType']))
	{
		for ($i=0; $i < count($contenu); $i++)
		{
			echo "addContenuText(0, '".$contenu[$i]['type']."','".remplace($contenu[$i]['titre'])."', '".remplace($contenu[$i]['contenu'])."');\n";
		}
	}
	elseif(isset($_POST['contenuType']))
	{
		for ($i=0; $i < count($_POST['contenuType']); $i++)
		{ 
			echo "addContenuText(0, '".$_POST['contenuType'][$i]."','".remplace($_POST['contenuTitre'][$i])."', '".remplace($_POST['contenuValeur'][$i])."');\n";
		}
	}
	else
	{
		echo 'addContenuText()';
	}
?>
</script>



<?php
if(isset($needHTML))
{
	require("footer.php");
}
?>
