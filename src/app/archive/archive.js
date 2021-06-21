'use strict';

angular.module('paperlesspo')
	.config(function ( $stateProvider ){
		$stateProvider
		.state( 'site.archive', {
			parent: 'site',
			url: '/archive',
			data: {
				accessRoles: ['staff', 'faculty', 'admin' ]
			},
			views: {
				'topbar@': {
					controller: 'TopbarController as topbar',
					templateUrl: 'app/topbar/topbar.tmpl.html'
				},
				'content@': {
					controller: 'ArchiveController as archive',
					templateUrl: 'app/archive/archive.tmpl.html'
				}
			}
		})
	})
	.controller('ArchiveController', [ 
		'$rootScope',
		'$scope',
		'$uibModal',
		'po',
		'principal',
		'utilities',
		function ( $rootScope, $scope, $uibModal, po, principal, utilities ) {
			var archive   = this;
			$scope.appUrl = utilities.getApiUrl();
			
			
			po.getPos().then( function (pos) {
				$scope.pos          = pos;
				$scope.displayedPos = [].concat($scope.pos);
				$scope.itemsByPage  = 10;
			});

			$rootScope.$on('poDeleted', function ( event, data ) {
				var deletedPoNumber = data.poNumber,
				i = 0, l = $scope.pos.length;

				for (i; i<l; i++ )
				{
					if ( $scope.pos[i].poNumber === deletedPoNumber )
					{
						$scope.pos.splice( i, 1 );
						return;
					}
				}
			});

			$scope.requestDeletePO = function ( poNumber ) {
				$scope.modalInstance = $uibModal.open({
					controller: 'DeletePOmodalController',
					size: 'md',
					templateUrl: 'app/archive/deletepomodal.tmpl.html',
					resolve: {
						poNumber: function () {
							return poNumber;
						}
					}
				});
			}
	}])
;