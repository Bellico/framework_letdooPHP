<div id="wrap">
	
	<div class="content">
		<h1> <?php echo $titlePage ?></h1>
	</div>
	
	<div class="content">
		
		<h2>Ce ceci est la page par defaut de LetDoo PHP</h2>
		
		<h2>Vous commencez un nouveau projet ?</h2>
		
		<p>
			Personnalisez votre configuration : "core/config.php"
		</p>
		
		<p>
			Pour changer le contenu cette page, creez une nouvelle vue "view/index.php" associée à un controller "controller/indexController.php"
		</p>

		<p>
			Pour changer le thème, modifiez : "view/layouts/default.php"
		</p>
	</div>
	
	<div class="content">
		<a href="<?php Rooter::url("documentation.html")?>"><h1>Documentation</h1></a>
	</div>
</div>