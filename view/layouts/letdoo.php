<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<title> <?php echo $titlePage ?> </title>
		<meta name="author" content="MARTIN Franck" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Type" content="audio/mpeg" />
		<?php $this->css();?>
			<?php $this->js();?>
	</head>
	<body>
		<div class="wrap">
			<?php 
				require_once $viewContent;
			?> 
		</div>
	
	</body>
</html>