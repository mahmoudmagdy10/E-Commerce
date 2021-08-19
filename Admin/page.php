<?php
/* 
	Categories => [Manage | Edit | Update | Add | Insert | Delete | Stats]
*/
	$do = isset($_GET['do']);

	isset($_GET['do']) ? $do = $_GET['do'] : $do = 'Manage';

//If Page Is The Main Page
if ($do =='Manage') {
	
	echo "Welcome You Are In Manage Category Page";

	echo "<a href = '? do=Add'>Add New Category</a>";

} elseif ($do =='Add') {

	echo "Welcome In Add Categoty";

} elseif ($do == 'Insert') {

	echo "Welcome In Insert Categoty";

} else {

	echo "Error , There \'s No Page With This Name";
}