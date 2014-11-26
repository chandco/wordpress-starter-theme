var gulp = require('gulp');

var less = require('gulp-less');
var path = require('path');
var autoprefixer = require('gulp-autoprefixer');
var browserSync = require('browser-sync');

gulp.task('browser-sync', function() {
    browserSync({
        proxy: "ornc.local",
        files: "library/css/*.css"
    });
});

gulp.task('default', function () {
  gulp.src('./library/less/main.less')
    .pipe(less({
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .pipe(autoprefixer())
    .pipe(gulp.dest('./library/css/'));
});