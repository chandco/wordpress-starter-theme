var gulp = require('gulp'),
			less = require('gulp-less'),
			path = require('path'),
			watch = require('gulp-watch'),
			autoprefixer = require('gulp-autoprefixer'),
			browserSync = require('browser-sync'),
			uglify = require('gulp-uglify'),
			sourcemaps = require('gulp-sourcemaps'),
			jshint = require('gulp-jshint'),
			imageResize = require('gulp-image-resize'),
			rename = require("gulp-rename"),
			changed = require("gulp-changed");

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





gulp.task('resize-images', function () {

	// this doesn't happen automatically, because it's a bit intensive, it should be done when we need.
	
	var originalName;

     gulp.src("images/src/**/*.{jpg,png}")
    .pipe(changed("images/dist"))


    // This isn't ideal, but it'll work fine
    // Make sure that you go BIGGEST FIRST because of piping

    // Need to change renaming to the wordpress convention

    // need to specify heights?

    // need to do lossless optimisation

    // remember to set new name as well as new size for each resize.
    .pipe(imageResize({ 
    	imageMagick : true,
    	width : 200
    }))
    .pipe(rename(function (path) {
    	originalName = path.basename;
        path.basename = originalName + "-200";        
    }))
    .pipe(gulp.dest("images/dist"))
 

    .pipe(imageResize({ 
    	imageMagick : true,
    	width : 100
    }))
    .pipe(rename(function (path) {
        path.basename = originalName + "-100";        
    }))
    .pipe(gulp.dest("images/dist"));

});




