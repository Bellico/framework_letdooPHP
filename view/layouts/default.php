<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<title> <?php echo $titlePage ?> </title>
		<meta name="author" content="MARTIN Franck" />
		<meta name="description" content="LetDoo PHP est un framework qui fonctionne sous un modèle MVC" />
		<meta name="keywords" content="LetDoo PHP, MVC" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php $this->link_headerElements();?>
		<style type="text/css">
			body{margin: 0; padding: 0;  color: #FFF; font-size: 100%;background: #2F2D26; font-family: 'Times New Roman', 'Calibri', 'Arial', 'Verdana', 'Helvetica', 'sans-serif'}
			a{color:#000; text-decoration: none;font-weight: bold}
			a:hover{text-decoration: underline;color:#650617}
			img{border: none}
			#wrap{width: 95%;margin:20px auto}
			h1{text-align: center}
			p{font-size: 20px;text-shadow: 2px 2px 3px  #000}
			.content{margin: 30px auto;background: #2B353F;padding: 20px;border-radius:20px;box-shadow: 2px 2px 10px #000}
		</style>
	</head>
	<body>
		<?php 
			require_once $viewContent;
		?>
	</body>
</html>