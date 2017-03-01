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

.config(function($stateProvider, $urlRouterProvider,$httpProvider) {

  // Ionic uses AngularUI Router which uses the concept of states
  // Learn more here: https://github.com/angular-ui/ui-router
  // Set up the various states which the app can be in.
  // Each state's controller can be found in controllers.js
  $stateProvider
  .state('index', {
    url: '/index',
    abstract: false,
    templateUrl: 'templates/index.html',
    controller: 'IndexCtrl',
    cache:'false'
  })
  .state('item', {
    url: '/item/:item_id',
    abstract: false,
    templateUrl: 'templates/item.html',
    controller: 'ItemCtrl',
    cache:'false'
  })
  .state('menu', {
    url: '/menu/:item_id/:year',
    abstract: false,
    templateUrl: 'templates/menu.html',
    controller: 'MenuCtrl',
    cache:false
  })
  .state('preview', {
    url: '/preview/:menu_id',
    abstract: false,
    templateUrl: 'templates/preview.html',
    controller: 'PreviewCtrl',
    cache:'false'
  })
  .state('upload', {
    url: '/upload',
    abstract: false,
    templateUrl: 'templates/upload.html',
    controller: 'UploadCtrl',
    cache:'false'
  })

  .state('bindidcard', {
    url: '/bindidcard',
    abstract: false,
    templateUrl: 'templates/bindidcard.html',
    controller: 'BindidcardCtrl',
    cache:'false'
  })
  .state('core', {
    url: '/core',
    abstract: false,
    templateUrl: 'templates/core.html',
    controller: 'CoreCtrl',
    cache:'false'
  })
  .state('score', {
    url: '/score/:year',
    abstract: false,
    templateUrl: 'templates/Score.html',
    controller: 'ScoreCtrl',
    cache:'false'
  })
  .state('eventDetail', {
    url: '/eventDetail/:type/:type_id',
    abstract: false,
    templateUrl: 'templates/EventDetail.html',
    controller: 'EventDetailCtrl',
    cache:'false'
  })
  .state('taskDetail', {
    url: '/taskDetail/:type/:type_id',
    abstract: false,
    templateUrl: 'templates/TaskDetail.html',
    controller: 'TaskDetailCtrl',
    cache:'false'
  })
  .state('serviceDetail', {
    url: '/serviceDetail/:type/:type_id',
    abstract: false,
    templateUrl: 'templates/ServiceDetail.html',
    controller: 'ServiceDetailCtrl',
    cache:'false'
  })
  .state('addService', {
    url: '/addService',
    abstract: false,
    templateUrl: 'templates/addService.html',
    controller: 'AddServiceCtrl',
    cache:'false'
  })
  .state('login', {
    url: '/login',
    abstract: false,
    templateUrl: 'templates/login.html',
    controller: 'LoginCtrl',
    cache:'false'
  })
  .state('article', {
    url: '/article/:article_id',
    abstract: false,
    templateUrl: 'templates/article.html',
    controller: 'ArticleCtrl',
    cache:'false'
  })
  .state('articleDetail', {
    url: '/articleDetail/:id',
    abstract: false,
    templateUrl: 'templates/articleDetail.html',
    controller: 'ArticleDetailCtrl',
    cache:'false'
  })

  ;

  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/index');

  $httpProvider.defaults.transformRequest=function(obj){
    var str=[];
    for(var p in obj){
      str.push(encodeURIComponent(p)+"="+encodeURIComponent(obj[p]));
    }
    return str.join("&");
  };
  $httpProvider.defaults.headers.post={
    'Content-Type':'application/x-www-form-urlencoded'
  }
});
