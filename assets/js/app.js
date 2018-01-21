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
	const $productSection = $('#product-section');
	if($productSection.length > 0){
		const $output = $productSection.find('output');
		const $selects = $productSection.find('.materials-choices select');
		$selects.change(() => {
			const $selectsArr = $selects.toArray();
			const res = $selectsArr.reduce((acc, select) => {
				if(typeof acc !== 'number'){
					return null;
				}
				const $select = $(select);
				const price = $select.find(`option[value="${$select.val()}"]`).data('price');
				const quantity = $select.closest('[data-part-mass]').data('part-mass');
				return price * quantity + acc;
			}, 0);
			$output.val((res || '--.--') + ' €');
		}).change();
	}
});