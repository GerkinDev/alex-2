import { formatCurrency } from './currencyFormatter';

export const cartCalculator = () => {
	const $productSections = $('#product-section, .cart-item');
	if($productSections.length > 0){
		$productSections.each((index, productSection) => {
			const $productSection = $(productSection);
			const $outputUnit = $productSection.find('.unit-price');
			const $outputSum = $productSection.find('.sum-price');
			const $count = $productSection.find('.unit-count');
			const $selects = $productSection.find('.materials-choices select');
			let sum;
			$selects.add($count).change(() => {
				const $selectsArr = $selects.toArray();
				const res = $selectsArr.reduce((acc: number | null, select: HTMLElement) => {
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
				}, 0) as number;
				$outputUnit.val(formatCurrency(res));
				sum = res * ($count.val() as number);
				$outputSum.val(formatCurrency(sum));
			}).change();
		});
    }
};