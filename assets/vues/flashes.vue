<template>
	<div id="flashes">
		<flash
			v-for="(message, index) in messages"
			:key="index"
			:type="message.type"
			:message="message.message"></flash>
	</div>
</template>

<script lang="ts">
import Vue from 'vue'
import {default as FlashMessage, IFlashMessage, TYPE as FLASH_TYPE} from './flash.vue';
import Component from 'vue-class-component'

export {IFlashMessage, FLASH_TYPE};
export default class Flashes extends Vue{
	private header = document.getElementById('header');

	constructor(public messages: Array<IFlashMessage> = [{message: 'test', type: FLASH_TYPE.info}]){
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
	addMessage(message: IFlashMessage): void
	addMessage(message: any, type?: FLASH_TYPE, expiration?: number){
		if(type){
			message = {message, type, expiration};
		}
		expiration = message.expiration;
		this.messages.push(message);
		if(expiration){
			setTimeout(() => {
				const index = this.messages.indexOf(message);
				if(index >= 0){
					this.messages.splice(index, 1);
				}
			}, expiration);
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
