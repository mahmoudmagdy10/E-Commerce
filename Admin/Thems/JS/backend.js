$(function () {

	'use strict';

	// Calls the selectBoxIt method on your HTML select box
	$("select").selectBoxIt({
		autoWidth: false

	});

	// Dashboard
	$(".toggle-info").click(function () {
		$(this).toggleClass('selected').parent().next().fadeToggle(100);

		if ($(this).hasClass('selected')) {
			$(this).html('<i class=" fa fa-plus"></i>')
		} else {
			$(this).html('<i class=" fa fa-minus"></i>')
		}
	});

	// Hide Placeholder On Form Focus

	$('[Placeholder]').focus(function () {

		$(this).attr('data-text', $(this).attr('Placeholder'));
		$(this).attr('Placeholder', '');
	}).blur(function () {

		$(this).attr('Placeholder', $(this).attr('data-text'));
	});

	$('input').each(function () {
		if ($(this).attr('required') === 'required') {

			$(this).after('<span class ="asterisk">*</span>');
		}
	});
	var passfield = $('.password');
	$('.show-pass').hover(function () {
		passfield.attr('type', 'text');

	}, function () {

		passfield.attr('type', 'password');
	});
	$('.confirm').click(function () {

		return confirm('Are You Sure?');
	});

	$(".cat h3").click(function () {

		$(this).next('.full-view').fadeToggle(200);
	});
	$('.option span').click(function () {

		$(this).addClass('active').siblings('span').removeClass('active');
		if ($(this).data('view') === 'full') {
			$('.cat .full-view').fadeIn();
		} else {
			$('.cat .full-view').fadeOut();
		}
	});

	$('.child-link').hover(function () {
		$(this).find('.show-delete').fadeIn(400);

	}, function () {
		$('.show-delete').fadeOut(400);
	});
});