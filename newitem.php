<?php 
	ob_start();
	session_start();
	$pageTitle = "Create New Item";
	include 'initialize.php';

	if(isset($_SESSION['user'])){
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$formError=array();

			// Filter Fields 
			$name 		= filter_var($_POST['name'],FILTER_SANITIZE_STRING);
			$desc 		= filter_var($_POST['description'],FILTER_SANITIZE_STRING);
			$price 		= filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
			$country 	= filter_var($_POST['country'],FILTER_SANITIZE_STRING);
			$status 	= filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
			$category 	= filter_var($_POST['categories'],FILTER_SANITIZE_NUMBER_INT);
			$tags		= filter_var($_POST['tags'],FILTER_SANITIZE_STRING);

			if (empty($name)) {
				$formError[] = "Name Can't be Empty";
			}
			if (empty($desc) >20) {
				$formError[] = "Description Can't be Empty";
			}
			if (empty($price)) {
				$formError[] = "Email Can't be Empty";
			}
			if (empty($country)) {
				$formError[] = "Password Can't be Empty";
			}
			if ($status == 0) {
				$formError[] = "You Must Choose The Status";
			}				
				
			if ($category == 0) {
				$formError[] = "You Must Choose The Category";
			}
			// Check If Ther Is No Errors in The Form
			if (empty($formError)) { //Insert The Data Base With This Info

				$stmt=$con->prepare("INSERT INTO 
									items(Name,Description,Price,Country_Made,Status,Add_Date,Cat_ID,Member_ID,Tags)
									VALUES(:zname,:zdesc,:zprice,:zcountry,:zstatus,now(),:zcat,:zmember,:ztag)"); // Can replace :zuser = ? , zpass = ?
				$stmt->execute(array(
						'zname'		=> $name,
						'zdesc'		=> $desc,
						'zprice'	=> $price,
						'zcountry'	=> $country,
						'zstatus'	=> $status,
						'zmember'	=> $_SESSION['uid'],
						'zcat'		=> $category,
						'ztag'		=> $tags
				));

				//Successed Message
				if($stmt) {
					$successMsg = "Item Added";
				}				
			}
		}

		$getUser = $con->prepare("SELECT * FROM users WHERE UserName=?");
		$getUser->execute(array($sessionUser));
		$profile=$getUser->fetch();
?>
		<h1 class="text-center"><?php echo $pageTitle;?></h1>
		<div class="information">
			<div class="panel-container">
			    <div class="panel panel-default">
			        <div class="panel-heading"> 
			         	<?php echo $pageTitle;?>
			        </div>
			        <div class="panel-body profile"> 
			        	<div class="row">
			        		<div class="col-md-8">
								<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
									<!--Start Name Field-->
									<div class="member form-group form-group-lg">
										<label class="col-ms-3 control-label">Name</label>
										<div class=" col-sm-10 col-md-12 star"><!-- col-md-6 => for size of screen in lab and phone -->
										<input 
											type="text" 
											pattern=".{4,}"
											title="This Field Require At Least 4 Characters" 
											class="form-control live" 
											name="name" 
											placeholder="Name of Item"
											required="required" 
											data-class='.live-name'>
										</div>
									</div>
									<!--End of Name Field-->
									<!--Start Description Field-->
									<div class="member form-group form-group-lg">
										<label class="col-ms-2 control-label">Description</label>
										<div class="col-sm-10 col-md-12 star"> <!-- col-md-6 => for size of screen in lab and phone -->
										  <input 
											  type="text" 
											  pattern=".{10,}"
											  title="This Field Require At Least 10 Characters" 
											  class=" form-control live" 
											  name="description" 
											  placeholder="Description of Item" 
											  required="required" 
											  data-class='.live-desc'>
										</div>
									</div>
									<!--End of Description Field-->
									<!--Start Price Field-->
									<div class="member form-group form-group-lg">
										<label class="col-ms-2 control-label">Price</label>
										<div class=" col-sm-10 col-md-12 star"> <!-- col-md-6 => for size of screen in lab and phone -->
										  <input 
											  type="text" 
											  class=" form-control live" 
											  name="price"
											  placeholder="Price of Item" 
											  required="required" 
											  data-class='.live-price'>
										</div>
									</div>
									<!--End of Price Field-->
									<!--Start Country Made Field-->
									<div class="member form-group form-group-lg">
										<label class="col-ms-2 control-label">Country</label>
										<div class=" col-sm-10 col-md-12 star"> <!-- col-md-6 => for size of screen in lab and phone -->
										  <input 
											  type="text" 
											  class=" form-control" 
											  name="country" 
											  placeholder="country of Item" 
											  required="required">
										</div>
									</div>
									<!--End of Country Field-->
									<!--Start Status Made Field-->
									<div class="member form-group form-group-lg">
										<label class="col-ms-2 control-label">Status</label>
										<div class=" col-sm-10 col-md-12 star"> <!-- col-md-6 => for size of screen in lab and phone -->
											<select name="status">
												<option value="0">....</option>
												<option value="1">New</option>
												<option value="2">Like New</option>
												<option value="3">Used</option>
												<option value="4">Old</option>
											</select>
										</div>
									</div>
									<!--End of Status Field-->				
									<!--Start Categories Field-->
									<div class="member form-group form-group-lg">
										<label class="col-ms-2 control-label">Categories</label>
										<div class=" col-sm-10 col-md-12 star"> <!-- col-md-6 => for size of screen in lab and phone -->
											<select name="categories">
												<option value="0">....</option>
												<?php
													$allCats = getAllFrom("*","categories","where Parent = 0","","ID");
													foreach($allCats as $cat) {
														echo"<option value ='".$cat['ID']."'>".$cat['Name']."</option>";
														$allChild = getAllFrom("*","categories","where Parent = {$cat['ID']}","","ID");
														foreach($allChild as $child){
															echo"<option value ='".$child['ID']."'>--> ".$child['Name']."</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									<!--End of Categories Field-->
									<!--Start Tags Field-->
									<div class="member form-group form-group-lg">
										<label class="col-ms-2 control-label">Tags</label>
										<div class=" col-sm-10 col-md-12 star"> <!-- col-md-6 => for size of screen in lab and phone -->
										  <input 
										  	type="text" 
										  	class=" form-control" 
										  	name="tags"
										    placeholder="Separete Between Tags By (,)" 
										    >
										</div>
									</div>
									<!--End of Tags Field-->
									<!--Start submit Field-->
									<div class="member form-group mb-3">
										<div class=" col-sm-10 col-md-12"><!-- col-md-6 => for size of screen in lab and phone -->
										    <input style="margin-top: 5px;" type="submit" class="btn btn-primary mb-3" value="Add Item">
										 </div>
									</div>
									<!--End of submit Field-->
								</form>
			        		</div>
			        		<div class="col-md-4">
								<div class='thumbnail item-box live-preview'>
									<span class='price'>
									$<span class="live-price"></span>
									</span>
									<img class='img-responsive text-center' src='img.png'>
									<div class='caption'>
										<h3 class="live-name">Title</h3>
										<p class="live-desc">Description</p>
									</div>
								</div>	
			        		</div>
			        	</div>
			        	<?php 
			        		if(!empty($formError)){
			        			foreach($formError as $error){
			        				echo"<div class='alert alert-danger'>".$error."</div>";
			        			}
			        		}
			        		if(isset($successMsg)) {
			        			echo"<div class='alert alert-success'>".$successMsg."</div>";
			        		}
			        	?>

			        </div>
			    </div>
			</div>
<?php
	}else {
			header('Location:login.php');
		  }
	include $tpl. 'footer.php'; 
ob_end_flush();
?>