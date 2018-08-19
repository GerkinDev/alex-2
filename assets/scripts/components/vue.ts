import { FlashModule } from './flashes/flashModule';
import { FlashType } from './flashes/types';
import Vue from 'vue'
import Component from 'vue-class-component'


export {FlashType};
export const flashes = new FlashModule();
flashes.removeAlert({message: 'test', type: FlashType.error});

(window as any).flashes = flashes;