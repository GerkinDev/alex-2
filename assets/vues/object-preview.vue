<template>
	<div>
		<div v-if="fileContentReflect">
			<div v-if="fileNameReflect.match(/\.obj$/)"><model-obj :content="fileContentReflect"></model-obj></div>
			<div v-else-if="fileNameReflect.match(/\.stl$/)"><model-stl :content="fileContentReflect"></model-stl></div>
		</div>
		<div v-else-if="fileUrlReflect">
			<div v-if="fileUrlReflect.match(/\.obj$/)"><model-obj :src="fileUrl"></model-obj></div>
			<div v-else-if="fileUrlReflect.match(/\.stl$/)"><model-stl :src="fileUrl"></model-stl></div>
		</div>
	</div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from 'vue-property-decorator';

const { ModelObj, ModelStl } = require( 'vue-3d-model' );

@Component({
	components: {
		modelObj: ModelObj,
		modelStl: ModelStl,
	}
})
export default class ObjectPreviewComponent extends Vue {
	@Prop({
		type: String,
		required: false,
	})
	fileContent!: string | null;
	public get fileContentReflect(){
		return this.fileContent || null;
	}

	@Prop({
		type: String,
		required: false,
	})
	fileUrl!: string | null;
	public get fileUrlReflect(){
		return this.fileUrl || null;
	}

	@Prop({
		type: String,
		required: false,
	})
	fileName!: string | null;
	public get fileNameReflect(){
		return this.fileName || null;
	}
}
</script>

<style lang="scss" scoped>
</style>