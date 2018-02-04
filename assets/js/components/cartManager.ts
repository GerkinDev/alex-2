import { computeRouteUrl } from './routes';

export const emptyCart = () => {
	$('#emptyCart').click(() => {
		$('#confirmEmptyCart').modal();
	});
}

export const handleDeleteButtons = () => {
	console.log('Init', $('.removeItem'))
	$('.removeItem').click(function(){
		const $cartItem = $(this).closest('.cart-item');
		const index = $cartItem.index();
		console.log(index, $cartItem);
		const route = computeRouteUrl('removeFromCart', {id: index});
		console.log(route);
		if(route){
			$.ajax(route, {
				method: 'POST',
				complete(res){
					if(res && res.responseJSON){
						if(res.responseJSON.success === true){
							$cartItem.remove();
						}
					} else {

					}
				}
			});
		}
	});
}