import { Vue } from 'vue-property-decorator';

export interface IFlashItem{
	message: string,
	type: FlashType,
}
export enum FlashType{
	error = 'danger',
	info = 'info',
}


export interface IFlashMessage extends Vue{
	expiration?: number;
	dismissCountDown?: number;
	show: boolean;
	props: string[];
	originalMessage?: IFlashMessage;

	data (): object,
	countDownChanged (dismissCountDown: number): void;
}
