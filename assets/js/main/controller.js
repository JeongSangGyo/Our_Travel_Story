main.controller('indexController', ['$scope', '$location', '$http', '$window', function( $scope, $location, $http, $window ) {
  $scope.effect = 'slideright';
  $scope.pageClass = 'main';
  $scope.page = 1;

  $scope.toPlan = function() {
    console.log('cc');
    $location.path('/plan');
  };
}]);

main.controller('startController', ['$scope', '$location', '$http', '$mdDialog', function( $scope, $location, $http, $mdDialog ) {
  $scope.showLogin = function(ev) {
    $mdDialog.show({
      templateUrl: 'login.html',
      parent: angular.element(document.body),
      targetEvent: ev,
      clickOutsideToClose:true
    })
    .then(function(answer) {
      $scope.status = 'You said the information was "' + answer + '".';
    }, function() {
      $scope.status = 'You cancelled the dialog.';
    });
  };
}]);

main.controller('loginController', ['$scope', '$http', '$mdDialog', function( $scope, $http, $mdDialog ) {
  $scope.toLogin = function() {
    console.log("Cyka");
  };
}]);

main.controller('planController', ['$scope', '$http', function( $scope, $http ) {
  $scope.effect = 'slideright';
  $scope.pageClass = 'main';
  $scope.page = 1;

  console.log('cyka');
}]);
