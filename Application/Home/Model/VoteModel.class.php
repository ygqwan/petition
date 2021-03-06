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
        return $this->where("pid=$pid and user_email='$user_email'")->count() > 0;
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
			//判断是否达到投满的数目
			$num = M('vote')->where("pid=$pid")->count();
			if($num >= C('default_vote_target')){
				$mypetition = M('petition')->where("id=$pid")->select()[0];
				$title = C('vote_target_email_desc')." : ".$mypetition['title'];
				$desc = $mypetition['desc'].'\n'."链接如下:"."http://10.0.0.206:12710/fb/index.html?is_follower=true&currentId=$pid";
				$receiver = D('Petition')->arrayToString(C('ADMIN_EMAIL'));
				$copyto = $mypetition['owner'];
				D('Petition')->emailToSave($pid, $title, $desc, $receiver, $copyto);
			}
			
            D("petition")->boolChangeStatus($pid);
            return model_res(array(), ERR_SUCCESS);
        }else {
            return model_res(-1, $this->getError());
        }
    }

    public function votedCnt($pid) {
        if($pid == '') {
            return 0;
        }

        return $this->where("pid=$pid")->count();

    }

    public function boolIsFollower($pid, $email = '') {
        if($pid == '' or $email == '') {
            return false;
        }
        return $this->where("pid=$pid and user_email='$email'")->count() > 0;
    }
    /*返回我推过票的id*/
    public function myVotedId() {
        return model_res(1, '',
            $this->where("user_email='".$this->email()."'")->field('pid')->select());
    }
}
