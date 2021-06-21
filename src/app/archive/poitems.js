'use strict';

angular.module('paperlesspo')
.directive('poItems', [function(){
	return {
		restrict: 'E',
		scope: {
			items: '=items',
			po: '=po'
		},
		templateUrl: 'app/archive/poitems.html',
		link: function postLink( $scope, $element, $attrs) {

			if ( $scope.items)
			{
				$scope.items = _.sortBy( $scope.items, 'budgetNumber' );
				for (var i=0; i < $scope.items.length; i++)
				{
					var item = $scope.items[i];
					item.quantity *= 1;
					item.unitPrice *= 1;
					item.totalAmount = item.unitPrice * item.quantity;
				}
			}
			
		}
	}
}]);