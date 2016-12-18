var module=angular.module('starter.services',[]);

module.factory('Item',[
    '$rootScope',
    function($rootScope){
    var items=[{id:1,name:'张小泉',checked:false},{id:2,name:'小德子',checked:false},{id:3,name:'小小',checked:false}];

    return {
        all:function(){
            return items;
        },
        add:function(item){
            items.push(item);
        },
        get:function(id){
            for(i=0;i<items.length;i++){
                if(items[i].id==id){
                    return items[i];
                }
            }
            return null;
        }
    };
}])
.factory('ObjectFactory',[function(){
    var _obj=[];
    return {
        set:function(key,obj){
            _obj[key]=obj;
        },
        get:function(key){
            return _obj[key];
        }
    }
}])
;

