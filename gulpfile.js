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
    scss: 	'assets/scss/*',
    js:		['assets/js/admin-options-page.js']
};

// Setup chosen
gulp.task( 'chosen', function() {

	// Move the images we need
	gulp.src(['bower_components/chosen/chosen-sprite.png','bower_components/chosen/chosen-sprite@2x.png'])
		.pipe(gulp.dest('assets/chosen'));

	// Compress the CSS
	gulp.src('bower_components/chosen/chosen.css')
		.pipe(clean_css())
		.pipe(rename({
			suffix: '.min'
		}))
		.pipe(gulp.dest('assets/chosen'));

	// Minify the JS
	gulp.src('bower_components/chosen/chosen.jquery.js')
		.pipe(uglify({
			mangle: false
		}))
		.pipe(rename({
			suffix: '.min'
        }))
    	.pipe(gulp.dest('assets/chosen'));

});

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
gulp.task('js', function() {
    gulp.src(src.js)
        .pipe(uglify({
            mangle: false
        }))
        .pipe(rename({
			suffix: '.min'
		}))
        .pipe(gulp.dest('assets/js'))
});

// I've got my eyes on you(r file changes)
gulp.task('watch', function() {
	gulp.watch(src.scss, ['sass']);
	gulp.watch(src.js, ['js']);
});

// Let's get this party started
gulp.task('default', ['sass','js','chosen','watch']);