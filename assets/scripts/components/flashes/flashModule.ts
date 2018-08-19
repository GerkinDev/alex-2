import { Vue } from 'vue-property-decorator';
import { FlashType, IFlashItem } from './types';

import FlashesComponent from '../../../vues/flash/flashes.vue';

export class FlashModule extends Vue{
	constructor(public messageObjects: Array<IFlashItem> = []){
		super({
			el: '#flashes',
			template: '<flashes :messageObjects="messageObjects"></flashes>',
			data: {
				messageObjects,
			},
			components: {
				flashes: FlashesComponent,
			},
		});
	}
	
	addMessage(message: string, type: FlashType, expiration?: number): void
	addMessage(message: IFlashItem): void
	addMessage(message: any, type?: FlashType, expiration?: number){
		// Cast expiration in seconds
		if(expiration && expiration > 1000){
			expiration /= 1000;
		}
		if(type){
			message = {message, type, expiration};
		}
		this.messageObjects.push(message);
	}
	
	removeAlert(originalMessage: IFlashItem){
		const index = this.messageObjects.indexOf(originalMessage)
		if(index >= 0){
			this.messageObjects.splice(index, 1);
		}
	}
}