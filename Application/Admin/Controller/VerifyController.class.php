<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 后台配置控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class VerifyController extends AdminController {
    public function __construct(){
        parent::__construct();
		$startYear=intval(C('SCORE_START_YEAR'));
        $endYear=date('Y');
        $years=array();
        for($i=$endYear;$i>=$startYear;$i--){
            $years[$i]=$i;
        }
        $this->assign('years',$years);

        C('LIST_ROWS',20);
    }

	function eventData(){
		$type=I('type');
		$year=I('year');
		$uid=I('uid');

		$map=[];
		$map['year']=$year;

		$events=M('Event')->where($map)->select();

		for($i=0;$i<count($events);$i++){
			$events[$i]['userScore']=M('UserScore')->where(['type'=>0,'type_id'=>$events[$i]['id'],'uid'=>$uid])->select();
		}

		// $this->assign('list',$events);

		$this->ajaxReturn($events);
	}
    public function index(){
        /* 查询条件初始化 */
        $map = array();

		$type=I('type');

		if($type==0){
			//义务
			$members=M('Member2')->select();
			$this->assign('members',$members);

			$year=I('year');
			$uid=intval(I('uid'));
			$this->assign('uid',$uid);

			if(!$year){
				$year=date('Y');
				$this->redirect('Verify/index', array('type' => 0,'year'=>$year,'uid'=>$uid), 0, '页面跳转中...');
			}
			$map['year']=$year;
			$this->assign('currYear',$year);


			$list=M('Event')->where($map)->select();
			for($i=0;$i<count($list);$i++){
				if(0!=$uid){
					$list[$i]['userScore']=M('UserScore')->where(['type'=>0,'type_id'=>$list[$i]['id'],'uid'=>$uid])->find();
				}
			}

			$page='eventIndex';
		}else if($type==1){
			$map=['type'=>$type];

			$map['type']=1;
			$map['status']=['in','1,3'];
			$list = $this->lists('UserScore', $map,'id');
			$this->taskCombines($list);

			$page='taskIndex';
		}else if($type==2){
			$map=['type'=>$type];

			$map['type']=2;
			$map['status']=['in','1,3'];
			$list = $this->lists('UserScore', $map,'id');
			$this->serviceCombines($list);

			$page='serviceIndex';
		}

		$this->assign('list', $list);
        $this->meta_title = '审核';

		$this->display($page);
    }
	function taskCombine(&$task){
		$id=$task['id'];
		$taskId=$task['type_id'];
		$uid=$task['uid'];
		$task['task']=M('task')->where(['id'=>$taskId])->find();
		$task['user']=M('member2')->where(['uid'=>$uid])->find();
		$userScorePictures=M('userScorePicture')->where(['user_score_id'=>$id])->select();
		for($i=0;$i<count($userScorePictures);$i++){
			$pic=M('picture')->where(['id'=>$userScorePictures[$i]['picture_id']])->find();
			$userScorePictures[$i]['picture_path']=$pic['path'];
		}
		$task['userScorePictures']=$userScorePictures;
	}
	function taskCombines(&$list){
		for($i=0;$i<count($list);$i++){
			$this->taskCombine($list[$i]);
		}
	}
	function serviceCombine(&$service){
		$id=$service['id'];
		$serviceId=$service['type_id'];
		$uid=$service['uid'];
		$service['service']=M('service')->where(['id'=>$serviceId])->find();
		$service['user']=M('member2')->where(['uid'=>$uid])->find();
		$userScorePictures=M('userScorePicture')->where(['user_score_id'=>$id])->select();
		for($i=0;$i<count($userScorePictures);$i++){
			$pic=M('picture')->where(['id'=>$userScorePictures[$i]['picture_id']])->find();
			$userScorePictures[$i]['picture_path']=$pic['path'];
		}
		$service['userScorePictures']=$userScorePictures;
	}
	function serviceCombines(&$list){
		for($i=0;$i<count($list);$i++){
			$this->serviceCombine($list[$i]);
		}
	}
	function verifyTask(){
		$id=intval(I('id'));
		$userScore=M('userScore')->where(['id'=>$id])->find();
		$this->taskCombine($userScore);
		$this->assign('info',$userScore);
		if(IS_POST){
			$verify=I('verify');
			$type_id=$userScore['type_id'];
			$task=M('task')->where(['id'=>$type_id])->find();
			if(empty($task)){
				$this->error('任务不存在，审核失败！',U('verify/index',['type'=>$userScore['type']]));
			}
			if($userScore['status']==2){
				$this->error('任务已经审核过，无需重复审核！',U('verify/index',['type'=>$userScore['type']]));
			}
			if($verify==1){


				$score=$task['score'];
				$uid=$userScore['uid'];

				M('UserScore')->where(['id'=>$id])->save([
					'status'=>2,
					'score'=>$score,
					'verifytime'=>date('Y-m-d H:i:s'),
					'admin_id'	=>UID
				]);
				M('Member2')->where(['uid'=>$uid])->setInc('score',$score);

			}else if($verify==0){
				M('UserScore')->where(['id'=>$id])->save(['status'=>3]);
			}

			$this->success('审核成功',U('verify/index',['type'=>$userScore['type']]));
		}else{
			$this->display();
		}
	}
	function verifyService(){
		$id=intval(I('id'));
		$userScore=M('userScore')->where(['id'=>$id])->find();
		$this->serviceCombine($userScore);
		$this->assign('info',$userScore);
		if(IS_POST){
			$verify=I('verify');
			$score=intval(I('score'));

			if($score<=0&&$verify==1){
				$this->error('积分错误，请输入正确的积分');
			}

			$type_id=$userScore['type_id'];
			$service=M('service')->where(['id'=>$type_id])->find();
			if(empty($service)){
				$this->error('服务不存在，审核失败！',U('verify/index',['type'=>$userScore['type']]));
			}
			if($userScore['status']==2){
				$this->error('服务已经审核过，无需重复审核！',U('verify/index',['type'=>$userScore['type']]));
			}
			if($verify==1){
				$uid=$userScore['uid'];
				M('UserScore')->where(['id'=>$id])->save([
					'status'=>2,
					'score'=>$score,
					'verifytime'=>date('Y-m-d H:i:s'),
					'admin_id'	=>UID
				]);
				M('Member2')->where(['uid'=>$uid])->setInc('score',$score);

			}else if($verify==0){
				M('UserScore')->where(['id'=>$id])->save(['status'=>3]);
			}

			$this->success('审核成功',U('verify/index',['type'=>$userScore['type']]));
		}else{
			$this->display();
		}
	}
	function verifyEvent(){
		$uid=intval(I('uid'));
		$id=intval(I('id'));

		$userScore=M('UserScore')->where(['type'=>0,'type_id'=>$id,'uid'=>$uid])->find();
		if($userScore && $userScore['status']==2){
			$this->error("义务已经审核过，无需重复审核！");
		}

		if(!$userScore){
			M('UserScore')->add([
				'uid'		=>	$uid,
				'type'		=>	0,
				'type_id'	=>	$id,
				'score'		=>	0,
				'status'	=>	0,
				'addtime'	=>	date('Y:m:s H:i:s')
			]);
		}

		$event=M('event')->where(['id'=>$id])->find();

		$score=$event['score'];
		$uid=$userScore['uid'];

		M('UserScore')->where(['uid'=>$uid,'type'=>0,'type_id'=>$id])->save([
			'status'	=>2,
			'score'		=>$score,
			'verifytime'=>date('Y-m-d H:i:s'),
			'admin_id'	=>UID
		]);
		M('Member2')->where(['uid'=>$uid])->setInc('score',$score);

		$this->success('审核成功');
	}

}
