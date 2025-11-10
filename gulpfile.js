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
const path = require('path');

// Scripts
const uglify = require('gulp-terser');

// Styles
const sass = require('gulp-sass')(require('sass'));
const sourcemaps = require('gulp-sourcemaps');
const postcss = require('gulp-postcss');
const prefix = require('autoprefixer');
let tailwindcss = require('tailwindcss');
const minify = require('cssnano');

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

/**
 * Generic script builder
 * @param {string|string[]} input - Source file(s) pattern
 * @param {string|function} output - Destination path or function
 * @returns {Stream}
 */
const buildScriptsTask = (input, output) => {
  return src(input)
    .pipe(plumber({ errorHandler: handleError }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(uglify())
    .pipe(dest(output));
};

/**
 * Generic style builder
 * @param {string|string[]} input - Source file(s) pattern
 * @param {string|function} output - Destination path or function
 * @param {boolean} useTailwind - Whether to include Tailwind CSS
 * @returns {Stream}
 */
const buildStylesTask = (input, output, useTailwind = false) => {
  const postcssPlugins = useTailwind
    ? [tailwindcss, prefix]
    : [prefix({ cascade: true, remove: true })];

  return src(input)
    .pipe(plumber({ errorHandler: handleError }))
    .pipe(sourcemaps.init())
    .pipe(sass({
      outputStyle: 'expanded',
      sourceComments: true
    }))
    .pipe(postcss(postcssPlugins))
    .pipe(sourcemaps.write({ includeContent: false }))
    .pipe(dest(output))
    .pipe(rename({ suffix: '.min' }))
    .pipe(postcss([
      minify({
        preset: ["default", { discardComments: { removeAll: true } }],
      })
    ]))
    .pipe(dest(output));
};

const buildSingleScript = (file) => {
  const dir = path.dirname(file);
  return buildScriptsTask(file, dir);
};

const buildSingleStyle = (file, useTailwind = false) => {
  const dir = path.dirname(file);
  return buildStylesTask(file, dir, useTailwind);
};

const buildScripts = () => buildScriptsTask(
  paths.scripts.input,
  paths.scripts.output
);

const buildBlockScripts = () => buildScriptsTask(
  paths.blockScripts.input,
  (file) => file.base
);

const buildStyles = () => buildStylesTask(
  paths.styles.input,
  paths.styles.output,
  true // Use Tailwind
);

const buildBlockStyles = () => buildStylesTask(
  paths.blockStyles.input,
  (file) => file.base,
  false // No Tailwind
);

const copyJSLibs = () => src(paths.libs.input)
  .pipe(plumber({ errorHandler: handleError }))
  .pipe(dest(paths.libs.output));

const watchSource = (done) => {
  watch(paths.scripts.input, series(buildScripts, reloadBrowser));

  watch(paths.blockScripts.input).on('change', (file) => {
    buildSingleScript(file).on('end', () => {
      browserSync.reload();
    });
  });

  watch(paths.libs.input, series(copyJSLibs, reloadBrowser));

  watch(paths.styles.input, series(buildStyles, reloadBrowser));

  watch(paths.blockStyles.input).on('change', (file) => {
    buildSingleStyle(file, false).on('end', () => {
      browserSync.reload();
    });
  });

  watch(paths.php, reloadBrowser);

  done();
};


/**
 * Export Tasks
 */

exports.default = series(
  parallel(
    buildScripts,
    buildStyles,
    buildBlockStyles,
    buildBlockScripts,
    copyJSLibs
  )
);

exports.watch = series(
  exports.default,
  startServer,
  watchSource
);

exports.scripts = buildScripts;
exports.blockScripts = buildBlockScripts;

exports.styles = buildStyles;
exports.blockStyles = buildBlockStyles;

exports.copyJSLibs = copyJSLibs;
