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
class ItemController extends AdminController {
    public function __construct(){
        parent::__construct();
		$this->assign('types',array('item'=>"列表",'article'=>"文章"));
        C('LIST_ROWS',20);
    }

    public function combine(&$item){
		$item['url']='http://'.$_SERVER['HTTP_HOST'].'/weixin/#/'.$item['type'].'/'.$item['id'];
		$item['type_name']=array('item'=>"列表",'article'=>"文章")[$item['type']];
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
    public function index($type=0){
        /* 查询条件初始化 */
        $map = array();

        if($type>0){
            $map['type']=$type;
        }

        $list = $this->lists('Item', $map,'id');
        $this->combines($list);

        $this->assign('list', $list);
        $this->meta_title = '栏目管理';
        $this->display();
    }

    /**
     * 新增配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if(IS_POST){
            $Item = D('Item');
            $data = $Item->create();
            if($data){
                if($Item->add()){
                    S('DB_ITEM_DATA',null);
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Item->getError());
            }
        } else {
            $this->meta_title = '新增栏目';
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
            $Item = D('Item');
            $data = $Item->create();
            if($data){
                if($Item->save()){
                    S('DB_EVENT_DATA',null);
                    //记录行为
                    action_log('update_item','item',$data['id'],UID);
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Item->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Item')->field(true)->find($id);

            if(false === $info){
                $this->error('获取新闻错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑栏目';
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

        $map = array('id' => $id );

		$itemMenus=M('ItemMenu')->where(array('item_id'=>$id))->select();
		if(count($itemMenus)>0){
			$this->error('请先删除栏目下的菜单');
		}

        if(M('Item')->where($map)->delete()){
            //记录行为
            action_log('update_item', 'item', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

}
