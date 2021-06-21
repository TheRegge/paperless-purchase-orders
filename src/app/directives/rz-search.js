angular.module('smart-table')
    .directive('rzSearch', ['$timeout', function ($timeout) {
        return {
            require: '^stTable',
            scope: {
                predicate: '=?rzSearch'
            },
            link: function (scope, element, attr, ctrl) {
                var tableCtrl = ctrl;
                var promise = null;
                var throttle = attr.stDelay || 400;

                scope.$watch('predicate', function (newValue, oldValue) {
                    if (newValue !== oldValue) {
                        ctrl.tableState().search = {};
                        //hack

                        var filteredValue = element[0].value.replace('$', '');
                        tableCtrl.search(filteredValue || '', newValue);
                        // hacked: tableCtrl.search(element[0].value || '', newValue);
                        

                        filteredValue = filteredValue.replace(',','');
                        var filteredValue = filteredValue.split('.0')[0];

                        // Date? 
                        var dateArr = filteredValue.split('/');
                        console.log('Date Arr');
                        console.log(dateArr);
                        if (dateArr.length === 3)
                        {
                            var year  = dateArr[2];
                            var month = dateArr[0];
                            var day   = dateArr[1];

                            if ( year.length == 2 )
                            {
                                 year = '20' + year;
                            }
                            if ( year != '' && month !== '' && day != '' )
                            {
                                filteredValue = new Date( year, month, day);
                                filteredValue = filteredValue.valueOf();
                                console.log("filtering with value:" + filteredValue);
                            }
                        }

                    }
                });

                //table state -> view
                scope.$watch(function () {
                    return ctrl.tableState().search;
                }, function (newValue, oldValue) {
                    var predicateExpression = scope.predicate || '$';
                    if (newValue.predicateObject && newValue.predicateObject[predicateExpression] !== element[0].value) {
                        element[0].value = newValue.predicateObject[predicateExpression] || '';
                    }
                }, true);

                // view -> table state
                element.bind('input', function (evt) {
                    evt = evt.originalEvent || evt;
                    if (promise !== null) {
                        $timeout.cancel(promise);
                    }
                    promise = $timeout(function () {
                        //hack
                        console.log('test 2');
                        var filteredValue = evt.target.value.replace('$','');
                        filteredValue = filteredValue.replace(',','');
                        var filteredValue = filteredValue.split('.0')[0];

                        // Date? 
                        var dateArr = filteredValue.split('/');
                        console.log('Date Arr');
                        console.log(dateArr);
                        if (dateArr.length === 3)
                        {
                            var year  = dateArr[2];
                            var month = dateArr[0];
                            var day   = dateArr[1];

                            if ( year.length == 2 )
                            {
                                 year = '20' + year;
                            }
                            if ( year != '' && month !== '' && day != '' )
                            {
                                filteredValue = new Date( year, month, day);
                                filteredValue = filteredValue.valueOf();
                                console.log(filteredValue);
                            }
                        }
                        tableCtrl.search(filteredValue, scope.predicate || '');
                        //hacked: tableCtrl.search(evt.target.value, scope.predicate || '');
                        promise = null;
                    }, throttle);
                });
            }
        };
    }]);
