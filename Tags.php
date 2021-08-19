<?php include 'initialize.php'; 
ob_start();
?>
<div class="container">
	<div class="row">
		<?php
			if (isset($_GET['name'])) {
				$tag =$_GET['name'];
				echo"<h1 class='text-center'>".$tag."</h1>";
				$allTags = getAllFrom("*","items","where Tags like '%$tag%'", "AND Approve = 1","ID");
				foreach ($allTags as $item) {
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
				echo"You Must Add Page Name";
			}
		?>
	</div>
</div>
<?php include $tpl. 'footer.php'; 
ob_end_flush();
?>