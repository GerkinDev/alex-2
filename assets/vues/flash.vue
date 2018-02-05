<template>
    <b-alert :show="dismissCountDown"
             dismissible
             variant="warning"
             @dismissed="dismissCountDown=0"
             @dismiss-count-down="countDownChanged">
      <p>This alert will dismiss after {{dismissCountDown}} seconds...</p>
      <b-progress variant="warning"
                  :max="dismissSecs"
                  :value="dismissCountDown"
                  height="4px">
      </b-progress>
    </b-alert>
</template>


<script lang="ts">
import Vue from 'vue'
import Component from 'vue-class-component'

export interface IFlashMessage{
	message: string,
	type: TYPE,
}
export enum TYPE{
	error = 'danger',
	info = 'info',
}
interface FlashMessage{
	dismissCountDown?: number;
	dismissSecs?: number;
	props: string[];

	data (): object,
	countDownChanged (dismissCountDown: number): void;
	showAlert (): void;
}
export default {
	props: ['type', 'message'],

	data () {
		return {
			dismissSecs: 10,
			dismissCountDown: 0,
			showDismissibleAlert: false
		}
	},
	methods: {
		countDownChanged (this: FlashMessage, dismissCountDown: number) {
			this.dismissCountDown = dismissCountDown
		},
		showAlert (this: FlashMessage) {
			this.dismissCountDown = this.dismissSecs
		}
	}
}
</script>

<style lang="scss">
</style>