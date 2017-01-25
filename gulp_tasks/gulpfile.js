const gulp       = require('gulp');
const uglify     = require('gulp-uglify');
const jshint     = require('gulp-jshint');
const concat     = require('gulp-concat');
const order      = require('gulp-order');
const cleanCSS   = require('gulp-clean-css');


gulp.task('js', () => {
   return gulp.src([
    '!../assets/js/html5shiv-printshiv.min.js',
    '../assets/js/*.js'
    ])
    .pipe(order([
        "jquery-3.1.0.min.js",
        "jquery.easing.min.js",
        "*.js"
    ]))
    .pipe(uglify())
    .pipe(concat('app.js'))
    .pipe(gulp.dest('../assets/builds'));
});

gulp.task('css', () => {
    return gulp.src('../assets/css/*.css')
    .pipe(cleanCSS())
    .pipe(concat('styles.css'))
    .pipe(gulp.dest('../assets/builds'));
});

gulp.task('watch', () => {
  gulp.watch('../assets/js/*.js', ['js']);
  gulp.watch('../assets/css/*.css', ['css']);  
});