'use strict';

angular.module('paperlesspo')
.directive('formadditem', [function(){
	return {
		require: _,
		restrict: 'E',
		// transclude: true,
		scope: true,
		templateUrl: 'app/directives/formadditem/formadditem.tmpl.html'
	}
}]);