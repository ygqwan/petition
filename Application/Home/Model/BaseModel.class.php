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
    public function isLogined() {
        if(session(C('session.user')['email']) != "") {
            return true;
        }
        return false;
    }
    public function isAdmin($email = '') {
        return true;
    }
    public function needLogin() {
        if (!$this->isLogined()) {
            header('Content-type: application/json');
            echo json_encode(
                array('status' => ERR_NO_LOGIN, getErr(ERR_NO_LOGIN))
            );
            exit();
        }
    }
    public function needAdmin() {
        if(!$this->isAdmin()) {
            header('Content-type: application/json');
            echo json_encode(
                array('status' => ERR_NO_PERMISSION, getErr(ERR_NO_PERMISSION))
            );
            exit();
        }
    }
    public function buildUserInfo() {
        if($this->isLogined()) {
            return model_res(ERR_SUCCESS, '', array(
                'user_info' => array(
                    'user_email' => $this->email(),
                    'user_name' => $this->username(),
                    'is_admin' => false,
                )
            ));
        }else {
            return model_res(ERR_NO_LOGIN);
        }
    }
}