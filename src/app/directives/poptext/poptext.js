'use strict';

angular.module('paperlesspo')
.directive('popText', [function(){
	return {
		restrict: 'E',
		scope: {
			text: '=text',
			domid: '=domid',
			popt: '=popt'
		},
		templateUrl: 'app/directives/poptext/poptext.html'
	}
}]);