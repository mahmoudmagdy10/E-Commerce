<?php
	ob_start();
	session_start();
	$pageTitle="Login";
	include 'initialize.php';

	if (isset($_SESSION['user'])) {

		header('Location:index.php'); 
	}

	//Check If User Coming From HTTP post Request
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['login'])) {

			$user = $_POST['username'];
			$pass = $_POST['password'];
			$hashedPass = sha1($pass);

			//Checked If User Exist In DataBase
			$stmt = $con->prepare("SELECT UserID,UserName,Password 
								   FROM 
								   		`users`
								   WHERE 
								   		UserName = ?
								   AND 
								   		Password =? ");
	        $stmt-> execute(array($user,$hashedPass));
	        $get = $stmt->fetch();
			$count = $stmt->rowCount();

			// if count > 0 this mean the database contain information about this record
			if($count > 0) {
				echo "Welcome " . $user;
				//* Session Register *
				$_SESSION['user'] = $user;  //Regiser Session User
				$_SESSION['uid'] = $get['UserID'];  //Regiser Session UserID 
				header('Location:index.php');
				exit();
			}
		} else {

			$formError  = array();
			$username   = $_POST['username'];
			$password   = $_POST['password'];
			$password2  = $_POST['comfirm_password'];
			$email 		= $_POST['email'];

			if(isset($username)){
				$filterUser = filter_var($username, FILTER_SANITIZE_STRING);
				if (strlen($filterUser) < 4) {
					$formError[] = "Username Must Be greater Than 4 Characters";
				}
			}
			if(isset($password) && isset($password2)){

				if (empty($password)) { // because in sha1() Empty field have an value with sha1()
					$formError[] = "Sorry, Password Shouldn't be Empty";
				}
				if (sha1($password) !== sha1($password2)) {
					$formError[] = "Sorry, Password Are Not Equall";
				}
			}
			if(isset($email)){
				$filterEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
				if (filter_var($filterEmail,FILTER_VALIDATE_EMAIL) != true) {
					$formError[] = "This Email Isn't Validate";
				}
			}

			if (empty($formError)) {
				//Check if User existed
				$check = checkItem("UserName","users",$username);

				if ($check == 1) {
					$formError[] = "Sorry This User Is Existed";
				} else {
					// Insert User info in database
					$stmt =$con->prepare("INSERT INTO 
												users(UserName ,Password,Email,RegStatus,thedate)
												VALUES(:zuser ,:zpass,:zmail,0,now()) 
												");
					$stmt->execute(array(
						'zuser' => $username,
						'zpass' => sha1($password),
						'zmail' => $email
					));
					$successMsg ="Congrates Yor Are Now Registerd User";


				}
			}

			}	
	}

?>
<div class="container-form">
	<div class="container login-page">
		<h1 class="container">
			<span class="selected" data-class="login">LOGIN</span> | <span data-class="signup">SIGN UP</span>
		</h1>
		<!--Start LogIn Form -->	
		<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method = 'POST'>
			<div class="input-container">
				<input
				 class="form-control"
				 type="text" 
				 name="username"
				 placeholder="Type Your Name"
				 required="required">
			</div>
			<div class="input-container">
				<input class="form-control" 
				type="password" 
				name="password"
				placeholder="Type Your Password"
				autocomplete="new-password "
				required="required">
			</div>
			<input class="form-control btn btn-primary" type="submit" name="login" value="Log In">
				<?php
					if (!empty($formError)) {
						foreach ($formError as $error) {
							echo"<div class='error alert alert-danger'>".$error."</div>";
						}
					}
					if (isset($successMsg)) {
						echo "<div class='alert alert-success'>".$successMsg."</div>";
					}

				?>
		</form>
		<!--End LogIn Form -->	
		<!--Start Sign up Form -->	
		<form class="signup" action="<?php echo $_SERVER['PHP_SELF']?>" method = 'POST'>
			<div class="input-container">
				<input class="form-control"
					 type="text" 
					 name="username"
				 placeholder="Type Your Name"
				 required>
			</div>
			<div class="input-container">
				<input class="form-control" 
					type="password" 
					name="password"
					placeholder="Type Your Password"
					autocomplete="new-password"
					required 
					>
			</div>
			<div class="input-container">
				<input class="form-control" 
					type="password" 
					name="comfirm_password"
					placeholder="Confirm Your Password"
					autocomplete="new-password "
					required>
			</div>
			<div class="input-container">
				<input class="form-control" 
					type="email" 
					name="email"
					placeholder="Type a Valid E-mail"
					required>
			</div>
			<div class="input-container">
				<input class="form-control btn btn-success" type="submit" name="signup" value="Sign Up">
			</div>
		</form>	
		<!--Start Sign up Form -->	
	</div>
</div>

<?php include $tpl. 'footer.php'; 
ob_end_flush();
?>