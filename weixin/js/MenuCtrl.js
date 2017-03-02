module.controller('MenuCtrl', function($scope,$rootScope,$http,$stateParams,ObjectFactory,$ionicNavBarDelegate) {
    console.log($stateParams);

	$scope.year=$stateParams.year;
	$scope.item_id=$stateParams.item_id;

    $scope.takePicture=function(obj){
        // console.log(obj);
        // var file=$("input[type=file]");
        // file.val("");
		//
        // file.change(function(){
        //     var objUrl=$scope.getObjectURL(this.files[0]);
        //     obj.url=objUrl;
        //     console.log(obj);
        //     ObjectFactory.set('file',this.files[0]);
        //     ObjectFactory.set("info",obj);
        //     $scope.go('upload',{});
        // });
        // file.click();
		ObjectFactory.set("info",obj);
		$scope.go('upload',{});
    }

    var requestData=function(){
	    $http.post($scope.baseUrl+'/Home/home/menu.html',{item_id:$stateParams.item_id,year:$stateParams.year}).success(function(data){
	        $scope.item=data;
	        console.log($scope.item);
			$ionicNavBarDelegate.title($scope.year+"年"+data.item.name);
	    });
    }
    requestData();

});
