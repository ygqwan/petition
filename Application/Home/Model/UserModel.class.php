<?php
/**
 * Created by IntelliJ IDEA.
 * User: lyo
 * Date: 2016/12/9
 * Time: 13:01
 */

//注意本文件名后缀是.class.php
namespace Home\Model;

//得先use一下
use Think\Model;

class UserModel extends BaseModel {
    public function __construct(){
        parent::__construct();
    }

    public function stringGetUsernameFromEmail($email) {
        return "麟"; //FIXME
    }

}
