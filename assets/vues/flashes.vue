<template>
	<div id="flashes">
		<flash
			v-for="(message, index) in messages"
			:key="index"
			v-bind="message"
			:original-message="message"
			@dismissed="removeAlert"></flash>
	</div>
</template>

<script lang="ts">
import Vue from 'vue'
import {default as FlashMessage, IFlashItem, TYPE as FLASH_TYPE} from './flash.vue';
import Component from 'vue-class-component'

export {IFlashItem, FLASH_TYPE};
export default class Flashes extends Vue{
	private header = document.getElementById('header');

	constructor(public messages: Array<IFlashItem> = []){
		super({
			el: '#flashes',
			data: {
				messages,
			},
			components: {
				flash: FlashMessage,
			},
		});

		const handleScrollListener = this.handleScroll.bind(this);
		window.addEventListener('DOMContentLoaded', handleScrollListener);
		window.addEventListener('scroll', handleScrollListener);
	}

	handleScroll () {
		if(this.header){
			const y = Math.max(this.header.getBoundingClientRect().bottom, 0)
			this.$el.style.top = y + 'px';
		}
	}

	addMessage(message: string, type: FLASH_TYPE, expiration?: number): void
	addMessage(message: IFlashItem): void
	addMessage(message: any, type?: FLASH_TYPE, expiration?: number){
		// Cast expiration in seconds
		if(expiration && expiration > 1000){
			expiration /= 1000;
		}
		if(type){
			message = {message, type, expiration};
		}
		this.messages.push(message);
	}

	removeAlert = (originalMessage: IFlashItem) => {
		const index = this.messages.indexOf(originalMessage)
		if(index >= 0){
			this.messages.splice(index, 1);
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
}
</style>
