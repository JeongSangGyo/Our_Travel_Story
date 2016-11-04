var plan = angular.module('plan', ['ui.router', 'ngAnimate', 'ngMessages', 'ngMaterial', 'ngFileUpload', 'btford.socket-io', 'ui.calendar', 'ngDragDrop', 'luegg.directives', 'ngTouch', 'angular-carousel']);

var serverBaseUrl = 'http://167.88.115.33:3000';

plan.factory('socket', function (socketFactory) {
    var myIoSocket = io.connect(serverBaseUrl);

    var socket = socketFactory({
        ioSocket: myIoSocket
    });

    return socket;
});

plan.factory('companionSearch', function ($http, $q, $rootScope) {
  return {
    getCompanion: function(typed_text, story_idx) {
      var url = "http://167.88.115.33/Plan/getRealtimeCompanion";
      var data = {'typed_text': typed_text, 'story_idx': story_idx};

      return $http.post(url, data).then(function(response) {
                if (typeof response.data === 'object') {
                  $rootScope.companion_search_info = response.data;
                  console.log(response);
                  return response.data;
                } else {
                  // invalid response
                  return $q.reject(response.data[0]);
                }
              }, function(response) {
                  // something went wrong
                  console.log($scope.story_idx);
                  return $q.reject(response.data[0]);
              });
            }
          };
});

plan.config(function( $stateProvider, $locationProvider, $urlRouterProvider ) {
    $stateProvider
        .state('index', {
            url: '/plan/:story_idx',
            views: {
                'otsPlan': {
                  controller: 'planController',
                  templateUrl: '/assets/views/plan.php'
                }
            }
        });

    $urlRouterProvider.otherwise('/plan');
    $locationProvider.html5Mode({
      enabled: true,
      requireBase: false
    });
});
