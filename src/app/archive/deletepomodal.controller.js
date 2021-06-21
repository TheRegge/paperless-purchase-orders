'use strict';

angular.module('paperlesspo')
.controller( 'DeletePOmodalController', [
	'$uibModalInstance',
	'$rootScope',
	'$scope',
	'po',
	'poNumber',
	function ( $uibModalInstance, $rootScope, $scope, po, poNumber ) {
		$scope.poNumber     = poNumber;
		$scope.modalMessage = '<p>Do you really want to delete the Purchase Order number <strong>'+poNumber+'</strong>?</p>';

		$scope.closeModal = function () {
			$uibModalInstance.close();
		};

		$scope.deletePo = function ( poNumber ) {
			po.deletePo( poNumber )
			.then( function ( response ) {
				if ( response.data.deleted ) {
					$rootScope.$broadcast('poDeleted', {poNumber: poNumber});
					$uibModalInstance.close();
				}
				else
				{
					$scope.modalMessage = '<p class="text-danger">Something may have gone wrong while deleting the Purchase order. Plase write down the PO number ('+poNumber+') and contact the New Lab.</p>';
				}
			});
		}
}]);