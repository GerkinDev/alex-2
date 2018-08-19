<template>
	<div id="cart">
		<table>
			<thead><tr>
				<th>Name</th>
				<th>Materials</th>
				<th>Number</th>
				<th>Unit price</th>
				<th>Total price</th>
				</tr></thead>
			<tr
				v-for="(product, index) in products"
				:key="index"
				class="cart-item"
				:model-id="product.entity.id">
				<!--<td><a :href="path('product', {slug: cartItem.entity.slug} )">{{ cartItem.entity.title }}</a></td>
				<td>
				</td>
				<td><input type="number" min="1" :value="cartItem.count" :name="`cart${cartIndex}[number]`" class="unit-count form-control"/></td>
				<td><output :value="formatCurrency(cartItem.price)" class="unit-price form-control" disabled/></td>
				<td><output :value="formatCurrency(cartItem.price * cartItem.count)" class="sum-price form-control" disabled/></td>
				<td>
					<button class="btn btn-outline-danger removeItem" aria-hidden="true">&times;</button>
				</td>-->
			</tr>
		</table>
	</div>
</template>

<script lang="ts">
import Vue from 'vue'
import { formatCurrency } from '../../scripts/components/currencyFormatter';

import CartComponent from './cart-line.vue';

export interface CartItem{
}
export default class Cart extends Vue{
	constructor(public products: Array<CartItem> = []){
		super({
			el: '#cart',
			data: {
				products,
			},
			components: {
				cart: CartComponent
			},
		});
	}
}
</script>

<style lang="scss">
</style>
