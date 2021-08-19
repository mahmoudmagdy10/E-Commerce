<?php 
	ob_start();
	session_start();
	$pageTitle = "Show Items";
	include 'initialize.php';

	// Check If The Request userid Is Numeric & Get The Integar value
	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])?intval($_GET['itemid']):0; //intval() => integar Value

	//Select Data Depend On This ID
	$stmt = $con->prepare(' SELECT 
								items.*,categories.Name AS category_name, users.UserName 
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
							WHERE items.ID =?
							AND Approve = 1');

	//Execute The Array
	$stmt->execute(array($itemid));//To Know $userid = UserID

	$count =$stmt->rowCount();
	if($count >0){
		//Fetch The Data
		$item = $stmt->fetch(); // $row[] Use To Show Fetched Data In Inputs

	?>
	<h1 class="text-center"><?php echo $item['Name'];?></h1>
			<div class="row">
				<div class="col-md-2 img-item text-center">
					<img class='img-responsive text-center' src='img.png'>
				</div>
				<div class="col-md-9 item-info">
					<h2><?php echo $item['Name'];?></h2>
					<p><?php echo $item['Description'];?></p>
					<div>
					<ul class="list-unstyled">
						<li>
	 						<i class="fa fa-calendar fa-fw"></i>
							<span> Added Date :</span> <?php echo $item['Add_Date'];?>
						</li>
						<li>
	 						<i class="fa fa-building fa-fw"></i>
							<span> Made In :</span> <?php echo $item['Country_Made'];?>
						</li>
						<li>
	 						<i class="fa fa-building fa-fw"></i>
							<span> Price :</span><?php echo $item['Price'];?>
						</li>
						<li>
	 						<i class="fa fa-tags fa-fw"></i>
							<span> Category </span> : <a style="text-decoration: none" href="categories.php?pageid=<?php echo $item['Cat_ID'];?>"><?php echo $item['category_name'];?></a>
						</li>
						<li>
	 						<i class="fa fa-user fa-fw"></i>
							<span> Added By </span>: <a style="text-decoration: none" href="#"><?php echo $item['UserName'];?></a>
						</li>
     					<li>
			         		<i class="fa fa-tag fa-fw"></i>
				         	<span class="tag">Tags</span> : 
				         	<?php
				         		$allTags = explode(",", $item['Tags']);
				         		foreach($allTags as $tag){
					         		$tag = str_replace(" ", "", $tag);
					         		$lowerTage = strtolower($tag);
					         		if (!empty($tag)) {
				         				echo"<a class='tag-link' href='Tags.php?name={$lowerTage}'>".$tag."</a> | ";
				         			}
				         		} 
				         	?>
			         	</li>
					</ul>
					</div>
				</div>
				<hr class="custom-hr">
		  <?php if(isset($_SESSION['user'])){?>
					<div class="row">
						<div class="">
							<div class="add-comm">
								<form action="<?php $_SERVER['PHP_SELF']?>" method='POST'>
									<h3>Add Comment</h3>
									<textarea name="comment"></textarea><br>
									<input type="submit" class="btn btn-primary" value="Add Comment">
								</form>
								<?php 
									if ($_SERVER['REQUEST_METHOD']=='POST') {
										
										$comment=filter_var($_POST['comment'],FILTER_SANITIZE_STRING);
										$itemid=$item['ID'];
										$userid=$_SESSION['uid'];

										if (!empty($comment)) {
											$stmt = $con->prepare("INSERT INTO 
																		comments(Comment,Item_ID ,User_ID,Status,Comment_Date) 
																	VALUES(:zcomm,:zitemid,:zuserid,0,NOW())");
											$stmt->execute(array(
												'zcomm' 	=> $comment,
												'zitemid' 	=> $itemid,
												'zuserid'   => $userid
											));
											if ($stmt) {																		
													echo"<div class='alert alert-success'>Comment Is Added<div>";
											}
										}
									}
								?>
							</div>
						</div>
					</div>
				<?php } else {
						echo"<a href='login.php'>Login</a> or <a href='login.php'>Regiser</a>";
				}
				?>
				<hr class="custom-hr">
<!-- Select All Approved Comments Belongs to this user-->
		<?php
			$stmt=$con->prepare("SELECT 
									 comments.* , users.UserName AS Member
								FROM comments
								INNER JOIN 
									users
								ON
									users.UserID = comments.User_ID
								WHERE Item_ID =?
								AND 
									Status =1
								ORDER BY 
									Comment_ID DESC 
											");
			$stmt->execute(array($item['ID']));
			$comments = $stmt->fetchAll();
		?>
	<?php
		foreach($comments as $comm ){ ?>
			<div class="comment-box">
				<div class='row text-center'>;
					<div class='col-sm-5 text-center'>
						<img class='img-responsive text-center img-thumbnails img-circle center-block' src='img.png'><br>
						<?php echo $comm['Member'];?>
					</div>
					<div class='comms center-block'>
						<p class="lead"><?php echo $comm['Comment']; ?></p>		
					</div>
				</div>
			</div>
			<hr class="custom-hr">

		<?php } ?>
<?php
	} else {
		echo"<div class='alert alert-danger' style='margin-left:60px;'>There Isn't Such ID Like This Or Not Approved Yiet</div>";
	}
include $tpl. 'footer.php'; 
ob_end_flush();
?>