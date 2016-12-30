<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller {

	function __construct(){
	}

	function item($item_id=0){
		$item=M('item')->where(array('id'=>$item_id))->find();
		$item_titles=M('ItemTitle')->where(array('item_id'=>$item_id))->select();
		// $item_menus=M('ItemMenu')->where(array('item_id'=>$item_id))->select();

		$result=array();
		$result['item']=$item;
		$result['item_titles']=$item_titles;
		// $item['item_menus']=$item_menus;
		

		$this->ajaxReturn($result);
	}
	function menu($item_id=0,$title_id=0){
		$item_title=M('ItemTitle')->where(array('id'=>$title_id))->find();
		$item_menus=M('ItemMenu')->where(array('item_id'=>$item_id))->select();

		for($i=0;$i<count($item_menus);$i++){
			$cnt=M('ItemPicture')->where(array('menu_id'=>$item_menus[$i]['id'],'title_id'=>$title_id))->count();
			$item_menus[$i]['cnt']=$cnt;
		}

		$result=array();
		$result['item_title']=$item_title;
		$result['item_menus']=$item_menus;

		$this->ajaxReturn($result);
	}
	function picture($menu_id=0,$title_id=0){

		$pictures=M('ItemPicture')->where(array('menu_id'=>$menu_id,'title_id'=>$title_id))->select();
		for($i=0;$i<count($pictures);$i++){
			$pic=M('picture')->where(array('id'=>$pictures[$i]['picture_id']))->find();
			$pictures[$i]['picture_path']=$pic['path'];
		}
		$result=array();
		$result['pictures']=$pictures;
		$this->ajaxReturn($result);
	}

	function upload(){
		$title_id=I('post.title_id');
		$menu_id=I('post.menu_id');

		$Picture = D('Admin/Picture');
        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
        $info = $Picture->upload(
            $_FILES,
            C('PICTURE_UPLOAD'),
            C('PICTURE_UPLOAD_DRIVER'),
            C("UPLOAD_{$pic_driver}_CONFIG")
        );
        
     	M('ItemPicture')->data(array(
     		'title_id'		=>	$title_id,
     		'menu_id'		=>	$menu_id,
     		'picture_id'	=>	$info['fileList']['id']
     		))->add();

     	$this->ajaxReturn($info);
	}

}
