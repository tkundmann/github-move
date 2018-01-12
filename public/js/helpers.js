jQuery(function($) {

	$(document.body).on('click', '.js-toggle-password-display', function() {

		var $clickedOnToggle = $(this);
		var $togglePasswordContainer = $clickedOnToggle.closest('.js-toggle-password-display-container');

		var toggleType = 'show';
		var $toggleSibling = $togglePasswordContainer.find("[data-password-display='hide']");

		if ($clickedOnToggle.data('password-display') === 'hide') {
			toggleType = 'hide';
			$toggleSibling = $togglePasswordContainer.find("[data-password-display='show']");
		}
		$clickedOnToggle.hide();
		$toggleSibling.show();

		var $formContainer = $clickedOnToggle.closest('.js-form-container');
		$formContainer.find('.js-password-input').each(function() {
			$currentPwdInput = $(this);

			if (toggleType === 'show') {
				$currentPwdInput.attr('type','text');
			}
			else {
				$currentPwdInput.attr('type','password');
			}
		});

	});

});