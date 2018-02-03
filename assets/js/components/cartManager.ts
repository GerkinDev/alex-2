export const emptyCart = () => {
	$('#emptyCart').click(() => {
		$('#confirmEmptyCart').modal();
	});
}

export const handleDeleteButtons = () => {
	console.log('Init', $('.removeItem'))
	$('.removeItem').click(function(){
		const index = $(this).closest('.cart-item').index();
		console.log(index);
	});
}