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
class GroupController extends AdminController {

    /**
     * 配置管理
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        /* 查询条件初始化 */
        $map = array();

        $list = $this->lists('Group', $map,'id');

        $this->assign('list', $list);
        $this->meta_title = '小组管理';
        $this->display();
    }

    /**
     * 新增配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if(IS_POST){
            $Group = D('Group');
            $data = $Group->create();
            if($data){
                if($Group->add()){
                    S('DB_GROUP_DATA',null);
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Group->getError());
            }
        } else {
            $this->meta_title = '新增小组';
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
            $Group = D('Group');
            $data = $Group->create();
            if($data){
                if($Group->save()){
                    S('DB_GROUP_DATA',null);
                    //记录行为
                    action_log('update_group','group',$data['id'],UID);
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Group->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Group')->field(true)->find($id);

            if(false === $info){
                $this->error('获取小组信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑小组';
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

        $members=M('member2')->where(array('groupid' => array('in', $id)))->select();
        if($members){
            $this->error('该分组下有成员，不能直接删除！');
        }

        $map = array('id' => array('in', $id) );
        if(M('Group')->where($map)->delete()){
            //记录行为
            action_log('update_group', 'group', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

}