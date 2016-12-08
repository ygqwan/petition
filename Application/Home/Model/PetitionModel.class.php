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
        array('status','1', self::MODEL_INSERT),
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

    public function history($offset, $pageSize) {
        return $this->_getPetitions('end_time <= ' . time(), $offset, $pageSize);
    }

    public function running($offset, $pageSize) {
        return $this->_getPetitions('end_time > ' . time(), $offset, $pageSize);
    }



    public function buildOnePetitionInfo($dbPetition) {
        return array(
            'petition_info' => array(
                'petition' => $dbPetition,
                'is_mine' => $dbPetition->owner === session(C('session.user')['email']),
                'is_follower' => D("Vote")->boolIsFollower($dbPetition->pid, $dbPetition->user_email),
                'expire_time_left' => 7,//FIXME
                'vote_number_left' => 12, //FIXME
            ),
        );
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


    private function _getPetitions($where = '', $offset = 0, $pageSize = 15) {
        $ps = $this->where($where)
            ->limit($offset, $pageSize)
            ->order('id desc')
            ->select();
        $res = array('petition_info_list' =>array());
        foreach($ps as $p) {
            array_push($res['petition_info_list'],
                $this->buildOnePetitionInfo($p));
        }
        //$res['page_size'] = $pageSize;
        $res['next_offset'] = $offset + count($ps);
        $res['exists'] = count($ps) == $pageSize;
        return model_res(ERR_SUCCESS, '', $res);
    }


    public function _endTime() {
        return time() + C('default_delay') * 86400;
    }
    public function _voteTarget() {
        return C('default_vote_target');
    }

}