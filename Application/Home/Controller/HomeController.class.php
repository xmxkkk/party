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
		// session('uid',0);
		// session('isleader',0);
		// session('isadmin',0);
	}
	private function user(){
		$uid=empty(session('uid'))?0:session('uid');
		$isleader=empty(session('isleader'))?0:session('isleader');
		$isadmin=empty(session('isadmin'))?0:session('isadmin');

		return array('uid'=>$uid,'isleader'=>$isleader,'isadmin'=>$isadmin);
	}
	private function islogin($level){
		$user=$this->user();
		$uid=$user['uid'];
		$isleader=$user['isleader'];
		$isadmin=$user['isadmin'];

		$result=array();
		if($level=='USER'){
			if($uid==0){
				$result['status']=999;
				$result['message']='请先登录';
			}
		}else if($level=='LEADER'){
			if($uid==0 || $isleader==0){
				$result['status']=999;
				$result['message']='只有组长才能操作';
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
	function index(){
		$this->islogin('USER');

		$items=M('item')->select();

		$this->ajaxReturn(['status'=>1,'data'=>$items]);
	}
	function login(){
		$phone=I('phone');
		$member=M('member2')->where(array('phone'=>$phone))->find();
		if(empty($member)){
			$this->ajaxReturn(['status'=>0,'message'=>'没有该帐号']);
		}

		session('uid',$member['id']);
		// session('isleader',1);
		session('isadmin',$member['is_admin']);
		// $uid=empty(session('uid'))?0:session('uid');
		// $isleader=empty(session('isleader'))?0:session('isleader');
		// $isadmin=empty(session('isadmin'))?0:session('isadmin');

		$this->ajaxReturn(['status'=>1,'message'=>'登录成功']);
	}
	function core(){
		$this->islogin('USER');

		$user=$this->user();
		$uid=$user['uid'];
		$member2=M('Member2')->where(array('id'=>$uid))->find();
		$member2['idcard']=null;
		$member2['password']=null;
		$member2['phone']=null;
		$member2['openid']=null;

		$picture=M('Picture')->where(array('id'=>$member2['header']))->find();
		$member2['header_path']=$picture['path'];

		$age=intval(date('Y'))-intval(substr($member2['birthday'], 0,4));
		$member2['age']=$age;

		$group=M('Group')->where(array('id'=>$member2['groupid']))->find();
		$member2['group_name']=$group['name'];


		$this->ajaxReturn($member2);
	}
	function scoreMenus(){
		$scoreStartYearConfig=D('config')->where(array('name'=>'SCORE_START_YEAR'))->find();

		$scoreStartYear=intval($scoreStartYearConfig['value']);
		$scoreEndYear=intval(date('Y'));

		$scoreMenus=array();
		for($i=$scoreEndYear;$i>=$scoreStartYear;$i--){
			$scoreMenus[]=array("name"=>$i."年积分事件","year"=>$i);
		}

		$this->ajaxReturn($scoreMenus);
	}
	function score(){
		$user=$this->user();
		$uid=$user['uid'];

		$type=intval(I('post.type'));
		$year=intval(I('post.year'));
		if($type==0){
			//义务分
			$events=D('event')->where(array('year'=>$year))->select();
			for($i=0;$i<count($events);$i++){
				$type_id=$events[$i]['id'];
				$events[$i]['userScore']=D('userScore')->where(array('uid'=>$uid,'type'=>$type,'type_id'=>$type_id))->find();
			}
			$temp=array();
			for($i=0;$i<count($events);$i++){
				if($events[$i]['userScore']&&$events[$i]['userScore']['status']==2){
					$temp[]=$events[$i];
				}
			}
			for($i=0;$i<count($events);$i++){
				if(!($events[$i]['userScore']&&$events[$i]['userScore']['status']==2)){
					$temp[]=$events[$i];
				}
			}
			$events=$temp;

			$this->ajaxReturn($events);
		}else if($type==1){
			//任务分
			$tasks=D('task')->where(array('year'=>$year))->select();
			for($i=0;$i<count($tasks);$i++){
				$type_id=$tasks[$i]['id'];
				$tasks[$i]['userScore']=D('userScore')->where(array('uid'=>$uid,'type'=>$type,'type_id'=>$type_id))->find();
			}

			$temp=array();
			for($i=0;$i<count($tasks);$i++){
				if($tasks[$i]['userScore']&&$tasks[$i]['userScore']['status']==2){
					$temp[]=$tasks[$i];
				}
			}
			for($i=0;$i<count($tasks);$i++){
				if(!($tasks[$i]['userScore']&&$tasks[$i]['userScore']['status']==2)){
					$temp[]=$tasks[$i];
				}
			}
			$tasks=$temp;

			$this->ajaxReturn($tasks);
		}else if($type==2){
			//服务分
			$services=D('service')->where(array('year'=>$year,'uid'=>$uid))->select();
			for($i=0;$i<count($services);$i++){
				$type_id=$services[$i]['id'];
				$services[$i]['userScore']=D('userScore')->where(array('uid'=>$uid,'type'=>$type,'type_id'=>$type_id))->find();
			}

			$temp=array();
			for($i=0;$i<count($services);$i++){
				if($services[$i]['userScore']&&$services[$i]['userScore']['status']==2){
					$temp[]=$services[$i];
				}
			}
			for($i=0;$i<count($services);$i++){
				if(!($services[$i]['userScore']&&$services[$i]['userScore']['status']==2)){
					$temp[]=$services[$i];
				}
			}
			$services=$temp;

			$this->ajaxReturn($services);
		}
	}
	function eventDetail(){
		$user=$this->user();
		$uid=$user['uid'];

		$type_id=intval(I('post.type_id'));

		$us=D('userScore')->where(array('uid'=>$uid,'type'=>0,'type_id'=>$type_id))->find();
		if(!$us){
			D('userScore')->add(array(
				"type"		=>	0,
				"uid"		=>	$uid,
				"type_id"	=>	$type_id,
				"score"		=>	0,
				"status"	=>	0,
				"addtime"	=>	date("Y-m-d H:i:s")
				));
		}

		$event=D('event')->where(array('id'=>$type_id))->find();
		$event['userScore']=D('userScore')->where(array('uid'=>$uid,'type'=>0,'type_id'=>$type_id))->find();

		$this->ajaxReturn($event);/**/
	}
	function taskDetail(){
		$user=$this->user();
		$uid=$user['uid'];

		$type_id=intval(I('post.type_id'));

		$us=D('userScore')->where(array('uid'=>$uid,'type'=>1,'type_id'=>$type_id))->find();
		if(!$us){
			D('userScore')->add(array(
				"type"		=>	1,
				"uid"		=>	$uid,
				"type_id"	=>	$type_id,
				"score"		=>	0,
				"status"	=>	0,
				"addtime"	=>	date("Y-m-d H:i:s")
				));
		}

		$event=D('task')->where(array('id'=>$type_id))->find();
		$event['userScore']=D('userScore')->where(array('uid'=>$uid,'type'=>1,'type_id'=>$type_id))->find();

		if($event['userScore']){
			$userScoreId=$event['userScore']['id'];
			$userScorePictures=D('userScorePicture')->where(array("user_score_id"=>$userScoreId))->order('ord')->select();
			for($i=0;$i<count($userScorePictures);$i++){
				$picture_id=$userScorePictures[$i]['picture_id'];
				$pic=D('picture')->where(array('id'=>$picture_id))->find();
				$userScorePictures[$i]['picture_path']=$pic['path'];
				$userScorePictures[$i]['ord']=$i;

				M('userScorePicture')->where(array('id'=>$userScorePictures[$i]['id']))->save(array('ord'=>$userScorePictures[$i]['ord']));
			}
			$event['userScorePictures']=$userScorePictures;
		}else{
			$event['userScorePictures']=[];
		}

		$this->ajaxReturn($event);
	}
	function serviceDetail(){
		$user=$this->user();
		$uid=$user['uid'];

		$type_id=intval(I('post.type_id'));

		$us=D('userScore')->where(array('uid'=>$uid,'type'=>2,'type_id'=>$type_id))->find();
		if(!$us){
			D('userScore')->add(array(
				"type"		=>	2,
				"uid"		=>	$uid,
				"type_id"	=>	$type_id,
				"score"		=>	0,
				"status"	=>	1,
				"addtime"	=>	date("Y-m-d H:i:s")
				));
		}

		$service=D('service')->where(array('id'=>$type_id))->find();
		$service['userScore']=D('userScore')->where(array('uid'=>$uid,'type'=>2,'type_id'=>$type_id))->find();

		if($service['userScore']){
			$userScoreId=$service['userScore']['id'];
			$userScorePictures=D('userScorePicture')->where(array("user_score_id"=>$userScoreId))->order('ord')->select();
			for($i=0;$i<count($userScorePictures);$i++){
				$picture_id=$userScorePictures[$i]['picture_id'];
				$pic=D('picture')->where(array('id'=>$picture_id))->find();
				$userScorePictures[$i]['picture_path']=$pic['path'];
				$userScorePictures[$i]['ord']=$i;

				M('userScorePicture')->where(array('id'=>$userScorePictures[$i]['id']))->save(array('ord'=>$userScorePictures[$i]['ord']));
			}
			$service['userScorePictures']=$userScorePictures;
		}else{
			$service['userScorePictures']=[];
		}

		$this->ajaxReturn($service);
	}


	function verifyTask(){
		$user_score_id=intval(I('post.user_score_id'));

		$userScore=M('UserScore')->where(array('id'=>$user_score_id))->find();

		if($userScore['status']==2){
			$result['status']=2;
			$result['message']="已经审核通过";
			$this->ajaxReturn($result);
		}

		$userScorePictures=M('UserScorePicture')->where(array('user_score_id'=>$user_score_id))->select();

		$result=array();
		if(!$userScorePictures){
			$result['status']=1;
			$result['message']="请提交相关资料！";
			$this->ajaxReturn($result);
		}
		//0未完成，1等待，2审核通过，3审核没有通过
		M('UserScore')->where(array('id'=>$user_score_id))->save(array(
				'status'	=>	1
			));

		$result['status']=0;
		$result['message']="提交成功，等待审核！";
		$this->ajaxReturn($result);
	}

	function item($item_id=0){
		$item=M('item')->where(array('id'=>$item_id))->find();

		$startYear=intval($item['year']);
		$endYear=date('Y');

		$temp=array();
		for($i=$endYear;$i>=$startYear;$i--){
			$temp[]=['name'=>$i."年".$item['name'],'year'=>$i];
		}

		$result=array();
		$result['item']=$item;
		$result['item_titles']=$temp;

		$this->ajaxReturn($result);
	}
	function articleDetail(){
		$id=intval(I('id'));
		$new=M('news')->where(array('id'=>$id))->find();

		$picture=M('picture')->where(array('id'=>$new['cover']))->find();
		if(empty($picture)){
			$new['is_cover']=0;
			$new['cover_path']=null;
		}else{
			$new['is_cover']=1;
			$new['cover_path']=$picture['path'];
		}

		$item=M('item')->where(array('id'=>$new['article_id']))->find();

		$result=array();
		$result['new_']=$new;
		$result['item']=$item;

		$this->ajaxReturn($result);
	}
	function article($article_id=0){
		$pageNo=intval(I('pageNo'));
		$len=4;

		$item=M('item')->where(array('id'=>$article_id,'type'=>'article'))->find();

		$news=M('news')->where(array('article_id'=>$article_id,'type'=>3))->order('is_top desc')->limit($pageNo*$len.",".$len)->select();
		for($i=0;$i<count($news);$i++){
			$picture=M('picture')->where(array('id'=>$news[$i]['cover']))->find();
			if(empty($picture)){
				$news[$i]['is_cover']=0;
			}else{
				$news[$i]['is_cover']=1;
				$news[$i]['cover_path']=$picture['path'];
			}

			$news[$i]['summary']=strip_tags($news[$i]['content']);
            if(mb_strlen($news[$i]['summary'])>100){
                $news[$i]['summary']=mb_substr($news[$i]['summary'], 0,100);
            }
		}

		if(empty($news)){
			$news=[];
		}

		$result=array();
		$result['news']=$news;
		$result['item']=$item;

		$this->ajaxReturn($result);
	}
	function menu(){
		$item_id=I('post.item_id');
		$year=intval(I('post.year'));

		$item=M('item')->where(array('id'=>$item_id))->find();
		$item_menus=M('ItemMenu')->where(array('item_id'=>$item_id,'year'=>$year))->order('ord')->select();

		for($i=0;$i<count($item_menus);$i++){
			$cnt=M('ItemPicture')->where(array('menu_id'=>$item_menus[$i]['id'],'title_id'=>$title_id))->count();
			$item_menus[$i]['cnt']=$cnt;
		}

		$result=$this->user();
		$result['item']=$item;
		$result['item_menus']=$item_menus;

		$this->ajaxReturn($result);
	}
	function picture($menu_id=0){
		$itemMenu=M('itemMenu')->where(array('id'=>$menu_id))->find();

		$pictures=M('ItemPicture')->where(array('menu_id'=>$menu_id))->order('ord asc,addtime asc')->select();

		for($i=0;$i<count($pictures);$i++){
			$pic=M('picture')->where(array('id'=>$pictures[$i]['picture_id']))->find();
			$pictures[$i]['picture_path']=$pic['path'];
			$pictures[$i]['ord']=$i;
			M('ItemPicture')->where(array('id'=>$id))->save(array('ord'=>$pictures[$i]['ord']));
		}

		$result=$this->user();
		$result['pictures']=$pictures;
		$result['itemMenu']=$itemMenu;

		$this->ajaxReturn($result);
	}
	function upload(){
		$info=I('post.data');
		$info=json_decode($info);

		if($info->type=='menu'){
			$this->islogin('ADMIN');
		}else if($info->type=='task' || $info->type=='service'){
			$this->islogin('USER');
		}else{
			echo 2;
		}

		$data=$info->data;

		$filename=md5($data);

		$picture=D('Admin/Picture')->where(array("md5"=>$filename))->find();
		if(empty($picture)){
			$image = base64_decode(str_replace("data:image/jpeg;base64,", "", $data));
			$dir="./Uploads/Picture/".date('Y-m-d')."/";
			mkdir($dir);

			$filepath=$dir.substr($filename,0,16).".jpg";
			file_put_contents($filepath, $image);

			$md5=$filename;
			$sha1=sha1_file($filepath);

			$id=M('picture')->add(array(
				'path'		=>	substr($filepath,1),
				'url'		=>	'',
				'md5'		=>	$md5,
				'sha1'		=>	$sha1,
				'status'	=>	1,
				'create_time'=>	time()
			));

			$picture=D('Admin/Picture')->where(array('id'=>$id))->find();
		}

		if($info->type=='menu'){
			$title_id=$info->title_id;
			$menu_id=$info->menu_id;
			M('ItemPicture')->data(array(
	     		'menu_id'		=>	$menu_id,
	     		'picture_id'	=>	$picture['id'],
				'ord'			=>	10000,
				'addtime'		=>	date('Y-m-d H:i:s')
	     		))->add();
		}else if($info->type=='task' || $info->type=='service'){
			$userScoreId=$info->userScoreId;
			M('UserScorePicture')->add(array(
				'user_score_id'		=>	$userScoreId,
				'picture_id'		=>	$picture['id'],
				'ord'				=>	0,
				'addtime'			=>	date("Y-m-d H:i:s")
				));
		}
		$upload=array();
     	$upload['status']=0;
     	$upload['message']="上传成功";
     	$upload['info']=$picture;

     	$this->ajaxReturn($upload);



		// $image = base64_decode(str_replace('data:image/jpeg;base64,' '', $info));
		// file_put_contents('text.jpg', $image);
	}
	/*
	function upload(){
		$info=I('post.info');
		$info=json_decode($info);

		if($info->type=='menu'){
			$this->islogin('LEADER');
		}else if($info->type=='task' || $info->type=='service'){
			$this->islogin('USER');
		}

		$user=$this->user();
		$uid=$user['uid'];

		$Picture = D('Admin/Picture');
        $pic_driver = C('PICTURE_UPLOAD_DRIVER');
        $upload = $Picture->upload(
            $_FILES,
            C('PICTURE_UPLOAD'),
            C('PICTURE_UPLOAD_DRIVER'),
            C("UPLOAD_{$pic_driver}_CONFIG")
        );
		file_put_contents('a.log',json_encode($Picture->error));
        if($info->type=='menu'){
			$title_id=$info->title_id;
			$menu_id=$info->menu_id;
			M('ItemPicture')->data(array(
	     		'menu_id'		=>	$menu_id,
	     		'picture_id'	=>	$upload['fileList']['id'],
				'ord'			=>	10000,
				'addtime'		=>	date('Y-m-d H:i:s')
	     		))->add();
		}else if($info->type=='task' || $info->type=='service'){
			$userScoreId=$info->userScoreId;
			M('UserScorePicture')->add(array(
				'user_score_id'		=>	$userScoreId,
				'picture_id'		=>	$upload['fileList']['id'],
				'ord'				=>	0,
				'addtime'			=>	date("Y-m-d H:i:s")
				));
		}

     	$upload['status']=0;
     	$upload['message']="上传成功";
     	$upload['info']=$info;

     	$this->ajaxReturn($upload);
	}*/
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
	function imageOpTask(){
		$this->islogin('USER');

		$data=I('post.data');
		$user_score_id=intval(I('post.user_score_id'));

		$data=json_decode($data);

		$arr=array();
		for($i=0;$i<count($data);$i++){
			$arr[]=$data[$i]->id;
		}
		$str=join(',',$arr);

		M('UserScorePicture')->where(array('user_score_id'=>$user_score_id,'id'=>array('not in',$str)))->delete();

		for($i=0;$i<count($data);$i++){
			M('UserScorePicture')->where(array('id'=>$data[$i]->id))->save(array('ord'=>$data[$i]->ord));
		}

		$result=array('status'=>0,'message'=>'更新成功');

		$this->ajaxReturn($result);
	}
	function imageOp(){
		$this->islogin('ADMIN');

		$data=I('post.data');
		$menu_id=intval(I('post.menu_id'));

		$data=json_decode($data);

		$arr=array();
		for($i=0;$i<count($data);$i++){
			$arr[]=$data[$i]->id;
		}
		$str=join(',',$arr);

		M('ItemPicture')->where(array('menu_id'=>$menu_id,'id'=>array('not in',$str)))->delete();

		for($i=0;$i<count($data);$i++){
			M('ItemPicture')->where(array('id'=>$data[$i]->id))->save(array('ord'=>$data[$i]->ord));
		}

		$result=array('status'=>0,'message'=>'更新成功');

		$this->ajaxReturn($result);
	}

	function titleOp(){
		$this->islogin('ADMIN');

		$data=I('post.data');
		$title_id=intval(I('post.title_id'));

		$data=json_decode($data);

		$arr=array();
		for($i=0;$i<count($data);$i++){
			$arr[]=$data[$i]->id;
		}
		$str=join(',',$arr);

		M('ItemTitle')->where(array('id'=>array('not in',$str)))->delete();

		M('ItemPicture')->where(array('title_id'=>array('not in',$str)))->delete();

		for($i=0;$i<count($data);$i++){
			M('ItemTitle')->where(array('id'=>$data[$i]->id))->save(array('ord'=>$data[$i]->ord,'title'=>$data[$i]->title));
		}

		$this->ajaxReturn(array('status'=>0,'message'=>'更新成功'));
	}
	function addTitle(){
		$this->islogin('ADMIN');
		$item_id=intval(I('post.item_id'));
		$title=I('title');

		$ord=1+M('ItemTitle')->where(array('item_id'=>$item_id))->max('ord');


		$id=M('ItemTitle')->data(array(
			'item_id'		=>	$item_id,
			'title'			=>	$title,
			'ord'			=>	$ord
		))->add();


		$this->ajaxReturn(array('status'=>0,'message'=>'更新成功'));
	}
	function addService(){
		$this->islogin('USER');
		$user=$this->user();
		$uid=$user['uid'];

		$name=I('name');
		$content=I('content');

		$services=M('Service')->where(array('name'=>$name,'uid'=>$uid))->select();
		if(count($services)>0){
			$this->ajaxReturn(['status'=>0,'message'=>'服务已经提交过，不要重复提交。']);
			return;
		}

		$id=M('Service')->add([
			'name'		=>	$name,
			'content'	=>	$content,
			'score'		=>	0,
			'year'		=>	date('Y'),
			'addtime'	=>	date('Y-m-d H:i:s'),
			'uid'		=>	$uid
		]);
		$userScoreId=0;
		if($id>0){
			$userScoreId=M('UserScore')->add([
				'uid'		=>	$uid,
				'type'		=>	2,
				'type_id'	=>	$id,
				'score'		=>	0,
				'status'	=>	0,
				'addtime'	=>	date('Y-m-d H:i:s')
			]);
		}
		$this->ajaxReturn(['status'=>1,'userScoreId'=>$userScoreId,'type_id'=>$id]);
	}

}
