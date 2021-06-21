'use strict';

angular.module('paperlesspo')
.controller('MainCtrl', [
	"$cookies",
	"$routeParams",
	"$location",
	"utilities",
	function ( $cookies, $routeParams, $location, utilities) {

// Check login
	var paramSessionId,
		ci_session,
		ci_session_id;

		if ( ! $routeParams.session || ! $cookies.ci_session ) {
			$location.path('login');
		}
		else
		{
			paramSessionId =  $routeParams.session;
			ci_session     = utilities.unserialize( $cookies.ci_session );
			ci_session_id  = ci_session.session_id;
			
			if ( paramSessionId !== ci_session_id || ! utilities.check_group( ci_session.groups ) )
			{
				$location.path('login');
			}
		}
}]);