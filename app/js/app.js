// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
angular.module('starter', ['ionic', 'starter.controllers'])

.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if (window.cordova && window.cordova.plugins.Keyboard) {
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
  $stateProvider

  .state('app', {
    url: '/app',
    abstract: true,
    templateUrl: 'templates/menu.html',
    controller: 'AppCtrl',
    cache:false
  })

  .state('app.member', {
    url: '/member/:md5',
    views: {
      'menuContent': {
        templateUrl: 'templates/member.html'
      }
    },
    controller:'MemberCtrl',
    cache:false
  })
  .state('app.score', {
    url: '/score/:md5',
    views: {
      'menuContent': {
        templateUrl: 'templates/score.html'
      }
    },
    controller:'ScoreCtrl',
    cache:false
  })
  .state('app.scoreItem', {
    url: '/scoreItem/:md5/:type',
    views: {
      'menuContent': {
        templateUrl: 'templates/scoreItem.html'
      }
    },
    controller:'ScoreItemCtrl',
    cache:false
  })
  .state('app.addScore', {
    url: '/addScore/:md5/:type/:eventId',
    views: {
      'menuContent': {
        templateUrl: 'templates/addScore.html'
      }
    },
    controller:'AddScoreCtrl',
    cache:false
  })
  .state('app.scoreRecord', {
    url: '/scoreRecord',
    views: {
      'menuContent': {
        templateUrl: 'templates/scoreRecord.html'
      }
    },
    controller:'ScoreRecordCtrl',
    cache:false
  })
  .state('app.task', {
    url: '/task',
    views: {
      'menuContent': {
        templateUrl: 'templates/task.html'
      }
    },
    controller:'TaskCtrl',
    cache:false
  })
  .state('app.news',{
    url: '/news/:type',
    views: {
      'menuContent': {
        templateUrl: 'templates/news.html'
      }
    },
    controller:'NewsCtrl',
    cache:false
  })
  .state('app.resetPass',{
    url: '/resetPass',
    views: {
      'menuContent': {
        templateUrl: 'templates/resetPass.html'
      }
    },
    controller:'ResetPassCtrl'
  })
  .state('app.topn',{
    url: '/topn',
    views: {
      'menuContent': {
        templateUrl: 'templates/topn.html'
      }
    },
    controller:'TopnCtrl',
    cache:false
  })
  .state('app.new',{
    url: '/new/:id',
    views: {
      'menuContent': {
        templateUrl: 'templates/new.html'
      }
    },
    controller:'NewCtrl'
  })
  ;
  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/app/news/2');

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
