// Require all the things (that we need)
var autoprefixer = require('gulp-autoprefixer');
var clean_css = require('gulp-clean-css');
var gulp = require('gulp');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var watch = require('gulp-watch');

// Define the source paths for each file type
var src = {
    scss: 	'assets/scss/*'
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

// I've got my eyes on you(r file changes)
gulp.task('watch', function() {
	gulp.watch(src.scss, ['sass']);
});

// Let's get this party started
gulp.task('default', ['sass','watch']);