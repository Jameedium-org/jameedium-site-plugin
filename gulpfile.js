// // Load Gulp...of course
const { src, dest, task, series, watch, parallel } = require('gulp');

// // CSS related plugins
var sass         = require( 'gulp-sass' );
var postcss      = require('gulp-postcss');
var autoprefixer = require( 'autoprefixer' );

// // JS related plugins
var uglify       = require( 'gulp-uglify' );
var babelify     = require( 'babelify' );
var browserify   = require( 'browserify' );
var source       = require( 'vinyl-source-stream' );
var buffer       = require( 'vinyl-buffer' );
var stripDebug   = require( 'gulp-strip-debug' );

// // Utility plugins
var sourcemaps   = require( 'gulp-sourcemaps' );
var notify       = require( 'gulp-notify' );
var options      = require( 'gulp-options' );
var gulpif       = require( 'gulp-if' );

// // Browers related plugins
var browserSync  = require( 'browser-sync' ).create();

// // Project related variables
var projectURL   = 'https://jameedium.org';

var styleSRC     = 'src/scss/ja-style.scss';
var styleFiles   = [styleSRC];
var styleURL     = './assets/css';
var mapURL       = './';

var jsSRC        = 'src/js/';
var jsAdmin      = 'ja-script.js';
var jsFiles      = [jsAdmin];
var jsURL        = './assets/js/';

var styleWatch   = 'src/scss/**/*.scss';
var jsWatch      = 'src/js/**/*.js';
var phpWatch     = './**/*.php';

function css(done) {
	src(styleFiles)
		.pipe( sourcemaps.init() )
		.pipe( sass({
			errLogToConsole: true,
			outputStyle: 'compressed'
		}) )
		.on( 'error', console.error.bind( console ) )
		.pipe(postcss([ autoprefixer({ remove: false }) ]))
		.pipe( sourcemaps.write( mapURL ) )
		.pipe( dest( styleURL ) )
		.pipe( browserSync.stream() );
	done();
}

function js(done) {
	jsFiles.map(function (entry) {
		return browserify({
			entries: [jsSRC + entry]
		})
		.transform( babelify, { presets: [ '@babel/preset-env' ] } )
		.bundle()
		.pipe( source( entry ) )
		.pipe( buffer() )
		.pipe( gulpif( options.has( 'production' ), stripDebug() ) )
		.pipe( sourcemaps.init({ loadMaps: true }) )
		.pipe( uglify() )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( dest( jsURL ) )
		.pipe( browserSync.stream() );
	});
	done();
}

function reload(done) {
	browserSync.reload();
	done();
}

function watch_files(done) {
	watch( phpWatch, reload );
	watch( styleWatch, css );
	watch( jsWatch, series( js, reload ) );
	src( jsURL + 'ja-script.js' )
        .pipe( notify({ message: 'Gulp is Watching, Happy Coding!' }) );
    done();
};

task("css", css);
task("js", js);
task("default", series(css, js));
task("watch", watch_files);