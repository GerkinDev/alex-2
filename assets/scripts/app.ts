require('../scss/main.scss');

// Init & configure Vue.js
import Vue from 'vue'

const { ModelObj, ModelStl } = require( 'vue-3d-model' );
import FlashesComponent from '../vues/flash/flashes.vue';
import ModelUploadFormComponent from '../vues/model-upload-form.vue';


const BootstrapVue = require('bootstrap-vue').default;
Vue.use(BootstrapVue);

// My modules
import { cartCalculator } from './components/priceCalculator';
import { emptyCart, handleDeleteButtons, initCartVue } from './components/cartManager';
import { computeRouteUrl } from './components/routes';
import './components/vue';

$(document).ready(() => {

	const route = $('body').data('route');
	console.log(`On route "${route}"`);
	$('.post-enable').removeAttr('disabled');
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


    new Vue({
        el: '#vue-app',
        data: {
            messageObjects: [],
        },
        components: {
            flashes: FlashesComponent,
            modelObj: ModelObj,
            modelStl: ModelStl,
            modelUploadForm: ModelUploadFormComponent,
        },
    });
	switch(route){
		case 'cart': {
			//initCartVue();
			//emptyCart();
			//handleDeleteButtons();
		}
		case 'product': {
			//cartCalculator();
		} break;
	}
});