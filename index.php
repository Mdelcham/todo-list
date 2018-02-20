<?php 
    $file = file_get_contents('./assets/db/todo.json');
    // Si le fichier est vide, créer un objet vide "$taches" et ses deux tableaux.
	if (EMPTY($file))
	{
		$taches = new stdClass();
		$aFaire = array();
		$archives = array();
	}
	// Sinon importer l'objet du fichier json et lier ses tableaux à des variables.
	else
	{
		$taches = json_decode($file);
		$aFaire = $taches->aFaire;
		$archives = $taches->archives;
	}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To Do List</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <script src="assets/js/main.js"></script>
    <form class="programme" action="formulaire.php" method="post">
    	<legend>A FAIRE</legend>
    	<div id="aFaireListParent">
		<?php
			foreach ($GLOBALS["aFaire"] as $value) 
			{
				?>
					<div class="aFaireList">
		    			<input class="pointerEventsNone" name="aFaireArray[]" type="checkbox" value="<?=$value?>">
		    			<label><?=$value?></label>    		
		    		</div>
		    	<?php
			}
		?>
		</div>
		<div>
    		<button type="submit">Enregistrer</button>
    	</div>
	    <fieldset id="archive" disabled="true">
	    	<legend>ARCHIVE</legend>
	    	<?php
				foreach ($GLOBALS["archives"] as $value) 
				{
					?>
			    		<div>
			    			<input class="checkArchive" type="checkbox" checked>
			    			<label><s><?=$value?></s></label>    		
			    		</div>
			    	<?php
				}
			?>
		</fieldset>
	</form>
	<form class="ajouterTache" id="ajouterTache" name="ajouterTache" action="formulaire.php" method="post">
		<legend>Ajouter une tâche</legend>
		<label class="description">La tâche à effectuer</label>
		<textarea id="newTache" name="newTache" autofocus></textarea>
		<button id="submitNewTask" type="submit">Ajouter</button>
	</form>
    <script src="assets/js/main.js" type="text/javascript"></script>
</body>
</html>