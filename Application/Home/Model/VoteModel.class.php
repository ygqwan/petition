<?php

//注意本文件名后缀是.class.php
namespace Home\Model;


use Home\Common;

class VoteModel extends BaseModel  {
    protected $tableName = 'vote';
    protected $fileds = array('pid', 'user_email', 'vote_time', 'status');
    protected $pk = array('pid', 'user_email');
    function __construct() {
        parent::__construct();
    }
    protected $_validate = array(
    );
    protected $_auto = array (
        array('vote_time', 'time', self::MODEL_INSERT, 'function'),
        array('status', 1, self::MODEL_INSERT),
        array('user_email', 'email', self::MODEL_INSERT, 'callback'),
    );

    function boolIsVated($pid, $user_email) {
        if($pid == "" || $user_email == "") {
            return false;
        }
        return ! ($this->where("pid=$pid and user_email='$user_email'")->find() === null);
    }

    function vote($pid) {
        if(!D('Petition')->boolExists($pid)) {
            return model_res(ERR_NOT_EXISTS_PETITION, '', array());
        }
        if($this->boolIsVated($pid, $this->email())){
            return model_res(ERR_VOTED);
        }
        $vote = array(
            'pid' => $pid,
            'user_email' => session(C('session.user')['email']),
        );
        if($this->field('pid,user_email,vote_time,status')->create($vote, 1)) {
            $this->add();
            return model_res(array(), ERR_SUCCESS);
        }else {
            return model_res(-1, $this->getError());
        }
    }

    public function boolIsFollower($pid, $email = '') {
        if($pid == '' or $email == '') {
            return false;
        }
        return ! $this->where("id=$pid and email=$email")->find() === null;
    }
    /*返回我推过票的id*/
    public function myVotedId() {
        return model_res(1, '',
            $this->where("user_email='".$this->email()."'")->field('pid')->select());
    }
}