/** BrowserSync Options
 *
 */
let browserSyncOptions = {
	proxy: "skeleton-wp",
	notify: false,
  open: "local"
};


/**
 * Paths to project folders
 */

let paths = {
	php: '**/*.php',
	scripts: {
		input: ['assets/js/*.js', '!assets/js/*.min.js'],
		output: 'assets/js/'
	},
	libs: {
		input: 'assets/js/libs/*',
		output: 'assets/js/'
	},
	styles: {
		input: 'assets/css/**/*.{scss,sass}',
		output: 'assets/css/'
	},
	blockStyles: {
		input: 'blocks/**/*.{scss,sass}',
		output: 'blocks/'
	},
	blockScripts: {
		input: ['blocks/**/script.js', '!blocks/**/script.min.js'],
		output: 'blocks/'
	},
};

/**
 * Gulp Packages
 */

// General
let {gulp, src, dest, watch, series, parallel} = require('gulp');
let flatmap = require('gulp-flatmap');
let lazypipe = require('lazypipe');
let rename = require('gulp-rename');


// Scripts
let concat = require('gulp-concat');
let uglify = require('gulp-terser');

// Styles
const sass = require('gulp-sass')(require('sass'));
const sourcemaps = require('gulp-sourcemaps');
let postcss = require('gulp-postcss');
let prefix = require('autoprefixer');
let minify = require('cssnano');
let mqpacker = require("css-mqpacker");

// BrowserSync
let browserSync = require('browser-sync');


/**
 * Gulp Tasks
 */

/**
 * Starts Browser Sync server
 * @param done
 */
let startServer = function (done) {

  // Initialize BrowserSync
  browserSync.init(browserSyncOptions);

  // Signal completion
  done();

};

/**
 * Reloads Browser Sync browser
 * @param done
 */
let reloadBrowser = function (done) {
  browserSync.reload();
  done();
};

// Repeated JavaScript tasks
let jsTasks = lazypipe()
	.pipe(rename, {suffix: '.min'})
	.pipe(uglify)
	.pipe(dest, paths.scripts.output);

// Lint, minify, and concatenate scripts
let buildScripts = function (done) {

	// Run tasks on script files
	return src(paths.scripts.input)
		.pipe(flatmap(function (stream, file) {
			return stream.pipe(jsTasks());
		}));

};

let jsBlockTasks = lazypipe()
	.pipe(rename, {suffix: '.min'})
	.pipe(uglify)
	.pipe(dest, paths.blockScripts.output);

let buildBlockScripts = function (done) {

	// Run tasks on script files
	return src(paths.blockScripts.input)
		.pipe(flatmap(function (stream, file) {
			return stream.pipe(jsBlockTasks());
		}));
};


// Process, lint, and minify Sass files
let buildStyles = function (done) {

	// Run tasks on all Sass files
	return src(paths.styles.input)
		.pipe(sourcemaps.init())
		.pipe(sass({
			outputStyle: 'expanded',
			sourceComments: true
		}))
		.pipe(postcss([
			prefix({
				cascade: true,
				remove: true
			}),
			mqpacker({
				sort: true
			}),
		]))
		.pipe(sourcemaps.write({includeContent: false}))
		.pipe(dest(paths.styles.output))
		.pipe(rename({suffix: '.min'}))
		.pipe(postcss([
			minify({
				preset: ["default", { discardComments: { removeAll: true } }],
			})
		]))
		.pipe(dest(paths.styles.output));

};

// Process, lint, and minify Sass files
let buildBlockStyles = function (done) {

	// Run tasks on all Sass files
	return src(paths.blockStyles.input)
		.pipe(sourcemaps.init())
		.pipe(sass({
			outputStyle: 'expanded',
			sourceComments: true
		}))
		.pipe(postcss([
			prefix({
				cascade: true,
				remove: true
			}),
			mqpacker({
				sort: true
			}),
		]))
		.pipe(dest(paths.blockStyles.output))
		.pipe(rename({suffix: '.min'}))
		.pipe(postcss([
			minify({
				preset: ["default", { discardComments: { removeAll: true } }],
			})
		]))
		.pipe(sourcemaps.write({includeContent: false}))
		.pipe(dest(paths.blockStyles.output))

};

let copyJSLibs = function (done) {

	// Copy static files
	return src(paths.libs.input)
		.pipe(dest(paths.libs.output));

};

// Watch for changes
let watchSource = function (done) {
	watch(paths.scripts.input, series(exports.scripts, reloadBrowser));
	watch(paths.blockScripts.input, series(exports.blockScripts, reloadBrowser));
	watch(paths.libs.input, series(exports.copyJSLibs, reloadBrowser));
	watch(paths.styles.input, series(exports.styles, reloadBrowser));
	watch(paths.blockStyles.input, series(exports.blockStyles, reloadBrowser));
	watch(paths.php, reloadBrowser);
	done();
};


/**
 * Export Tasks
 */

// Default task
// gulp
exports.default = series(
	parallel(
		buildScripts,
		buildStyles,
		buildBlockStyles,
		buildBlockScripts,
		copyJSLibs
	)
);

// Watch and reload
// gulp watch
exports.watch = series(
	exports.default,
	startServer,
	watchSource
);

//Build and link scripts
exports.scripts = buildScripts;
//Build and link block scripts
exports.blockScripts = buildBlockScripts;

//Compile styles
exports.styles = buildStyles;
//Compile Block styles
exports.blockStyles = buildBlockStyles;

//Copy Libs
exports.copyJSLibs = copyJSLibs;







