// webpack.config.js
const Encore = require('@symfony/webpack-encore');
const _ = require('lodash');

Encore
// the project directory where all compiled assets will be stored
.setOutputPath('public/build/')

// the public path used by the web server to access the previous directory
.setPublicPath('/build')

// will create public/build/app.js and public/build/app.css
.addEntry('app', './assets/scripts/app.ts')

// allow sass/scss files to be processed
.enableSassLoader()

// allow TypeScript files to be processed
.enableTypeScriptLoader(config => {
	config.appendTsSuffixTo = [/\.vue$/];
	return config;
})

// allow Vue files to be processed
.enableVueLoader(options => {
	//	console.log(options);
	// https://vue-loader.vuejs.org/en/configurations/advanced.html

	options.loaders.js.push({
		loader: 'ts-loader'
	});
	//	console.log(require('util').inspect(options, {colors: true, depth: 8}));
})

// allow legacy applications to use $/jQuery as a global variable
//    .autoProvidejQuery()

.enableSourceMaps(!Encore.isProduction())

// empty the outputPath dir before each build
.cleanupOutputBeforeBuild()

// show OS notifications when builds finish/fail
.enableBuildNotifications()

// create hashed filenames (e.g. app.abc123.css)
// .enableVersioning()
;

// export the final configuration
const config = Encore.getWebpackConfig();

// From https://github.com/webpack/webpack/issues/2031#issuecomment-317589620
function excludeNodeModulesExcept (modules)
{
	const path = require('path');

	var pathSep = path.sep;
	if (pathSep == '\\') // must be quoted for use in a regexp:
	pathSep = '\\\\';
	var moduleRegExps = modules.map (function (modName) { return new RegExp("node_modules" + pathSep + modName)})

	return function (modulePath) {
		if (/node_modules/.test(modulePath)) {
			for (var i = 0; i < moduleRegExps.length; i ++)
			if (moduleRegExps[i].test(modulePath)) return false;
			return true;
		}
		return false;
	};
}

_.find(config.module.rules, { test: /\.jsx?$/}).exclude = excludeNodeModulesExcept([
	'bootstrap-vue', // white-list bootstrap-vue
]);

module.exports = config;