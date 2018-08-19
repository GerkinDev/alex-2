import { FlashModule } from './flashes/flashModule';
import { FlashType } from './flashes/types';
import Vue from 'vue'
import Component from 'vue-class-component'
const { ModelObj, ModelStl } = require( 'vue-3d-model' );


export {FlashType};
export const flashes = new FlashModule();
flashes.removeAlert({message: 'test', type: FlashType.error});

const vue = new Vue({
	el: '#object-view',
	components:{
		modelObj: ModelObj,
		modelStl: ModelStl,
	}
});

(window as any).flashes = flashes;