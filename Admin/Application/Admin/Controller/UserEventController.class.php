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
class UserEventController extends AdminController {
    public function __construct(){
        parent::__construct();
        $this->assign('groups',M('group')->select());
        C('LIST_ROWS',20);
    }
    
    public function combine(&$userEvent){
        if($userEvent['type']==0){
            $event=M('Event')->where(['id'=>$userEvent['third_id']])->find();
            $userEvent['name']=$event['name'];
            $userEvent['content']=$event['content'];
        }else if($userEvent['type']==1){
            $task=M('Task')->where(['id'=>$userEvent['third_id']])->find();
            $userEvent['name']=$task['name'];
            $userEvent['content']=$task['content'];
        }
        $member=M('Member2')->where(['id'=>$userEvent['uid']])->find();
        $userEvent['realname']=$member['realname'];
        $userEvent['userscore']=$member['score'];
        $userEvent['type_name']=get_user_event_type($userEvent['type']);
        $userEvent['status_name']=[0=>'有效',1=>'过期',2=>'撤销'][$userEvent['status']];
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

        if(I('uid')){
            $map['uid']=I('uid');
        }

        $list = $this->lists('user_event', $map,'id desc');


        $this->combines($list);

        $this->assign('list', $list);
        $this->meta_title = '积分事件管理';
        $this->display();
    }

    /**
     * 新增配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if(IS_POST){
            $Event = D('Event');
            $data = $Event->create();
            if($data){
                if($Event->add()){
                    S('DB_EVENT_DATA',null);
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Event->getError());
            }
        } else {
            $this->meta_title = '新增积分事件';
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
            $Event = D('Event');
            $data = $Event->create();
            if($data){
                if($Event->save()){
                    S('DB_EVENT_DATA',null);
                    //记录行为
                    action_log('update_event','event',$data['id'],UID);
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Event->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Event')->field(true)->find($id);

            if(false === $info){
                $this->error('获取积分事件错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑积分事件';
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

        $userEvent=M('user_event')->where(['third_id'=> array('in', $id),'type'=>0])->find();
        if($userEvent){
            $this->error('该积分事件已经存在，删除失败！');
            return;
        }

        $map = array('id' => array('in', $id) );
        if(M('Event')->where($map)->delete()){
            //记录行为
            action_log('update_event', 'event', $id, UID);
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

        $userEvents=M('user_event')->where($map)->select();
        for($i=0;$i<count($userEvents);$i++){
            if($userEvents[$i]['status']==$status){
                return $this->error('参数错误');
            }
        }

        $res=M('user_event')->where($map)->data(['status'=>$status])->save();
        if($res){

            $userEvents=M('user_event')->where($map)->select();

            for($i=0;$i<count($userEvents);$i++){
                $uid=$userEvents[$i]['uid'];
                $score=$userEvents[$i]['score']*-1;
                if($status==2){
                    M('Member2')->where(['id'=>$uid])->setInc('score',$score);
                }else if($status==0){
                    M('Member2')->where(['id'=>$uid])->setDec('score',$score);
                }
            }

            $this->success('更新成功');
        }else{
            $this->error('参数错误');
        }
    }

}