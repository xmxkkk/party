module.controller('ItemCtrl', function($scope,$rootScope,$http,$stateParams,$ionicNavBarDelegate) {
    console.log($stateParams);
	var viewTitle="";

    $rootScope.data={
        text:""
    };

    var sortItem=function(){
        var temp=new Array();
        for(var i=0;i<$scope.item.item_titles.length;i++){
            if($scope.item.item_titles[i].ord!=3.14){
                temp[temp.length]=$scope.item.item_titles[i];
            }
        }
        $scope.item.item_titles=temp;

        for(var i=0;i<$scope.item.item_titles.length;i++){
            for(var j=i;j<$scope.item.item_titles.length;j++){
                if($scope.item.item_titles[i].ord>$scope.item.item_titles[j].ord){
                    var temp = $scope.item.item_titles[i];
                    $scope.item.item_titles[i] = $scope.item.item_titles[j];
                    $scope.item.item_titles[j] = temp;
                }
            }
        }
        for(var i=0;i<$scope.item.item_titles.length;i++){
            $scope.item.item_titles[i].ord=i;
        }
    }
    var sendData=function(title){
        sortItem();

        var data=new Array();
        for(var i=0;i<$scope.item.item_titles.length;i++){
            data[data.length]={id:$scope.item.item_titles[i].id,ord:$scope.item.item_titles[i].ord,title:$scope.item.item_titles[i].title};
        }
        data=JSON.stringify(data);

        $http.post($scope.baseUrl+'/Home/home/titleOp.html',{data:data,title_id:title.id}).success(function(data){
            console.log(data);
        });
    }
    $scope.titleOp=function(title,type,id){
        console.log("title");
        console.log(title);
        if(type=='up'){
            title.ord-=1.5;
            sendData(title,type,id);
        }else if(type=='down'){
            title.ord+=1.5;
            sendData(title,type,id);
        }else if(type=='del'){
            $scope.confirm('确认删除<br>将删除所有相关资料！',function(res){
                if(res){
                    title.ord=3.14;
                    sendData(title,type,id);
                }
            });
        }else if(type=='edit'){
            $rootScope.data.text=title.title;
            $scope.showInput('输入标题',function(res){
                res=res||'';
                if(res){
                    console.log(res);
                    title.title=res;
                    sendData(title,type,id);
                }
            });
        }
    }

    var requestData=function(){
        $http.get($scope.baseUrl+'/Home/home/item/item_id/'+$stateParams.item_id+".html",{}).success(function(data){
            $scope.item=data;
			$ionicNavBarDelegate.title(data.item.name);
        });
    }

    $scope.addTitle=function(item_id){
        $rootScope.data.text='';

        $scope.showInput('新增'+$scope.item.item.name,function(res){
            res=res||'';
            if(res){
                $http.post($scope.baseUrl+'/Home/home/addTitle.html',{item_id:item_id,title:res}).success(function(data){
                    requestData();
                });
            }
        });
    }
	$scope.init=function(){
    	requestData();
	}

})
