<template>
	<b-alert class="fade" :class="{show}"
		:show="dismissCountDown === null || dismissCountDown"
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
import { Vue, Component, Prop } from 'vue-property-decorator';

import { IFlashItem, FlashType } from '../../scripts/components/flashes/types';
import { type } from 'os';

@Component({})
export default class FlashComponent extends Vue {
	@Prop({
		type: String,
		required: true,
	})
	public type!: string;

	@Prop({
		type: String,
		required: true,
	})
	public message!: string;

	@Prop({
		type: Number,
		required: false,
	})
	public expiration?: number;

	public dismissCountDown: number | null = null;
	public show = false;

	constructor(){
		super();
		this.dismissCountDown = this.expiration ? this.expiration + 1: null;
	}

	mounted(){
		setTimeout(() => {
			this.show = true;
		}, 100);
	}
	
	countDownChanged (dismissCountDown: number) {
		this.dismissCountDown = dismissCountDown;
		switch(this.dismissCountDown){
			case 1:
			this.show = false;
			break;

			case 0:
			this.$emit('dismissed', {message: this.message, type: this.type});
			break;
		}
	}

	alertDismissed(){
		this.dismissCountDown = 0;
		this.$emit('dismissed', {message: this.message, type: this.type});
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