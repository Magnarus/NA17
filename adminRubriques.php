<?php
	if(!isset($engineLoaded))
	{
		$needHTML = true;
		require("engine.php");
	}

	if(!isset($_SESSION['admin']))
	{
		MessagesService::ajouter(MessagesService::ERREUR, "Vous n'êtes pas administrateur !");
		home();
	}

	require("include/traitementRubriques.php");

	if(isset($needHTML))
	{
		$titre = "Administration Rubriques";
		require("header.php");
	}


	$res = pg_query($vConn,"SELECT nom, rub_mere FROM rubrique");

?>

<div class="">
	<div class="col-md-3" style="float:none">
		<div class="panel panel-default">
			<table class="table">
				<tr>
					<th>Rubrique</th>
					<th>Mère</th>
					<th>Supprimer</th>
				</tr>
				<?php
					while($row = pg_fetch_object($res))
					{
						?>
						<tr>
							<td><?php echo $row->nom?></td>
							<td><?php echo $row->rub_mere?></td>
							<td><a href="?del=<?php echo $row->nom?>">Supprimer</a></td>
						</tr>
						<?php
					}
				?>
			</table>
		</div>
	</div>
</div>

<h3>Ajouter une nouvelle rubrique</h3>
<form method="POST" class="sizeForm">
	<div class="input-group">
		<span class="input-group-addon" id="sizing-addon2">Nom : </span>
		<input type="text" class="form-control"  name="nom" id="nom" value="<?php echo defaultPost('nom')?>"/>
	</div>
	<div class="input-group">
		<span class="input-group-addon" id="sizing-addon2">Rubrique mère : </span>
		<select name="mere" class="form-control">
			<option value="">-- Aucune --</option>
			<?php
			$res = pg_query($vConn,"SELECT nom, rub_mere FROM rubrique");
				while($row = pg_fetch_object($res))
				{
					?>
					<option value="<?php echo $row->nom?>"><?php echo $row->nom?></option>
					<?php
				}
			?>
		</select>
	</div>
	
	<input type="submit" value="Ajouter"  class="btn btn-default navbar-btn" />
</form>

<?php
	if(isset($needHTML))
	{
		require("footer.php");
	}
?>
