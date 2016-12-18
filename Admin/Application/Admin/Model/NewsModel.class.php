<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;
/**
 * 配置模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class NewsModel extends Model {
    protected $_validate = array(
        array('title', 'require', '新闻标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('content', 'require', '新闻内容不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('type', '/^[1,2]{1}$/', '请选择新闻类型', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        
    );

    protected $_auto = array(
        ['publishtime','current_time',self::MODEL_INSERT,'function']
    );
}
