$(function (){

	'use strict';

	// Switch Btween Login And Sign Up
	$('.login-page span').click(function() {
		$(this).addClass('selected').siblings().removeClass('selected');

		$('.login-page form').hide();
		$('.' + $(this).data('class')).fadeIn();

	});

	 // Calls the selectBoxIt method on your HTML select box
	$("select").selectBoxIt({
	  autoWidth:false

	});
	     

	// Hide Placeholder On Form Focus

	$('[Placeholder]').focus(function () {

		$(this).attr('data-text', $(this).attr('Placeholder'));
		$(this).attr('Placeholder','');
	}).blur(function () {

		$(this).attr('Placeholder', $(this).attr('data-text'));
	});

	$('input').each(function () {
		if ($(this).attr('required') === 'required') {
			
			$(this).after('<span class ="asterisk">*</span>');
		}
	});

	$('.confirm').click(function(){

		return confirm('Are You Sure?');
	});

	$('.live').keyup(function(){
		$($(this).data('class')).text($(this).val());
	});
});