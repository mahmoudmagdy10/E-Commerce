<?php 

// Start The Session----
	session_start();

	if (isset($_SESSION['UserName'])) {

		$pageTitle ='Dashboard';
		include'initialize.php';
		/*Start Dashboard Page */

		$numUsers = 4; // Number OF Latest Users
		$latestUsers = getLatest("*","users","UserID",$numUsers); //Call getLatest Function For Users
		$numItems = 4; // Number OF Latest Items
		$latestItems = getLatest("*","items","ID",$numItems); //Call getLatest Function For Items
		$numComments = 4;
		$latestComments = getLatest("*","comments","Comment_ID",$numComments); //Call getLatest Function For Comments

		?>
		<div class="container text-center homestat">
			<h1>Dashboard</h1>
			<div class="row">
				<div class="col-md-3">
					<div class="stat st-member">
						<i class="i fa fa-users"></i>
						<div class="info">
							Total Members
							<span>
								<a href="members.php"><?php echo countItems("UserID","users");?></a>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-Pending">
						<i class="i fa fa-user-plus"></i>
						<div class="info">
							Pending Members
							<span><a href="members.php?do=Manage&page=Pending"><?php echo checkItem("RegStatus","users",0);?></a></span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-Items">
						<i class="i fa fa-tag"></i>
						<div class="info">
							Total Items
							<span>
								<a href="items.php"><?php echo countItems("ID","items");?></a>
							</span>
						</div>

					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-Comments">
						<i class="i fa fa-comments"></i>
						<div class="info">
							Total Comments
							<span>
								<a href="comments.php"><?php echo countItems("Comment_ID","comments");?></a>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		 <!--   -->
		<div class="latest">
			<div class="container ">
				<div class="row">
					<div class="col-ms-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-users"></i>
								Last [<?php echo $numUsers ?>] Regiser Users
								<span class="pull-right toggle-info">
									<i class=" fa fa-minus"></i>
								</span>
							</div>
							<div class="panel-body">
								<ul class="list-unstyled latest-user">
									<?php
									if(!empty($latestUsers)){
										foreach($latestUsers as $user){
											echo"<li>";
											echo "<a href='members.php?do=Edit&userid=".$user['UserID']."'>".$user['UserName']."</a>";
											echo"<a href='members.php?do=Edit&userid=".$user['UserID']."'>";
												echo"<span class ='btn btn-success pull-right'>";
													echo"<i class='fa fa-edit'></i> Edit";
													if($user['RegStatus']==0){
														echo "<a href='members.php?do=active&userid=".$user['UserID']." 'class='btn btn-info active'><i class='far fa-check-circle'></i> Active</a>";
													} else {
														echo "<a href='members.php?do=unActive&userid=".$user['UserID']." 'class='btn btn-primary active'><i class='fa fa-times-circle'></i> Un Active</a>";
													}
												echo"</span>";
											echo"</a>";
											echo"</li>";
										}
									} else {
										echo "<div class='alert alert-info text-center'> No Users To be Shown</div>";
									}
									?>
								</ul>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading">
							<i class="fa fa-tag"></i>
								Latest [<?php echo $numItems ?>] Items
							<span class="pull-right toggle-info">
									<i class=" fa fa-minus"></i>
							</span>
						</div>
						<div class="panel-body">
								<ul class="list-unstyled latest-user">
									<?php
									if(!empty($latestItems)){
										foreach($latestItems as $item){
											echo"<li>";
											echo "<a href='items.php?do=Edit&itemid=".$item['ID']."'>".$item['Name']."</a>";
											echo"<a href='items.php?do=Approve&itemid=".$item['ID']."'>";
												echo"<span class ='btn btn-success pull-right'>";
													echo"<i class='fa fa-edit'></i> Edit";
													if($item['Approve'] == 0){
														echo "<a href='items.php?do=Approve&itemid=".$item['ID']." 'class='btn btn-info active'><i class='far fa-check-circle'></i> Approve</a>";
													} else {
														echo "<a href='items.php?do=unApprove&itemid=".$item['ID']." 'class='btn btn-primary active'><i class='fa fa-times-circle'></i> Un Approve</a>";
													}
												echo"</span>";
											echo"</a>";
											echo"</li>";
										}
									} else {
										echo "<div class='alert alert-info text-center'> No Items To be Shown</div>";
									}
									?>
								</ul>
						</div>
					</div>
					</div>
				</div>
				<!--    -->
				<div class="row">
					<div class="col-ms-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-comments"></i>
									Latest [<?php echo $numComments ?>] Comments 
								<span class="pull-right toggle-info">
									<i class=" fa fa-minus"></i>
								</span>
							</div>
							<div class="panel-body">
								<?php
									$stmt=$con->prepare("SELECT
																comments.* , users.UserName AS Member
														 FROM 
														 		comments

														 INNER JOIN users

														 ON users.UserID = comments.User_ID
														 ORDER BY 
														 	Comment_ID DESC
														 LIMIT $numComments

														 ");
									$stmt->execute();
									$comments = $stmt->fetchAll();

									if(!empty($latestComments)) {
										foreach($comments as $comment){
											echo"<div class ='comment-box'>";
											echo"<div class ='comment-box'>";
												echo "<a href='comments.php?do=Edit&comid=".$comment['Comment_ID']."'>".$comment['Member']."</a>";												echo"<p class='member-c'>".$comment['Comment']."</p>";
											echo"</div>";
										}
									} else {
										echo "<div class='alert alert-info text-center'> No Comments To be Shown</div>";
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php		
		include $tpl. 'footer.php';

	} else {

		header('Location:index.php');
		
		exit();
	}
?>