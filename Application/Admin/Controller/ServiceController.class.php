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
class ServiceController extends AdminController {
    public function __construct(){
        parent::__construct();
        $this->assign('groups',M('group')->select());

        $startYear=intval(C('SCORE_START_YEAR'));
        $endYear=date('Y');
        $years=array();
        for($i=$endYear;$i>=$startYear;$i--){
            $years[$i]=$i;
        }
        $this->assign('years',$years);
    }

    public function combine(&$service){
        // $service['status_name']=get_task_status()[$service['status']];
		$service['member']=M('Member2')->where(array('uid'=>$service['uid']))->find();

    }
    public function combines(&$list){
        for($i=0;$i<count($list);$i++){
            $this->combine($list[$i]);
        }
    }/**/
    /**
     * 配置管理
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        /* 查询条件初始化 */
        $map = array();
/*
        if(I('groupid')){
            $map['groupid']=I('groupid');
        }
        if(I('stage')){
            $map['stage']=I('stage');
        }*/

        $list = $this->lists('Service', $map,'id');
        $this->combines($list);

        $this->assign('list', $list);
        $this->meta_title = '积分任务管理';
        $this->display();
    }

    /**
     * 新增配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if(IS_POST){
            $Service = D('Service');
            $data = $Service->create();
            if($data){
                if($Service->add()){
                    S('DB_TASK_DATA',null);
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Service->getError());
            }
        } else {
            $this->meta_title = '新增积分任务';
            $this->assign('info',null);
            $this->display('edit');
        }
    }

    /**
     * 编辑配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function edit($id = 0){
        if(IS_POST){
            $Service = D('Service');
            $data = $Service->create();
            if($data){
                if($Service->save()){
                    S('DB_TASK_DATA',null);
                    //记录行为
                    action_log('update_service','task',$data['id'],UID);
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Service->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Service')->field(true)->find($id);

            if(false === $info){
                $this->error('获取积分任务信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑积分任务';
            $this->display();
        }
    }

    /**
     * 删除频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

		$id=$id[0];

		$task_condi=['type'=>2,'type_id'=>$id,'status'=>2];

		$userScore=M('userScore')->where($task_condi)->select();
		if($userScore){
			$this->error('删除失败！该服务已经完成。');
		}

		if(M('Service')->where(['id'=>$id])->delete()){
			M('UserScore')->where(['type'=>2,'type_id'=>$id])->delete();
			action_log('update_service', 'service', $id, UID);
            $this->success('删除成功');
		}else{
			$this->error('删除失败！');
		}

    }

    public function setStatus($model='Task'){
        $ids    =   I('request.ids');

        if ( empty($ids) ) {
            $this->error('请选择要操作的数据!');
        }

        $status=I('status');

        $map = array('id' => array('in', $ids) );
        $res=M('Service')->where($map)->data(['status'=>$status])->save();
        if($res){
            $this->success('更新成功');
        }else{
            $this->error('参数错误');
        }


    }

}
