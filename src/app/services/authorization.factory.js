'use strict';

angular.module('paperlesspo')
.factory('authorization', ['$rootScope', '$state', 'principal', '$log',
	function($rootScope, $state, principal, $log) {
		return {
			authorize: function() {
				return principal.identity().then(function() {
					
					var isAuthenticated = principal.isAuthenticated();

					if ($rootScope.toState.data.accessRoles && $rootScope.toState.data.accessRoles.length > 0 && !principal.isInAnyAccessRole($rootScope.toState.data.accessRoles))
					{
						if (isAuthenticated)
						{
							$state.go('site.login');
							// user is signed in but not authorized for desired state
						} 
						else
						{
							// $log.info('user is not authenticated');
							// user is not authenticated. stow the state they wanted before you
							// send them to the signin state, so you can return them when you're done
							$rootScope.returnToState = $rootScope.toState;
							$rootScope.returnToStateParams = $rootScope.toStateParams;
							// now, send them to the signin state so they can log in
							$state.go('site.login');
						}
					}
				});
			}
		};
	}
])
;