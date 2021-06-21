'use strict';

angular.module('paperlesspo')
	.controller('TopbarController', [
		'$scope',
		'$rootScope',
		'principal',
		function ( $rootScope, principal)
		{
			var topbar     = this;
			topbar.appName = "Demo PPO";
			topbar.nav     = [
				{ uri: 'site.orderform', label: 'Order form' },
				{ uri: 'site.archive', label: 'Archive' }
			];

			topbar.user = $rootScope.user;

			if ( topbar.user && topbar.user.is_admin )
			{
				topbar.isAdmin = topbar.user.is_admin > 0;
			}
			else
			{
				topbar.isAdmin = false;
			}

			$rootScope.$watch( 'user', function ( newValue, oldValue ){
				if ( newValue )
				{
					topbar.user       = newValue;
					topbar.isLoggedIn = newValue.username;
					topbar.isAdmin    = newValue.is_admin > 0;
					topbar.fullname   = newValue.firstname+' '+newValue.lastname;
					topbar.photo      = newValue.photo;
				}
				else
				{
					topbar.user       = false;
					topbar.isLoggedIn = false;
					topbar.isAdmin    = false;
					topbar.fullname   = '';
					topbar.photo      = false;
				}
			});

			topbar.chekckIfIsAdmin = function() {
				if ( topbar.user )
				{
					return topbar.user.is_admin;
				}
				else
				{
					return false;
				}
			}
	}])
	;