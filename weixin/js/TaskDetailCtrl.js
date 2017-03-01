module.controller('TaskDetailCtrl', function($scope,$rootScope,$http,$stateParams,ObjectFactory,$ionicLoading) {
   var type_id=$stateParams.type_id;

   $scope.init=function(){
	   $http.post($scope.baseUrl+'/Home/home/taskDetail.html',{type_id:type_id}).success(function(data){
	        $scope.task=data;
	   });
	   console.log("init");
   }

   var sortItem=function(){
        var temp=new Array();
        for(var i=0;i<$scope.task.userScorePictures.length;i++){
            if($scope.task.userScorePictures[i].ord!=3.14){
                temp[temp.length]=$scope.task.userScorePictures[i];
            }
        }
        $scope.task.userScorePictures=temp;

        for(var i=0;i<$scope.task.userScorePictures.length;i++){
            for(var j=i;j<$scope.task.userScorePictures.length;j++){
                if($scope.task.userScorePictures[i].ord>$scope.task.userScorePictures[j].ord){
                    var temp = $scope.task.userScorePictures[i];
                    $scope.task.userScorePictures[i] = $scope.task.userScorePictures[j];
                    $scope.task.userScorePictures[j] = temp;
                }
            }
        }
        for(var i=0;i<$scope.task.userScorePictures.length;i++){
            $scope.task.userScorePictures[i].ord=i;
        }
    }
    var sendData=function(picture){
        sortItem();

        var data=new Array();
        for(var i=0;i<$scope.task.userScorePictures.length;i++){
            data[data.length]={id:$scope.task.userScorePictures[i].id,ord:$scope.task.userScorePictures[i].ord};
        }
        data=JSON.stringify(data);

        $http.post($scope.baseUrl+'/Home/home/imageOpTask.html',{data:data,user_score_id:picture.user_score_id}).success(function(data){
            console.log(data);
        });
    }
    $scope.imageOp=function(picture,type,id){
        console.log("picture");
        console.log(picture);
        if(type=='up'){
            picture.ord-=1.5;
            sendData(picture);
        }else if(type=='down'){
            picture.ord+=1.5;
            sendData(picture);
        }else if(type=='del'){
            $scope.confirm('确认删除',function(res){
                if(res){
                    picture.ord=3.14;
                    sendData(picture);
                }
            });
        }
    }

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

    $scope.submitVerify=function(user_score_id){
    	$http.post($scope.baseUrl+'/Home/home/verifyTask.html',{user_score_id:user_score_id}).success(function(data){
            console.log(data);
            $scope.alert(data.message,function(res){
            	if(data.status==0){
                	$scope.back();
                }
            },data.status!=0);
        });
    }
});
