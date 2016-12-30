// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.services' is found in services.js
// 'starter.controllers' is found in controllers.js
angular.module('starter', ['ionic', 'starter.controllers','starter.base', 'starter.services'])

.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if (window.cordova && window.cordova.plugins && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
      cordova.plugins.Keyboard.disableScroll(true);

    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
  });
})

.config(function($stateProvider, $urlRouterProvider) {

  // Ionic uses AngularUI Router which uses the concept of states
  // Learn more here: https://github.com/angular-ui/ui-router
  // Set up the various states which the app can be in.
  // Each state's controller can be found in controllers.js
  $stateProvider
  .state('index', {
    url: '/index',
    abstract: false,
    templateUrl: 'templates/index.html',
    controller: 'IndexCtrl'
  })
  .state('title', {
    url: '/title/:item_id',
    abstract: false,
    templateUrl: 'templates/title.html',
    controller: 'TitleCtrl'
  })
  .state('menu', {
    url: '/menu/:title_id/:item_id',
    abstract: false,
    templateUrl: 'templates/menu.html',
    controller: 'MenuCtrl'
  })
  .state('preview', {
    url: '/preview/:title_id/:menu_id',
    abstract: false,
    templateUrl: 'templates/preview.html',
    controller: 'PreviewCtrl'
  })
  .state('upload', {
    url: '/upload/:title_id/:menu_id/:url',
    abstract: false,
    templateUrl: 'templates/upload.html',
    controller: 'UploadCtrl'
  })
  
  ;

  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/title/2');

});
