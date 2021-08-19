<?php

/*
=========================================================
	== Manage Member Page
	== You Can Edit | Add | Deletes members From Here
========================================================= 
*/
	ob_start(); // Output Buffer Start
	session_start();

	if (isset($_SESSION['UserName'])) {

		$pageTitle ='Members';

		include'initialize.php';

		$do = isset($_GET['do']) ? $do = $_GET['do'] : $do = 'Manage';

		if ($do == 'Manage') { 
		} 

		elseif($do == 'Add') { 
		 }
		elseif($do == "Insert"){

		}
		elseif($do == 'Delete'){
		}
		else if ($do == 'Edit') {	// Edit Page 
			//Update Page
		} else if($do == 'Update'){}

		include $tpl. 'footer.php';

	} else {

		header('Location:index.php');

		exit();
	}
ob_end_flush()
?>

<!-- 
** Revesion 
** Classes Bootstrap for form in Categories page , members page
-------------------------
<div class = "container">
	<form class='form-group form-group-lg'>
		<label class='col-ms-10 col-md-6'></label>
		<input type="text" class='form-control'>
	</form>
</div>

 -->