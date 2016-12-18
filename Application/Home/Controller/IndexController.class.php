<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function qrcode($md5=""){
        $config=M('config')->where(['name'=>'QRCODE_BASE_HOST'])->find();
        $url=$config['value'].'/www/index.html#/app/member/'.$md5;

        $style = array(
            'border' => true,
            'padding' => 4,
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
        );
        vendor("phpqrcode.phpqrcode");
        $object = new \QRcode();
        
        $level=3;
        $size=8;
        $errorCorrectionLevel ="M";//intval($level) ;//容错级别
        $matrixPointSize = intval($size);//生成图片大小
        $object->png($url, false, $errorCorrectionLevel, $matrixPointSize, 0);
    }
    public function newdetail(){
        $id=I('id');
        $news=M('news')->where(['id'=>$id])->find();
        $picture=M('Picture')->where(['id'=>$news['cover']])->find();
        $news['cover_url']=$picture['path'];

        $result=[];
        $result['status']=0;
        $result['data']=$news;
        return $this->ajaxReturn($result);
    }
    public function topn(){
        $list=M('member2')->order('score desc,id asc')->limit("10")->select();
        $this->_members($list);

        $members=[];
        for($i=0;$i<count($list);$i++){
            $members[]=["id"=>$list[$i]['id'],"score"=>$list[$i]['score'],'header_url'=>$list[$i]['header_url'],'realname'=>$list[$i]['realname']];
        }

        $result=[];
        $result['status']=0;
        $result['members']=$members;
        return $this->ajaxReturn($result);
    }
    public function news(){
        $p=I('p');
        $type=I('type');
        $len=10;
        $m=M('news');

        $news=$m->where('publishtime<now() and type='.$type)->limit($p*$len.",".$len)->select();

        for($i=0;$i<count($news);$i++){
            $news[$i]['summary']=strip_tags($news[$i]['content']);
            if(mb_strlen($news[$i]['summary'])>100){
                $news[$i]['summary']=mb_substr($news[$i]['summary'], 0,100);  
            }
            $picture=M('Picture')->where(['id'=>$news[$i]['cover']])->find();
            if($picture){
                $news[$i]['cover_url']=$picture['path'];
                $news[$i]['is_cover']=1;
            }else{
                $news[$i]['cover_url']='';
                $news[$i]['is_cover']=0;
            }
            
        }

        $result=[];
        $result['status']=0;
        $result['news']=$news;
        return $this->ajaxReturn($result);
    }
    public function reset_pass(){
        $oldpassword=I('oldpassword');
        $newpassword=I('newpassword');

        $result=[];

        $id=session('id');
        if(empty($id)){
            $result['status']=1;
            $result['message']="请先登录";
            return $this->ajaxReturn($result);
        }

        $member=M('member2')->where(['id'=>$id])->find();
        if($member['password']!=md5($oldpassword)){
            $result['status']=2;
            $result['message']="旧密码输入错误";
            return $this->ajaxReturn($result);
        }

        M('member2')->data(['id'=>$id,'password'=>md5($newpassword)])->save();
        $result['status']=0;
        return $this->ajaxReturn($result);
    }
    public function logout(){
        session('id',null);
    }
    public function is_login(){
        $id=session('id');
        $result=[];
        if(empty($id)){
            $result['status']=1;
        }else{
            $member=M('member2')->where(['id'=>$id])->find();
            $result['status']=0;
            $result['md5']=$member['md5'];
            $result['realname']=$member['realname'];
            $result['level']=$member['level'];
        }
        return $this->ajaxReturn($result);
    }
    public function score_record(){
        $md5=I("md5");
        $p=intval(I("p"));
        $len=10;

        $result=[];
        $member=D('member2')->where(['md5'=>$md5])->find();
        if(!$member){
            $result['data']=1;//请先登录
            return $this->ajaxReturn($result);
        }

        $data=M()->query("select * from (
select a.*,b.name from __PREFIX__user_event a 
left join __PREFIX__event b on a.third_id=b.id
where a.uid=".$member['id']." and a.type=0
union all
select a.*,b.name from __PREFIX__user_event a 
left join __PREFIX__task b on a.third_id=b.id
where a.uid=".$member['id']." and a.type=1
) x where x.status in (0,1) order by x.addtime desc limit ".($p*$len).",".$len);
        $result['status']=0;
        $result['data']=$data;

        return $this->ajaxReturn($result);
    }
    public function add_score_event(){
        $id=session("id");
        $result=[];
        if(!$id){
            $result['status']=1;//请先登录
            return $this->ajaxReturn($result);
        }

        $groupMember=D('member2')->where(['id'=>$id])->find();

        $md5=I('md5');
        $eventId=I('eventId');
        $remark=I('remark');

        $member=D('member2')->where(['md5'=>$md5])->find();
        $event=D('event')->where(['id'=>$eventId])->find();

        D('user_event')->data([
                'uid'   =>  $member['id'],
                'addtime'=> date('Y-m-d H:i:s'),
                'third_id'=>$eventId,
                'remark'=>  $remark,
                'type'  =>  0,
                'score'=>   $event['score'],
                'status'=>0,
                'mid'   =>   $id
            ])->add();

        D('member2')->where(['md5'=>$md5])->setInc('score',$event['score']);

        $result['status']=0;
        return $this->ajaxReturn($result);
    }
    public function group_member(){
        $id=session("id");
        $member=D('member2')->where('id='.$id)->find();
        $members=D('member2')->where('groupid='.$member['groupid'])->select();

        $result=[];
        for($i=0;$i<count($members);$i++){
            $result[]=['md5'=>$members[$i]['md5'],'realname'=>$members[$i]['realname']];
        }
        return $this->ajaxReturn($result);
    }
    public function score(){
        $result=[];
        $events=D('event')->where(['type'=>1])->select();
        $result[]=['type'=>1,'name'=>'任务分','events'=>$events];
        $events=D('event')->where(['type'=>2])->select();
        $result[]=['type'=>2,'name'=>'义务分','events'=>$events];
        $events=D('event')->where(['type'=>3])->select();
        $result[]=['type'=>3,'name'=>'减分项目','events'=>$events];

        return $this->ajaxReturn($result);
    }
    public function member(){
        $md5=I("md5");
        $member=D('member2')->where(['md5'=>$md5])->find();

        $this->_member($member);


        $result=[];
        $result['realname']=$member['realname'];

        $result['md5']=$member['md5'];
        $result['score']=$member['score'];
        $result['stage']=$member['stage'];
        $result['stage_name']=$member['stage_name'];
        $result['level']=$member['level'];
        $result['header']=$member['header'];
        $result['groupid']=$member['groupid'];
        $result['gender']=$member['gender'];
        $result['gender_name']=$member['gender_name'];
        $result['age']=$member['age'];
        $result['header_url']=$member['header_url'];
        $result['qrcode_url']=$member['qrcode_url'];

        if(session("id")==$member['id']){
            $result['idcard']=$member['idcard'];
            $result['phone']=$member['phone'];
            $result['birthday']=$member['birthday'];
        }

        if(session("id")){
            $result['id']=$member['id'];

            $member_login=D('member2')->where(['id'=>session('id')])->find();
            if($member_login['groupid']==$member['groupid']){
                $result['level']=$member_login['level'];
            }else{
                $result['level']=0;
            }

        }else{
            $result['id']=0;
            $result['level']=0;
        }

        return $this->ajaxReturn($result);
    }

    public function task(){
        $id=session('id');
        $result=[];
        if(!$id){
            $result['status']=1;//请先登录
            return $this->ajaxReturn($result);
        }


        $member=D('member2')->where(['id'=>$id])->find();

        $tasks=D('task')->where('status=0 and (gid=0 or gid='.$member['groupid'].')')->order('id desc')->select();
        
        $result['tasks']=$tasks;
        $result['status']=0;
        return $this->ajaxReturn($result);
    }


    public function login(){
        $result=[];

        $phone=trim(I('phone',""));
        $password=md5(trim(I('password')));
        
        $member=D('member2')->where(['phone'=>$phone])->find();
        if(!$member){
            $result=['status'=>1,'message'=>'手机号错误'];
            return $this->ajaxReturn($result);
        }

        $member=D('member2')->where(['phone'=>$phone,'password'=>$password])->find();

        if(!$member){
            $this->ajaxReturn(['status'=>2,'message'=>'密码错误']);
        }else{
            session("id",$member['id']);
            $_url='';
            if($_SERVER['HTTP_REFERER']){
                $_url=$_SERVER['HTTP_REFERER'];
            }
            $this->ajaxReturn(['status'=>0,'message'=>'登录成功','md5'=>$member['md5'],'realname'=>$member['realname'],'_url'=>$_url,'level'=>$member['level']]);
        }
    }

    private function _member(&$member){
        if($member['stage']==1){
            $member['stage_name']='入党积极分子';
        }else if($member['stage']==2){
            $member['stage_name']='预备党员';
        }else if($member['stage']==3){
            $member['stage_name']='正式党员';
        }
        if($member['birthday']){
            $member['age']=date("Y")-date("Y",strtotime($member['birthday']));
        }
        if($member['gender']==0){
            $member['gender_name']="女";
        }else if($member['gender']==1){
            $member['gender_name']="男";
        }

        $picture=M('Picture')->where(['id'=>$member['header']])->find();
        if($picture){
            $member['header_url']=$picture['path'];
        }else{
            $member['header_url']='';
        }
        $config=M('config')->where(['name'=>'QRCODE_BASE_HOST'])->find();
        $member['qrcode_url']=$config['value'].'/index.php?m=home&c=index&a=qrcode&md5='.$member['md5'];
    }

    private function _members(&$list){
        for($i=0;$i<count($list);$i++){
            $this->_member($list[$i]);
        }
    }
}