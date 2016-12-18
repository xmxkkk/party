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
class NewsController extends AdminController {
    public function __construct(){
        parent::__construct();
        C('LIST_ROWS',20);
    }
    
    public function combine(&$news){
        if($news['type']==1){
            $news['type_name']='支部公告';
        }else if($news['type']==2){
            $news['type_name']='支部新闻';
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
    public function index($type=0){
        /* 查询条件初始化 */
        $map = array();

        if($type>0){
            $map['type']=$type;
        }


        $list = $this->lists('News', $map,'id');
        $this->combines($list);

        $this->assign('list', $list);
        $this->meta_title = '新闻管理';
        $this->display();
    }

    /**
     * 新增配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if(IS_POST){
            $News = D('News');
            $data = $News->create();
            if($data){
                if($News->add()){
                    S('DB_NEWS_DATA',null);
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($News->getError());
            }
        } else {
            $this->meta_title = '新增新闻';
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
            $News = D('News');
            $data = $News->create();
            if($data){
                if($News->save()){
                    S('DB_EVENT_DATA',null);
                    //记录行为
                    action_log('update_news','news',$data['id'],UID);
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($News->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('News')->field(true)->find($id);

            if(false === $info){
                $this->error('获取新闻错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑新闻';
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
        if(M('News')->where($map)->delete()){
            //记录行为
            action_log('update_news', 'news', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

}