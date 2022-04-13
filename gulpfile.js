/**
 * Settings
 * Turn on/off build features
 */

let settings = {
  scripts: true,
  libs: true,
  polyfills: false,
  styles: true,
  reload: true
};

/** BrowserSync Options
*
 */
let browserSyncOptions = {
	proxy: "skeleton-wp",
	notify: false
};


/**
 * Paths to project folders
 */

let paths = {
  php: '**/*.php',
  scripts: {
    input: 'assets/js/src/*',
    watchPath: 'assets/js/src/*.js',
    polyfills: '.polyfill.js',
    output: 'assets/js/'
  },
  libs: {
    input: 'assets/js/libs/*',
    output: 'assets/js/'
  },
  styles: {
    input: 'assets/css/src/**/*.{scss,sass}',
    output: 'assets/css/'
  },
  reload: './dist/'
};


/**
 * Template for banner to add to file headers
 */

let banner = {
  main:
    '/*!' +
    ' <%= pkg.name %> v<%= pkg.version %>' +
    ' | (c) ' + new Date().getFullYear() + ' <%= pkg.author.name %>' +
    ' | <%= pkg.license %> License' +
    ' | <%= pkg.repository.url %>' +
    ' */\n'
};


/**
 * Gulp Packages
 */

// General
let {gulp, src, dest, watch, series, parallel} = require('gulp');
let flatmap = require('gulp-flatmap');
let lazypipe = require('lazypipe');
let rename = require('gulp-rename');
let header = require('gulp-header');
let pkg = require('./package.json');

// Scripts
let concat = require('gulp-concat');
let uglify = require('gulp-terser');
let optimizejs = require('gulp-optimize-js');

// Styles
const sass = require('gulp-sass')(require('sass'));
let postcss = require('gulp-postcss');
let prefix = require('autoprefixer');
let minify = require('cssnano');
let mqpacker = require("css-mqpacker");
let inlineSVG = require('postcss-inline-svg');

// BrowserSync
let browserSync = require('browser-sync');


/**
 * Gulp Tasks
 */

// Repeated JavaScript tasks
let jsTasks = lazypipe()
  .pipe(header, banner.main, {pkg: pkg})
  .pipe(optimizejs)
  .pipe(dest, paths.scripts.output)
  .pipe(rename, {suffix: '.min'})
  .pipe(uglify)
  .pipe(optimizejs)
  .pipe(header, banner.main, {pkg: pkg})
  .pipe(dest, paths.scripts.output);

// Lint, minify, and concatenate scripts
let buildScripts = function (done) {

  // Make sure this feature is activated before running
  if (!settings.scripts) return done();

  // Run tasks on script files
  return src(paths.scripts.input)
    .pipe(flatmap(function (stream, file) {

      // If the file is a directory
      if (file.isDirectory()) {

        // Setup a suffix letiable
        let suffix = '';

        // If separate polyfill files enabled
        if (settings.polyfills) {

          // Update the suffix
          suffix = '.polyfills';

          // Grab files that aren't polyfills, concatenate them, and process them
          src([file.path + '/*.js', '!' + file.path + '/*' + paths.scripts.polyfills])
            .pipe(concat(file.relative + '.js'))
            .pipe(jsTasks());

        }

        // Grab all files and concatenate them
        // If separate polyfills enabled, this will have .polyfills in the filename
        src(file.path + '/*.js')
          .pipe(concat(file.relative + suffix + '.js'))
          .pipe(jsTasks());

        return stream;

      }

      // Otherwise, process the file
      return stream.pipe(jsTasks());

    }));

};


// Process, lint, and minify Sass files
let buildStyles = function (done) {

  // Make sure this feature is activated before running
  if (!settings.styles) return done();

  // Run tasks on all Sass files
  return src(paths.styles.input)
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
      inlineSVG()
    ]))
    .pipe(header(banner.main, {pkg: pkg}))
    .pipe(dest(paths.styles.output))
    .pipe(rename({suffix: '.min'}))
    .pipe(postcss([
      minify({
        preset: ["default", { discardComments: { removeAll: true } }],
      })
    ]))
    .pipe(dest(paths.styles.output));

};

let copyJSLibs = function (done) {

  // Make sure this feature is activated before running
  if (!settings.libs) return done();

  // Copy static files
  return src(paths.libs.input)
    .pipe(dest(paths.libs.output));

};

// Watch for changes to the src directory
let startServer = function (done) {

  // Make sure this feature is activated before running
  if (!settings.reload) return done();

  // Initialize BrowserSync
  browserSync.init(browserSyncOptions);

  // Signal completion
  done();

};

// Reload the browser when files change
let reloadBrowser = function (done) {
  if (!settings.reload) return done();
  browserSync.reload();
  done();
};

// Watch for changes
let watchSource = function (done) {
  watch(paths.scripts.watchPath, series(exports.scripts, reloadBrowser));
  watch(paths.libs.input, series(exports.copyJSLibs, reloadBrowser));
  watch(paths.styles.input, series(exports.styles, reloadBrowser));
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

//Compile styles
exports.styles = buildStyles;

//Copy Libs
exports.copyJSLibs = copyJSLibs;






