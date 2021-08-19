<?php 
	ob_start();
	session_start();
	$pageTitle = "Profile";
	include 'initialize.php';

	if(isset($_SESSION['user'])){
		$getUser  = $con->prepare("SELECT * FROM users WHERE UserName=?");
		$getUser->execute(array($sessionUser));
		$profile=$getUser->fetch();
		$userid =$profile['UserID'];
?>
		<h1 class="text-center">My Profile</h1>
		<div class="information">
			<div class="panel-container">
			    <div class="panel panel-default">
			        <div class="panel-heading"> 
			         	My Information
			        </div>
			        <div class="panel-body profile"> 
							<ul class="list-unstyled">
					         	<li>
					         		<i class="fa fa-unlock-alt fa-fw"></i>
					         		<span>Name</span> : <?php echo $profile['UserName'] ."<br>"?>
					         	</li>
					         	<li>
									<i class="fas fa-envelope"></i>
					         		<span>Email</span> : <?php echo $profile['Email'] ."<br>"?>
					         	</li>
					         	<li>
	     						    <i class="fa fa-user fa-fw"></i>
					         		<span>Full Name</span> : <?php echo $profile['FullName'] ."<br>"?>
					         	</li>
					         	<li>
		 						    <i class="fa fa-calendar fa-fw"></i>
						         	<span>Regiser Date</span> : <?php echo $profile['thedate'] ."<br>"?>
					         	</li>
					         	<li>
					         		<i class="fa fa-tag fa-fw"></i>
						         	<span>Favourite Category</span> : 
					         	</li>
				         	</ul>
				         	<a href="Admin/members.php?do=Edit&userid=<?php echo $profile['UserID'];?>"class="btn btn-default edit">Edit Information</a>
			        </div>
			    </div>
			</div>
			<div id="my-items" class="panel-container">
			    <div class="panel panel-default">
			        <div class="panel-heading"> 
			        	My Items
			        </div>
			        <div class="panel-body profile"> 
			        <?php
			        	$allItems = getAllFrom("*","items","where Member_ID =$userid","","ID");
			        	if (!empty($allItems)) {
			        		echo"<div class='row hover'>";
				        	foreach($allItems as $item){
								echo"<div class='col-sm-6 col-md-4'>";
									echo"<div class='thumbnail item-box'>";
										if ($item['Approve']==0) {echo"<div class='unapproval'>Un Approved</div>";}
										echo"<span class='price'>$".$item['Price']."</span>";
										echo"<img class='img-responsive text-center' src='img.png'>";
										echo"<div class='caption'>";
											echo"<h3><a href='items.php?itemid=".$item['ID']."'>".$item['Name']."</a></h3>";
											echo"<p>".$item['Description']."</p>";
										echo"</div>";
										echo"<span class='date'>".$item['Add_Date']."</span>";
									echo"</div>";
								echo"</div>";
				        	}
				        	echo"</div>";
			        	}
			        ?> 
			        </div>
			    </div>
			</div>
			<div class="panel-container">
			    <div class="panel panel-default">
			        <div class="panel-heading"> 
			        	Latest Comments
			        </div>
			        <div class="panel-body profile"> 
			        	<?php
			        		$allcomments = getAllFrom("Comment","comments","where User_ID = $userid","","Comment_ID");
			        		if (!empty($allcomments)) {
			        			foreach($allcomments as $comment){
			        			echo"<p class='alert alert-primary'>".$comment['Comment']."</p>";
			        			}
			        		} else{
			        			echo"<div class='alert alert-info'>There Is No Comments</div>";
			        		}
			        	?>
			        </div>
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