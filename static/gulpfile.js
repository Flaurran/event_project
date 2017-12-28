var gulp = require('gulp');
var $    = require('gulp-load-plugins')();
var sassGlob = require('gulp-sass-glob');

var sassPaths = [
  'bower_components/normalize.scss/sass',
  'bower_components/foundation-sites/scss',
  'bower_components/motion-ui/src',
  'bower_components/components-font-awesome/scss'
];

gulp.task('sass', function() {
  return gulp.src('scss/app.scss')
    .pipe(sassGlob())
    .pipe($.sass({
      includePaths: sassPaths,
      outputStyle: 'compressed'
    })
      .on('error', $.sass.logError))
    .pipe($.autoprefixer({
      browsers: ['last 2 versions', 'ie >= 9']
    }))
    .pipe(gulp.dest('css'));
});

gulp.task('fonts', function() {
  return gulp.src('bower_components/components-font-awesome/fonts/*')
    .pipe(gulp.dest('fonts'));
});

gulp.task('default', ['sass', 'fonts'], function() {
  gulp.watch(['scss/**/*.scss'], ['sass']);
});
