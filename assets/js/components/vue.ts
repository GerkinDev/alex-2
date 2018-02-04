import V from 'vue';
import Component from 'vue-class-component'
declare const Vue: typeof V;

(Vue.config as any).delimiters = ['<%', '%>'];

export const flashes = (() => {

// Le décorateur @Component indique que la classe est un composant Vue
	@Component({
		// Toutes les options de composant sont autorisées ici.
		template: `<div class="alert alert-<%flash.alertType%> alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<section><%flash.message%></section>
	</div>`,
		props: ['flash'],
	})
	class Flash extends Vue{
	}
	const vue = new Vue({
		el: '#flashMessages',
		data: {
			flashes: [{
				alertType: 'info',
				message: 'Hello Vue!',
			}],
			headText: 'FooBar'
		},
		components: {
			// <my-component> will only be available in parent's template
			flashmessage: Flash,
		}
	});
	    // create an ephemeral div to mount the component into
		const vueContainer = document.createElement('div')
		// add it to `div#app`
		$('body').append(vueContainer)
		// mount the Vue component to the ephemeral div, which Vue will remove from DOM
		vue.$mount(vueContainer)
		console.log({vueContainer, vue});
	return vue;
})();

(window as any).flashes = flashes;