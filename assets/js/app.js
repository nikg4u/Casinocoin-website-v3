var casinocoin = angular.module('myApp', ['ui.bootstrap', 'ngRoute', 'ngtweet']);

casinocoin.controller('SearchController', function ($scope, $http, $window, $location) {

    $scope.sendMail = function (emailName, listName) {
        var data = {name: emailName, list: listName};
        $http.post('http://localhost/personal/casinocoin/src/inc/mail.php', data).then(function (data) {
        console.log(data);
            alert("Sent successfully");
        })
    }

    $scope.options = ["General", "Developer"]
    $scope.list = $scope.options[0];

    function NavBarCtrl($scope) {
        $scope.isCollapsed = true;
    }
    
    $scope.$on('$viewContentLoaded', function(event) {
    	$window.ga('send', 'pageview', { page: $location.url() });
    });
    

});

casinocoin.config(['$routeProvider', '$locationProvider',
    function ($routeProvider, $locationProvider) {
        $routeProvider.when('/', {
            title: 'Home',
            templateUrl: 'src/views/home.php',
            controller: 'SearchController'
        }).when('/about-casinocoin', {
            title: 'About',
            templateUrl: 'src/views/about-casinocoin.php',
            controller: 'SearchController'
        }).when('/casinocoin-resources', {
                title: 'About',
                templateUrl: 'src/views/casinocoin-resources.php',
                controller: 'SearchController'
            })
            .when('/casinocoin-wallet', {
                title: 'About',
                templateUrl: 'src/views/getting-started/casinocoin-wallet.php',
                controller: 'SearchController'
            })
            .when('/casinocoin-node', {
                title: 'About',
                templateUrl: 'src/views/getting-started/casinocoin-node.php',
                controller: 'SearchController'
            })
            .when('/casinocoin-integration', {
                title: 'About',
                templateUrl: 'src/views/getting-started/casinocoin-integrate.php',
                controller: 'SearchController'
            })
            .when('/casinocoin-foundation', {
                title: 'About',
                templateUrl: 'src/views/getting-started/casinocoin-foundation.php',
                controller: 'SearchController'
            })
            .when('/slack', {
                title: 'About',
                templateUrl: 'src/views/slack.php',
                controller: 'SearchController'
            })
            .otherwise({
                redirectTo: '/'
            });

        if (window.history && window.history.pushState) {

            $locationProvider.html5Mode(true);
        }
    }
]);

casinocoin.run(function ($rootScope, $location) {
    $rootScope.$on('$routeChangeSuccess', function(){
        ga('send', 'pageview', $location.path());
    });
});