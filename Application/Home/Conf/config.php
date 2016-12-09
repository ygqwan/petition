<?php
const ERR_SUCCESS = 1;
const ERR_INVALID_PWD = 101;
const ERR_NO_LOGIN = 102;
const ERR_NOT_EXISTS_PETITION = 103;
const ERR_VOTED = 104;
const ERR_REALNAME = 105;

const ERR_PARAM_ERROR = 401;
const ERR_SERVER_ERROR = 500;

const STATUS_PETITION_VOTING = 0;
const STATUS_PETITION_NOTIFYING = 1;
const STATUS_PETITION_DOING = 2;
const STATUS_PETITION_CLOSED = 3;
const STATUS_PETITION_FAILED = 4;

return array(
	'DB2_USERINFO' => array(
    	'db_type'  => 'mysql',
   		'db_user'  => 'domob',
   	 	'db_pwd'   => 'domob',
   	 	'db_host'  => 'dbm.office.domob-inc.cn',
   	 	'db_port'  => '3306',
   	 	'db_name'  => 'first_blood_user'
	),
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'domob-206.domob-inc.cn', // 服务器地址
	'DB_NAME'   => 'firstblood_petition', // 数据库名
	'DB_USER'   => 'oyl', // 用户名
	'DB_PWD'    => '123456', // 密码
	'DB_PORT'   => 9987, // 端口
	'DB_PREFIX' => '', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
    'URL_MODEL'          => '2', //URL模式
    'ERRCODE' => array(
        ERR_SUCCESS => 'success',
        ERR_INVALID_PWD => '错误的邮箱或者密码组合',
        ERR_NO_LOGIN => '用户未登录',
        ERR_NOT_EXISTS_PETITION => '不存在该请愿',
        ERR_VOTED => '已经支持过了',
        ERR_PARAM_ERROR => '请求参数错误',
        ERR_SERVER_ERROR => '服务器错误',
		ERR_REALNAME => '签名与真实姓名不符',

    ),
    'ADMIN_EMAIL' => array(
        'zhuanghongli@domob.cn',
        'dengwei@domob.cn',
    ),
    'SESSION' => array(
        'user' => array(
            'email' => 'email',
            'username' => 'username',
            'use_sso' => 'use_sso',
        ),
    ),
    'CAS' => array(
        'host' => 'sso.back.domob-inc.cn',
        'context' => '/cas',
        'port' => 4443,
    ),
    'EMAIL' => array(
        'smtp_host' => '',
        'smtp_port' => 33,
        'smtp_'
    ),
    'default_vote_target' => 30,
    'default_delay' => 15,
);
