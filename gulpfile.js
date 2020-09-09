const autoprefixer = require('gulp-autoprefixer');
const cleanCSS = require('gulp-clean-css');
const gulp = require('gulp');
const mergeMediaQueries = require('gulp-merge-media-queries');
const notify = require('gulp-notify');
const rename = require('gulp-rename');
const sass = require('gulp-sass');
const sort = require('gulp-sort');
const wp_pot = require('gulp-wp-pot');

// Define the source paths for each file type.
const src = {
	php: ['**/*.php','!node_modules/**'],
	sass: ['assets/scss/**/*']
};

// Define the destination paths for each file type.
const dest = {
	sass: 'assets/css',
	translate: 'languages'
};

// Take care of SASS.
gulp.task('sass', function() {
	return gulp.src(src.sass)
		.pipe(sass({
			outputStyle: 'expanded'
		}).on('error', sass.logError))
		.pipe(mergeMediaQueries())
		.pipe(autoprefixer({
			browsers: ['last 2 versions'],
			cascade: false
		}))
		.pipe(cleanCSS({
			compatibility: 'ie8'
		}))
		.pipe(rename({
			suffix: '.min'
		}))
		.pipe(gulp.dest(dest.sass))
		.pipe(notify('wA11y SASS compiled'));
});

// Create the .pot translation file.
gulp.task('translate', function() {
	gulp.src(src.php)
		.pipe(sort())
		.pipe(wp_pot({
			domain: 'wa11y',
			destFile: 'wa11y.pot',
			package: 'wA11y',
			bugReport: 'https://github.com/bamadesigner/wa11y/issues',
			lastTranslator: 'Rachel Cherry <bamadesigner@gmail.com>',
			team: 'Rachel Cherry <bamadesigner@gmail.com>',
			headers: false
		}))
		.pipe(gulp.dest(dest.translate))
		.pipe(notify('wA11y translated'));
});

// Compile all the things.
gulp.task('compile',['sass']);

// I've got my eyes on you(r file changes).
gulp.task('watch',function() {
	gulp.watch(src.sass,['sass']);
	gulp.watch(src.php,['translate']);
});

// Let's get this party started.
gulp.task('default',['compile','translate']);
