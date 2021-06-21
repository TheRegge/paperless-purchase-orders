'use strict';

angular.module('paperlesspo')
	.config(function ($stateProvider){
		$stateProvider
		.state( 'site.orderform', {
			parent: 'site',
			url: '/orderform',
			data: {
				accessRoles: ['staff','faculty', 'admin' ]
			},
			views: {
				'topbar@': {
					controller: 'TopbarController as topbar',
					templateUrl: 'app/topbar/topbar.tmpl.html'
				},
				'content@': {
					controller: 'OrderformController as orderform',
					templateUrl: 'app/orderform/orderform.tmpl.html'
				}
			}
		})
	})
	.controller('OrderformController', [
		'$http',
		'$location',
		'utilities',
		'$filter',
		'$sce',
		'$state',
		'$stateParams',
		'principal',
	 function ($http, $location, utilities, $filter, $sce, $state, $stateParams, principal) {
		var d, t, s,
		// paramSessionId,
		ci_session,
		ci_session_id;

		var orderform = this;
		orderform.test = 'This is the test for <strong> Order Form</strong>';

		// Ladda buttons
		orderform.formLoading = false;

		// PO Number Generator
		// ==================
		var makePoNumber = function() {
			var d,t,po;
			d  = new Date();
			t  = d.getTime();
			s  = String(t);
			po = '1' + s.substring(6);
			return po;
		}

		// If true, enables fake data button:
		orderform.testing = utilities.isTesting();

		// orderform.filterByDepartment = '';

		// FORM General Information
		orderform.info              = {};
		// orderform.info.department   = '';
		orderform.info.poNumber     = makePoNumber();
		orderform.info.vendor       = '';
		orderform.info.shipTo       = '';
		orderform.info.date         = null;
		orderform.info.dateRequired = null;
		// orderform.info.budgets      = [];
		orderform.info.remarks      = '';
		orderform.info.teacher      = '';
		orderform.info.divDirector  = '';

		orderform.showMe = function(n) {
			if (n > 0) {
				return 'dlt-show';
			} else {
				return 'dlt-hide';
			}
		};

		orderform.note = '<p class="dlt-loud"><strong>All invoices and statements shoud be sent to the individual placing the order with a copy to 108 East 89th St. New York, N.Y. 10128, <span class="text-error">attention accounts payable</span></strong> or by email to <strong>AccountsPayable@dalton.org</strong> . Our purchase order number must appear on all correspondence, invoices &amp; packages. Notify us immediatly if unable to ship complete order by date specified.</p>';

		// Departments:
		// -----------
		$http.get( utilities.getApiUrl() + 'main/departments')
		.success(function(data){
			orderform.departments = data;
		});

		// Available budgets
		// -----------------
		// $http.get( utilities.apiUrl() + 'main/budgets')
		// .success(function(data){
		// 	orderform.budgets = data;
		// });

		// orderform.getBudgets = function (budgetId) {

		// 	if ( budgetId )
		// 	{
		// 		return _.filter(orderform.budgets, function (budget){
		// 			return budget.budgetId === budgetId;
		// 		});
		// 	}
		// 	else if ( orderform.filterByDepartment )
		// 	{
		// 		return _.filter(orderform.budgets, function (budget){
		// 			return budget.departmentId === orderform.filterByDepartment;
		// 		});
		// 	}
		// 	else
		// 	{
		// 		return orderform.budgets;
		// 	}
		// }

		// Ship to Locations:
		// -----------------
		$http.get( utilities.getApiUrl() + 'main/shiptolocations')
		.success(function(data){
			orderform.shipToLocations = data;
		});

		// alerts
		// array of objects { type: '', msg: '' }
		orderform.alerts = [];

		orderform.closeAlert = function ($index) {
			orderform.alerts.splice($index, 1);
		};

		orderform.makeInt = function( value ) {
			if ( ! value ) { return 0; } else { return value; }
		};

		// DATE PICKER
		// ===========
		
		orderform.clear = function() {
			orderform.info.date = null;
			orderform.info.dateRequired = null;
		};

		orderform.toggleMin = function() {
			orderform.minDate = orderform.minDate ? null : new Date();
		};
		orderform.toggleMin();

		orderform.open = function($event, cal) {	
			$event.preventDefault();
			$event.stopPropagation();
			if ( cal === 'dt' )
			{
				orderform.openeddt = true;
			}
			else if ( cal === 'dtr' )
			{
				orderform.openeddtr = true;
			}
		};

		// orderform.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'yyyy-MM-dd', 'shortDate'];
		orderform.format = 'M/d/yyyy';

		orderform.dateOptions = {
			format: orderform.format,
			startingDay: 1
		};

		/**
		 * Init first calendar
		 * @return {undefined}
		 */
		orderform.today = function() {
			orderform.info.date = $filter('date')(new Date(), orderform.format);
		};
		orderform.today();

		// SSID
		// ====
		orderform.ssid = principal.user().ssid;

		// FORM ITEMS
		// ==========
		orderform.items                     = [];
		orderform.addItemQuantity           = 1;
		orderform.addItemUnitPrice          = 0;
		orderform.addItemCatalogNo          = '';
		orderform.addItemDescription        = '';
		orderform.addItemPrice              = orderform.addQuantity;
		orderform.budgets                   = [];
		orderform.shippingAndHandling       = 0;
		orderform.shippingAndHandlingBudget = false;
		orderform.disabled                  = orderform.items.length;

		// Add known vendors for type ahead functionality
		// (https://angular-ui.github.io/bootstrap/#/typeahead)
		$http.get( utilities.getApiUrl() + 'vendors')
		.success(function(data){
			orderform.vendors = data;
		});

		orderform.fakeData = function() {
			console.log('===FAKE DATA===');
			orderform.info.vendor         = chance.last() + '.Inc<br>' + chance.address() + '<br>' + chance.city() + ' ' + chance.state() + ' ' + chance.zip();
			orderform.info.shipTo         = 2;
			var i = Math.floor(Math.random()*6);
			orderform.info.department     = orderform.departments[i];
			orderform.info.remarks        = chance.paragraph({sentences: 7 });
			orderform.info.teacher        = chance.name();
			orderform.info.divDirector    = chance.name();
			orderform.shippingAndHandling = chance.natural({min: 1, max: 30});
			// Test data generator
			var itemNumber = Math.floor(Math.random()*17);
			var budgetNumber = chance.natural({min: 999999, max: 99999999});
			
			while ( itemNumber > 0 )
			{
				orderform.addItemQuantity    = chance.natural({min: 1, max: 5 });
				orderform.currentBudget      = budgetNumber;
				orderform.addItemCatalogNo   = chance.natural({min: 1, max: 9999 });
				orderform.addItemDescription = chance.sentence({ words: 5 } );
				orderform.addItemUnitPrice   = chance.floating({min: 1, max: 100, fixed: 2});
				orderform.addItem();
				itemNumber--;
			}
		};

		var clearAddItemForm = function() {
			orderform.filterByDepartment = '';
			orderform.addItemQuantity    = 1;
			orderform.addItemUnitPrice   = 0;
			orderform.addItemCatalogNo   = '';
			orderform.addItemDescription = '';
		}

		var addLineBreaks = function (s) {
			s = escape(s);
			for(var i=0; i<s.length; i++)
			{
				if ( s.indexOf('%0D%0A') > -1 )
				{
					// Windows \r\n hex
					s = s.replace('%0D0A', '<br>');
				}
				if ( s.indexOf('%0A') > -1 )
				{
					// Unix \n hex
					s = s.replace('%0A', '<br>');
				}
				if ( s.indexOf('%0D') > -1 )
				{
					s = s.replace('%0D', '<br>');
				}
			}
			s = unescape(s);
			return s;
		}

		/**
		 * 17 item lines max on purchase order
		 * @return {boolean} true is disabled
		 */
		orderform.getAddItemDisabled = function() {
			if (
				orderform.items.length > 16 ||
				orderform.addItemQuantity <= 0 ||
				orderform.currentBudget == '' ||
				!orderform.currentBudget ||
				orderform.addItemDescription == '' ||
				!orderform.addItemDescription
			) { return true; } else { return false; }
		}

		/**
		 * Don't submit if no items
		 * @return {boolean} true is disabled
		 */
		orderform.getSubmitDisabled = function() {
			if (orderform.items.length) return false;
			return true;
		}

		orderform.removeItem = function ($index) {
			orderform.items.splice($index, 1);
		};

		orderform.addItem = function() {
			var item         = {
				budgetNumber : orderform.currentBudget,
				quantity     : orderform.addItemQuantity,
				catalogNo    : orderform.addItemCatalogNo,
				description  : orderform.addItemDescription,
				unitPrice    : orderform.addItemUnitPrice
			}
			orderform.items.push(item);
			// Associate shipping & handling to the first budget number by default:
			if ( ! orderform.shippingAndHandlingBudget )
			{
				orderform.shippingAndHandlingBudget = orderform.currentBudget;
			}
			clearAddItemForm();
		};

		orderform.calculateTotalAmount = function() {
			var total = 0;
			orderform.items.forEach( function(item) {
				total = total + item.unitPrice * item.quantity;
			});
			total = total + orderform.shippingAndHandling*1
			return total;
		};

		orderform.inputMax = function (input, max) {
			var s = String(input);
			var l = input.length;

			orderform.itemDescriptionClass = 'label-default';

			if (l === 0)
			{
				orderform.itemDescriptionClass = '';
			}
			if (l > max - 20 )
			{
				orderform.itemDescriptionClass = 'label-warning';
			}
			if (l > max - 10 )
			{
				orderform.itemDescriptionClass = 'label-danger';
			}
			if (l > max)
			{
				orderform.addItemDescription = orderform.addItemDescription.substring(0,max-1);
			}
			orderform.displayCount = l + '/' + max;
		};

		orderform.submit = function($event) {
			$event.preventDefault();
			orderform.alerts = [];

			// Convert default formatted date into regular
			// js date for good data submission
			var date = new Date(orderform.info.date);

			var errorMsg = '<ul>';
			var alert;
			var required = [
				'vendor',
				'shipTo',
				'teacher',
				'date',
				'divDirector'
			];
			var requiredErrorCounter = 0;
			// Required fields in orderform.info
			for ( var item in orderform.info )
			{
				if ( undefined != item && ! orderform.info[item] )
				{
					if ( required.indexOf( item ) >= 0 )
					{
						errorMsg += '<li><strong>' + item + '</strong> is required.</li>';
						requiredErrorCounter++;
					}
				}
			}
			// Other required fields
			if ( ! orderform.selectedDepartment )
			{
				errorMsg += '<li><strong>Department</strong> is required.</li>';
			}
			errorMsg += '</ul>';
			if ( requiredErrorCounter > 0 )
			{
				errorMsg = '<h3>Please fix the following</h3>' + errorMsg;
				alert = { type: 'danger', msg: $sce.trustAsHtml(errorMsg) };
				orderform.alerts.push(alert);
				return;
			}
			else
			{
				// Start button animation
				orderform.formLoading = true;
				// Prepare data
				var po = {
					poNumber                  : orderform.info.poNumber,
					ssid                      : orderform.ssid,
					date                      : date,
					dateRequired              : orderform.info.dateRequired,
					department_id             : orderform.selectedDepartment,
					vendor                    : addLineBreaks(orderform.info.vendor),
					shipTo                    : orderform.info.shipTo,
					teacher                   : orderform.info.teacher,
					divDirector               : orderform.info.divDirector,
					remarks                   : addLineBreaks(orderform.info.remarks),
					shippingAndHandling       : orderform.shippingAndHandling,
					shippingAndHandlingBudget : orderform.shippingAndHandlingBudget,
					items                     : orderform.items,
					totalAmount               : orderform.calculateTotalAmount()
				};
				var data = angular.toJson(po);
				data     = $.param({'po': data});
				// Save vendor
				$http({
					url: utilities.getApiUrl()+'vendors/save_user_vendor',
					method: 'POST',
					data: $.param({'ssid': orderform.ssid, 'vendor':orderform.info.vendor}),
					headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				});
				
				// save PO
				$http({
					url: utilities.getApiUrl() + 'purchaseorders/save',
					method: 'POST',
					data: data,
					headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				})
				.then(
					//Success
					function(response) {
						//Stop button animation
						orderform.formLoading = false;

						if ( response.data.success && response.data.poId )
						{
							$state.go( 'site.pdf', { 'test': 'blah', poid: response.data.poId } );
						}
						else
						{
							console.log('something went wrong, no success or no poId...' );
						}
					},
					//Error
					function(response) {
						//Stop button animation
						orderform.formLoading = false;
						console.log('error');
					}
				);
			}
		};
	}])
;