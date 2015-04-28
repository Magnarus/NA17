<?php
	
if(!isset($engineLoaded))
{
	$needHTML = true;
	require("engine.php");
}

require("include/verifInscription.php");

if(isset($needHTML))
{
	$titre = "Inscription";
	require("header.php");
}
?>

<form method="POST" action="inscription.php" class="sizeForm">
	<div class="input-group">
  		<span class="input-group-addon">Nom</span>
  		<input type="text" class="form-control" name="nom" id="nom" value="<?php echo defaultPost('nom')?>" placeholder="Nom" aria-describedby="sizing-addon2">
	</div>

	<div class="input-group">
  		<span class="input-group-addon">Prénom</span>
  		<input type="text" class="form-control" name="prenom" id="prenom" value="<?php echo defaultPost('prenom')?>" placeholder="Prénom" aria-describedby="sizing-addon2">
	</div>

	<div class="input-group">
  		<span class="input-group-addon">Email</span>
  		<input type="text" class="form-control" name="email" id="email" value="<?php echo defaultPost('email')?>" placeholder="Email" aria-describedby="sizing-addon2">
	</div>

	<div class="input-group">
  		<span class="input-group-addon">Mot de passe</span>
  		<input type="password" class="form-control" name="mdp" id="mdp" value="<?php echo defaultPost('mdp')?>" placeholder="Mot de passe" aria-describedby="sizing-addon2">
	</div>

	<div class="input-group">
  		<span class="input-group-addon">Repetez</span>
  		<input class="form-control" type="password" name="mdp2" id="mdp2" value="<?php echo defaultPost('mdp2')?>" placeholder="Repetez" aria-describedby="sizing-addon2">
	</div>

	<div class="input-group">
  		<span class="input-group-addon">Auteur</span>
  		<input class="form-control" type="checkbox"  name="auteur" id="auteur" aria-describedby="sizing-addon2" <?php echo defaultPostChecked('auteur')?>/>
	</div>
	<!--<label for="auteur">Auteur :</label><input type="checkbox" name="auteur" id="auteur" <?=defaultPostChecked('auteur')?>/>Oui<br/>-->
	<div>
		<button type="submit" class="btn btn-default navbar-btn">Envoyer</button>
	</div>
</form>

<?php
if(isset($needHTML))
{
	require("footer.php");
}
?>
