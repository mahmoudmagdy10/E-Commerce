<?php 

// Start The Session----
	session_start();
	$nonavbar = '';
	$pageTitle ='Login';
	if (isset($_SESSION['UserName'])) {
			header('Location:dashboard.php');//Redirect To Dashboard
	}
	include 'initialize.php';

	//Check If User Coming From HTTP post Request
	if($_SERVER['REQUEST_METHOD'] == 'POST') {

		$username = $_POST['user'];
		$password = $_POST['pass'];
		$hashedPass = sha1($password);

		//Checked If User Exist In DataBase

		$stmt = $con->prepare("SELECT UserID,UserName,Password 
							   FROM 
							   		`users`
							   WHERE 
							   		UserName = ?
							   AND 
							   		Password =?
							   AND 
							   		GroupID = 1

							   LIMIT 1");

		//ERROR !!!!!!!!!!!!!!!!!
        $stmt-> execute(array($username,$hashedPass));
        $row = $stmt ->fetch(); // Retrive Data From DataBase
		$count = $stmt->rowCount();

		// if count > 0 this mean the database contain information about this record
		if($count > 0) {
			echo "Welcome " . $username;
			//* Session Register *
			$_SESSION['UserName'] = $username;  //Regiser Session User
			$_SESSION['ID']   = $row['UserID']; //Register Session ID
			header('Location:dashboard.php');
			exit();
		}
	}
?>
	<div class="login-page">
		<form class="Login" action="<?php echo $_SERVER['PHP_SELF']?>" method = 'POST'>
			<h4 class="text-center">Admin Login</h4>
			<input class="form-control log" type="text" name="user" placeholder="User Name" autocomplete="off">
			<input class="form-control log" type="password" name="pass" placeholder="PassWord" autocomplete="new-password">
			<input class="btn btn-danger btn-block " type="submit" value="Login">
		</form>
	</div>
<?php include $tpl. 'footer.php'; ?>