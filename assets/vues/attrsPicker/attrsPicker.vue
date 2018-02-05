<template>
	<table class="materials-choices">
		{% for partName, material in cartItem.attrs %}
		<tr :part-mass="cartItem.factors[partName]">
			<td><label :for="`cart${cartIndex}-${partName}`" >{{ partName }}</label></td>
			<td>{{ include(
				'components/material-select.html.twig',
				{ partName: partName, materials: materials, namePrefix: 'cart' ~ cartIndex, selected: material.id },
				with_context = false
				) }}</td>
		</tr>
		{% endfor %}
	</table>
</template>