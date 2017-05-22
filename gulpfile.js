// Gulp
//
// This is not a normal require, because our gulp-help tool (which provides the
// nice task descriptions on the command-line) requires changing the function
// signature of gulp tasks to include the task description.
var theme = theme_name = main_branch = 'noah-lite';
var theme_txtdomain = 'noah-lite';
var gulp = require('gulp-help')(require('gulp'));

// Gulp / Node utilities
var u = require( 'gulp-util' );
var log = u.log;
var c = u.colors;
var sequence = require( 'run-sequence' );
var exec = require( 'gulp-exec' );
var del = require( 'del' );
var fs = require( 'fs' );
var rename = require( 'gulp-rename' );
var replace = require( 'gulp-replace' );
var prompt = require( 'gulp-prompt' );

// Basic workflow plugins
var prefix = require( 'gulp-autoprefixer' );
var sass = require( 'gulp-sass' );
var sourcemaps = require( 'gulp-sourcemaps' );
var concat = require( 'gulp-concat' );
var bs = require( 'browser-sync' );
var jsonToSass = require( 'gulp-json-to-sass-map' );
var rtlcss = require( 'gulp-rtlcss' );

var jsFiles = [
	'./assets/js/main/wrapper-start.js',
	'./assets/js/modules/*.js',
	'./assets/js/main/unsorted.js',
	'./assets/js/main/main.js',
	'./assets/js/main/wrapper-end.js',
	'./assets/js/vendor/*.js'
];

var config = {
	"baseurl": "demos.dev/noah-lite"
};

if ( fs.existsSync( './gulpconfig.json' ) ) {
	config = require( './gulpconfig.json' )
} else {
	console.log( "Don't forget to create your own gulpconfig.json from gulpconfig.json.example" );
}

// -----------------------------------------------------------------------------
// Sass Task
//
// Compiles Sass and runs the CSS through autoprefixer. A separate task will
// combine the compiled CSS with vendor files and minify the aggregate.
// -----------------------------------------------------------------------------

function logError( err, res ) {
	log( c.red( 'Sass failed to compile' ) );
	log( c.red( '> ' ) + err.file.split( '/' )[err.file.split( '/' ).length - 1] + ' ' + c.underline( 'line ' + err.line ) + ': ' + err.message );
}

gulp.task( 'typeline-config', 'Create SCSS typeline config from json', function() {

	return gulp.src( 'inc/integrations/typeline-config.json' )
	           .pipe( jsonToSass( {
		           source: 'inc/integrations/typeline-config.json',
		           output: 'assets/scss/tools/_typeline-config.scss'
	           } ) )
	           .pipe( jsonToSass( {
		           source: 'inc/integrations/typeline-config-editor.json',
		           output: 'assets/scss/tools/_typeline-config-editor.scss'
	           } ) );
} );

gulp.task( 'css', 'Compiles main css files (ie. style.css editor-style.css)', function() {

	return gulp.src( 'assets/scss/*.scss' )
	           .pipe( sourcemaps.init() )
	           .pipe( sass().on( 'error', logError ) )
	           .pipe( prefix() )
	           .pipe( sourcemaps.write() )
	           .pipe( gulp.dest( '.' ) );
} );

gulp.task( 'styles', 'Generate rtl.css file based on style.css', ['css'], function() {

	return gulp.src( 'style.css' )
	           .pipe( rtlcss() )
	           .pipe( rename( 'rtl.css' ) )
	           .pipe( gulp.dest( '.' ) );
} );

gulp.task( 'css-process', function() {

	return gulp.src( 'style.css' )
	           .pipe( sourcemaps.init( {loadMaps: true} ) )
	           .pipe( cmq() )
	           //	           .pipe( csso() )
	           //	           .pipe( csscomb() )
	           .pipe( sourcemaps.write() )
	           .pipe( gulp.dest( '.' ) );
} );

gulp.task( 'styles-components', 'Compiles Sass and uses autoprefixer', function() {

	return gulp.src( 'components/**/*.scss' )
	           .pipe( sass().on( 'error', logError ) )
	           .pipe( prefix() )
	           .pipe( rename( function( path ) {
		           path.dirname = path.dirname.replace( '/scss', '' ); //Remove the scss directory at the end
		           path.dirname += "/css"; //Append the css subdirectory to the path; see http://stackoverflow.com/questions/31358552/gulp-write-output-files-to-subfolder-relative-of-src-path
	           } ) )
	           .pipe( gulp.dest( './components' ) );
} );

// -----------------------------------------------------------------------------
// Combine JavaScript files
// -----------------------------------------------------------------------------
gulp.task( 'scripts', 'Concatenate all JS into main.js and wrap all code in a closure', function() {
	return gulp.src( jsFiles )
	           // Concatenate all our files into main.js
	           .pipe( concat( 'main.js' ) )
	           .pipe( gulp.dest( './assets/js/' ) );
} );

// -----------------------------------------------------------------------------
// Browser Sync using Proxy server
//
// Makes web development better by eliminating the need to refresh. Essential
// for CSS development and multi-device testing.
//
// This is how you'd connect to a local server that runs itself.
// Examples would be a PHP site such as Wordpress or a
// Drupal site, or a node.js site like Express.
//
// Usage: gulp browser-sync-proxy --port 8080
// -----------------------------------------------------------------------------
gulp.task( 'browser-sync', false, function() {
	bs( {
		// Point this to your pre-existing server.
		proxy: config.baseurl + (
			u.env.port ? ':' + u.env.port : ''
		),
		files: ['*.php', 'style.css', 'assets/js/main.js'],
		// This tells BrowserSync to auto-open a tab once it boots.
		open: true
	}, function( err, bs ) {
		if ( err ) {
			console.log( bs.options );
		}
	} );
} );


// -----------------------------------------------------------------------------
// Watch tasks
//
// These tasks are run whenever a file is saved. Don't confuse the files being
// watched (gulp.watch blobs in this task) with the files actually operated on
// by the gulp.src blobs in each individual task.
//
// A few of the performance-related tasks are excluded because they can take a
// bit of time to run and don't need to happen on every file change. If you want
// to run those tasks more frequently, set up a new watch task here.
// -----------------------------------------------------------------------------
gulp.task( 'watch', 'Watch for changes to various files and process them', function() {
	gulp.watch( [
		'inc/integrations/typeline-config.json',
		'inc/integrations/typeline-config-editor.json'
	], ['typeline-config'] );
	gulp.watch( 'components/**/*.scss', ['styles-components'] );
	gulp.watch( 'assets/scss/**/*.scss', ['styles'] );
	gulp.watch( 'assets/js/**/*.js', ['scripts'] );
} );
// -----------------------------------------------------------------------------
// Convenience task for development.
//
// This is the command you run to warm the site up for development. It will do
// a full build, open BrowserSync, and start listening for changes.
// -----------------------------------------------------------------------------
gulp.task( 'bs', 'Main development task:', ['styles', 'scripts', 'browser-sync', 'watch'] );

// -----------------------------------------------------------------------------
// Copy theme folder outside in a build folder, recreate styles before that
// -----------------------------------------------------------------------------
gulp.task( 'copy-folder', 'Copy theme production files to a build folder', function() {
	return gulp.src( './' )
	           .pipe( exec( 'rm -Rf ./../build; mkdir -p ./../build/' + theme + '; rsync -av --exclude="node_modules" ./* ./../build/' + theme + '/', {
		           silent: true,
		           continueOnError: true // default: false
	           } ) );
} );

/**
 * Replace strings in components to match the theme's - like textdomain, styles prefix, etc
 */
gulp.task( 'string-replace', ['copy-folder'], function() {
	return gulp.src( '../build/' + theme + '/components/**/*.php' )
	           .pipe( replace( /['|"]components['|"]/g, '\'' + theme_txtdomain + '\'' ) ) // the text domain
	           .pipe( replace( /style\( ?'pixelgrade/g, 'style\( \'' + theme ) ) //the style registering and enqueue
	           .pipe( replace( /script\( ?'pixelgrade/g, 'script\( \'' + theme ) ) //the script registering and enqueue
	           .pipe( gulp.dest( '../build/' + theme + '/components' ) );
} );

/**
 * Remove unneeded files and folders from the build folder
 */
gulp.task( 'build', 'Remove unneeded files and folders from the build folder', ['string-replace'], function() {

    // files that should not be present in build
    files_to_remove = [
        '**/codekit-config.json',
        'node_modules',
        'config.rb',
        'gulpfile.js',
        'gulpconfig.js',
        'gulpconfig.json',
        'gulpconfig.json.example',
        'package.json',
        'pxg.json',
        'build',
        'css',
        '.idea',
        '.editorconfig',
        '**/.svn*',
        '**/*.css.map',
        '**/.sass*',
        '.sass*',
        '**/.git*',
        '*.sublime-project',
        '.DS_Store',
        '**/.DS_Store',
        '__MACOSX',
        '**/__MACOSX',
        'README.md',
        '.csscomb',
        '.codeclimate.yml',
        'tests',
        'circle.yml',
        'assets/scss',
        '.labels',
		'assets/js/main',
		'assets/js/modules',
		'assets/js/vendor',
        'inc/integrations' // since we are not using typeline with Customify there is no need to keep the json config
    ];

	files_to_remove.forEach( function( e, k ) {
		files_to_remove[k] = '../build/' + theme + '/' + e;
	} );

	return del.sync( files_to_remove, {force: true} );
} );

/**
 * Create the theme installer archive and delete the build folder
 */
gulp.task( 'zip', 'Create the theme installer archive and delete the build folder', ['build'], function() {

	var versionString = '';
	// get theme version from styles.css
	var contents = fs.readFileSync( "./style.css", "utf8" );

	// split it by lines
	var lines = contents.split( /[\r\n]/ );

	function checkIfVersionLine( value, index, ar ) {
		var myRegEx = /^[Vv]ersion:/;
		if ( myRegEx.test( value ) ) {
			return true;
		}
		return false;
	}

	// apply the filter
	var versionLine = lines.filter( checkIfVersionLine );

	versionString = versionLine[0].replace( /^[Vv]ersion:/, '' ).trim();
	versionString = '-' + versionString.replace( /\./g, '-' );

	return gulp.src( './' )
	           .pipe( exec( 'cd ./../; rm -rf ' + theme[0].toUpperCase() + theme.slice( 1 ) + '*.zip; cd ./build/; zip -r -X ./../' + theme[0].toUpperCase() + theme.slice( 1 ) + '-Installer' + versionString + '.zip ./; cd ./../; rm -rf ./build' ) );
} );

gulp.task( 'server', 'Compile scripts and styles for production purposes', ['styles', 'scripts'], function() {
	console.log( 'The styles and scripts have been compiled for production! Go and clear the caches!' );
} );

// -----------------------------------------------------------------------------
// Default: load task listing
//
// Instead of launching some unspecified build process when someone innocently
// types `gulp` into the command line, we provide a task listing so they know
// what options they have without digging into the file.
// -----------------------------------------------------------------------------
gulp.task( 'default', false, ['help'] );
