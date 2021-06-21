(function () {
	'use strict';

angular.module('paperlesspo', [
	'angular-ladda',
	'ngAnimate',
	'ngCookies',
	'ngResource',
	'ngSanitize',
	'smart-table',
	'toaster',
	'ui.bootstrap',
	'ui.router',
	'underscore'
])
.config(function ($stateProvider, $urlRouterProvider, laddaProvider) {
	$stateProvider
	.state('site', {
		abstract: true,
		url: '',
		resolve: {
			authorize: ['authorization',
			function (authorization) {
				return authorization.authorize();
			}]
		}
	})
	;

	$urlRouterProvider.when('','home');

	// Ladda config (animated buttons)
	laddaProvider.setOption({
		style: 'zoom-out'
	});
})
.run([
	'$rootScope',
	'$state',
	'$stateParams',
	'authorization',
	'principal',
	function ($rootScope, $state, $stateParams, authorization, principal) {

		$rootScope.$on('$stateChangeStart', function (event, toState, toStateParams) {

			$rootScope.toState = toState;
			$rootScope.toStateParams = toStateParams;

			if (principal.isIdentityResolved()) {
				authorization.authorize();
			}
		});
	}]);
})();