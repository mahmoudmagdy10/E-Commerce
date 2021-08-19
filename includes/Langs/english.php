<?php
	function lang($phrase) {
		static $lang = array(
			// - Navbar -
				//Home Page
			'HOME'		 => 'Home',
			'CATEGORIES' => 'Categories',
			'SELECTION'  => 'Selections',
			'ITEMS' 	 => 'Items',
			'MEMBERS' 	 => 'Members',
			'STATISTICS' => 'Statistics',
			'COMMENTS' 	 => 'Comments',
			'LOGS'		 => 'Logs',
				//Settings
			'EDIT' 		 => 'Edit Profile',
			'SETTINGS' 	 => 'Settings',
			'EXIT' 		 => 'Logout'	
		);
		return $lang[$phrase];
	}
?>