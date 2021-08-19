<?php
/*
=========================================================
	== Manage Items Page
	== You Can Edit | Add | Deletes members From Here
========================================================= 
*/
	ob_start(); // Output Buffer Start
	session_start();

	if (isset($_SESSION['UserName'])) {

		$pageTitle ='Items';

		include'initialize.php';

		$do = isset($_GET['do']) ? $do = $_GET['do'] : $do = 'Manage';

		if ($do == 'Manage') { 
			$stmt=$con->prepare("SELECT items.*,categories.Name AS category_name, users.UserName 
								FROM 
									items
								INNER JOIN 
									categories
								ON 
									items.Cat_ID = categories.ID
								INNER JOIN 
									users
								ON 
									items.Member_ID = users.UserID
								ORDER BY 
									ID DESC
									");
			$stmt->execute();
			$items = $stmt->fetchAll();
			if(!empty($items)){

			?>
				<h1 class="text-center">Manage Items</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table text-center table table-bordered">
							<tr>
								<td>#ID</td>
								<td>Name</td>
								<td>Description</td>
								<td>Price</td>
								<td>Category</td>
								<td>UserName</td>
								<td>Adding Date</td>
								<td>Control</td>
							</tr>
							<?php
								foreach($items as $item) {

									echo"<tr>";
										echo"<td>".$item['ID']."</td>";
										echo"<td>".$item['Name']."</td>";
										echo"<td>".$item['Description']."</td>";
										echo"<td>".$item['Price']."</td>";
										echo"<td>".$item['category_name']."</td>";
										echo"<td>".$item['UserName']."</td>";
										echo"<td>".$item['Add_Date']."</td>";
										echo"<td>
											<a href='items.php?do=Edit&itemid="   .$item['ID'] . " 'class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
											<a href='items.php?do=Delete&itemid=" .$item['ID'] . " 'class='btn btn-danger confirm'><i class='fa fa-times-circle'></i> Delete</a>";
											if($item['Approve']==0){
											echo "<a href='items.php?do=Approve&itemid=".$item['ID']." 'class='btn btn-info active'><i class='far fa-check-circle'></i> Approve</a>";} else {
											echo "<a href='items.php?do=unApprove&itemid=".$item['ID']." 'class='btn btn-primary unactive'><i class='fa fa-times-circle'></i> Un Approved</a>";
										}									
										echo"</td>";
									echo"</tr>";
								}
							?>
						</table>
						
					</div>
					<a href='items.php?do=Add' class="btn btn-primary"><i class="fa fa-plus"></i> New Item</a>
				</div>
			<?php } else{
				echo "<div class='alert alert-info text-center'> No Items To be Shown</div>";
				echo "<a href='items.php?do=Add' class='btn btn-primary'><i class='fa fa-plus'></i> New Item</a>";
			}?>
<?php		 

		} elseif($do == 'Add') { ?>
			<h1 class="text-center">Add New Items</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Insert" method="POST">
					<!--Start Name Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Name</label>
						<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
						  <input type="text" class="form-control" name="name" placeholder="Name of Item" aria-label="name" required="required">
						</div>
					</div>
					<!--End of Name Field-->
					<!--Start Description Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Description</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
						  <input type="text" class=" form-control" name="description" aria-label="description" placeholder="Description of Item" required="required">
						</div>
					</div>
					<!--End of Description Field-->
					<!--Start Price Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Price</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
						  <input type="text" class=" form-control" name="price" aria-label="description" placeholder="Price of Item" required="required">
						</div>
					</div>
					<!--End of Price Field-->
					<!--Start Country Made Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Country</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
						  <input type="text" class=" form-control" name="country" aria-label="country" placeholder="country of Item" required="required">
						</div>
					</div>
					<!--End of Country Field-->
					<!--Start Status Made Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Status</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
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
					<!--Start Member Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Members</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
							<select name="member">
								<option value="0">....</option>
								<?php
									$allUsers = getAllFrom("*","users","","","UserID");
									foreach($allUsers as $user) {
										echo"<option value ='".$user['UserID']."'>".$user['UserName']."</option>";
									}
								?>
							</select>
						</div>
					</div>
					<!--End of Member Field-->					
					<!--Start Categories Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Categories</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
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
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
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
						<div class=" col-sm-10 col-md-6"><!-- col-md-6 => for size of screen in lab and phone -->
						    <input type="submit" class="btn btn-primary mb-3" value="Add Item">
						 </div>
					</div>
					<!--End of submit Field-->
				</form>
			</div>			
<?php
		 }
		elseif($do == "Insert"){
			if($_SERVER['REQUEST_METHOD']== 'POST') { //Check if Request Method Is POST
				echo "<h1 class='text-center'>Update Items Info</h1>";
				echo "<div class = 'container'>";

				//Get Variables from the form 
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$price		= $_POST['price'];
				$status 	= $_POST['status'];
				$country 	= $_POST['country'];
				$member_id 	= $_POST['member'];
				$cat_id 	= $_POST['categories'];
				$tag		= $_POST['tags'];

				// Validate The Form
				$formError = array();
				if (empty($name)) {
					$formError[] = "Name Can't be Empty";
				}
				if (empty($desc)) {
					$formError[] = "UserName Can't be Empty";
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
				if ($member_id == 0) {
					$formError[] = "You Must Choose The Member";
				}				
				if ($cat_id == 0) {
					$formError[] = "You Must Choose The Category";
				}
				// Loop Into Errors Array
				foreach ($formError as $error) {
					echo "<div class = 'alert alert-danger'>".$error."</div>";
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
								'zmember'	=> $member_id,
								'zcat'		=> $cat_id,
								'ztag'		=> $tag
						));

						//Successed Message
						$theMsg="<div class = 'alert alert-success'> [ " .$stmt->rowCount() . ' ] Recored Inserted </div>';
						redirectHome($theMsg,'back');
					
				}
			} else {

				$theMsg ="<div class='alert alert-danger'>Sorry, You Can't Access Directly</div>";
				redirectHome($theMsg,'back');		

			}
			echo "</div>";

		}
		elseif($do == 'Delete'){
				echo "<h1 class='text-center'>Delete Item</h1>";
				echo "<div class = 'container'>";
					// Check If The Request Is Numeric & Get The Integar value
					$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])?intval($_GET['itemid']):0; //intval() => integar Value
					$check  = checkItem('ID','items',$itemid);
					if($check > 0) {
						$stmt=$con->prepare("DELETE FROM items WHERE ID = :zid");
						$stmt->bindparam(':zid',$itemid); // Bind parametar with query
						$stmt->execute();
						//Successed Message
						$theMsg ="<div class = 'alert alert-success'> [ " .$stmt->rowCount() . ' ] Recored Deleted </div>';
						redirectHome($theMsg,'back');		

					} else {
						$theMsg ="<div class = 'alert alert-danger'>Not Such ID Like This!!</div>";
						redirectHome($theMsg);
					}


		}
		elseif($do =='Approve'){
				echo "<h1 class='text-center'>Approve Item</h1>";
				echo "<div class = 'container'>";
					// Check If The Request Is Numeric & Get The Integar value
					$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])?intval($_GET['itemid']):0; //intval() => integar Value
					$check  = checkItem('ID','items',$itemid);
					if($check > 0) {
						$stmt=$con->prepare("UPDATE items SET Approve = 1 WHERE ID = ?");
						$stmt->execute(array($itemid));
						//Successed Message
						$theMsg ="<div class = 'alert alert-success'> [ " .$stmt->rowCount() . ' ] Recored Approved </div>';
						redirectHome($theMsg,'back');		

					} else {
						$theMsg ="<div class = 'alert alert-danger'>Not Such ID Like This!!</div>";
						redirectHome($theMsg);
					}
		}		
		elseif($do =='unApprove'){
				echo "<h1 class='text-center'>Approved Item</h1>";
				echo "<div class = 'container'>";
					// Check If The Request Is Numeric & Get The Integar value
					$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])?intval($_GET['itemid']):0; //intval() => integar Value
					$check  = checkItem('ID','items',$itemid);
					if($check > 0) {
						$stmt=$con->prepare("UPDATE items SET Approve = 0 WHERE ID = ?");
						$stmt->execute(array($itemid));
						//Successed Message
						$theMsg ="<div class = 'alert alert-success'> [ " .$stmt->rowCount() . ' ] Recored Un Approved </div>';
						redirectHome($theMsg,'back');		

					} else {
						$theMsg ="<div class = 'alert alert-success'>Not Such ID Like This!!</div>";
						redirectHome($theMsg);
					}
		}
		else if ($do == 'Edit') {

			// Check If The Request userid Is Numeric & Get The Integar value
			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])?intval($_GET['itemid']):0; //intval() => integar Value

			//Select Data Depend On This ID
			$stmt = $con->prepare('SELECT * FROM `items` WHERE ID =?'); // Limit 1 => one record is feched

			//Execute The Array
			$stmt->execute(array($itemid));//To Know $userid = UserID

			//Fetch The Data
			$item = $stmt->fetch(); // $row[] Use To Show Fetched Data In Inputs

			//Check The Row count
			$count = $stmt->rowCount();
			
			if($count > 0) { ?>

			<h1 class="text-center">Edit Items</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Update" method="POST">
					<input type="hidden" name="itemid" value="<?php echo $itemid ?>"> <!--  Hidden input contain item ID Which be sent to next page To Update  -->
					<!--Start Name Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Name</label>
						<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
						  <input type="text" class="form-control" name="name" placeholder="Name of Item" aria-label="name" required="required"
						  value="<?php echo $item['Name']; ?>">
						</div>
					</div>
					<!--End of Name Field-->
					<!--Start Description Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Description</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
						  <input type="text" class=" form-control" name="description" aria-label="description" placeholder="Description of Item" required="required"
						  value="<?php echo $item['Description']; ?>">
						</div>
					</div>
					<!--End of Description Field-->
					<!--Start Price Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Price</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
						  <input type="text" class=" form-control" name="price" aria-label="price" placeholder="Price of Item" required="required"
						  value="<?php echo $item['Price']; ?>">
						</div>
					</div>
					<!--End of Price Field-->
					<!--Start Country Made Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Country</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
						  <input type="text" class=" form-control" name="country" aria-label="country" placeholder="country of Item" required="required"
						  value="<?php echo $item['Country_Made']; ?>">
						</div>
					</div>
					<!--End of Country Field-->
					<!--Start Status Made Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Status</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
							<select name="status" >
								<option value="0">....</option>
								<option value="1" <?php if($item['Status'] == 1){echo"selected";} ?>>New</option>
								<option value="2" <?php if($item['Status'] == 2){echo"selected";} ?>>Like New</option>
								<option value="3" <?php if($item['Status'] == 3){echo"selected";} ?>>Used</option>
								<option value="4" <?php if($item['Status'] == 4){echo"selected";} ?>>Old</option>
							</select>
						</div>
					</div>
					<!--End of Status Field-->
					<!--Start Member Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Members</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
							<select name="member">
								<option value="0">....</option>
								<?php 
									$allUsers = getAllFrom("*","users","","","UserID");
									foreach($allUsers as $user) {
										echo"<option value ='".$user['UserID']."'";
										if($item['Member_ID'] == $user['UserID']){echo"selected";}
										echo ">".$user['UserName']."</option>";
									}
								?>
							</select>
						</div>
					</div>
					<!--End of Member Field-->					
					<!--Start Categories Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Categories</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
							<select name="categories">
								<option value="0">....</option>
								<?php 
									$allCats = getAllFrom("*","categories","where Parent = 0","","ID");
									foreach($allCats as $cat) {
										echo"<option value ='".$cat['ID']."'";
										if($item['Cat_ID'] == $cat['ID']){echo"selected";}
										echo ">".$cat['Name']."</option>";
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
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
						  <input 
						  	type="text" 
						  	class="form-control" 
						  	name="tags"
						  	placeholder="Separete Between Tags By (,)"
						  	value="<?php echo $item['Tags']; ?>">
						</div>
					</div>
					<!--End of Tags Field-->
					<!--Start submit Field-->
					<div class="member form-group mb-3">
						<div class=" col-sm-10 col-md-6"><!-- col-md-6 => for size of screen in lab and phone -->
						    <input type="submit" class="btn btn-primary mb-3" value="Update Item">
						 </div>
					</div>
					<!--End of submit Field-->
				</form>
<?php
				$stmt=$con->prepare("SELECT
											comments.* ,  users.UserName
									 FROM 
									 		comments

									 INNER JOIN users

									 ON users.UserID = comments.User_ID

									 WHERE Item_ID = ?

									 ");
				$stmt->execute(array($itemid));
				$rows = $stmt->fetchAll();
				if (!empty($rows)) {
					?>
						<h1 class="text-center">Manage [ <?php echo $item['Name'];?> ] Comments</h1>
						<div class="container">
							<div class="table-responsive">
								<table class="main-table text-center table table-bordered">
									<tr>
										<td>#ID</td>
										<td>Comments</td>
										<td>User Name</td>
										<td>Added Date</td>
										<td>Control</td>
									</tr>
									<?php
										foreach($rows as $row) {

											echo"<tr>";
												echo"<td>".$row['Comment_ID']."</td>";
												echo"<td>".$row['Comment']."</td>";
												echo"<td>".$row['UserName']."</td>";
												echo"<td>".$row['Comment_Date']."</td>";
												echo"<td>
													<a href='comments.php?do=Edit&comid=".$row['Comment_ID']." 'class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
													<a href='comments.php?do=Delete&comid=".$row['Comment_ID']." 'class='btn btn-danger confirm'><i class='fa fa-times-circle'></i> Delete</a>";
												if($row['Status']==0){
													echo "<a href='comments.php?do=Approve&comid=".$row['Comment_ID']." 'class='btn btn-info active'><i class='far fa-check-circle'></i> Approve</a>";
												} else {
													echo "<a href='comments.php?do=unApprove&comid=".$row['Comment_ID']." 'class='btn btn-primary unactive'><i class='fa fa-times-circle'></i> Un Approve</a>";
												}
												echo"</td>";
											echo"</tr>";
										}
									?>
								</table>
							</div>
						<?php } ?>
						</div>
			</div>			
		<?php

			} else {
					$theMsg ="<div class = 'alert alert-danger'>Not Such ID Like This!!</div>";
					redirectHome($theMsg);
			}

		} else if($do == 'Update'){

			echo "<h1 class='text-center'>Update Information</h1>";
			echo "<div class = 'container'>";

			if($_SERVER['REQUEST_METHOD']== 'POST') { //Check if Request Method Is POST

				//Get Variables from the form 
				$id 		= $_POST['itemid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$price 		= $_POST['price'];
				$country 	= $_POST['country'];
				$status 	= $_POST['status'];
				$member 	= $_POST['member'];
				$cat 		= $_POST['categories'];
				$tags		= $_POST['tags'];

// Validate The Form
				$formError = array();
				if (empty($name)) {
					$formError[] = "Name Can't be Empty";
				}
				if (empty($desc) >20) {
					$formError[] = "UserName Can't be Empty";
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
				if ($member == 0) {
					$formError[] = "You Must Choose The Member";
				}				
				if ($cat == 0) {
					$formError[] = "You Must Choose The Category";
				}
				// Loop Into Errors Array
				foreach ($formError as $error) {
					echo "<div class = 'alert alert-danger'>".$error."</div>";
				}
				// Check If Ther Is No Errors in The Form
				if (empty($formError)) {

					//Update The Data Base With This Info

					$stmt = $con -> prepare("UPDATE
												items 
											SET Name 		 = ?,
												Description  = ?, 
												Price 		 = ?, 
												Country_Made = ?,
												Status 		 = ?,
												Member_ID 	 = ?,
												Cat_ID 		 = ?,
												Tags 		 = ?
											WHERE ID = ?");

					$stmt->execute(array($name, $desc, $price, $country,$status,$member,$cat,$tags, $id));

					//Successed Message
					$theMsg= "<div class = 'alert alert-success'> [ " .$stmt->rowCount() . ' ] Recored Updated </div>';
					redirectHome($theMsg,'back');		
				} 

			} else {

				$theMsg ="<div class='alert alert-danger'>Sorry, You Can't Access Directly";
				redirectHome($theMsg);

			}
			echo "</div>";
		}

		include $tpl. 'footer.php';

	} else {

		header('Location:index.php');

		exit();
	}
ob_end_flush()
?>