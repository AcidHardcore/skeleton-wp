/**
 * Settings
 * Turn on/off build features
 */

var settings = {
  clean: false,
  scripts: true,
  libs: true,
  polyfills: false,
  styles: true,
  svgs: true,
  sprite: false,
  images: true,
  reload: true
};

/** BrowserSync Options
*
 */
var browserSyncOptions = {
	proxy: "skeleton-wp",
	notify: false
};


/**
 * Paths to project folders
 */

var paths = {
  php: '**/*.php',
  scripts: {
    input: 'assets/js/src/*',
    watchPath: 'assets/js/*.js',
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
  images: {
    input: 'src/img/**/*.{jpg,jpeg,gif,png}',
    output: 'dist/img/'
  },
  svgs: {
    input: 'src/svg/*.svg',
    output: 'dist/img/'
  },
  reload: './dist/'
};


/**
 * Template for banner to add to file headers
 */

var banner = {
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
var {gulp, src, dest, watch, series, parallel} = require('gulp');
var del = require('del');
var flatmap = require('gulp-flatmap');
var lazypipe = require('lazypipe');
var rename = require('gulp-rename');
var header = require('gulp-header');
var pkg = require('./package.json');

// Scripts
var concat = require('gulp-concat');
var uglify = require('gulp-terser');
var optimizejs = require('gulp-optimize-js');

// Styles
var sass = require('gulp-sass');
var postcss = require('gulp-postcss');
var prefix = require('autoprefixer');
var minify = require('cssnano');
var mqpacker = require("css-mqpacker");
var inlineSVG = require('postcss-inline-svg');

// SVGs
var svgmin = require('gulp-svgmin');
var svgstore = require('gulp-svgstore');

//Images
var imagemin = require('gulp-imagemin');

// BrowserSync
var browserSync = require('browser-sync');


/**
 * Gulp Tasks
 */

// Repeated JavaScript tasks
var jsTasks = lazypipe()
  .pipe(header, banner.main, {pkg: pkg})
  .pipe(optimizejs)
  .pipe(dest, paths.scripts.output)
  .pipe(rename, {suffix: '.min'})
  .pipe(uglify)
  .pipe(optimizejs)
  .pipe(header, banner.main, {pkg: pkg})
  .pipe(dest, paths.scripts.output);

// Lint, minify, and concatenate scripts
var buildScripts = function (done) {

  // Make sure this feature is activated before running
  if (!settings.scripts) return done();

  // Run tasks on script files
  return src(paths.scripts.input)
    .pipe(flatmap(function (stream, file) {

      // If the file is a directory
      if (file.isDirectory()) {

        // Setup a suffix variable
        var suffix = '';

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
var buildStyles = function (done) {

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

// Optimize SVG files
var buildSVGs = function (done) {

  // Make sure this feature is activated before running
  if (!settings.svgs) return done();

  // Optimize SVG files
  return src(paths.svgs.input)
    .pipe(svgmin())
    .pipe(dest(paths.svgs.output));

};

//make SVG sprite
var svgSprite =  function (done) {

  // Make sure this feature is activated before running
  if (!settings.sprite) return done();

  return src(paths.svgs.input)
    .pipe(svgmin(function (file) {
      return {
        plugins: [{
          cleanupIDs: {
            minify: true
          }
        }]
      }
    }))
    .pipe(svgstore({inlineSvg: true}))
    .pipe(rename('sprite.svg'))
    .pipe(dest(paths.svgs.output));
};


var images = function (done) {

  // Make sure this feature is activated before running
  if (!settings.images) return done();

  // optimize images
  return src(paths.images.input)
    .pipe(imagemin([
      imagemin.gifsicle({interlaced: true}),
      imagemin.mozjpeg({quality: 70, progressive: true}),
      imagemin.optipng({optimizationLevel: 5}),
    ]))
    .pipe(dest(paths.images.output));

};

var copyJSLibs = function (done) {

  // Make sure this feature is activated before running
  if (!settings.libs) return done();

  // Copy static files
  return src(paths.libs.input)
    .pipe(dest(paths.libs.output));

};

// Watch for changes to the src directory
var startServer = function (done) {

  // Make sure this feature is activated before running
  if (!settings.reload) return done();

  // Initialize BrowserSync
  browserSync.init(browserSyncOptions);

  // Signal completion
  done();

};

// Reload the browser when files change
var reloadBrowser = function (done) {
  if (!settings.reload) return done();
  browserSync.reload();
  done();
};

// Watch for changes
var watchSource = function (done) {
  watch(paths.scripts.watchPath, series(exports.scripts, reloadBrowser));
  watch(paths.libs.input, series(exports.copyJSLibs, reloadBrowser));
  watch(paths.styles.input, series(exports.styles, reloadBrowser));
  watch([paths.svgs.input, paths.images.input], series(exports.assets, reloadBrowser));
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
    buildSVGs,
    svgSprite,
    images,
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

exports.assets = series(
  buildSVGs,
  svgSprite,
  images
);

//Copy Libs
exports.copyJSLibs = copyJSLibs;






