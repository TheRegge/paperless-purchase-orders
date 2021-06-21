'use strict';

angular.module('paperlesspo')
	.config( function ( $stateProvider ) {
		$stateProvider
		.state('site.pdf', {
			parent: 'site',
			url: '/pdf/:poid',
			data: {
				accessRoles: ['basic']
			},
			views: {
				'topbar@': {
					controller: 'TopbarController as topbar',
					templateUrl: 'app/topbar/topbar.tmpl.html'
				},
				'content@': {
					controller: 'PdfController as pdf',
					templateUrl: 'app/pdf/pdf.tmpl.html'
				}
			}
		})
	})
	.controller('PdfController', function ( utilities, $scope, $stateParams, po){

		var pdf              = this;
		pdf.poid             = $stateParams.poid;
		pdf.po               = po;
		pdf.downloadDisabled = true;
		pdf.downloadMsg      = "Preparing your pdf...";

		po.getPos(pdf.poid).then( function (pos) {
			pdf.pos = pos;
			pdf.po = pos[0];
			pdf.downloadDisabled = false

			if ( pdf.po ) {
				pdf.downloadMsg = 'Your PDF for Purchase Order #'+pdf.po.poNumber+' is ready!';
			}
		})
		
		pdf.pdfUrl = utilities.getApiUrl() + 'pdf/make/?poid=' + pdf.poid;
	});