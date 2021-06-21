'use strict';

angular.module('paperlesspo')
.factory('po',
	[
		'$http',
		'$q',
		'utilities',
		function poFactory ($http, $q, utilities) {

			return {
				getPos: function (poid) {
					var deferred = $q.defer();
					poid = undefined === poid ? '-1' : poid;

					$http({
						url     : utilities.getApiUrl()+'purchaseorders/get',
						method  : 'POST',
						data    : $.param({poid: poid}),
						headers : {'Content-Type': 'application/x-www-form-urlencoded'},
						timeout : 10000
					})
					.then(function (response) {
							var a, d, dr, pos;
							pos = response.data.purchaseOrders;

							if ( pos )
							{
								// Transfor values to clean js types
								// or add convenient parameters
								for (var i=0; i<pos.length; i++)
								{
									// Real JS date:
									d = new Date( pos[i].date);
									pos[i].date = d;
									
									if (pos[i].dateRequired)
									{
										dr = new Date(pos[i].dateRequired);
										pos[i].dateRequired = dr;
									}

									// Cast to Number:
									pos[i].totalAmount *= 1;

									// Add new param 'shortVendor'
									a = pos[i].vendor.split('<br>');
									pos[i].shortVendor = a[0];
								}
							}
							deferred.resolve( pos );
					});
					return deferred.promise;
				},

				deletePo: function (poNumber) {
					var deferred = $q.defer();
					$http({
						url: utilities.getApiUrl()+'purchaseorders/delete',
						method: 'POST',
						data: $.param({poNumber: poNumber}),
						headers : {'Content-Type': 'application/x-www-form-urlencoded'},
						timeout: 10000
					})
					.then(function (response) {
						deferred.resolve( response );
					});
					return deferred.promise;
				}
			}
		}
	]
)
;