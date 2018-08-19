import { computeRouteUrl } from './routes';
import { FlashType } from './vue';

import Cart from '../../vues/cart/cart.vue'

const flashes = new class{
	addMessage(...args: any[]){}
}
export const initCartVue = () => {
	const cart = new Cart([{
		entity: {
			id: 1,
		},
	}]);
}
export const emptyCart = () => {
	$('#emptyCart').click(() => {
		$('#confirmEmptyCart').modal();
	});
}

export const handleDeleteButtons = () => {
	$('.removeItem').click(function(){
		const $cartItem = $(this).closest('.cart-item');
		const index = $cartItem.index();
		const route = computeRouteUrl('removeFromCart', {id: index});
		if(route){
			$.ajax(route, {
				method: 'POST',
				statusCode: {
					500: () => flashes.addMessage('Server error. Please retry', FlashType.error),
				},
				error: (data) => {
					console.error('Error XHR with data: ', data);
					flashes.addMessage('Server error. Please retry', FlashType.error);
				},
				complete(res, status){
					if(status === 'success' && res && res.responseJSON){
						if(res.responseJSON.success === true){
							//							$cartItem.remove();
							flashes.addMessage('Item removed from cart', FlashType.info, 10);
						} else {
							flashes.addMessage('An error occured. Please retry', FlashType.error);
						}
					} else {
						flashes.addMessage('Invalid response. Please retry', FlashType.error);
					}
				}
			});
		}
	});
}