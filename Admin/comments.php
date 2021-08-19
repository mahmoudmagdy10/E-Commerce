<?php

/*
=========================================================
	== Manage Comments Page
	== You Can Edit | Add | Deletes | Approved comments From Here
========================================================= 
*/
	ob_start(); // Output Buffer Start
	session_start();

	if (isset($_SESSION['UserName'])) {

		$pageTitle ='Comments';

		include'initialize.php';

		$do = isset($_GET['do']) ? $do = $_GET['do'] : $do = 'Manage';

		if ($do == 'Manage') { 

			$stmt=$con->prepare("SELECT
										comments.* , items.Name , users.UserName
								 FROM 
								 		comments

								 INNER JOIN items

								 ON items.ID  = comments.Item_ID

								 INNER JOIN users

								 ON users.UserID = comments.User_ID
								 ORDER BY 
									Comment_ID DESC
								 ");
			$stmt->execute();
			$rows = $stmt->fetchAll();

			if(!empty($rows)){

			?>
				<h1 class="text-center">Manage Comments</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table text-center table table-bordered">
							<tr>
								<td>#ID</td>
								<td>Comments</td>
								<td>Item Name</td>
								<td>User Name</td>
								<td>Added Date</td>
								<td>Control</td>
							</tr>
							<?php
								foreach($rows as $row) {

									echo"<tr>";
										echo"<td>".$row['Comment_ID']."</td>";
										echo"<td>".$row['Comment']."</td>";
										echo"<td>".$row['Name']."</td>";
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
				</div>
			<?php } else{
				echo "<div class='alert alert-info text-center'> No Comments To be Shown</div>";
			}?>
		<?php

		} elseif($do == 'Delete'){
				echo "<h1 class='text-center'>Delete Comment</h1>";
				echo "<div class = 'container'>";
					// Check If The Request Is Numeric & Get The Integar value
					$comid = isset($_GET['comid']) && is_numeric($_GET['comid'])?intval($_GET['comid']):0; //intval() => integar Value
					$check  = checkItem('Comment_ID','comments',$comid);
					if($check > 0) {
						$stmt=$con->prepare("DELETE FROM comments WHERE Comment_ID = :zid");
						$stmt->bindparam(':zid',$comid); // Bind parametar with query
						$stmt->execute();
						//Successed Message
						$theMsg ="<div class = 'alert alert-success'>" .$stmt->rowCount() . 'Recored Deleted </div>';
						redirectHome($theMsg,'back');		

					} else {
						$theMsg ="<div class = 'alert alert-success'>Not Such ID Like This!!</div>";
						redirectHome($theMsg);
					}


		}
		elseif($do =='Approve'){
				echo "<h1 class='text-center'>Approve Comment</h1>";
				echo "<div class = 'container'>";
					// Check If The Request Is Numeric & Get The Integar value
					$comid = isset($_GET['comid']) && is_numeric($_GET['comid'])?intval($_GET['comid']):0; //intval() => integar Value
					$check  = checkItem('Comment_ID','comments',$comid);
					if($check > 0) {
						$stmt=$con->prepare("UPDATE comments SET Status = 1 WHERE Comment_ID = ?");
						$stmt->execute(array($comid));
						//Successed Message
						$theMsg ="<div class = 'alert alert-success'> [ " .$stmt->rowCount() . ' ] Recored Approved </div>';
						redirectHome($theMsg,'back');		

					} else {
						$theMsg ="<div class = 'alert alert-danger'>Not Such ID Like This!!</div>";
						redirectHome($theMsg);
					}
		}		
		elseif($do =='unApprove'){
				echo "<h1 class='text-center'>Un Approve Comment</h1>";
				echo "<div class = 'container'>";
					// Check If The Request Is Numeric & Get The Integar value
					$comid = isset($_GET['comid']) && is_numeric($_GET['comid'])?intval($_GET['comid']):0; //intval() => integar Value
					$check  = checkItem('Comment_ID','comments',$comid);
					if($check > 0) {
						$stmt=$con->prepare("UPDATE comments SET Status = 0 WHERE Comment_ID = ?");
						$stmt->execute(array($comid));
						//Successed Message
						$theMsg ="<div class = 'alert alert-success'> [ " .$stmt->rowCount() . ' ] Recored Approved </div>';
						redirectHome($theMsg,'back');		

					} else {
						$theMsg ="<div class = 'alert alert-danger'>Not Such ID Like This!!</div>";
						redirectHome($theMsg);
					}
		}
		else if ($do == 'Edit') {	// Edit Page 

			// Check If The Request userid Is Numeric & Get The Integar value
			$comid = isset($_GET['comid']) && is_numeric($_GET['comid'])?intval($_GET['comid']):0; //intval() => integar Value
			//Select Data Depend On This ID
			$stmt = $con->prepare('SELECT * FROM `comments` WHERE Comment_ID =?'); // Limit 1 => one record is feched
			//Execute The Array
			$stmt->execute(array($comid));//To Know $userid = UserID
			//Fetch The Data
			$row = $stmt->fetch(); // $row[] Use To Show Fetched Data In Inputs
			//Check The Row count
			$count = $stmt->rowCount();
			if($count > 0) { ?>

				<h1 class="text-center">Edite Comment</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="comid" value="<?php echo $comid ?>">  <!-- Depended on edite info from userid -->
						<!--Start Comment Field-->
						<div class="member form-group form-group-lg">
							<label class="col-ms-2 control-label">Comment</label>
							<div class=" col-sm-10 col-md-6 star comm"><!-- col-md-6 => for size of screen in lab and phone -->
								<textarea class="form-control" name="comment"><?php echo $row['Comment'];?></textarea>
							</div>
						</div>
						<!--End of Comment Field-->
						<!--Start submit Field-->
						<div class="member form-group mb-3">
							<div class=" col-sm-10 col-md-6"><!-- col-md-6 => for size of screen in lab and phone -->
							    <input type="submit" class="btn btn-primary mb-3" value="Save Changes">
							 </div>
						</div>
						<!--End of submit Field-->
					</form>
				</div>
		<?php
			} else {
					$theMsg ="<div class = 'alert alert-danger'>Not Such ID Like This!!</div>";
					redirectHome($theMsg);
			}
			//Update Page
		} else if($do == 'Update'){

			echo "<h1 class='text-center'>Update Comment</h1>";
			echo "<div class = 'container'>";

			if($_SERVER['REQUEST_METHOD']== 'POST') { //Check if Request Method Is POST

				//Get Variables from the form 
				$comid	  = $_POST['comid'];
				$comment  = $_POST['comment'];

				//Update The Data Base With This Info

				$stmt = $con -> prepare("UPDATE comments SET Comment = ? WHERE Comment_ID = ?");

				$stmt->execute(array($comment, $comid));

				//Successed Message
				$theMsg= "<div class = 'alert alert-success'> [ " .$stmt->rowCount() . ' ] Recored Updated </div>';
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