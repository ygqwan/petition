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


class PetitionModel extends BaseModel {
    protected $tableName = 'petition';
    protected $fileds = array('id', 'title', 'desc',
        'owner', 'create_time', 'status', 'end_time');
    protected $pk = 'id';
    protected  $autoinc = true;
    
    protected $_validate = array(
        array('title', 'require', '标题不能为空', 1),
        array('desc', 'require', '内容不能为空', 1),
    );
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('end_time', '_endTime', self::MODEL_INSERT, 'callback'),
        array('status',STATUS_PETITION_VOTING, self::MODEL_INSERT),
        array('vote_target', '_voteTarget', self::MODEL_INSERT, 'callback'),
        array('owner', 'email', 1, 'callback'),
     );
     
    function __construct() {
        parent::__construct();

    }
    /*
     * @param pid int 请愿id
     * @return bool 是否存在
     */
    function boolExists($pid) {
        //var_dump($this->find($pid));
        return ! ($this->find($pid) === null);
    }

    public function petition($pid) {
        $res = $this->find($pid);
        if($res === null) {
            return model_res(ERR_NOT_EXISTS_PETITION);
        }
        return model_res(ERR_SUCCESS, '', $this->buildOnePetitionInfo($res));
    }

    public function history($offset = 0, $pageSize = 15) {
        //已经被回应过的就是历史
        $pids = M("")->query("select pid from petition_reply group by pid order by create_time desc limit $offset, $pageSize");
        $dbPetitions = $this->getPetitionByPids($pids);
        return $this->_getPetitions($dbPetitions, $offset, $pageSize);
    }

    public function running($offset, $pageSize) {
        $ps = $this->where('end_time > ' . time())
            ->limit($offset, $pageSize)
            ->order('id desc')
            ->select();
        return $this->_getPetitions($ps, $offset, $pageSize);
    }

    public function myOwner($offset, $pageSize) {
        $this->needLogin();
        $where = array(
            'owner' => array('eq', $this->email()),
        );
        $ps = $this->where($where)
            ->limit($offset, $pageSize)
            ->order('id desc')
            ->select();
        $myP = $this->_getPetitions($ps, $offset, $pageSize);
        return $myP;
    }

    public function myVoted($offset, $pageSize) {
        $this->needLogin();
        $where = array(
            'id' => array('in', "'".explode(',', D('Vote')->myVotedId())."'"),
        );
        $ps = $this->where($where)
            ->limit($offset, $pageSize)
            ->order('id desc')
            ->select();
        $myVote = $this->_getPetitions($ps, $offset, $pageSize);
        return $myVote;
    }

    public function boolChangeStatus($pid, $status = null) {
        $petition = $this->petition($pid);
        if($petition['errcode'] != 1) {
            return false;
        }
        if($status == null) {
            if(D('vote')->votedCnt($pid) >= $petition['response']['petition_info']['vote_target']) {
                $petition['response']['petition_info']['status'] = STATUS_PETITION_NOTIFYING;
            }
        }
        $this->where("id=$pid")->save($petition['response']['petition_info']);
        return true;
    }




    public function launch() {
        if($this->field('title,desc,owner,status,create_time,end_time,vote_target')->create()) {
            $this->add();
            return model_res(ERR_SUCCESS);
        }else {
            return model_res(-1, $this->getError());
        }
    }

    public function close($pid) {
        if(!$this->boolExists($pid)) {
            return model_res(ERR_NOT_EXISTS_PETITION);
        }
        $data['status'] = STATUS_PETITION_CLOSED;

        if($this->where("id=$pid")->save($data)) {
            return model_res(ERR_SUCCESS);
        }else {
            return model_res(-1, '之前已经被close过了');
        }
    }

    public function reply($pid, $desc){
        $this->needAdmin();
        if(empty($pid) or !$this->boolExists($pid)){
            return model_res(ERR_NOT_EXISTS_PETITION);
        }
        if(empty($desc)) {
            return model_res(ERR_PARAM_ERROR);
        }
        $reply = array(
            'pid' => $pid,
            'user_email' => $this->email(),
            'desc' => $desc,
            'create_time' => time(),
        );
        $dbReply = M("petition_reply");
        if($dbReply->create($reply)) {
            $dbReply->add();
			//向数据库中插入一条发邮件的数据
			$vote = D('vote')->where("pid=$pid")->select();
			$myreceiver = array();
			foreach($vote as $myvote){
				array_push($myreceiver, $myvote['user_email']);
			}
			$copyto = $this->arrayToString(C('ADMIN_EMAIL'));
			$receiver = $this->arrayToString($myreceiver);
			$title = $this->where("id=$pid")->getField('title');
			$title = C('reply_desc')." : ".$title;
			$desc = "问题链接 ：http://10.0.0.206:12710/fb/result.html?id=$pid\n";
			$this->emailToSave($pid, $title, $desc, $receiver, $copyto);
            return model_res(ERR_SUCCESS);
        }else{
            return model_res(-1, $dbReply->getDbError());
        }
    }

    public function getPetitionByPids($pids = array()) {
        //print_r($pids);die;
        $arrPids = array();//一维
        if(is_array($pids[0])) {
            foreach($pids as $v) {
                if(isset($v['pid'])) {
                    $arrPids []= $v['pid'];
                }else if(isset($v['id'])){
                    $arrPids []= $v['id'];
                }
            }
        } else{
            $arrPids = $pids;
        }
        $strPids = implode(',', $arrPids);
        $strPids = '('.$strPids.')';
        return $this->where(array('id' => array('in', $strPids)))->select();
    }

    public function getShouldEmailPetition() {
        $res = M("")->query("SELECT p.id FROM  petition p JOIN ( SELECT v.pid AS pid,count(v.pid) AS cnt FROM vote v GROUP BY v.pid) AS v2 ON v2.pid = p.id WHERE v2.cnt >= p.vote_target");
        $ids = "(";
        foreach($res as $i => $arr) {
            $ids .= $arr['id'];
            if($i != count($res) - 1) {
                $ids .= ",";
            }
        }
        $ids .= ")";
        if(strlen($ids) < 3) {
            return array();
        }

        $res = M("")->query("select pid, user_email from vote where pid in $ids group by pid, user_email");
        $pidToUsers = array();
        foreach($res as $pair) {
            if(isset($pidToUsers[$pair['pid']])) {
                array_push($pidToUsers[$pair['pid']], $pair['user_email']);
            }else{
                $pidToUsers[$pair['pid']] = array($pair['user_email']);
            }
        }
        return $pidToUsers;
    }

    public function buildOnePetitionInfo($dbPetition) {
        $userInfo = array(
            'user_email' => $dbPetition['owner'],
            'user_name' => D("user")->stringGetUsernameFromEmail($dbPetition['owner']),
            'is_admin' => $this->isAdmin($dbPetition['owner']),
        );
        $dbPetition['owner'] = $userInfo;
        return array(
            'petition_info' => array(
                'petition' => $dbPetition,
                'is_mine' => $userInfo['user_email'] === session(C('session.user')['email']),
                'is_follower' => D("Vote")->boolIsFollower($dbPetition['id'], $userInfo['user_email']),
                'expire_time_left' => $dbPetition['end_time'] - time() > 0 ? $dbPetition['end_time'] - time() : 0,
                'voted_number' => D('vote')->votedCnt($dbPetition['id']),
                'url' => './index.php?s=home/petition/detail/id/'.$dbPetition['id'],
            ),
        );
    }
    private function _getPetitions($dbPetitions, $offset = 0, $pageSize = 15) {
        $res = array('petition_info_list' =>array());
        foreach($dbPetitions as $p) {
            array_push($res['petition_info_list'],
                $this->buildOnePetitionInfo($p));
        }
        $res['next_offset'] = $offset + count($dbPetitions);
        $res['is_exists'] = count($dbPetitions) == $pageSize;
        return model_res(ERR_SUCCESS, '', $res);
    }


    public function _endTime() {
        return time() + C('default_delay') * 86400;
    }
    public function _voteTarget() {
        return C('default_vote_target');
    }

	public function emailToSave($pid, $title, $desc, $receiver, $copyto){
		$email = M('email_to_send');
		$data['pid'] = $pid;
		$data['title'] = $title;
		$data['desc'] = $desc;
		$data['receiver'] = $receiver;
		$data['copyto'] = $copyto;
		$data['create_time'] = time();
		$data['sent_status'] = 0;
		$email->add($data);
	}

	public function arrayToString($myarray){
		$mystring = "";
		foreach($myarray as $my){
			if ($mystring == "")
				$mystring = $my;
			else
				$mystring .= ",".$my;
		}
		return $mystring;
		
	}

}
