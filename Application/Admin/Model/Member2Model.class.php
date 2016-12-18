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

class Member2Model extends Model {
    protected $_validate = array(
        array('realname', 'require', '姓名不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('realname','checkRealname','姓名有误', self::MUST_VALIDATE, 'function', self::MODEL_BOTH),
        
        array('idcard', 'require', '身份证号码不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('idcard', 'checkIdcard', '身份证号码输入有误', self::MUST_VALIDATE, 'function', self::MODEL_BOTH),
        array('idcard', '', '身份证号码已经存在', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),

        array('phone', 'require', '手机号码不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('phone','checkPhone','手机号码有误', self::MUST_VALIDATE, 'function', self::MODEL_BOTH),
        array('phone', '', '手机号码已经存在', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),
        
        array('score','number','积分有误', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),

        array('stage',[1,2,3],'请选择身份', self::MUST_VALIDATE, 'in', self::MODEL_BOTH),

    );

    protected $_auto = array(
        ['md5','getMd5',self::MODEL_INSERT,'function']
    );

    /*
    public function checkIdcard($idcard){
        echo $idcard;exit();
        return false;
    }*/

}
