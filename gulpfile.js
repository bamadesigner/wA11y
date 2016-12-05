// Require all the things (that we need)
var autoprefixer = require('gulp-autoprefixer');
var clean_css = require('gulp-clean-css');
var gulp = require('gulp');
var phpcs = require('gulp-phpcs');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var sort = require('gulp-sort');
var uglify = require('gulp-uglify');
var watch = require('gulp-watch');
var wp_pot = require('gulp-wp-pot');

// Define the source paths for each file type
var src = {
    scss: 'assets/scss/*',
    php: ['**/*.php','!vendor/**','!node_modules/**']
};

// Sass is pretty awesome, right?
gulp.task('sass', function() {
    return gulp.src(src.scss)
        .pipe(sass({
			outputStyle: 'compressed'
		})
		.on('error', sass.logError))
        .pipe(autoprefixer({
        	browsers: ['last 2 versions'],
			cascade: false
		}))
		.pipe(rename({
			suffix: '.min'
		}))
		.pipe(gulp.dest('assets/css'));
});

// Minify the JS
/*gulp.task('js', function() {
    gulp.src(src.js)
        .pipe(uglify({
            mangle: false
        }))
        .pipe(rename({
			suffix: '.min'
		}))
        .pipe(gulp.dest('assets/js'))
});*/

// Create the .pot translation file
gulp.task('translate', function() {
    gulp.src('**/*.php')
        .pipe(sort())
        .pipe(wp_pot( {
            domain: 'wa11y',
            destFile:'wa11y.pot',
            package: 'wA11y',
            bugReport: 'https://github.com/bamadesigner/wa11y/issues',
            lastTranslator: 'Rachel Carden <bamadesigner@gmail.com>',
            team: 'Rachel Carden <bamadesigner@gmail.com>',
            headers: false
        } ))
        .pipe(gulp.dest('languages'));
});

// Check our PHP
gulp.task('phpcs', function() {
    return gulp.src(src.php)
		.pipe(phpcs({
			standard: 'WordPress-Core'
		}))
		.pipe(phpcs.reporter('log'));
});

// I've got my eyes on you(r file changes)
gulp.task('watch', function() {
	gulp.watch(src.scss, ['sass']);
	gulp.watch(src.php, ['translate']);
	gulp.watch(src.php, ['phpcs']);
});

// Let's test things out
gulp.task('test', ['phpcs']);

// Let's get this party started
gulp.task('default', ['sass','translate','watch']);