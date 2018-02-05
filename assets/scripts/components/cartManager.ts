import { computeRouteUrl } from './routes';
import { flashes, FLASH_TYPE } from './vue';

import Cart from '../../vues/cart/cart.vue'

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
					500: () => flashes.addMessage('Server error. Please retry', FLASH_TYPE.error),
				},
				error: (data) => {
					console.error('Error XHR with data: ', data);
					flashes.addMessage('Server error. Please retry', FLASH_TYPE.error);
				},
				complete(res, status){
					if(status === 'success' && res && res.responseJSON){
						if(res.responseJSON.success === true){
							//							$cartItem.remove();
							flashes.addMessage('Item removed from cart', FLASH_TYPE.info, 10);
						} else {
							flashes.addMessage('An error occured. Please retry', FLASH_TYPE.error);
						}
					} else {
						flashes.addMessage('Invalid response. Please retry', FLASH_TYPE.error);
					}
				}
			});
		}
	});
}