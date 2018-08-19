<template>
	<div>
		<object-preview :fileContent="fileContent" :fileName="fileName"></object-preview>
		<input type="file" v-on:change="fileSelected"/>	
	</div>
</template>

<script lang="ts">
import { Vue, Component, Prop } from 'vue-property-decorator';

import ObjectPreviewComponent from './object-preview.vue';

@Component({
	components: {
		objectPreview: ObjectPreviewComponent,
	}
})
export default class ModelUploadFormComponent extends Vue {
	public fileContent: string | null = null;

	private fileName: string | null = null;

	async readFile(file: File) {
		return new Promise<string>((resolve, reject) => {
			const loader = new FileReader();
			loader.onload = loadEvent => {
				if(!loadEvent.target || loadEvent.target.readyState != 2){
					return;
				}
				if (loadEvent.target.error) {
					return reject(new Error("Error while reading file " + file.name + ": " + loadEvent.target.error));
				}
				return resolve(loadEvent.target.result); // Your text is in loadEvent.target.result
			};
			loader.readAsText(file);
		});
	}
	
	public async fileSelected(event: Event){
		this.fileName = null;
		this.fileContent = null;
		const files = (event.target as HTMLInputElement).files;
		if(!files || files.length < 1){
			return;
		}
		const file = files[0];
		this.fileName = file.name;
		this.fileContent = await this.readFile(file);
	}
}
</script>

<style lang="scss" scoped>
</style>