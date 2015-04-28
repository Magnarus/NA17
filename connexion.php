<?php
	
if(!isset($engineLoaded))
{
	$needHTML = true;
	require("engine.php");
}

require("include/verifConnexion.php");

if(isset($needHTML))
{
	$titre = "Connexion";
	require("header.php");
}
?>

<form method="POST" action="connexion.php" class="sizeForm">
	<div class="input-group">
  		<span class="input-group-addon"> @ </span>
  		<input type="text" class="form-control" name="email" id="email" value="<?php echo defaultPost('email')?>" placeholder="Email" aria-describedby="sizing-addon2">
	</div>
	<div class="input-group">
  		<span class="input-group-addon">MDP</span>
  		<input type="password" class="form-control" name="mdp" id="mdp" value="<?php echo defaultPost('mdp')?>" placeholder="Mot de passe" aria-describedby="sizing-addon2">
	</div>
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
