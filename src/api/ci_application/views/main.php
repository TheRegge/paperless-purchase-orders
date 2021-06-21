<!DOCTYPE html>
<html ng-app="paperlesspo">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Demo Paperless PO</title>
	<meta name="viewport" content="width=device-width">
	<link rel="icon" href="favicon.ico">
	<link rel="stylesheet" href="css/styles.min.css">
</head>
<body>
	<!--[if lt IE 10]>
	<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<topbar ng-controller="TopbarController"></topbar>
	<div ng-view></div>

	<!-- build:js scripts/vendor.js -->
	<!-- bower:js -->
	<script src="../bower_components/jquery/dist/jquery.js"></script>
	<script src="../bower_components/angular/angular.js"></script>
	<script src="../bower_components/angular-animate/angular-animate.js"></script>
	<script src="../bower_components/angular-cookies/angular-cookies.js"></script>
	<script src="../bower_components/angular-resource/angular-resource.js"></script>
	<script src="../bower_components/angular-route/angular-route.js"></script>
	<script src="../bower_components/angular-sanitize/angular-sanitize.js"></script>
	<script src="../bower_components/bootstrap/dist/js/bootstrap.js"></script>
	<script src="../bower_components/angular-ui-bootstrap-bower/ui-bootstrap-tpls.js"></script>
	<script src="../bower_components/chance/chance.js"></script>
	<script src="../bower_components/angular-smart-table/dist/smart-table.debug.js"></script>
	<script src="../bower_components/ladda/dist/spin.min.js"></script>
	<script src="../bower_components/ladda/dist/ladda.min.js"></script>
	<script src="../bower_components/angular-ladda/dist/angular-ladda.min.js"></script>
	<script src="../bower_components/underscore/underscore-min.js"></script>
	<script src="../bower_components/angular-underscore-module/angular-underscore-module.js"></script>

	<!-- endbower -->
	<!-- endbuild -->

	<!-- build:js({src}) scripts/app.js -->
	<script src="js/index.js"></script>
	<script src="js/controllers/login.js"></script>
	<script src="js/controllers/main.js"></script>
	<script src="js/controllers/pdf.js"></script>
	<script src="js/controllers/poform.js"></script>
	<script src="js/controllers/topbar.js"></script>
	<script src="js/controllers/archive.js"></script>
	<script src="js/directives/topbar.js"></script>
	<script src="js/directives/search.js"></script>
	<script src="js/directives/rz-search.js"></script>
	<script src="js/directives/poitems.js"></script>
	<script src="js/directives/formadditem.js"></script>
	<script src="js/directives/formitemslist.js"></script>
	<script src="js/directives/poptext.js"></script>
	<script src="js/services/purchaseorders.js"></script>
	<script src="js/services/utilities.js"></script>
	<!-- endbuild -->
</body>
</html>