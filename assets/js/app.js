'use strict';

require('../scss/main.scss');

const formatCurrencyMatcher = /(\d+)([\.,])(\d+)/;
const currencyComponents = global.currencyFormat.match(formatCurrencyMatcher);
const composeCurrencyText = (int, float) => `${int}${currencyComponents[3].length > 0 ? currencyComponents[2] : ''}${ (float).substr(0, (currencyComponents[3].length))}`;
const formatCurrency = value => {
	if(value === null){
		return global.currencyFormat.replace(
			formatCurrencyMatcher,
			composeCurrencyText('--', '--')
		);
	} else {
		const numComponents = (value + '').split('.');
		if(numComponents.length === 1){
			numComponents.push('');
		}
		numComponents[1] = numComponents[1] + '00';
		return global.currencyFormat.replace(
			formatCurrencyMatcher,
			composeCurrencyText(...numComponents)
		);
	}
}


$(document).ready(() => {
	$('.post-enable').attr('disabled', false);
	$("form input, form select").on('input keydown change', function (e) {
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
	const $productSections = $('#product-section, .cart-item');
	if($productSections.length > 0){
		$productSections.each((index, productSection) => {
			const $productSection = $(productSection);
			const $outputUnit = $productSection.find('.unit-price');
			const $outputSum = $productSection.find('.sum-price');
			const $count = $productSection.find('.unit-count');
			const $selects = $productSection.find('.materials-choices select');
			$selects.add($count).change(() => {
				const $selectsArr = $selects.toArray();
				const res = $selectsArr.reduce((acc, select) => {
					if(typeof acc !== 'number'){
						return null;
					}
					const $select = $(select);
					const price = parseFloat($select.find(`option[value="${$select.val()}"]`).data('price'));
					const quantity = parseFloat($select.closest('[data-part-mass]').data('part-mass'));
					if(isNaN(price) || isNaN(quantity)){
						return null;
					}
					return price * quantity + acc;
				}, 0);
				$outputUnit.val(formatCurrency(res));
				$outputSum.val(formatCurrency(res * $count.val()));
			}).change();
		});
	}
});