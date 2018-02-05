console.log('vue.ts');

import Vue from 'vue'
import Component from 'vue-class-component'
import {default as Flashes, FLASH_TYPE} from './../../vues/flashes.vue';


export {FLASH_TYPE};
export const flashes = new Flashes();
flashes.removeAlert({message: 'test', type: FLASH_TYPE.error});

(window as any).flashes = flashes;