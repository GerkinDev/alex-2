<template>
	<section id="flashes">
		<flash
			v-for="(messageObject, index) in messageObjects"
			:key="index"
			v-bind="messageObject"
			@dismissed="removeAlert"></flash>
	</section>
</template>

<script lang="ts">
import { Vue, Component, Prop } from 'vue-property-decorator';

import FlashComponent from './flash.vue';
import { IFlashItem, FlashType } from '../../scripts/components/flashes/types';

@Component({
	components:{
		flash: FlashComponent,
	}
})
export default class FlashesComponent extends Vue{
	private header = document.getElementById('header');

	@Prop({
		type: Array,
		required: true,
	})
	public messageObjects!: IFlashItem[];

	public handleScroll () {
		if(this.header){
			const y = Math.max(this.header.getBoundingClientRect().bottom, 0)
			this.$el.style.top = y + 'px';
		}
	}

	public removeAlert(originalMessage: IFlashItem){
		const index = this.messageObjects.indexOf(originalMessage)
		if(index >= 0){
			this.messageObjects.splice(index, 1);
		}
	}
}
</script>

<style lang="scss">
#flashes{
	position: fixed;
	top: 0;
	right: 0;
	max-width: 250px;
	z-index: 1;
}
</style>
