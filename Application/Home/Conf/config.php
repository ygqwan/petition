<?php
return array(
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '192.168.200.151', // 服务器地址
	'DB_NAME'   => 'firstblood_petition', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => '', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => '', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
    'errcode' => array(
        1 => 'success',
        101 => '错误的邮箱或者密码组合',
        102 => '用户未登录',
        401 => '请求参数错误',
        500 => '服务器错误',
    ),
);