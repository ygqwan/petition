<?php
//注意本文件名后缀是.class.php
namespace Home\Model;

//得先use一下
use Think\Model;


class BaseModel extends Model {
    public function __construct() {
        parent::__construct();
    }
    public function email() {
        return session(C('session.user')['email']);
    }
    public function username() {
        return session(C('session.user')['username']);
    }
    public function buildUserInfo() {
        return model_res(ERR_SUCCESS, '', array(
            'user_info' => array(
                'user_email' => $this->email(),
                'user_name' => $this->username(),
                'is_admin' => false,
            )
        ));
    }
}