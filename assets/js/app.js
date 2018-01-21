'use strict';

require('../scss/main.scss');

$(document).ready(() => {
	$('.post-enable').attr('disabled', false);
	$("form input, form select").keydown(function (e) {
		const $form = $(this).closest('form');
		const sels = ['button', 'input'].map(tag => '[type="submit"].default');
		const selsIn = sels.join(', ');
		const selsOut = sels.map(s => s + `[form="${$form.attr('id')}"]`).join(', ');
		const $allButtons = $form.find(selsIn).add($(selsOut));
		if ($allButtons.length <= 0){
			return true;
		}

		if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
			$allButtons.click();
			return false;
		} else {
			return true;
		}
	});
}); 