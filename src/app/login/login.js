'use strict';

angular.module('paperlesspo')
.config(function ($stateProvider) {
	$stateProvider
	.state('site.login', {
		parent: 'site',
		url: '/login/:action',
		data: {
				accessRoles: []
			},
		views: {
			'topbar@': {
				controller: 'TopbarController as topbar',
				templateUrl: 'app/topbar/topbar.tmpl.html'
			},
			'content@': {
				controller: 'LoginController as login',
				templateUrl: 'app/login/login.tmpl.html'
			}
		}
	})
})
.controller('LoginController', [
	'$state',
	'$http',
	'$stateParams',
	'$rootScope',
	'principal',
	'authorization',
	'toaster',
	'utilities',
	function ( 
		$state,
		$http,
		$stateParams,
		$rootScope,
		principal,
		authorization,
		toaster,
		utilities
	){
		var action = $stateParams.action;

		var login    = this;
		login.alerts = [];

		login.login = function () {
			// trigger ladda animation:
			login.loginLoading = true;

			// prepare data for http:
			var data = $.param({username: login.username, password: login.password } );

			$http({
				method  : 'POST',
				url     : utilities.getApiUrl()+'login',
				data    : data,
				headers : { 'Content-Type': 'application/x-www-form-urlencoded' },
				timeout : 30000
			})
			.success( function ( data ) {
				login.loginLoading = false;

				if ( data.logged_in )
				{
					var accessRoles = utilities.getAccessRoles( data.groups );
					console.log( data );
					
					var _identity = {
						accessRoles : accessRoles,
						firstname   : data.firstname,
						fullname    : data.fullname,
						lastname    : data.lastname,
						photo       : data.photo,
						ssid        : data.ssid,
						username    : data.username
					};
					$rootScope.user = _identity;
					principal.authenticate( _identity );

					if (login.returnToState)
					{
						$state.go( login.returnToState.name, login.returnToStateParams);
					}
					else
					{
						$state.go('site.home');
					}
				}
				else
				{
					toaster.pop('error', "Login Denied", "Please check your credentials, and that you have been given access to this application.");
				}
			})
			.error( function () {
				toaster.pop('error', "Could not connect login server. Please try again or contact New Lab.");
			});
		};

		login.logout = function () {
			$http({
				method  : 'POST',
				url     : utilities.getApiUrl()+'login/logout',
				headers : { 'Content-Type': 'application/x-www-form-url-encoded' },
				timeout : 30000
			})
			.success( function ( data ) {
				if ( data.success )
				{
					principal.identity( true ); // nullifies _identity
					principal.remove();
					authorization.authorize();
				}
				else
				{
					toaster.pop('error', 'Could not log you out, api error. Please try again or contact New Lab.' );
				}
			})
			.error( function () {
				toaster.pop('error', 'Error contacting the logout api. Please try again or contact New Lab.' );
			});
		}

		if ( $stateParams.action === 'logout' )
		{
			login.logout();
		}
}]);