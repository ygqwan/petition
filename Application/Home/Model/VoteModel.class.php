<?php

//注意本文件名后缀是.class.php
namespace Home\Model;

//得先use一下
use Think\Model;

use Home\Common;

class VoteModel extends Model {
    protected $tableName = 'vote';
    protected $fileds = array('pid', 'user_email', 'vote_time', 'status');
    protected $pk = 'pid,user_email';
    function __construct() {
        parent::__construct();
    }
    protected $_validate = array(
        /*
        array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('verify','require','验证码必须！'), //默认情况下用正则进行验证
        array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
        array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
        array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
        */

    );
    protected $_auto = array (
        //array('status', '1'),
        array('status', '$this->cao', 3, 'function'),
        /*
         array('status','1'),  // 新增的时候把status字段设置为1
         array('password','md5',3,'function') , // 对password字段在新增和编辑的时候使md5函数处理
         array('name','getName',3,'callback'), // 对name字段在新增和编辑的时候回调getName方法
         array('update_time','time',2,'function'), // 对update_time字段在更新的时候写入当前时间戳
         */
     );

    public function cao($status) {
        return 10000;
    }

    function vote($pid) {
        if(!D('Petition')->bool_exists($pid)) {
            return model_res(ERR_NOT_EXISTS_PETITION, '', array());
        }
        $vote = array(
            'pid' => $pid,
            'user_email' => session(C("session.user.email")),
        );
        if($this->create($vote)) {
            $this->add();
            return model_res(array(), ERR_SUCCESS);
        }else {
            return model_res(-1, $this->getError());
        }
    }

    public function isFollower($pid, $email = '') {
        if($pid == '' or $email == '') {
            return false;
        }
        return ! $this->where("id=$pid and email=$email")->find() === null;
    }
}