<template>
	<b-alert class="fade" :class="{show}"
		:show="typeof dismissCountDown === 'undefined' || dismissCountDown"
		dismissible
		:variant="type"
		@dismissed="alertDismissed"
		@dismiss-count-down="countDownChanged">
		<section>{{ message }}</section>

		<b-progress v-if="typeof expiration !== 'undefined'"
			variant="warning"
			:max="expiration"
			:value="dismissCountDown - 1"
			height="4px">
		</b-progress>
	</b-alert>
</template>


<script lang="ts">
import Vue from 'vue'

export interface IFlashItem{
	message: string,
	type: TYPE,
}
export enum TYPE{
	error = 'danger',
	info = 'info',
}


interface IFlashMessage extends Vue{
	expiration?: number;
	dismissCountDown?: number;
	show: boolean;
	props: string[];
	originalMessage: IFlashMessage;

	data (): object,
	countDownChanged (dismissCountDown: number): void;
}
export default {
	props: {
		type: {
			type: String,
			required: true,
		},
		message: {
			type: String,
			required: true,
		},
		expiration: Number,
		originalMessage: Object,
	},

	data (this: IFlashMessage) {
		return {
			dismissCountDown: this.expiration ? this.expiration + 1: undefined,
			show: false,
		}
	},
	mounted(this: any){
		setTimeout(() => {
			this.show = true;
		}, 100);
	},
	methods: {
		countDownChanged (this: IFlashMessage, dismissCountDown: number) {
			this.dismissCountDown = dismissCountDown;
			switch(this.dismissCountDown){
				case 1:
				this.show = false;
				break;

				case 0:
				this.$emit('dismissed', this.originalMessage);
				break;
			}
		},
		alertDismissed(this: IFlashMessage){
			this.dismissCountDown = 0;
			this.$emit('dismissed', this.originalMessage);
		}
	}
}
</script>

<style lang="scss" scoped>
.alert{
	padding-right: 3rem;
}
section{
	margin-bottom: 5px;
}
</style>