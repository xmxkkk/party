module.controller('EventDetailCtrl', function($scope,$rootScope,$http,$stateParams,ObjectFactory) {
   var type_id=$stateParams.type_id;

   $http.post($scope.baseUrl+'/Home/home/eventDetail.html',{type_id:type_id}).success(function(data){
        $scope.event=data;
   });
});