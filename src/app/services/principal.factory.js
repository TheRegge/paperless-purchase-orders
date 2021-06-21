'use strict';

angular.module('paperlesspo')
.factory('principal', ['$q', '$http', '$timeout', '$rootScope', 'utilities',
	function($q, $http, $timeout, $rootScope, utilities) {
		var _identity,
		_authenticated = false;

		return {
			isIdentityResolved: function() {

				return angular.isDefined(_identity);
			},
			isAuthenticated: function() {
				return _authenticated;
			},
			isInAccessRoles: function(accessRole) {
				if (!_authenticated || !_identity.accessRoles) { return false; }

				return _identity.accessRoles.indexOf(accessRole) !== -1;
			},
			isInAnyAccessRole: function(accessRoles) {
				if (!_authenticated || !_identity.accessRoles) { return false; }

				for (var i = 0; i < accessRoles.length; i++) {
					if (this.isInAccessRoles(accessRoles[i])) { return true; }
				}

				return false;
			},
			authenticate: function(identity) {
				_identity = identity;
				_authenticated = (identity !== null);
			},

			user: function ()
			{
				return _identity;
			},

			remove: function () {
				_identity = null;
				$rootScope.user = _identity;
			},

			identity: function(force)
			{
				var deferred = $q.defer();

				if (force === true) { _identity = undefined; }

				// check and see if we have retrieved the identity data from the server. if we have, reuse it by immediately resolving
				if (angular.isDefined(_identity)) {
					$rootScope.user = _identity;
					deferred.resolve(_identity);
					return deferred.promise;
				}
				var self = this;

				$http.get(utilities.getApiUrl()+'login/get_current_user')
				.success( function ( data ) {
					var accessRoles = utilities.getAccessRoles( data.groups );
					
					_identity = {
						accessRoles : accessRoles,
						firstname   : data.firstname,
						fullname    : data.fullname,
						lastname    : data.lastname,
						photo       : data.photo,
						ssid        : data.ssid,
						username    : data.username
					};
					$rootScope.user = _identity;
					self.authenticate( _identity );
					deferred.resolve( _identity );
				})
				.error( function () {
					_identity = null;
					_authenticated = false;
					self.authenticate( _identity );
					deferred.resolve( _identity );
				});
				return deferred.promise;
			}
		};
	}
]);