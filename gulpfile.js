var gulp = require('gulp'),
			less = require('gulp-less'),
			path = require('path'),
			watch = require('gulp-watch'),
			autoprefixer = require('gulp-autoprefixer'),
			browserSync = require('browser-sync'),
			uglify = require('gulp-uglify'),
			sourcemaps = require('gulp-sourcemaps'),
			jshint = require('gulp-jshint');


//gulp.src(['js/**/*.js', '!js/**/*.min.js'])

gulp.task('default', function () {

	browserSync({
	        proxy: "ornc.local",
	        files: "library/css/*.css"
	    });
	
	gulp.watch('./library/less/**/*.less', ['compile-css']);

	gulp.watch('./library/js/*.js', ['javascript', browserSync.reload]);

});


gulp.task('javascript', function() {
	 gulp.src('./library/js/*.js')  	// ignore vendor stuff
        .pipe(jshint())
      	.pipe(jshint.reporter('default'));

    gulp.src('./library/js/**/*.js')  	
        .pipe(uglify())
        .pipe(gulp.dest('library/dist/js'));
});


gulp.task('compile-css', function () {
	gulp.src('./library/less/main.less')
				.pipe(sourcemaps.init())
			    .pipe(less())
			    .pipe(autoprefixer())
			    .pipe(sourcemaps.write('./maps'))
			    .pipe(gulp.dest('./library/css/'));

});