var gulp      = require('gulp'),
$ = require('gulp-load-plugins')();


var paths = {
	bower : 'bower_components/',
	app   : 'src/app/',
	css   : 'src/css/',
	less  : 'src/less/',
	js    : 'src/',
	fonts : 'src/fonts/',
	tmp   : 'tmp/'
};

gulp.task('less', function () {
	return gulp.src([
		paths.less+'hack.less', // enables to import '*.css' files before 'index.less'
		paths.less+'index.less'
	])
	.pipe( $.less() )
	.pipe( $.concat('lessfiles.tmp.css'))
	.pipe( gulp.dest( paths.tmp ))
	;
});

gulp.task('concatCss',['less'], function () {
	return gulp.src([
		paths.tmp+'lessfiles.tmp.css',
		paths.bower+'ladda/dist/ladda-themeless.min.css',
		paths.bower+'AngularJS-Toaster/toaster.css'
	])
	.pipe($.concat('styles.min.css'))
	.pipe($.cleanCss())
	.pipe(gulp.dest(paths.css));
})

gulp.task('styles', ['less','concatCss']);

gulp.task( 'scripts', function () {
	return gulp.src([
		paths.bower+'jquery/dist/jquery.js',
		paths.bower+'angular/angular.js',
		paths.bower+'angular-animate/angular-animate.js',
		paths.bower+'angular-cookies/angular-cookies.js',
		paths.bower+'angular-resource/angular-resource.js',
		paths.bower+'angular-sanitize/angular-sanitize.js',
		paths.bower+'bootstrap/dist/js/bootstrap.js',
		paths.bower+'angular-bootstrap/ui-bootstrap-tpls.min.js',
		paths.bower+'angular-ui-router/release/angular-ui-router.min.js',
		paths.bower+'chance/chance.js',
		paths.bower+'angular-smart-table/dist/smart-table.js',
		paths.bower+'ladda/dist/spin.min.js',
		paths.bower+'ladda/dist/ladda.min.js',
		paths.bower+'angular-ladda/dist/angular-ladda.min.js',
		paths.bower+'underscore/underscore-min.js',
		paths.bower+'angular-underscore-module/angular-underscore-module.js',
		paths.bower+'AngularJS-Toaster/toaster.min.js',
		paths.app+'**/*.js'
	])
	//.pipe( $.uglify() )
	.pipe( $.concat( 'scripts.min.js' ) )
	.pipe( gulp.dest(paths.js) )
	;
});


gulp.task( 'fonts', function () {
	return gulp.src([
		paths.bower+'font-awesome/fonts/*'
	])
	.pipe(gulp.dest( paths.fonts ));
});

gulp.task('watch', function () {
	gulp.watch( paths.less+'**/*.less', ['less']);
	gulp.watch( paths.app+'**/*.js', ['scripts']);
});

gulp.task( 'default', ['scripts', 'styles', 'fonts', 'watch'] );