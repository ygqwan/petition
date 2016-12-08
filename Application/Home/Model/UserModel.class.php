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


class UserModel extends BaseModel {
    protected $tableName = 'user';
     
    function __construct() {
        parent::__construct();

    }


}
