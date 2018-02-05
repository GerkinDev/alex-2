import { computeRouteUrl } from './routes';
import { flashes, FLASH_TYPE } from './vue';

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
				complete(res){
					if(res && res.responseJSON){
						if(res.responseJSON.success === true){
//							$cartItem.remove();
							flashes.addMessage('Item removed from cart', FLASH_TYPE.info);
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