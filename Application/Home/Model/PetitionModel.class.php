<?php

//注意本文件名后缀是.class.php
namespace Home\Model;

//得先use一下
use Think\Model;

//获取最后执行的sql
//echo $User->getLastSql();
//echo $User->_sql();

//$User = M("User"); // 实例化User对象
//$result = $User->find(1);
//if(false === $result){
//    echo $User->getDbError();
//}


class PetitionModel extends Model {
    protected $tableName = 'petition';
    protected $fileds = array('id', 'title', 'desc',
        'owner', 'create_time', 'status', 'end_time');
    protected $pk = 'id';
    
    protected $_validate = array(
        /*
        array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('verify','require','验证码必须！'), //默认情况下用正则进行验证
        array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
        array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
        array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
        */
        array('title', array('cao'), '值的范围不对', 2, 'in'),
    );
    protected $_auto = array ( 
        /*
         array('status','1'),  // 新增的时候把status字段设置为1
         array('password','md5',3,'function') , // 对password字段在新增和编辑的时候使md5函数处理
         array('name','getName',3,'callback'), // 对name字段在新增和编辑的时候回调getName方法
         array('update_time','time',2,'function'), // 对update_time字段在更新的时候写入当前时间戳
         */
     );
     
    function __construct() {
        parent::__construct();
    }
    /*
     * @param pid int 请愿id
     * @return bool 是否存在
     */
    function bool_exists($pid) {
        return ! $this->find($pid) === null;
    }


    public function petition($pid) {
        $res = $this->find($pid);
        if($res === null) {
            return model_res(ERR_NOT_EXISTS_PETITION);
        }
        return model_res(ERR_SUCCESS, '', $this->buildOnePetitionInfo($res));
    }

    public function history() {
        $ps = $this->where('end_time <= ' . time())->select();
        $res = array('petition_info_list' =>array());
        foreach($ps as $p) {
            array_push($res['petition_info_list'], $this->buildOnePetitionInfo($p));
        }
        return model_res(ERR_SUCCESS, '', $res);
    }

    public function running() {
        $ps = $this->where('end_time > ' . time())->select();
        $res = array('petition_info_list' =>array());
        foreach($ps as $p) {
            array_push($res['petition_info_list'], $this->buildOnePetitionInfo($p));
        }
        return model_res(ERR_SUCCESS, '', $res);
    }

    public function buildOnePetitionInfo($dbPetition) {
        return array(
            'petition_info' => array(
                'petition' => $dbPetition,
                'is_mine' => $dbPetition->owner === session(C('session.user')['email']),
                'is_follower' => D("Vote")->isFollower($dbPetition->pid, $dbPetition->user_email),
                'expire_time_left' => 7,//FIXME
                'vote_number_left' => 12, //FIXME
            ),
        );
    }

    public function newOne() {
        $pet = I('post');
        $pet['owner'];
    }
}