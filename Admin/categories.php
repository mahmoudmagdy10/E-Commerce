<?php

/*
=========================================================
	== categories Page
	== You Can Edit | Add | Deletes members From Here
========================================================= 
*/
	ob_start(); // Output Buffer Start
	session_start();

	if (isset($_SESSION['UserName'])) {

		$pageTitle ='categories';

		include'initialize.php';

		$do = isset($_GET['do']) ? $do = $_GET['do'] : $do = 'Manage';

		if ($do == 'Manage') {
			$sort = 'Asc';
			$sort_array = array('Asc','Desc');
			if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
				
				$sort = $_GET['sort'];
			}

			$stmt2 = $con->prepare("SELECT * FROM categories WHERE Parent =0 ORDER BY Ordering $sort");
			$stmt2->execute();
			$cats = $stmt2->fetchAll();
			if(!empty($cats)){
			?>
				<h1 class="text-center">Manage Category</h1>
				<div class="container categories ">
					<form class="form-horizontal" action="?do=Insert" method="POST">
						<div class="card panel-default">
							<div class="card-heading"><i class="fa fa-edit"></i> Manage Category
								<div class="option pull-right">
									<i class='fa fa-sort'></i> Ordering [ 
									<a class="<?php if($sort == 'Asc'){echo 'active';} ?>" href ='?sort=Asc'>Asc</a> |
									<a class="<?php if($sort == 'Desc'){echo 'active';} ?>" href="?sort=Desc">Desc</a>
									]
									<i class='fa fa-eye'></i> View [
									<span class="active full" data-view = 'full'>Full</span> |
									<span data-view = 'classic'>Classic</span>
									]
								</div>
							</div>
							<div class="card-body">
								<?php 
									foreach($cats as $cat){
										echo"<div class ='cat'>";
											echo"<div class='hidden-btns'>";
												echo "<a href='categories.php?do=Edit&catid=".$cat['ID']."' class ='btn btn-primary'><i class='fa fa-edit'></i> Edit</a>";
												echo "<a href='categories.php?do=Delete&catid=".$cat['ID']."' class ='confirm btn btn-danger'><i class='fa fa-times-circle'></i> Delete</a>";
											echo"</div>";
											echo"<h3>" .$cat['Name'] ."</h3>";
											echo"<div class ='full-view'>";
												echo "<p>"; if($cat['Describtion']==''){echo"The Category Has No Describtion";}else{echo $cat['Describtion'];} echo"</p>";
												if ($cat['Visibility'] == 1) {echo"<span class='visibility'><i class='fa fa-eye'></i> Hidden</span>";}
												if ($cat['Allow_comment'] == 1) {echo"<span class='comments'><i class='fa fa-times-circle'></i> Comments Disabled</span>";}
												if ($cat['Allow_ads'] == 1) {echo"<span class='advertises'><i class='fa fa-times-circle'></i> Ads Disabled</span>";}
												$childCats = getAllFrom("*","categories","where Parent ={$cat['ID']}","","ID","ASC");
												if (!empty($childCats)) {
													echo"<h5>Sub Category</h5>";
													echo"<ul class='list-unstyled child-cat'>";
													foreach($childCats as $child){
														echo"<li class='child-link'>
															<a href='categories.php?do=Edit&catid=".$child['ID']."'>". $child['Name']."</a>
															<a href='categories.php?do=Delete&catid=".$child['ID']."'class='show-delete confirm'>Delete</a>

														</li>";
													}
													echo"</ul>";
												}
											echo"</div>";
										echo"</div>";
										echo"<hr>";
								}
							?>
							</div>
						</div>
						<!--Start submit Field-->
						<div class="member form-group mb-3">
							<div class=" col-sm-10 col-md-6"><!-- col-md-6 => for size of screen in lab and phone -->
								<a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
							 </div>
						</div>
						<!--End of submit Field-->
					</form>
				</div>
			<?php } else{
				echo "<div style='margin: 10px;' class='alert alert-info text-center'> No Categories To be Shown</div>";
				echo '<div class=" col-sm-10 col-md-6"><!-- col-md-6 => for size of screen in lab and phone -->
					<a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
				 </div>';
			}?>	
		<?php
		}
		elseif($do == 'Add') { ?>
			<h1 class="text-center">Add New Category</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Insert" method="POST">
					<!--Start Name Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Name</label>
						<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
						  <input type="text" class="form-control" name="name" placeholder="Name Of Category" aria-label="name" required="required">
						</div>
					</div>
					<!--End of Name Field-->
					<!--Start Describtion Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Describtion</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
						  <input type="text" class=" form-control" name="describtion" aria-label="describtion" placeholder="Describtion Of Categories">
						</div>
					</div>
					<!--End of Describtion Field-->
					<!--Start Parent Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Parent</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
							<select name="parent">
								<option value="0">None</option>
								<?php
									$allCats = getAllFrom("*","categories","where Parent = 0","","ID","ASC");
									foreach($allCats as $cat){
										echo"<option value='".$cat['ID']."'>".$cat['Name']."</option>";
									} 
								?>
							</select>
						</div>
					</div>
					<!--End Parent Field-->
					<!--Start Ordering Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Ordering</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
						  <input type="text" class="form-control" name="ordering" placeholder="Number To Ordering Category" aria-label="text">
						</div>
					</div>
					<!--End of Ordering Field-->
					<!--Start Visibility Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Visibility</label>
						<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
							<div>	
							  <input type="radio" id="vis-yes" name="visibility" value="0" checked >
							  <label for="vis-yes">Yes</label>
							</div>
							<div>	
							  <input type="radio" id="vis-no" name="visibility" value="1" checked >
							  <label for="vis-no">No</label>
							</div>
						</div>
					</div>
					<!--End of Visibility Field-->
					<!--Start Comments Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Comments</label>
						<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
							<div>	
							  <input type="radio" id="com-yes" name="comments" value="0" checked >
							  <label for="com-yes">Yes</label>
							</div>
							<div>	
							  <input type="radio" id="com-no" name="comments" value="1" checked >
							  <label for="com-no">No</label>
							</div>
						</div>
					</div>
					<!--End of Comments Field-->
					<!--Start Allow_Ads Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Allow_Ads</label>
						<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
							<div>	
							  <input type="radio" id="ads-yes" name="ads" value="0" checked >
							  <label for="ads-yes">Yes</label>
							</div>
							<div>	
							  <input type="radio" id="ads-no" name="ads" value="1" checked >
							  <label for="ads-no">No</label>
							</div>
						</div>
					</div>
					<!--End of Allow_Ads Field-->
					<!--Start submit Field-->
					<div class="member form-group mb-3">
						<div class=" col-sm-10 col-md-6"><!-- col-md-6 => for size of screen in lab and phone -->
						    <input type="submit" class="btn btn-primary mb-3" value="Add Category">
						 </div>
					</div>
					<!--End of submit Field-->
				</form>
			</div>			
		<?php	
		} elseif($do == "Insert"){

		if($_SERVER['REQUEST_METHOD']== 'POST') { //Check if Request Method Is POST
				echo "<h1 class='text-center'>Insert Categories</h1>";
				echo "<div class = 'container'>";

				//Get Variables from the form 
				$name 		= $_POST['name'];
				$desc 		= $_POST['describtion'];
				$parent 	= $_POST['parent'];
				$order		= $_POST['ordering'];
				$visibile 	= $_POST['visibility'];
				$comments 	= $_POST['comments'];
				$ads 		= $_POST['ads'];

				$check = checkItem("Name","categories",$name);
				if ($check ==1) {
					$theMsg ="<div class = 'alert alert-danger'> Sorry This Category Is Exist</div>";
					redirectHome($theMsg,'back');		
				} else {
					$stmt=$con->prepare("INSERT INTO 
										categories(Name,Describtion,Parent,Ordering,Visibility,Allow_comment,Allow_ads)
										VALUES(:zname,:zdesc,:zparent,:zorder,:zvisibile,:zcomments,:zads)"); // Can replace :zname = ? , zdesc = ?
					$stmt->execute(array(
							'zname'		=> $name,
							'zdesc'		=> $desc,
							'zparent'	=> $parent,
							'zorder'	=> $order,
							'zvisibile' => $visibile,
							'zcomments' => $comments,
							'zads' 		=> $ads,
					));

					//Successed Message
					$theMsg="<div class = 'alert alert-success'> [ " .$stmt->rowCount() . ' ] Category Inserted </div>';
					redirectHome($theMsg,'back');
				} 
			} else {
				$theMsg ="<div class='alert alert-danger'>Sorry, You Can't Access Directly</div>";
				redirectHome($theMsg,'back');
			}
			echo "</div>";		
		} elseif($do == 'Delete'){
				echo "<h1 class='text-center'>Delete Category</h1>";
				echo "<div class = 'container'>";
					// Check If The Request Catid Is Numeric & Get The Integar value
					$catid = isset($_GET['catid']) && is_numeric($_GET['catid'])?intval($_GET['catid']):0; //intval() => integar Value
					$check  = checkItem('ID','categories',$catid);
					if($check > 0) {
						$stmt=$con->prepare("DELETE FROM categories WHERE ID = :zid");
						$stmt->bindparam(':zid',$catid); // Bind parametar with query
						$stmt->execute();
						//Successed Message
						$theMsg ="<div class = 'alert alert-success'> [ " .$stmt->rowCount() . ' ] Category Deleted </div>';
						redirectHome($theMsg,'back');		

					} else {
						$theMsg ="<div class = 'alert alert-success'>Not Such ID Like This!!</div>";
						redirectHome($theMsg);
					}
		
		} else if ($do == 'Edit') {	

			// Check If The Request catergory id Is Numeric & Get The Integar value
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid'])?intval($_GET['catid']):0; //intval() => integar Value
			//Select Data Depend On This ID
			$stmt = $con->prepare('SELECT * FROM `categories` WHERE ID =?'); // Limit 1 => one record is feched
			//Execute The Array
			$stmt->execute(array($catid));//To Know $userid = UserID
			//Fetch The Data
			$cat = $stmt->fetch(); // $row[] Use To Show Fetched Data In Inputs
			//Check The Row count
			$count = $stmt->rowCount();
			if($count > 0) { ?>
			<h1 class="text-center">Edite Category Info</h1>
			<div class="container">
				<form class="form-horizontal" action="?do=Update" method="POST">
					<input type="hidden" name="catid" value="<?php echo $catid ?>">
					<!--Start Name Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Name</label>
						<div class="col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
						  <input type="text" class="form-control" name="name" placeholder="Name Of Category" aria-label="name" required="required" value="<?php echo $cat['Name'] ?>">
						</div>
					</div>
					<!--End of Name Field-->
					<!--Start Describtion Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Describtion</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
						  <input type="text" class=" form-control" name="describtion" aria-label="describtion" placeholder="Describtion Of Categories" value="<?php echo $cat['Describtion'] ?>">
						</div>
					</div>
					<!--End of Describtion Field-->
					<!--Start Ordering Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Ordering</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
						  <input type="text" class="form-control" name="ordering" placeholder="Number To Ordering Category" aria-label="text" value="<?php echo $cat['Ordering'] ?>">
						</div>
					</div>
					<!--End of Ordering Field-->
					<!--Start Parent Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Parent</label>
						<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
							<select name="parent">
								<option value="0">None</option>
								<?php
									$allCats = getAllFrom("*","categories","where Parent = 0","","ID","ASC");
									foreach($allCats as $c){
										echo"<option value='".$c['ID']."'";
										if ($cat['Parent']==$c['ID']) {echo"selected";}
										echo">".$c['Name']."</option>";
									} 
								?>
							</select>
						</div>
					</div>
					<!--End Parent Field-->
					<!--Start Visibility Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Visibility</label>
						<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
							<div>	
							  <input type="radio" id="vis-yes" name="visibility" value="0" <?php if($cat['Visibility'] == 0){echo"checked";}?> >
							  <label for="vis-yes">Yes</label>
							</div>
							<div>	
							  <input type="radio" id="vis-no" name="visibility" value="1" <?php if($cat['Visibility'] == 1){echo"checked";}?>  >
							  <label for="vis-no">No</label>
							</div>
						</div>
					</div>
					<!--End of Visibility Field-->
					<!--Start Comments Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Comments</label>
						<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
							<div>	
							  <input type="radio" id="com-yes" name="comments" value="0" <?php if($cat['Allow_comment'] == 0){echo"checked";}?> >
							  <label for="com-yes">Yes</label>
							</div>
							<div>	
							  <input type="radio" id="com-no" name="comments" value="1" <?php if($cat['Allow_comment'] == 1){echo"checked";}?> >
							  <label for="com-no">No</label>
							</div>
						</div>
					</div>
					<!--End of Comments Field-->
					<!--Start Allow_Ads Field-->
					<div class="member form-group form-group-lg">
						<label class="col-ms-2 control-label">Allow_Ads</label>
						<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
							<div>	
							  <input type="radio" id="ads-yes" name="ads" value="0" <?php if($cat['Allow_ads'] == 0){echo"checked";}?> >
							  <label for="ads-yes">Yes</label>
							</div>
							<div>	
							  <input type="radio" id="ads-no" name="ads" value="1" <?php if($cat['Allow_ads'] == 1){echo"checked";}?> >
							  <label for="ads-no">No</label>
							</div>
						</div>
				</div>
					<!--End of Allow_Ads Field-->
					<!--Start submit Field-->
					<div class="member form-group mb-3">
						<div class=" col-sm-10 col-md-6"><!-- col-md-6 => for size of screen in lab and phone -->
						    <input type="submit" class="btn btn-primary mb-3" value="Update Category">
						 </div>
					</div>
					<!--End of submit Field-->
				</form>
			</div>	
		<?php
			} else {
					$theMsg ="<div class = 'alert alert-success'>Not Such ID Like This!!</div>";
					redirectHome($theMsg);
			}
		} else if($do == 'Update'){
			echo "<h1 class='text-center'>Update Category</h1>";
			echo "<div class = 'container'>";

			if($_SERVER['REQUEST_METHOD']== 'POST') { //Check if Request Method Is POST

				//Get Variables from the form 
				$id 		= $_POST['catid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['describtion'];
				$parent 	= $_POST['parent'];
				$order 		= $_POST['ordering'];
				$visibile 	= $_POST['visibility'];
				$comment 	= $_POST['comments'];
				$ads 		= $_POST['ads'];

				
					//Update The Data Base With This Info

					$stmt = $con -> prepare("UPDATE categories SET  Name = ?,
																	Describtion = ?,
																	Ordering = ?,
																	Parent = ?,
																	Visibility = ?,
																	Allow_comment = ?,
																	Allow_ads = ? 
																WHERE 
																	ID = ?");

					$stmt->execute(array($name, $desc, $order,$parent ,$visibile, $comment, $ads,$id));

					//Successed Message
					$theMsg= "<div class = 'alert alert-success'> [ " .$stmt->rowCount() . ' ] Category Updated </div>';
					redirectHome($theMsg,'back');		

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