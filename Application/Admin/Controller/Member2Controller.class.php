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
class Member2Controller extends AdminController {
    public function __construct(){
        parent::__construct();
        $this->assign('groups',M('group')->select());
    }
    public function combine(&$member2){
        $member2['stage_name']=get_stage()[$member2['stage']];

        $member2['status_name']=get_status()[$member2['status']];

        $group=M('group')->where(['id'=>$member2['groupid']])->find();
        if($group){
            $member2['group_name']=$group['name'];
        }else{
            $member2['group_name']='没有小组';
        }
        
    }
    public function combines(&$list){
        for($i=0;$i<count($list);$i++){
            $this->combine($list[$i]);
        }
    }
    /**
     * 配置管理
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        /* 查询条件初始化 */
        $map = array();

        if(I('groupid')){
            $map['groupid']=I('groupid');
        }
        if(I('stage')){
            $map['stage']=I('stage');
        }

        $list = $this->lists('Member2', $map,'id');
        $this->combines($list);

        $this->assign('list', $list);
        $this->meta_title = '成员管理';
        $this->display();
    }

    /**
     * 新增配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if(IS_POST){
            $Member2 = D('Member2');
            $data = $Member2->create();
            if($data){
                $id=$Member2->add();
                if($id){
                    $password=md5(substr($data['idcard'],strlen($data['idcard'])-6));
                    M('Member2')->where(['id'=>$id])->save(['password'=>$password]);

                    S('DB_MEMBER2_DATA',null);
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Member2->getError());
            }
        } else {
            $this->meta_title = '新增成员';
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
            $Member2 = D('Member2');
            $data = $Member2->create();
            if($data){
                if($Member2->save()){
                    

                    S('DB_MEMBER2_DATA',null);
                    //记录行为
                    action_log('update_member2','member2',$data['id'],UID);
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Member2->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Member2')->field(true)->find($id);

            if(false === $info){
                $this->error('获取成员信息错误');
            }

            $config=M('config')->where(['name'=>'QRCODE_BASE_HOST'])->find();
            $info['qrcode_url']=$config['value'].'/index.php?m=home&c=index&a=qrcode&md5='.$info['md5'];

            $this->assign('info', $info);
            $this->meta_title = '编辑成员';
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

        $map = array('id' => array('in', $id) );
        if(M('Member2')->where($map)->delete()){
            //记录行为
            action_log('update_member2', 'member2', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

}