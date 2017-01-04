module.controller('MenuCtrl', function($scope,$rootScope,$http,$stateParams,ObjectFactory) {
    console.log($stateParams);

    $scope.takePicture=function(obj){
        console.log(obj);
        var file=$("input[type=file]");
        file.val("");

        file.change(function(){
            var objUrl=$scope.getObjectURL(this.files[0]);
            obj.url=objUrl;
            console.log(obj);
            ObjectFactory.set('file',this.files[0]);
            $scope.go('upload',obj);
        });
        file.click();
    }

    var requestData=function(){
	    $http.post($scope.baseUrl+'/Home/home/menu.html',{item_id:$stateParams.item_id,title_id:$stateParams.title_id}).success(function(data){
	        $scope.item=data;
	        console.log($scope.item);
	    });
    }
    requestData();

});