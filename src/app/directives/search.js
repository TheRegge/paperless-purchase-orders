'use strict';

angular.module('paperlesspo')
.directive('poSearch', ['$cookies','$http', 'utilities',  function($cookies, $http, utilities) {
	return {
		restrict: 'E',
		templateUrl: 'js/views/search.html',
		replace: true,
		scope: {
			placeholder: '=placeholder'
		},
		link: function postLink($scope, $element, $attrs) {

			var ci_session   = utilities.unserialize( $cookies.ci_session );
			$scope.ssid      = ci_session.ssid;
			$scope.firstname = ci_session.firstname;
			$scope.lastname  = ci_session.lastname;

			var data = $.param({'ssid': $scope.ssid });
			$http({
				url: utilities.getApiUrl() + 'purchaseorders/getBy',
				method: 'POST',
				data: data,
				header: {'Content-Type': 'application/x-www-form-urlencoded'}
			})
			.then(
				// Success
				function(response) {
					console.log(response);
				},
				// Error
				function(response) {
					console.log('FAILED');
					console.log(response);
				}
			);
		}
	}
}]);