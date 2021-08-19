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
			$query = '';
			if(isset($_GET['page']) && $_GET['page']=="Pending"){
				$query = 'AND RegStatus = 0';
			}

			$stmt=$con->prepare("SELECT * FROM users WHERE GROUPID !=1 $query  ORDER BY UserID DESC ");
			$stmt->execute();
			$rows = $stmt->fetchAll();

			if(!empty($rows)){

			?>
				<h1 class="text-center">Manage Member</h1>
				<div class="container">
					<div class="table-responsive">
						<table class="main-table text-center table table-bordered img-member">
							<tr>
								<td>#ID</td>
								<td>Avatar</td>
								<td>Username</td>
								<td>Email</td>
								<td>Fullname</td>
								<td>Registered Data</td>
								<td>Control</td>
							</tr>
							<?php
							foreach($rows as $row) {

								echo"<tr>";
									echo"<td>".$row['UserID']."</td>";
									echo"<td>";
										if (! empty($row['Avatar'])) {
											echo "<img src='uploads/avatar/".$row['Avatar']."'>";
										} else {
											echo "<img src='uploads/avatar/img.png'>";
										}
									echo"</td>";
									echo"<td>".$row['UserName']."</td>";
									echo"<td>".$row['Email']."</td>";
									echo"<td>".$row['FullName']."</td>";
									echo"<td>".$row['thedate']."</td>";
									echo"<td>
										<a href='members.php?do=Edit&userid=".$row['UserID']." 'class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='members.php?do=Delete&userid=".$row['UserID']." 'class='btn btn-danger confirm'><i class='fa fa-times-circle'></i> Delete</a>";
									if($row['RegStatus']==0){
										echo "<a href='members.php?do=active&userid=".$row['UserID']." 'class='btn btn-info active'><i class='far fa-check-circle'></i> Active</a>";
									} else if($row['RegStatus']==0){
										echo "<a href='members.php?do=unActive&userid=".$row['UserID']." 'class='btn btn-primary unactive'><i class='fa fa-times-circle'></i> Un Active</a>";
									}
									echo"</td>";
								echo"</tr>";
							}
							?>
						</table>
						
					</div>
					<a href='members.php?do=Add' class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
				</div>
				<?php } else{
					echo "<div class='alert alert-info text-center'> No Members To be Shown</div>";
				}?>

		<?php } elseif($do == 'Add') { ?>
				<h1 class="text-center">Add New Member</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
						<!--Start UserName Field-->
						<div class="member form-group form-group-lg">
							<label class="col-ms-2 control-label">UserName</label>
							<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
							  <input type="text" class="form-control" name="username" placeholder="Username" aria-label="Username"autocomplete="off" required="required">
							</div>
						</div>
						<!--End of UserName Field-->
						<!--Start Password Field-->
						<div class="member form-group form-group-lg">
							<label class="col-ms-2 control-label">Password</label>
							<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
							  <input type="password" class="password form-control" name="password" aria-label="password"autocomplete="off"  placeholder="Password" required="required">
							  <i class="show-pass password fa fa-eye fa-2x"></i>
							</div>
						</div>
						<!--End of Password Field-->
						<!--Start Email Field-->
						<div class="member form-group form-group-lg">
							<label class="col-ms-2 control-label">Email</label>
							<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
							  <input type="email" class="form-control" name="email" placeholder="Email" aria-label="Email"autocomplete="off" required="required" >
							</div>
						</div>
						<!--End of Email Field-->
						<!--Start FullName Field-->
						<div class="member form-group form-group-lg">
							<label class="col-ms-2 control-label">FullName</label>
							<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
							  <input type="text" class="form-control" name="fullname" placeholder="FullName"autocomplete="off" required="required" >
							</div>
						</div>
						<!--End of FullName Field-->
						<!--Start Avatar Field-->
						<div class="member form-group form-group-lg">
							<label class="col-ms-2 control-label">Avatar</label>
							<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
							  <input 
							  	type="file" 
							  	class="form-control" 
							  	name="avatar"
							  	required="required" >
							</div>
						</div>
						<!--End of Avatar Field-->
						<!--Start submit Field-->
						<div class="member form-group mb-3">
							<div class=" col-sm-10 col-md-6"><!-- col-md-6 => for size of screen in lab and phone -->
							    <input type="submit" class="btn btn-primary mb-3" value="Add Member">
							 </div>
						</div>
						<!--End of submit Field-->
					</form>
				</div>
		<?php }
		elseif($do == "Insert"){

			if($_SERVER['REQUEST_METHOD']== 'POST') { //Check if Request Method Is POST
				echo "<h1 class='text-center'>Update Information</h1>";
				echo "<div class = 'container'>";
	 			// Upload Variables
				$avatarName = $_FILES['avatar']['name'];
				$avatarType = $_FILES['avatar']['type'];
				$avatarTemp = $_FILES['avatar']['tmp_name'];
				$avatarSize = $_FILES['avatar']['size'];
				//Get Avatar Extention
				$avatarAllowedExtentions = array('jpeg','jpg','png','gif');
				$exploadExtention = explode(".",$avatarName);
				$avatarExtention = strtolower(end($exploadExtention));


				//Get Variables from the form 
				$user = $_POST['username'];
				$pass = $_POST['password'];
				$email= $_POST['email'];
				$name = $_POST['fullname'];

				$hashPass = sha1($_POST['password']);
				// Validate The Form
				$formError = array();
				if (empty($user)) {
					$formError[] = "UserName Can't be Empty";
				}
				if (strlen($user) < 3) {
					$formError[] = "UserName Can't be Less than 3 Characters";
				}
				if (empty($email)) {
					$formError[] = "Email Can't be Empty";
				}
				if (empty($pass)) {
					$formError[] = "Password Can't be Empty";
				}
				if (empty($name)) {
					$formError[] = "FullName Can't be Empty";
				}
				if (! empty($avatarName) && ! in_array($avatarExtention,$avatarAllowedExtentions)) {
					$formError[] = "This Extention Isn't Allowed";
				}
				if (empty($avatarName)) {
					$formError[] = "Avatar Is Required";
				}
				if ($avatarSize > 4132432) {
					$formError[] = "Avatar Can't Be Larger than 4MB";
				}
				// Loop Into Errors Array
				foreach ($formError as $error) {
					echo "<div class = 'alert alert-danger'>".$error."</div>";
				}
				// Check If Ther Is No Errors in The Form
				if (empty($formError)) { //Insert The Data Base With This Info
					$avatar = rand(0,1000000)."_".$avatarName;
					move_uploaded_file($avatarTemp,"uploads\avatar\\".$avatar);
					// Check If Item Is Exist
					$check = checkItem("UserName","users",$user);
					if ($check ==1) {
						$theMsg ="<div class = 'alert alert-danger'> Sorry This User Isn't Exist</div>";
						redirectHome($theMsg,'back');		
					} else {
						$stmt=$con->prepare("INSERT INTO 
											users(UserName,Password,Email,FullName,RegStatus,thedate,Avatar)
											VALUES(:zuser,:zpass,:zemail,:zfullname,1,now(),:zavatar)"); // Can replace :zuser = ? , zpass = ?
						$stmt->execute(array(
								'zuser'		=> $user,
								'zpass'		=> $hashPass,
								'zemail'	=> $email,
								'zfullname' => $name,
								'zavatar'	=> $avatar
						));

						//Successed Message
						$theMsg="<div class = 'alert alert-success'>" .$stmt->rowCount() . 'Recored Inserted </div>';
						redirectHome($theMsg,'back');
					} 
				}
			} else {

				$theMsg ="<div class='alert alert-danger'>Sorry, You Can't Access Directly</div>";
				redirectHome($theMsg);		

			}
			echo "</div>";
		}
		elseif($do == 'Delete'){
				echo "<h1 class='text-center'>Delete Member</h1>";
				echo "<div class = 'container'>";
					// Check If The Request Is Numeric & Get The Integar value
					$userid = isset($_GET['userid']) && is_numeric($_GET['userid'])?intval($_GET['userid']):0; //intval() => integar Value
					$check  = checkItem('userid','users',$userid);
					if($check > 0) {
						$stmt=$con->prepare("DELETE FROM users WHERE UserID = :zid");
						$stmt->bindparam(':zid',$userid); // Bind parametar with query
						$stmt->execute();
						//Successed Message
						$theMsg ="<div class = 'alert alert-success'>" .$stmt->rowCount() . 'Recored Deleted </div>';
						redirectHome($theMsg,'back');		

					} else {
						$theMsg ="<div class = 'alert alert-success'>Not Such ID Like This!!</div>";
						redirectHome($theMsg);
					}
		}
		elseif($do =='active'){
				echo "<h1 class='text-center'>Activate Member</h1>";
				echo "<div class = 'container'>";
					// Check If The Request Is Numeric & Get The Integar value
					$userid = isset($_GET['userid']) && is_numeric($_GET['userid'])?intval($_GET['userid']):0; //intval() => integar Value
					$check  = checkItem('userid','users',$userid);
					if($check > 0) {
						$stmt=$con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
						$stmt->execute(array($userid));
						//Successed Message
						$theMsg ="<div class = 'alert alert-success'> [ " .$stmt->rowCount() . ' ] Recored Activated </div>';
						redirectHome($theMsg,'back');		

					} else {
						$theMsg ="<div class = 'alert alert-success'>Not Such ID Like This!!</div>";
						redirectHome($theMsg);
					}
		}		
		elseif($do =='unActive'){
				echo "<h1 class='text-center'>Activate Member</h1>";
				echo "<div class = 'container'>";
					// Check If The Request Is Numeric & Get The Integar value
					$userid = isset($_GET['userid']) && is_numeric($_GET['userid'])?intval($_GET['userid']):0; //intval() => integar Value
					$check  = checkItem('userid','users',$userid);
					if($check > 0) {
						$stmt=$con->prepare("UPDATE users SET RegStatus = 0 WHERE UserID = ?");
						$stmt->execute(array($userid));
						//Successed Message
						$theMsg ="<div class = 'alert alert-success'> [ " .$stmt->rowCount() . ' ] Recored Un Activated </div>';
						redirectHome($theMsg,'back');		

					} else {
						$theMsg ="<div class = 'alert alert-success'>Not Such ID Like This!!</div>";
						redirectHome($theMsg);
					}
		}
		else if ($do == 'Edit') {	// Edit Page 

			// Check If The Request userid Is Numeric & Get The Integar value
			$userid = isset($_GET['userid']) && is_numeric($_GET['userid'])?intval($_GET['userid']):0; //intval() => integar Value
			//Select Data Depend On This ID
			$stmt = $con->prepare('SELECT * FROM `users` WHERE UserID =? LIMIT 1'); // Limit 1 => one record is feched
			//Execute The Array
			$stmt->execute(array($userid));//To Know $userid = UserID
			//Fetch The Data
			$row = $stmt->fetch(); // $row[] Use To Show Fetched Data In Inputs
			//Check The Row count
			$count = $stmt->rowCount();
			if($count > 0) { ?>

				<h1 class="text-center">Edite Information</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
						<input type="hidden" name="userid" value="<?php echo $userid ?>">  <!-- Depended on edite info from userid -->
						<!--Start UserName Field-->
						<div class="member form-group form-group-lg">
							<label class="col-ms-2 control-label">UserName</label>
							<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
							  <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo $row['UserName'] ?>" aria-label="Username"autocomplete="off" required = 'required'>
							</div>
						</div>
						<!--End of UserName Field-->
						<!--Start Password Field-->
						<div class="member form-group form-group-lg">
							<label class="col-ms-2 control-label">Password</label>
							<div class=" col-sm-10 col-md-6"><!-- col-md-6 => for size of screen in lab and phone -->
							  <input type="hidden" class="form-control" name="oldpassword" value="<?php echo $row['Password'] ?>">
							  <input type="password" class="form-control" name="newpassword" placeholder="Password" aria-label="Password"autocomplete="new-password">
							</div>
						</div>
						<!--End of Password Field-->
						<!--Start Email Field-->
						<div class="member form-group form-group-lg">
							<label class="col-ms-2 control-label">Email</label>
							<div class=" col-sm-10 col-md-6 star"> <!-- col-md-6 => for size of screen in lab and phone -->
							  <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $row['Email'] ?>" aria-label="Email"autocomplete="off" required = 'required'>
							</div>
						</div>
						<!--End of Email Field-->
						<!--Start FullName Field-->
						<div class="member form-group form-group-lg">
							<label class="col-ms-2 control-label">FullName</label>
							<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
							  <input type="text" class="form-control" name="fullname" placeholder="FullName" value="<?php echo $row['FullName'] ?>" autocomplete="off" required = 'required'>
							</div>
						</div>
						<!--End of FullName Field-->
						<!--Start Avatar Field-->
						<div class="member form-group form-group-lg">
							<label class="col-ms-2 control-label">Avatar</label>
							<div class=" col-sm-10 col-md-6 star"><!-- col-md-6 => for size of screen in lab and phone -->
							  <input 
							  	type="file" 
							  	class="form-control" 
							  	name="avatar"
							  	required="required" >
							</div>
						</div>
						<!--End of Avatar Field-->
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

			echo "<h1 class='text-center'>Update Information</h1>";
			echo "<div class = 'container'>";

			if($_SERVER['REQUEST_METHOD']== 'POST') { //Check if Request Method Is POST

	 			// Upload Variables
				$avatarName = $_FILES['avatar']['name'];
				$avatarType = $_FILES['avatar']['type'];
				$avatarTemp = $_FILES['avatar']['tmp_name'];
				$avatarSize = $_FILES['avatar']['size'];
				//Get Avatar Extention
				$avatarAllowedExtentions = array('jpeg','jpg','png','gif');
				$exploadExtention = explode(".",$avatarName);
				$avatarExtention = strtolower(end($exploadExtention));


				//Get Variables from the form 
				$id = $_POST['userid'];
				$user = $_POST['username'];
				$email = $_POST['email'];
				$name = $_POST['fullname'];

				//Password Trick

				$pass =empty($_POST['newpassword']) ? $pass = $_POST['oldpassword'] : $pass = sha1($_POST['newpassword']);

				// Validate The Form
				$formError = array();
				if (empty($user)) {
					$formError[] = "UserName Can't be Empty";
				}
				if (strlen($user) < 3) {
					$formError[] = "UserName Can't be Less than 3 Characters";
				}
				if (empty($email)) {
					$formError[] = "Email Can't be Empty";
				}
				if (empty($name)) {
					$formError[] = "FullName Can't be Empty";
				}
				if (! empty($avatarName) && ! in_array($avatarExtention,$avatarAllowedExtentions)) {
					$formError[] = "This Extention Isn't Allowed";
				}
				if (empty($avatarName)) {
					$formError[] = "Avatar Is Required";
				}
				if ($avatarSize > 4132432) {
					$formError[] = "Avatar Can't Be Larger than 4MB";
				}
				// Loop Into Errors Array
				foreach ($formError as $error) {
					echo "<div class = 'alert alert-danger'>".$error."</div>";
				}
				// Check If Ther Is No Errors in The Form
				if (empty($formError)) {
					$avatar = rand(0,1000000)."_".$avatarName;
					move_uploaded_file($avatarTemp,"uploads\avatar\\".$avatar);
					$stmt2 = $con->prepare("SELECT * 
											FROM 
												users 
											WHERE UserName =?
											AND 
												UserID !=?
											");
					$stmt2->execute(array($user,$id));
					$count = $stmt2->rowCount();
					if ($count == 1) {

						$theMsg ="<div class='alert alert-danger'>Sorry, This User Is Exist</div>";

						redirectHome($theMsg,'back');
					} else {

					//Update The Data Base With This Info

					$stmt = $con -> prepare("UPDATE 
												users 
											 SET 
											 	UserName =?,
											 	Password =?, 
											 	Email 	 =?, 
											 	FullName =?,
											 	Avatar 	 =? 
											 WHERE 
											 	UserID = ? LIMIT 1");

					$stmt->execute(array($user, $pass, $email, $name,$avatar, $id));

					//Successed Message
					$theMsg= "<div class = 'alert alert-success'> [ " .$stmt->rowCount() . ' ] Recored Updated </div>';
					redirectHome($theMsg,'back');

					}
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