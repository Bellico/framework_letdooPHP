<div id="wrap">
	<div class="content letdoo_error">
		<h1>Vous ne pouvez pas accéder à cette page</h1>
		<p>
			<?php 
				if(isset($errorMessage_ViewError)){
					echo "$errorMessage_ViewError";
				}else{
					echo "La page que vous tentez de charger n'existe pas.";
				}
			?>
		</p>
	</div>	
</div>