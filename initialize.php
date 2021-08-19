<!-- Initialize folder => The Path For All Folders -->
<?php
	ob_start();
	
	// Error Repoting
	ini_set('disaplay_errors','On');
	error_reporting(E_ALL);

	$sessionUser =  '';
	if (isset($_SESSION['user'])) {
		$sessionUser = $_SESSION['user'];
	}
	include "Admin/connect.php";
	
	// Routes
	$tpl = 'includes/Templete/'; 	//Templete Directory
	$language = 'includes/Langs/';  //Langs  Directory
	$func ='includes/Function/';	//function  Directory
	$css = "Thems/Css/"; 			//Css Directory
	$js = "Thems/JS/"; 				//JS Directory

//Include The Importants Files
	include $language .'english.php';
	include $func 	  . 'function.php';
	include $tpl 	  . 'header.php';
ob_end_flush();
?>