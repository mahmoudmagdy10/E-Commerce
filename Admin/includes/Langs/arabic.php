<?php
	
	function lang($phrase) {

		static $lang = array(

			'HOME' => 'Home',
			'CATEGORIES' => 'Categories',
			'SELECTION' => 'Selections',
			'' => '',
			'' => '',
			'' => ''
			
		);

		return $lang[$phrase];
	}
?>