<!-- Initialize folder => The Path For All Folders -->
<?php 

	include "connect.php";
	
	// Routes
	$tpl = 'includes/Templete/'; 	//Templete Directory
	$language = 'includes/Langs/';  //Langs  Directory
	$func ='includes/Function/';	//function  Directory
	$css = "Thems/Css/"; 			//Css Directory
	$js = "Thems/JS/"; 				//JS Directory
	$img = "uploads/avatar/"; 		//Avatar Directory

//Include The Importants Files
	include $language .'english.php';
	include $func 	  . 'function.php';
	include $tpl 	  . 'header.php';

//include navbar on all pages except the one with $nonavbar variable
	if (!isset($nonavbar)) {include $tpl . 'navbar.php';}
