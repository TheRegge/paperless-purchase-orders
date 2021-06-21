'use strict';

angular.module('paperlesspo')
.directive('formItemsList', [function(){
	return {
		restrict: 'E',
		transclude: true,
		scope: true,
		templateUrl: 'app/directives/formitemslist/formitemslist.tmpl.html'
	}
}]);