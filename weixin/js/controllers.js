angular.module('starter.controllers', [])

.controller('IndexCtrl', function($scope) {

})
.controller('TitleCtrl', function($scope,$http,$stateParams) {
    console.log($stateParams);
    $http.get($scope.baseUrl+'/Home/home/item/item_id/'+$stateParams.item_id+".html",{}).success(function(data){
        $scope.item=data;
        console.log($scope.item);
    });
})
.controller('MenuCtrl', function($scope,$http,$stateParams) {
    console.log($stateParams);
    $http.get($scope.baseUrl+'/Home/home/menu/item_id/'+$stateParams.item_id+"/title_id/"+$stateParams.title_id+".html",{}).success(function(data){
        $scope.item=data;
        console.log($scope.item);
    });
})
.controller('PreviewCtrl', function($scope,$http,$stateParams) {
    console.log($stateParams);
    $http.get($scope.baseUrl+'/Home/home/picture/title_id/'+$stateParams.title_id+"/menu_id/"+$stateParams.menu_id+".html",{}).success(function(data){
        $scope.item=data;
        console.log($scope.item);

        var html="";

        if($scope.item.pictures){
            for(var i=0;i<$scope.item.pictures.length;i++){
                if(i==0){
                    html += '<li style="display:block;"><div class="pinch-zoom"><img src="'+$scope.item.pictures[i].picture_path+'"/></div></li>';
                }else{
                    html += '<li style="display:none;"><div class="pinch-zoom"><img src="'+$scope.item.pictures[i].picture_path+'"/></div></li>';
                }
            }
            $("#slider ul").append(html);
            $('div.pinch-zoom').each(function () {
                new RTP.PinchZoom($(this), {});
            });

            window.mySwipe = new Swipe(document.getElementById('slider'), {
                speed: 400
            });
        }else{
            $(".page").append('<div class="list"><div class="item" style="text-align:center;border:0;">暂无资料！</div></div>');
        }
        

        
    });

   

})
;
