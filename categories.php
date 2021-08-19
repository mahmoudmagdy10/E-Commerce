<?php include 'initialize.php'; 
ob_start();
session_start();
?>
<div class="container">
	<h1 class="text-center">Show Category</h1>
	<div class="row">
		<?php
			if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
				$category =intval($_GET['pageid']);
				$allItems = getAllFrom("*","items","where Cat_ID ={$category}", "AND Approve = 1","ID");
				foreach ($allItems as $item) {
					echo"<div class='col-sm-6 col-md-3'>";
						echo"<div class='thumbnail item-box'>";
							if ($item['Approve']==0) {echo"<div class='unapproval'>Un Approved</div>";}
							echo"<span class='price'>$".$item['Price']."</span>";
							echo"<img class='img-responsive text-center' src='img.png'>";
							echo"<div class='caption'>";
								echo"<h3><a href='items.php?itemid=".$item['ID']."'>".$item['Name']."</a></h3>";
								echo"<p>".$item['Description']."</p>";
								echo"<span class='date'>".$item['Add_Date']."</span>";
							echo"</div>";
						echo"</div>";
					echo"</div>";
				}
			} else {
				echo"You Must Add Page ID";
			}
		?>
	</div>
</div>
<?php include $tpl. 'footer.php'; 
ob_end_flush();
?>