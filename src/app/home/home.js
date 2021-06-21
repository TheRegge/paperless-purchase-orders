'use strict';

angular.module('paperlesspo')
	.config(function ($stateProvider){
		$stateProvider
		.state('site.home', {
			parent: 'site',
			url: '/home',
			data: {
				accessRoles: ['basic']
			},
			views: {
				'content@': {
					controller: 'HomeController as home',
					templateUrl: 'app/home/home.tmpl.html'
				},
				'topbar@': {
					controller: 'TopbarController as topbar',
					templateUrl: 'app/topbar/topbar.tmpl.html'
				}
			}
		})
	})
	.controller('HomeController', function ( principal ){
		var home                 = this;
		home.user                = principal.user();
		home.userIsAuthenticated = home.user.username;
	})
;