/** BrowserSync Options
 *
 */
const browserSyncOptions = {
	proxy: "skeleton-wp",
	notify: false,
  open: "local"
};


/**
 * Paths to project folders
 */

const paths = {
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
const {gulp, src, dest, watch, series, parallel} = require('gulp');
const rename = require('gulp-rename');
const plumber = require('gulp-plumber');
const notify = require('gulp-notify');


// Scripts
const uglify = require('gulp-terser');

// Styles
const sass = require('gulp-sass')(require('sass'));
const sourcemaps = require('gulp-sourcemaps');
const postcss = require('gulp-postcss');
const prefix = require('autoprefixer');
const minify = require('cssnano');
const mqpacker = require("css-mqpacker");

// BrowserSync
const browserSync = require('browser-sync');

/**
 * Error handler for gulp tasks
 */
const handleError = (err) => {
	notify.onError({
		title: 'Gulp Error',
		message: 'Error: <%= error.message %>'
	})(err);
	this.emit('end');
};

/**
 * Gulp Tasks
 */

/**
 * Starts Browser Sync server
 * @param done
 */
const startServer = (done) => {

  // Initialize BrowserSync
  browserSync.init(browserSyncOptions);

  // Signal completion
  done();

};

/**
 * Reloads Browser Sync browser
 * @param done
 */
const reloadBrowser = (done) => {
  browserSync.reload();
  done();
};

// Lint, minify, and concatenate scripts
const buildScripts = () => src(paths.scripts.input)
	.pipe(plumber({ errorHandler: handleError }))
	.pipe(rename({ suffix: '.min' }))
	.pipe(uglify())
	.pipe(dest(paths.scripts.output));

const buildBlockScripts = () => src(paths.blockScripts.input)
	.pipe(plumber({ errorHandler: handleError }))
	.pipe(rename({ suffix: '.min' }))
	.pipe(uglify())
	.pipe(dest(paths.blockScripts.output));

// Process, lint, and minify Sass files
const buildStyles = () => src(paths.styles.input)
	.pipe(plumber({ errorHandler: handleError }))
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
	.pipe(sourcemaps.write({ includeContent: false }))
	.pipe(dest(paths.styles.output))
	.pipe(rename({ suffix: '.min' }))
	.pipe(postcss([
		minify({
			preset: ["default", { discardComments: { removeAll: true } }],
		})
	]))
	.pipe(dest(paths.styles.output));

const buildBlockStyles = () => src(paths.blockStyles.input)
	.pipe(plumber({ errorHandler: handleError }))
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
	.pipe(rename({ suffix: '.min' }))
	.pipe(postcss([
		minify({
			preset: ["default", { discardComments: { removeAll: true } }],
		})
	]))
	.pipe(sourcemaps.write({ includeContent: false }))
	.pipe(dest(paths.blockStyles.output));

const copyJSLibs = () => src(paths.libs.input)
	.pipe(plumber({ errorHandler: handleError }))
	.pipe(dest(paths.libs.output));

// Watch for changes
const watchSource = (done) => {
	watch(paths.scripts.input, series(buildScripts, reloadBrowser));
	watch(paths.blockScripts.input, series(buildBlockScripts, reloadBrowser));
	watch(paths.libs.input, series(copyJSLibs, reloadBrowser));
	watch(paths.styles.input, series(buildStyles, reloadBrowser));
	watch(paths.blockStyles.input, series(buildBlockStyles, reloadBrowser));
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







