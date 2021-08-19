<?php

/*
** Get All Records Function V1.
*/
Function getAllFrom($field ,$table,$where=null,$and = null,$order=null,$ordering ='DESC'){
	global $con;
	$getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $order $ordering");
	$getAll->execute();
	$all=$getAll->fetchAll();
	return $all;
}

/*
** Get Category Records Function V1.
*/
Function getCat(){
	global $con;
	$getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");
	$getCat->execute();
	$Cats=$getCat->fetchAll();
	return $Cats;
}
/*
** Get Items Records Function V1.
*/
Function getItems($where , $value , $approve = null){
	global $con;
	// $sql = $approve == null ? 'AND Approve = 1':'';
	if ($approve == null) {
		$sql ='AND Approve =1';
	} else {
		$sql = null;
	}
	$getItem = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY ID DESC");
	$getItem->execute(array($value));
	$Items=$getItem->fetchAll();
	return $Items;
}

/*
** Get Users Status Function V1.
*/
function checkUserStatus($user){
	global $con;
	$stmt = $con->prepare("SELECT UserName,RegStatus FROM `users` WHERE UserName = ? AND RegStatus =0 ");
	$stmt->execute(array($user));
	$status = $stmt->rowCount();
	return $status;
}
/*
** Check Items Function version 1
** Function To Check in Database 
** $Select => The Item To Select
** $From   => The Table To Select From
** $Value  => The Value Of Select
*/
Function checkItem($select,$from,$value){
	global $con;
	$stat1 = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
	$stat1->execute(array($value));
	$count = $stat1->rowCount();
	return $count;
}






























/*
	==Title Function Tha Echo The Page Title in case the page has the variable pageTitle and echi Default Title for other pages
*/
	Function getTitle(){

		global $pageTitle;

		if(isset($pageTitle)) {
			
			echo $pageTitle;

		} else {

			echo "Default";
		}
	}
//==============================================
/*
** Check Items Function version 1
== Home Redirct Message [Accept Parametars]
== $errormsg = echo The Error Msg
== $second = Seconds Before Redirect
*/
// Function redirectHome($errorMsg,$seconds = 3){
// 	echo"<div class='container'>";
// 	echo "<div class='alert alert-danger'>$errorMsg</div>";
// 	echo "<div class='alert alert-info'>You Will Be Redirected To HomePage After $seconds</div>";
// 	header("refresh:$seconds;url=index.php");
// 	exit();
// 	echo"</div>";
// }
//==============================================
/*
** Check Items Function version 2
== Home Redirct Message [Accept Parametars]
== $TheMgs = echo The Msg
== $url = The Pervious Message
== $second = Seconds Before Redirect
*/
Function redirectHome($theMsg,$url = null,$seconds = 3){
	if ($url === null) {
		$url = "index.php";
		$link = 'Home Page';
	} else {

		if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') { //$_SERVER['HTTP_REFERER'] => Get The Pervious Page
			$url = $_SERVER['HTTP_REFERER'];
			$link = "Previous Page";
		} else {
			$url = "index.php";
			$link = "Home Page";
		}
	}
	echo $theMsg;
	echo "<div class='alert alert-info'>You Will Be Redirected To $link After $seconds</div>";
	header("refresh:$seconds;url=$url");
	exit();
}
/*
** Check Items Function version 2
== Previous Redirct Message [Accept Parametars]
== $TheMsg = echo The Msg[Error | Success]
== $url = The Previous Page
== $second = Seconds Before Redirect
*/

/*
** Count Number Of Items Function 
*/
Function countItems($items,$table) {
	global $con;
	$stat2 = $con->prepare("SELECT COUNT($items) FROM $table");
	$stat2->execute();
	return $stat2->fetchColumn();
}
/*
** Get Latest Records Function V1.
** getLatest($Select,$Table,$orderd,$Limit)
*/
Function getLatest($select, $table, $order, $limit = 5){
	global $con;
	$getStat = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
	$getStat->execute();
	$rows=$getStat->fetchAll();
	return $rows;
}