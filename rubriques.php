<?php

	function affich_rec($vQuery){
		global $vConn;
		while ($rubriques = pg_fetch_array($vQuery, null, PGSQL_ASSOC)){
			echo"<li><a class='radparent' href='listeArticle.php?rubrique=$rubriques[nom]'>".$rubriques['nom']."</a><ul class='nav nav-pills nav-stacked'>";
			$req ="SELECT * FROM rubrique where rub_mere ='".$rubriques['nom']."';";
			$query=pg_query($vConn, $req);
			affich_rec($query);
			echo"</ul></li>";
		}

	}

	$vSql ="SELECT * FROM rubrique where rub_mere IS NULL;";
	$vQuery=pg_query($vConn, $vSql);
	echo "<div style='padding-top:60px' class='container-fluid'>";
	echo "<div class='row'>";
	echo "<div class='col-md-3 col-md-offset-2'>";
	echo "<ul id='rad0' class='radmc nav nav-pills nav-stacked'>";
	affich_rec($vQuery);
	echo "<li class='radclear'></li></ul>";
	echo "</div>";

?>
	<div class='col-md-4'>
		<FORM method='get' class="navbar-form navbar-left" action='listeArticle.php'>
			<p>Mot-clef :</p><input type='text' placeholder="Mot-Clef" class="form-control" name='mot-clef' />
			<input type='submit' class="btn btn-default"/>
		</FORM>
		<FORM method='get' class="navbar-form navbar-left" action='listeArticle.php'>
			<div class="form-group">
				<p>Titre :</p><input type='text' placeholder="Titre" class="form-control" name='titre' />
				<input type='submit' class="btn btn-default"/>
			</div>
			
		</FORM>
		<FORM method='get' class="navbar-form navbar-left" action='listeArticle.php'>
			<div class="form-group">
				<p>Auteur :</p><input type='text' placeholder="Nom" class="form-control" name='nom' />
				<input type='text' placeholder="Prenom" class="form-control" name='prenom' />
				<input type='submit' class="btn btn-default"/>
			</div>
			
		</FORM>
	</div>
</div>
