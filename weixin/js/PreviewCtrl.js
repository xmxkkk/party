module.controller('PreviewCtrl', function($scope,$http,$stateParams) {
    console.log($stateParams);
    var sortItem=function(){
        var temp=new Array();
        for(var i=0;i<$scope.item.pictures.length;i++){
            if($scope.item.pictures[i].ord!=3.14){
                temp[temp.length]=$scope.item.pictures[i];
            }
        }
        $scope.item.pictures=temp;

        for(var i=0;i<$scope.item.pictures.length;i++){
            for(var j=i;j<$scope.item.pictures.length;j++){
                if($scope.item.pictures[i].ord>$scope.item.pictures[j].ord){
                    var temp = $scope.item.pictures[i];
                    $scope.item.pictures[i] = $scope.item.pictures[j];
                    $scope.item.pictures[j] = temp;
                }
            }
        }
        for(var i=0;i<$scope.item.pictures.length;i++){
            $scope.item.pictures[i].ord=i;
        }
    }
    var sendData=function(picture){
        sortItem();

        var data=new Array();
        for(var i=0;i<$scope.item.pictures.length;i++){
            data[data.length]={id:$scope.item.pictures[i].id,ord:$scope.item.pictures[i].ord};
        }
        data=JSON.stringify(data);

        $http.post($scope.baseUrl+'/Home/home/imageOp.html',{data:data,menu_id:picture.menu_id}).success(function(data){
            console.log(data);
        });
    }
    $scope.imageOp=function(picture,type,id){
        console.log("picture");
        console.log(picture);
        if(type=='up'){
            picture.ord-=1.5;
            sendData(picture,type,id);
        }else if(type=='down'){
            picture.ord+=1.5;
            sendData(picture,type,id);
        }else if(type=='del'){
            $scope.confirm('确认删除',function(res){
                if(res){
                    picture.ord=3.14;
                    sendData(picture,type,id);
                }
            });
        }
    }

    $http.get($scope.baseUrl+"/Home/home/picture/menu_id/"+$stateParams.menu_id+".html",{}).success(function(data){
        $scope.item=data;
        console.log($scope.item);

        var html="";

        if($scope.item.pictures){

        }else{
            
        }

    });
});
