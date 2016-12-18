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
class TaskController extends AdminController {
    public function __construct(){
        parent::__construct();
        $this->assign('groups',M('group')->select());
    }
    
    public function combine(&$task){
        $task['status_name']=get_task_status()[$task['status']];

        if($task['handleuids']){
            $members=M('Member2')->where('id in ('.$task['handleuids'].')')->select();
            $handlemembers=[];
            for($i=0;$i<count($members);$i++){
                $handlemembers[]=$members[$i];
            }
            $task['handlemembers']=$handlemembers;
        }

        $group=M('group')->where(['id'=>$task['gid']])->find();
        if($group){
            $task['group_name']=$group['name'];
        }else{
            $task['group_name']='全部小组';
        }
        
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

        $list = $this->lists('Task', $map,'id');
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
            $Task = D('Task');
            $data = $Task->create();
            if($data){
                if($Task->add()){
                    S('DB_TASK_DATA',null);
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Task->getError());
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
            $Task = D('Task');
            $data = $Task->create();
            if($data){
                if($Task->save()){
                    S('DB_TASK_DATA',null);
                    //记录行为
                    action_log('update_task','task',$data['id'],UID);
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Task->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Task')->field(true)->find($id);

            if(false === $info){
                $this->error('获取积分任务信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑积分任务';
            $this->display();
        }
    }

    public function handle($id=0){
        if(IS_POST){
            if(empty(I('handleuids'))){
                $this->error('请选择完成人员！');
                return;
            }
            $uids=I('handleuids');

            $handleuids=join(",",I('handleuids'));
            $_POST['handleuids']=$handleuids;
            $_POST['status']=1;
            $_POST['handletime']=date("Y-m-d H:i:s");
            
            $size=count($uids);
            $score=I('score');

            $Task = D('Task');
            $data = $Task->create();
            if($data){
                if($Task->save()){
                    for($i=0;$i<$size;$i++){
                        $userEvent=M('user_event');
                        $userEvent->data([
                                'uid'       =>  $uids[$i],
                                'addtime'   =>  date("Y-m-d H:i:s"),
                                'third_id'  =>  $id,
                                'remark'    =>  I('remark'),
                                'type'      =>  1,
                                'score'     =>  $score,
                                'status'    =>  0,
                                'mid'       =>  0
                            ]);
                        $userEvent->add();
                        M('Member2')->where(['id'=>$uids[$i]])->setInc('score',$score);
                    }/**/
                       // echo $userEvent->getLastSql();

                    S('DB_TASK_DATA',null);
                    //记录行为
                    action_log('update_task','task',$data['id'],UID);
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Task->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Task')->field(true)->find($id);

            $map=[];
            if($info['gid']){
                $map['groupid']=$info['gid'];
            }
            $members=M('Member2')->where(['status'=>0])->join('party_group ON party_member2.groupid = party_group.id')->where($map)->getField("party_member2.id,party_member2.realname,party_group.name");

            if(false === $info){
                $this->error('获取积分任务信息错误');
            }
            $this->assign('members',$members);
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

        $map = array('id' => array('in', $id) ,'status'=>array('in',[1]));

        $task=M('Task')->where($map)->select();
        if($task){
            $this->error('删除失败！改任务已经完成。');
        }
        $map = array('id' => array('in', $id));

        if(M('Task')->where($map)->delete()){
            //记录行为
            action_log('update_task', 'task', $id, UID);
            $this->success('删除成功');
        } else {
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
        $res=M('Task')->where($map)->data(['status'=>$status])->save();
        if($res){
            $this->success('更新成功');
        }else{
            $this->error('参数错误');
        }


    }

}