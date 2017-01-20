var gulp = require('gulp'),
   uglify = require('gulp-uglify');
   jshint = require('gulp-jshint');
   concat = require('gulp-concat');

gulp.task('js', function () {
   return gulp.src('../assets/js/*.js')
      .pipe(uglify())
      .pipe(concat('app.js'))
      .pipe(gulp.dest('build'));
});