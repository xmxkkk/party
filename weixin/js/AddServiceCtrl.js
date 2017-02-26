module.controller('AddServiceCtrl', function($scope,$rootScope,$http,$stateParams,ObjectFactory) {
	$scope.init=function(){
		$scope.service={
			name:"",
			content:""
		}
	}

	var sortItem=function(){
         var temp=new Array();
         for(var i=0;i<$scope.service.userScorePictures.length;i++){
             if($scope.service.userScorePictures[i].ord!=3.14){
                 temp[temp.length]=$scope.service.userScorePictures[i];
             }
         }
         $scope.service.userScorePictures=temp;

         for(var i=0;i<$scope.service.userScorePictures.length;i++){
             for(var j=i;j<$scope.service.userScorePictures.length;j++){
                 if($scope.service.userScorePictures[i].ord>$scope.service.userScorePictures[j].ord){
                     var temp = $scope.service.userScorePictures[i];
                     $scope.service.userScorePictures[i] = $scope.service.userScorePictures[j];
                     $scope.service.userScorePictures[j] = temp;
                 }
             }
         }
         for(var i=0;i<$scope.service.userScorePictures.length;i++){
             $scope.service.userScorePictures[i].ord=i;
         }
     }
     var sendData=function(picture){
         sortItem();

         var data=new Array();
         for(var i=0;i<$scope.service.userScorePictures.length;i++){
             data[data.length]={id:$scope.service.userScorePictures[i].id,ord:$scope.service.userScorePictures[i].ord};
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
         console.log(obj);
         var file=$("input[type=file]");
         file.val("");

         file.change(function(){
             var objUrl=$scope.getObjectURL(this.files[0]);
             obj.url=objUrl;
             console.log(obj);
             ObjectFactory.set('file',this.files[0]);
             ObjectFactory.set("info",obj);
             $scope.go('upload',{});
         });
         file.click();
     }

	 $scope.submitService=function(){
		 var name=$scope.service.name;
		 var content=$scope.service.content;
		 if(name==null||name==''){
			 $scope.alert("服务名不能为空",function(){},true);
			 return;
		 }
		 $http.post($scope.baseUrl+'/Home/home/addService.html',{name:name,content:content}).success(function(data){
			 if(data.status==1){
				 $scope.back();
				 $scope.go('serviceDetail',{type:2,type_id:data.type_id});
			 }else{
				 $scope.alert(data.message,function(){},true);
			 }
		 });
	 }
});
