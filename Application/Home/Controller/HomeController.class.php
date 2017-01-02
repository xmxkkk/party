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
		parent::__construct();
		session('uid',1);
		session('isadmin',1);
	}
	private function user(){
		$uid=empty(session('uid'))?0:session('uid');
		$isadmin=empty(session('isadmin'))?0:session('isadmin');
		return array('uid'=>$uid,'isadmin'=>$isadmin);
	}
	private function islogin($level){
		$user=$this->user();
		$uid=$user['uid'];
		$isadmin=$user['isadmin'];

		$result=array();
		if($level=='USER'){
			if($uid==0){
				$result['status']=999;
				$result['message']='请先登录';
			}
		}else if($level=='ADMIN'){
			if($uid==0 || $isadmin==0){
				$result['status']=999;
				$result['message']='只有管理员才能操作';
			}
		}
		if(!empty($result)){
			$this->ajaxReturn($result);
		}else{
			return $user;
		}
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

		$result=$this->user();
		$result['item_title']=$item_title;
		$result['item_menus']=$item_menus;

		$this->ajaxReturn($result);
	}
	function picture($menu_id=0,$title_id=0){
		$pictures=M('ItemPicture')->where(array('menu_id'=>$menu_id,'title_id'=>$title_id))->order('ord')->select();

		for($i=0;$i<count($pictures);$i++){
			$pic=M('picture')->where(array('id'=>$pictures[$i]['picture_id']))->find();
			$pictures[$i]['picture_path']=$pic['path'];
			$pictures[$i]['ord']=$i;
			M('ItemPicture')->where(array('id'=>$id))->save(array('ord'=>$pictures[$i]['ord']));
		}

		$result=$this->user();
		$result['pictures']=$pictures;

		$this->ajaxReturn($result);
	}

	function upload(){
		$this->islogin('ADMIN');

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
     	$info['status']=0;
     	$info['message']="上传成功";

     	$this->ajaxReturn($info);
	}
	function bindidcard(){
		$idcard=I('post.idcard');
		$openid=I('post.openid');

		if(strlen($openid)==0 || strlen($idcard)==0){
			$result['status']=1;
			$result['message']="参数错误";
			$this->ajaxReturn($result);
		}

		$member2=D('Member2')->where(array('idcard'=>$idcard))->find();
		if(empty($member2)){
			$result['status']=2;
			$result['message']="没有改党员信息";
			$this->ajaxReturn($result);
		}
		
		$result=array();
		if(is_idcard($idcard)){
			$result['status']=0;
		}else{
			$result['status']=3;
			$result['message']="身份证号码错误";
		}

		$this->ajaxReturn($result);
	}
	function imageOp(){
		$this->islogin('ADMIN');

		$data=I('post.data');
		$title_id=intval(I('post.title_id'));
		$menu_id=intval(I('post.menu_id'));
		
		$data=json_decode($data);

		$arr=array();
		for($i=0;$i<count($data);$i++){
			$arr[]=$data[$i]->id;
		}
		$str=join(',',$arr);

		M('ItemPicture')->where(array('title_id'=>$title_id,'menu_id'=>$menu_id,'id'=>array('not in',$str)))->delete();

		for($i=0;$i<count($data);$i++){
			M('ItemPicture')->where(array('id'=>$data[$i]->id))->save(array('ord'=>$data[$i]->ord));
		}

		$result=array('status'=>0,'message'=>'更新成功');

		$this->ajaxReturn($result);
	}

}
