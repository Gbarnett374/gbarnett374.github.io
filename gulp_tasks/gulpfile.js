const gulp = require('gulp');
const uglify = require('gulp-uglify');
const jshint = require('gulp-jshint');
const concat = require('gulp-concat');
const order  = require('gulp-order');

gulp.task('js', function () {
   return gulp.src('../assets/js/*.js')
      .pipe(order([
        "jquery-3.1.0.min.js",
        "jquery.easing.min.js",
        "*.js"
      ]))
      .pipe(uglify())
      .pipe(concat('app.js'))
      .pipe(gulp.dest('build'));
});