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
export default {
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